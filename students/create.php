<?php
require_once __DIR__ . '/../config/bootstrap.php';
$user = require_login($pdo);
require_role($user, ['admin']);
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf_token();
    $fullName = sanitize($_POST['full_name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $password = (string)($_POST['password'] ?? 'student123');
    try {
        $pdo->beginTransaction();
        $stmt = $pdo->prepare('INSERT INTO users (email, password, full_name, role, avatar_initials) VALUES (?, ?, ?, "student", ?)');
        $stmt->execute([$email, password_hash($password, PASSWORD_BCRYPT), $fullName, avatar_initials($fullName)]);
        $userId = (int)$pdo->lastInsertId();

        $stmt = $pdo->prepare('INSERT INTO students (user_id, matric_no, full_name, email, program, level) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->execute([
            $userId,
            sanitize($_POST['matric_no'] ?? ''),
            $fullName,
            $email,
            sanitize($_POST['program'] ?? ''),
            (int)($_POST['level'] ?? 100),
        ]);
        $pdo->commit();
        redirect_to('students/index.php');
    } catch (PDOException $e) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        $error = 'Unable to create student. Email or matric number may already exist.';
    }
}

$pageTitle = 'Create Student';
require_once __DIR__ . '/../templates/header.php';
?>
<h1 class="page-title">Create Student</h1>
<p class="page-subtitle">Create a student profile and matching login account.</p>
<?php if ($error): ?><div class="card" style="padding:16px;margin-bottom:20px;color:#991b1b;background:#fee2e2;"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
<div class="card" style="padding:24px;">
    <form method="post">
        <?php echo csrf_field(); ?>
        <?php require __DIR__ . '/partials/student_form.php'; ?>
        <div class="form-group"><label class="form-label">Temporary Password</label><input class="form-input" type="text" name="password" value="student123"></div>
        <button class="btn btn-primary" type="submit">Create Student</button>
    </form>
</div>
<?php require_once __DIR__ . '/../templates/footer.php'; ?>
