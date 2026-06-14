<?php
require_once __DIR__ . '/../config/bootstrap.php';
$user = require_login($pdo);

if ($user['role'] === 'student') {
    $student = student_profile($pdo, (int)$user['id']);
    if (!$student) {
        $student = ['id' => 0, 'user_id' => $user['id'], 'full_name' => $user['full_name'], 'email' => $user['email'], 'matric_no' => '', 'program' => '', 'level' => 100];
    }
} else {
    require_role($user, ['admin']);
    $stmt = $pdo->prepare('SELECT * FROM students WHERE id = ?');
    $stmt->execute([(int)($_GET['id'] ?? 0)]);
    $student = $stmt->fetch();
    if (!$student) redirect_to('students/index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf_token();
    $fullName = sanitize($_POST['full_name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    if ((int)$student['id'] === 0) {
        $stmt = $pdo->prepare('INSERT INTO students (user_id, matric_no, full_name, email, program, level) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->execute([(int)$user['id'], sanitize($_POST['matric_no'] ?? ''), $fullName, $email, sanitize($_POST['program'] ?? ''), (int)($_POST['level'] ?? 100)]);
    } else {
        $stmt = $pdo->prepare('UPDATE students SET matric_no = ?, full_name = ?, email = ?, program = ?, level = ? WHERE id = ?');
        $stmt->execute([sanitize($_POST['matric_no'] ?? ''), $fullName, $email, sanitize($_POST['program'] ?? ''), (int)($_POST['level'] ?? 100), (int)$student['id']]);
    }
    if (!empty($student['user_id'])) {
        $stmt = $pdo->prepare('UPDATE users SET full_name = ?, email = ?, avatar_initials = ? WHERE id = ?');
        $stmt->execute([$fullName, $email, avatar_initials($fullName), (int)$student['user_id']]);
    }
    redirect_to($user['role'] === 'student' ? 'dashboard/student.php' : 'students/index.php');
}

$pageTitle = $user['role'] === 'student' ? 'My Profile' : 'Edit Student';
require_once __DIR__ . '/../templates/header.php';
?>
<h1 class="page-title"><?php echo htmlspecialchars($pageTitle); ?></h1>
<p class="page-subtitle">Keep student profile and login identity aligned.</p>
<div class="card" style="padding:24px;">
    <form method="post">
        <?php echo csrf_field(); ?>
        <?php require __DIR__ . '/partials/student_form.php'; ?>
        <button class="btn btn-primary" type="submit">Save Profile</button>
    </form>
</div>
<?php require_once __DIR__ . '/../templates/footer.php'; ?>
