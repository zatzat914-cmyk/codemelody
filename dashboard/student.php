<?php
require_once __DIR__ . '/../config/bootstrap.php';
$user = require_login($pdo);
require_role($user, ['student']);
$student = student_profile($pdo, (int)$user['id']);
if (!$student) redirect_to('students/edit.php');

$stmt = $pdo->prepare('SELECT c.*, e.enrolled_at, e.grade FROM enrollments e JOIN courses c ON c.id = e.course_id WHERE e.student_id = ? ORDER BY e.enrolled_at DESC');
$stmt->execute([$student['id']]);
$enrolled = $stmt->fetchAll();

$stmt = $pdo->prepare('SELECT c.* FROM courses c WHERE c.is_active = TRUE AND c.id NOT IN (SELECT course_id FROM enrollments WHERE student_id = ?) ORDER BY c.code LIMIT 8');
$stmt->execute([$student['id']]);
$available = $stmt->fetchAll();

$pageTitle = 'Student Dashboard';
require_once __DIR__ . '/../templates/header.php';
?>
<h1 class="page-title animate-in">Hi, <?php echo htmlspecialchars($student['full_name']); ?></h1>
<p class="page-subtitle animate-in delay-1">Continue your courses or explore what is open for enrollment.</p>

<div class="stats-grid">
    <div class="stat-card courses"><div class="stat-header"><div class="stat-icon"><i class="fas fa-book"></i></div></div><div class="stat-value"><?php echo count($enrolled); ?></div><div class="stat-label">Enrolled Courses</div></div>
    <div class="stat-card units"><div class="stat-header"><div class="stat-icon"><i class="fas fa-cube"></i></div></div><div class="stat-value"><?php echo array_sum(array_column($enrolled, 'credit_units')); ?></div><div class="stat-label">Credit Units</div></div>
    <div class="stat-card students"><div class="stat-header"><div class="stat-icon"><i class="fas fa-id-card"></i></div></div><div class="stat-value"><?php echo htmlspecialchars($student['level']); ?></div><div class="stat-label">Level</div></div>
    <div class="stat-card hours"><div class="stat-header"><div class="stat-icon"><i class="fas fa-compass"></i></div></div><div class="stat-value"><?php echo count($available); ?></div><div class="stat-label">Available Courses</div></div>
</div>

<div class="card" style="margin-bottom:24px;">
    <div class="card-header"><h3 class="card-title">My Enrolled Courses</h3></div>
    <div class="course-list">
        <?php if (!$enrolled): ?><p style="padding:0 0 24px;color:var(--gray);">You have not enrolled in any course yet.</p><?php endif; ?>
        <?php foreach ($enrolled as $course): ?>
            <div class="course-item">
                <div class="course-thumb <?php echo htmlspecialchars($course['color_class']); ?>"><?php echo htmlspecialchars(substr(str_replace(['HUI-', ' '], '', $course['code']), 0, 2)); ?></div>
                <div class="course-info"><div class="course-code"><?php echo htmlspecialchars($course['code']); ?></div><div class="course-name"><?php echo htmlspecialchars($course['title']); ?></div><div class="course-meta"><span><i class="fas fa-calendar"></i>Since <?php echo date('M j', strtotime($course['enrolled_at'])); ?></span><span><i class="fas fa-cube"></i><?php echo (int)$course['credit_units']; ?> Units</span></div></div>
                <button class="btn btn-primary" onclick="window.location='<?php echo app_url('courses/learn.php?id=' . (int)$course['id']); ?>'">Start Learning</button>
                <form method="post" action="<?php echo app_url('courses/enroll.php'); ?>"><input type="hidden" name="course_id" value="<?php echo (int)$course['id']; ?>"><input type="hidden" name="action" value="unenroll"><button class="btn btn-secondary" type="submit">Unenroll</button></form>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<div class="card">
    <div class="card-header"><h3 class="card-title">Explore Courses</h3><span class="card-action" onclick="window.location='<?php echo app_url('courses/index.php'); ?>'">View Catalog</span></div>
    <div class="course-list">
        <?php foreach ($available as $course): ?>
            <div class="course-item">
                <div class="course-thumb <?php echo htmlspecialchars($course['color_class']); ?>"><?php echo htmlspecialchars(substr(str_replace(['HUI-', ' '], '', $course['code']), 0, 2)); ?></div>
                <div class="course-info"><div class="course-code"><?php echo htmlspecialchars($course['code']); ?></div><div class="course-name"><?php echo htmlspecialchars($course['title']); ?></div><div class="course-meta"><span><i class="fas fa-cube"></i><?php echo (int)$course['credit_units']; ?> Units</span><span><?php echo ucfirst(htmlspecialchars($course['status'])); ?></span></div></div>
                <form method="post" action="<?php echo app_url('courses/enroll.php'); ?>"><input type="hidden" name="course_id" value="<?php echo (int)$course['id']; ?>"><input type="hidden" name="action" value="enroll"><button class="btn btn-primary" type="submit">Enroll</button></form>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php require_once __DIR__ . '/../templates/footer.php'; ?>
