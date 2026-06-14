<?php
require_once __DIR__ . '/../config/bootstrap.php';
$user = require_login($pdo);
require_role($user, ['admin']);
$m = metrics($pdo);

$users = $pdo->query('SELECT id, full_name, email, role, created_at FROM users ORDER BY created_at DESC LIMIT 8')->fetchAll();
$pageTitle = 'Admin Dashboard';
require_once __DIR__ . '/../templates/header.php';
?>
<h1 class="page-title animate-in">Platform overview</h1>
<p class="page-subtitle animate-in delay-1">Manage users, courses, and enrollment activity across CodeMelody.</p>

<div class="stats-grid">
    <div class="stat-card students"><div class="stat-header"><div class="stat-icon"><i class="fas fa-users"></i></div></div><div class="stat-value"><?php echo $m['users']; ?></div><div class="stat-label">Total Users</div></div>
    <div class="stat-card courses"><div class="stat-header"><div class="stat-icon"><i class="fas fa-book"></i></div></div><div class="stat-value"><?php echo $m['courses']; ?></div><div class="stat-label">Active Courses</div></div>
    <div class="stat-card units"><div class="stat-header"><div class="stat-icon"><i class="fas fa-user-graduate"></i></div></div><div class="stat-value"><?php echo $m['students']; ?></div><div class="stat-label">Student Profiles</div></div>
    <div class="stat-card hours"><div class="stat-header"><div class="stat-icon"><i class="fas fa-chart-line"></i></div></div><div class="stat-value"><?php echo $m['enrollments']; ?></div><div class="stat-label">Enrollments</div></div>
</div>

<div class="quick-actions">
    <div class="action-card" onclick="window.location='<?php echo app_url('students/create.php'); ?>'"><div class="action-icon"><i class="fas fa-user-plus"></i></div><div class="action-title">Add Student</div><div class="action-desc">Create profile and login</div></div>
    <div class="action-card" onclick="window.location='<?php echo app_url('lecturers/create.php'); ?>'"><div class="action-icon"><i class="fas fa-chalkboard-user"></i></div><div class="action-title">Add Lecturer</div><div class="action-desc">Create lecturer login</div></div>
    <div class="action-card" onclick="window.location='<?php echo app_url('students/index.php'); ?>'"><div class="action-icon"><i class="fas fa-users"></i></div><div class="action-title">Students</div><div class="action-desc">View and edit records</div></div>
    <div class="action-card" onclick="window.location='<?php echo app_url('courses/index.php'); ?>'"><div class="action-icon"><i class="fas fa-book-open"></i></div><div class="action-title">Courses</div><div class="action-desc">Manage catalog</div></div>
</div>

<div class="card">
    <div class="card-header"><h3 class="card-title">Recent Users</h3></div>
    <div style="overflow-x:auto; padding: 0 24px 24px;">
        <table style="width:100%; border-collapse:collapse; min-width:620px;">
            <thead><tr style="border-bottom:1px solid #e2e8f0;"><th style="padding:14px;text-align:left;">Name</th><th style="padding:14px;text-align:left;">Email</th><th style="padding:14px;text-align:left;">Role</th><th style="padding:14px;text-align:left;">Joined</th></tr></thead>
            <tbody>
            <?php foreach ($users as $account): ?>
                <tr style="border-bottom:1px solid #f1f5f9;"><td style="padding:14px;font-weight:700;"><?php echo htmlspecialchars($account['full_name']); ?></td><td style="padding:14px;"><?php echo htmlspecialchars($account['email']); ?></td><td style="padding:14px;"><?php echo ucfirst(htmlspecialchars($account['role'])); ?></td><td style="padding:14px;"><?php echo date('M j, Y', strtotime($account['created_at'])); ?></td></tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require_once __DIR__ . '/../templates/footer.php'; ?>
