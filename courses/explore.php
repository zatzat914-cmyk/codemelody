<?php
require_once __DIR__ . '/../config/bootstrap.php';
$user = require_login($pdo);

$query = trim($_GET['q'] ?? '');

if ($query) {
    $like = '%' . $query . '%';
    $stmt = $pdo->prepare('
        SELECT c.*, u.full_name AS lecturer_name 
        FROM courses c 
        LEFT JOIN users u ON u.id = c.lecturer_id 
        WHERE c.is_active = TRUE 
          AND (c.code LIKE ? OR c.title LIKE ?)
        ORDER BY c.code
    ');
    $stmt->execute([$like, $like]);
    $courses = $stmt->fetchAll();
} else {
    $courses = [];
}

$student = $user['role'] === 'student' ? student_profile($pdo, (int)$user['id']) : null;
$enrolledIds = [];
if ($student) {
    $stmt = $pdo->prepare('SELECT course_id FROM enrollments WHERE student_id = ?');
    $stmt->execute([$student['id']]);
    $enrolledIds = array_map('intval', $stmt->fetchAll(PDO::FETCH_COLUMN));
}

$pageTitle = 'Search Results';
require_once __DIR__ . '/../templates/header.php';
?>
<h1 class="page-title">Search Results</h1>
<p class="page-subtitle">
    <?php if ($query): ?>
        Showing results for "<strong><?php echo htmlspecialchars($query); ?></strong>" &mdash; <?php echo count($courses); ?> course<?php echo count($courses) !== 1 ? 's' : ''; ?> found
    <?php else: ?>
        Enter a search term to find courses.
    <?php endif; ?>
</p>

<?php if (count($courses) > 0): ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Matching Courses (<?php echo count($courses); ?>)</h3>
    </div>
    <div class="course-list">
        <?php foreach ($courses as $course): ?>
            <div class="course-item">
                <div class="course-thumb <?php echo htmlspecialchars($course['color_class'] ?? 'indigo'); ?>">
                    <?php echo htmlspecialchars(substr(str_replace(['HUI-', ' '], '', $course['code']), 0, 2)); ?>
                </div>
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
                <button class="btn btn-secondary" onclick="window.location='<?php echo app_url('courses/show.php?id=' . (int)$course['id']); ?>'">Details</button>
                <?php if ($user['role'] === 'student' && in_array((int)$course['id'], $enrolledIds, true)): ?>
                    <button class="btn btn-primary" onclick="window.location='<?php echo app_url('courses/learn.php?id=' . (int)$course['id']); ?>'">Learn</button>
                <?php elseif ($user['role'] === 'student'): ?>
                    <form method="post" action="<?php echo app_url('courses/enroll.php'); ?>" style="display:inline;">
                        <input type="hidden" name="course_id" value="<?php echo (int)$course['id']; ?>">
                        <input type="hidden" name="action" value="enroll">
                        <button class="btn btn-primary" type="submit">Enroll</button>
                    </form>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php elseif ($query): ?>
<div class="card" style="padding:48px; text-align:center;">
    <i class="fas fa-search" style="font-size:48px; color:#cbd5e1; margin-bottom:16px;"></i>
    <h3 style="margin-bottom:8px;">No courses found</h3>
    <p style="color:var(--gray);">Try a different search term or browse the <a href="<?php echo app_url('courses/index.php'); ?>" style="color:var(--primary);">course catalog</a>.</p>
</div>
<?php endif; ?>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>
