<?php
require_once __DIR__ . '/../config/bootstrap.php';
$user = require_login($pdo);
require_role($user, ['lecturer', 'admin']);

$students = $pdo->query('SELECT s.*, u.role, u.created_at AS account_created FROM students s LEFT JOIN users u ON u.id = s.user_id ORDER BY s.full_name')->fetchAll();
$pageTitle = 'Students Directory';
require_once __DIR__ . '/../templates/header.php';
?>
<div class="page-header animate-in">
    <h1 class="page-title">Students Directory</h1>
    <p class="page-subtitle">View registered students and manage profile data.</p>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">All Students (<?php echo count($students); ?>)</h3>
        <?php if ($user['role'] === 'admin'): ?><span class="card-action" onclick="window.location='<?php echo app_url('students/create.php'); ?>'">Add Student</span><?php endif; ?>
    </div>
    <div style="overflow-x:auto; padding:0 24px 24px;">
        <table style="width:100%; border-collapse:collapse; min-width:760px;">
            <thead><tr style="border-bottom:2px solid var(--light-gray);"><th style="padding:16px;text-align:left;">Student</th><th style="padding:16px;text-align:left;">Matric No</th><th style="padding:16px;text-align:left;">Email</th><th style="padding:16px;text-align:left;">Program</th><th style="padding:16px;text-align:left;">Level</th><th style="padding:16px;text-align:left;">Actions</th></tr></thead>
            <tbody>
            <?php foreach ($students as $student): ?>
                <tr style="border-bottom:1px solid var(--light-gray);">
                    <td style="padding:16px;font-weight:700;"><?php echo htmlspecialchars($student['full_name']); ?></td>
                    <td style="padding:16px;color:var(--primary);font-family:monospace;font-weight:700;"><?php echo htmlspecialchars($student['matric_no']); ?></td>
                    <td style="padding:16px;"><?php echo htmlspecialchars($student['email']); ?></td>
                    <td style="padding:16px;"><?php echo htmlspecialchars($student['program']); ?></td>
                    <td style="padding:16px;"><?php echo (int)$student['level']; ?></td>
                    <td style="padding:16px;">
                        <?php if ($user['role'] === 'admin'): ?>
                            <button class="btn btn-secondary" onclick="window.location='<?php echo app_url('students/edit.php?id=' . (int)$student['id']); ?>'">Edit</button>
                            <button class="btn btn-secondary" onclick="if(confirm('Delete this student?')) window.location='<?php echo app_url('students/delete.php?id=' . (int)$student['id']); ?>'">Delete</button>
                        <?php else: ?>
                            <span style="color:var(--gray);">View only</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require_once __DIR__ . '/../templates/footer.php'; ?>
