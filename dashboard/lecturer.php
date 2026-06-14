<?php
require_once __DIR__ . '/../config/bootstrap.php';
$user = require_login($pdo);
require_role($user, ['lecturer', 'admin']);
$m = metrics($pdo);

$stmt = $pdo->prepare('SELECT * FROM courses WHERE lecturer_id = ? ORDER BY is_active DESC, code');
$stmt->execute([$user['id']]);
$courses = $stmt->fetchAll();
$activities = $pdo->query('SELECT a.*, u.full_name FROM activities a JOIN users u ON u.id = a.user_id ORDER BY a.created_at DESC LIMIT 5')->fetchAll();

$pageTitle = 'Lecturer Dashboard';
require_once __DIR__ . '/../templates/header.php';
?>
<h1 class="page-title animate-in">Welcome back, <?php echo htmlspecialchars($user['full_name']); ?></h1>
<p class="page-subtitle animate-in delay-1">You are managing <?php echo count($courses); ?> course<?php echo count($courses) === 1 ? '' : 's'; ?> this semester.</p>

<div class="stats-grid">
    <div class="stat-card courses"><div class="stat-header"><div class="stat-icon"><i class="fas fa-book"></i></div></div><div class="stat-value"><?php echo count($courses); ?></div><div class="stat-label">My Courses</div></div>
    <div class="stat-card students"><div class="stat-header"><div class="stat-icon"><i class="fas fa-users"></i></div></div><div class="stat-value"><?php echo $m['students']; ?></div><div class="stat-label">Students</div></div>
    <div class="stat-card units"><div class="stat-header"><div class="stat-icon"><i class="fas fa-cube"></i></div></div><div class="stat-value"><?php echo $m['units']; ?></div><div class="stat-label">Credit Units</div></div>
    <div class="stat-card hours"><div class="stat-header"><div class="stat-icon"><i class="fas fa-clock"></i></div></div><div class="stat-value"><?php echo $m['hours']; ?></div><div class="stat-label">Contact Hours</div></div>
</div>

<div class="two-column">
    <div class="card">
        <div class="card-header"><h3 class="card-title">My Courses</h3><span class="card-action" onclick="window.location='<?php echo app_url('courses/create.php'); ?>'">Add Course</span></div>
        <div class="course-list">
            <?php foreach ($courses as $course): ?>
                <div class="course-item" onclick="window.location='<?php echo app_url('courses/edit.php?id=' . (int)$course['id']); ?>'">
                    <div class="course-thumb <?php echo htmlspecialchars($course['color_class']); ?>"><?php echo htmlspecialchars(substr(str_replace(['HUI-', ' '], '', $course['code']), 0, 2)); ?></div>
                    <div class="course-info"><div class="course-code"><?php echo htmlspecialchars($course['code']); ?></div><div class="course-name"><?php echo htmlspecialchars($course['title']); ?></div><div class="course-meta"><span><i class="fas fa-cube"></i><?php echo (int)$course['credit_units']; ?> Units</span><span><i class="fas fa-clock"></i><?php echo (int)$course['lecture_hours']; ?> LH</span></div></div>
                    <span class="course-status <?php echo htmlspecialchars($course['status']); ?>"><?php echo ucfirst(htmlspecialchars($course['status'])); ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="card">
        <div class="card-header"><h3 class="card-title">Recent Activity</h3></div>
        <div class="activity-list">
            <?php foreach ($activities as $activity): ?>
                <div class="activity-item"><div class="activity-avatar"><?php echo htmlspecialchars(avatar_initials($activity['full_name'])); ?></div><div class="activity-content"><div class="activity-text"><?php echo $activity['description']; ?></div><div class="activity-time"><?php echo timeAgo($activity['created_at']); ?></div></div></div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../templates/footer.php'; ?>
