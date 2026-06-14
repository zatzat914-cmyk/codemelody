<?php
require_once __DIR__ . '/../config/bootstrap.php';
$user = require_login($pdo);
require_role($user, ['admin']);
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = sanitize($_POST['full_name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $password = (string)($_POST['password'] ?? 'lecturer123');

    try {
        $stmt = $pdo->prepare('INSERT INTO users (email, password, full_name, role, avatar_initials) VALUES (?, ?, ?, "lecturer", ?)');
        $stmt->execute([$email, password_hash($password, PASSWORD_BCRYPT), $fullName, avatar_initials($fullName)]);
        redirect_to('lecturers/index.php');
    } catch (PDOException $e) {
        $error = 'Unable to create lecturer. The email may already exist.';
    }
}

$lecturer = [];
$pageTitle = 'Create Lecturer';
require_once __DIR__ . '/../templates/header.php';
?>
<h1 class="page-title">Create Lecturer</h1>
<p class="page-subtitle">Add a lecturer account that can manage courses.</p>
<?php if ($error): ?><div class="card" style="padding:16px;margin-bottom:20px;color:#991b1b;background:#fee2e2;"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
<div class="card" style="padding:24px;">
    <form method="post">
        <?php require __DIR__ . '/partials/lecturer_form.php'; ?>
        <div class="form-group">
            <label class="form-label">Temporary Password</label>
            <input class="form-input" type="text" name="password" value="lecturer123" required>
        </div>
        <button class="btn btn-primary" type="submit">Create Lecturer</button>
    </form>
</div>
<?php require_once __DIR__ . '/../templates/footer.php'; ?>
