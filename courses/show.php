<?php
require_once __DIR__ . '/../config/bootstrap.php';
$user = require_login($pdo);
$student = $user['role'] === 'student' ? student_profile($pdo, (int)$user['id']) : null;

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT c.*, u.full_name AS lecturer_name FROM courses c LEFT JOIN users u ON u.id = c.lecturer_id WHERE c.id = ? AND c.is_active = TRUE');
$stmt->execute([$id]);
$course = $stmt->fetch();
if (!$course) redirect_to('courses/index.php');

$isEnrolled = false;
if ($student) {
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM enrollments WHERE student_id = ? AND course_id = ?');
    $stmt->execute([$student['id'], $id]);
    $isEnrolled = (bool)$stmt->fetchColumn();
}

$pageTitle = 'Course Details';
require_once __DIR__ . '/../templates/header.php';
?>
<h1 class="page-title"><?php echo htmlspecialchars($course['title']); ?></h1>
<p class="page-subtitle"><?php echo htmlspecialchars($course['code']); ?> with <?php echo htmlspecialchars($course['lecturer_name'] ?? 'Unassigned lecturer'); ?></p>

<div class="stats-grid">
    <div class="stat-card courses"><div class="stat-header"><div class="stat-icon"><i class="fas fa-cube"></i></div></div><div class="stat-value"><?php echo (int)$course['credit_units']; ?></div><div class="stat-label">Credit Units</div></div>
    <div class="stat-card hours"><div class="stat-header"><div class="stat-icon"><i class="fas fa-clock"></i></div></div><div class="stat-value"><?php echo (int)$course['lecture_hours'] + (int)$course['practical_hours']; ?></div><div class="stat-label">Contact Hours</div></div>
    <div class="stat-card units"><div class="stat-header"><div class="stat-icon"><i class="fas fa-tag"></i></div></div><div class="stat-value" style="font-size:24px;"><?php echo !empty($course['is_paid']) ? 'Paid' : 'Free'; ?></div><div class="stat-label"><?php echo !empty($course['is_paid']) ? 'NGN ' . number_format((float)$course['price'], 2) : 'No payment required'; ?></div></div>
    <div class="stat-card students"><div class="stat-header"><div class="stat-icon"><i class="fas fa-list-check"></i></div></div><div class="stat-value" style="font-size:24px;"><?php echo ucfirst(htmlspecialchars($course['status'])); ?></div><div class="stat-label">Course Status</div></div>
</div>

<div class="card" style="padding:24px;">
    <h3 class="card-title" style="margin-bottom:12px;">Course Description</h3>
    <p style="color:var(--gray); line-height:1.7;"><?php echo nl2br(htmlspecialchars($course['description'] ?: 'No course description has been added yet.')); ?></p>
    <div style="display:flex; gap:12px; margin-top:24px;">
        <?php if ($user['role'] === 'student'): ?>
            <?php if ($isEnrolled): ?>
                <button class="btn btn-primary" onclick="window.location='<?php echo app_url('courses/learn.php?id=' . (int)$course['id']); ?>'">Start Learning</button>
                <form method="post" action="<?php echo app_url('courses/enroll.php'); ?>">
                    <input type="hidden" name="course_id" value="<?php echo (int)$course['id']; ?>">
                    <input type="hidden" name="action" value="unenroll">
                    <button class="btn btn-secondary" type="submit">Unenroll</button>
                </form>
            <?php else: ?>
                <form method="post" action="<?php echo app_url('courses/enroll.php'); ?>">
                    <input type="hidden" name="course_id" value="<?php echo (int)$course['id']; ?>">
                    <input type="hidden" name="action" value="enroll">
                    <button class="btn btn-primary" type="submit">Enroll to Learn</button>
                </form>
            <?php endif; ?>
        <?php else: ?>
            <button class="btn btn-primary" onclick="window.location='<?php echo app_url('courses/edit.php?id=' . (int)$course['id']); ?>'">Edit Course</button>
        <?php endif; ?>
        <button class="btn btn-secondary" onclick="window.location='<?php echo app_url('courses/index.php'); ?>'">Back to Catalog</button>
    </div>
</div>
<?php require_once __DIR__ . '/../templates/footer.php'; ?>
