<?php
require_once __DIR__ . '/../config/bootstrap.php';
$user = require_login($pdo);
$student = $user['role'] === 'student' ? student_profile($pdo, (int)$user['id']) : null;

$courses = $pdo->query('SELECT c.*, u.full_name AS lecturer_name FROM courses c LEFT JOIN users u ON u.id = c.lecturer_id WHERE c.is_active = TRUE ORDER BY c.code')->fetchAll();
$enrolledIds = [];
if ($student) {
    $stmt = $pdo->prepare('SELECT course_id FROM enrollments WHERE student_id = ?');
    $stmt->execute([$student['id']]);
    $enrolledIds = array_map('intval', $stmt->fetchAll(PDO::FETCH_COLUMN));
}

$pageTitle = 'Course Catalog';
require_once __DIR__ . '/../templates/header.php';
?>
<div class="page-header animate-in">
    <h1 class="page-title">Course Catalog</h1>
    <p class="page-subtitle">Browse active courses and manage enrollment from one place.</p>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Active Courses (<?php echo count($courses); ?>)</h3>
        <?php if ($user['role'] !== 'student'): ?><span class="card-action" onclick="window.location='<?php echo app_url('courses/create.php'); ?>'">Add Course</span><?php endif; ?>
    </div>
    <div class="course-list">
        <?php foreach ($courses as $course): ?>
            <div class="course-item">
                <div class="course-thumb <?php echo htmlspecialchars($course['color_class']); ?>"><?php echo htmlspecialchars(substr(str_replace(['HUI-', ' '], '', $course['code']), 0, 2)); ?></div>
                <div class="course-info">
                    <div class="course-code"><?php echo htmlspecialchars($course['code']); ?></div>
                    <div class="course-name"><?php echo htmlspecialchars($course['title']); ?></div>
                    <div class="course-meta">
                        <span><i class="fas fa-cube"></i><?php echo (int)$course['credit_units']; ?> Units</span>
                        <span><i class="fas fa-clock"></i><?php echo (int)$course['lecture_hours']; ?> LH</span>
                        <span><i class="fas fa-user"></i><?php echo htmlspecialchars($course['lecturer_name'] ?? 'Unassigned'); ?></span>
                        <span><i class="fas fa-tag"></i><?php echo !empty($course['is_paid']) ? 'Paid - NGN ' . number_format((float)$course['price'], 2) : 'Free'; ?></span>
                    </div>
                </div>
                <?php if ($user['role'] === 'student'): ?>
                    <button class="btn btn-secondary" onclick="window.location='<?php echo app_url('courses/show.php?id=' . (int)$course['id']); ?>'">Details</button>
                    <?php if (in_array((int)$course['id'], $enrolledIds, true)): ?>
                        <button class="btn btn-primary" onclick="window.location='<?php echo app_url('courses/learn.php?id=' . (int)$course['id']); ?>'">Start Learning</button>
                        <form method="post" action="<?php echo app_url('courses/enroll.php'); ?>">
                            <input type="hidden" name="course_id" value="<?php echo (int)$course['id']; ?>">
                            <input type="hidden" name="action" value="unenroll"><?php echo csrf_field(); ?><button class="btn btn-secondary" type="submit">Unenroll</button>
                        </form>
                    <?php else: ?>
                        <form method="post" action="<?php echo app_url('courses/enroll.php'); ?>">
                            <input type="hidden" name="course_id" value="<?php echo (int)$course['id']; ?>">
                            <input type="hidden" name="action" value="enroll"><?php echo csrf_field(); ?><button class="btn btn-primary" type="submit">Enroll</button>
                        </form>
                    <?php endif; ?>
                <?php else: ?>
                    <div style="display:flex; gap:8px; align-items:center;">
                        <span class="course-status <?php echo htmlspecialchars($course['status']); ?>"><?php echo ucfirst(htmlspecialchars($course['status'])); ?></span>
                        <button class="btn btn-secondary" onclick="window.location='<?php echo app_url('courses/show.php?id=' . (int)$course['id']); ?>'">Details</button>
                        <button class="btn btn-secondary" onclick="window.location='<?php echo app_url('courses/edit.php?id=' . (int)$course['id']); ?>'">Edit</button>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php require_once __DIR__ . '/../templates/footer.php'; ?>
