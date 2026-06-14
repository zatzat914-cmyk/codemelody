<?php
require_once __DIR__ . '/../config/bootstrap.php';
$user = require_login($pdo);
require_role($user, ['lecturer', 'admin']);
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = sanitize($_POST['status'] ?? 'compulsory');
    $colorClass = $status === 'elective' ? 'cyan' : ($status === 'required' ? 'amber' : 'indigo');
    try {
        $stmt = $pdo->prepare('INSERT INTO courses (code, title, credit_units, lecture_hours, practical_hours, status, color_class, is_paid, price, description, video_url, learning_content, lecturer_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([
            sanitize($_POST['code'] ?? ''),
            sanitize($_POST['title'] ?? ''),
            (int)($_POST['credit_units'] ?? 2),
            (int)($_POST['lecture_hours'] ?? 0),
            (int)($_POST['practical_hours'] ?? 0),
            $status,
            $colorClass,
            (int)($_POST['is_paid'] ?? 0),
            (float)($_POST['price'] ?? 0),
            sanitize($_POST['description'] ?? ''),
            sanitize($_POST['video_url'] ?? ''),
            sanitize($_POST['learning_content'] ?? ''),
            (int)$user['id'],
        ]);
        redirect_to('courses/index.php');
    } catch (PDOException $e) {
        $error = 'Unable to create course. Check for duplicate or missing values.';
    }
}

$pageTitle = 'Create Course';
require_once __DIR__ . '/../templates/header.php';
?>
<h1 class="page-title">Create Course</h1>
<p class="page-subtitle">Add a course to the active catalog.</p>
<?php if ($error): ?><div class="card" style="padding:16px;margin-bottom:20px;color:#991b1b;background:#fee2e2;"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
<div class="card" style="padding:24px;">
    <form method="post">
        <?php require __DIR__ . '/partials/course_form.php'; ?>
        <button class="btn btn-primary" type="submit">Create Course</button>
    </form>
</div>
<?php require_once __DIR__ . '/../templates/footer.php'; ?>
