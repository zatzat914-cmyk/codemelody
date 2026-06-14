<?php
require_once __DIR__ . '/../config/bootstrap.php';
$user = require_login($pdo);
require_role($user, ['lecturer', 'admin']);

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT * FROM courses WHERE id = ?');
$stmt->execute([$id]);
$course = $stmt->fetch();
if (!$course) redirect_to('courses/index.php');
if ($user['role'] !== 'admin' && (int)$course['lecturer_id'] !== (int)$user['id']) redirect_to('courses/index.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = sanitize($_POST['status'] ?? 'compulsory');
    $stmt = $pdo->prepare('UPDATE courses SET code = ?, title = ?, credit_units = ?, lecture_hours = ?, practical_hours = ?, status = ?, is_paid = ?, price = ?, description = ?, video_url = ?, learning_content = ? WHERE id = ?');
    $stmt->execute([
        sanitize($_POST['code'] ?? ''),
        sanitize($_POST['title'] ?? ''),
        (int)($_POST['credit_units'] ?? 2),
        (int)($_POST['lecture_hours'] ?? 0),
        (int)($_POST['practical_hours'] ?? 0),
        $status,
        (int)($_POST['is_paid'] ?? 0),
        (float)($_POST['price'] ?? 0),
        sanitize($_POST['description'] ?? ''),
        sanitize($_POST['video_url'] ?? ''),
        sanitize($_POST['learning_content'] ?? ''),
        $id,
    ]);
    redirect_to('courses/index.php');
}

$pageTitle = 'Edit Course';
require_once __DIR__ . '/../templates/header.php';
?>
<h1 class="page-title">Edit Course</h1>
<p class="page-subtitle"><?php echo htmlspecialchars($course['code']); ?> metadata and delivery hours.</p>
<div class="card" style="padding:24px;">
    <form method="post">
        <?php require __DIR__ . '/partials/course_form.php'; ?>
        <div style="display:flex; gap:12px;">
            <button class="btn btn-primary" type="submit">Save Changes</button>
            <button class="btn btn-secondary" type="button" onclick="window.location='<?php echo app_url('courses/delete.php?id=' . $id); ?>'">Delete</button>
        </div>
    </form>
</div>
<?php require_once __DIR__ . '/../templates/footer.php'; ?>
