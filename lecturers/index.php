<?php
require_once __DIR__ . '/../config/bootstrap.php';
$user = require_login($pdo);
require_role($user, ['admin']);

$lecturers = $pdo->query('
    SELECT u.id, u.full_name, u.email, u.avatar_initials, u.created_at, COUNT(c.id) AS course_count
    FROM users u
    LEFT JOIN courses c ON c.lecturer_id = u.id AND c.is_active = TRUE
    WHERE u.role = "lecturer"
    GROUP BY u.id, u.full_name, u.email, u.avatar_initials, u.created_at
    ORDER BY u.full_name
')->fetchAll();

$pageTitle = 'Lecturers';
require_once __DIR__ . '/../templates/header.php';
?>
<div class="page-header animate-in">
    <h1 class="page-title">Lecturers</h1>
    <p class="page-subtitle">Create, update, and remove lecturer accounts.</p>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">All Lecturers (<?php echo count($lecturers); ?>)</h3>
        <span class="card-action" onclick="window.location='<?php echo app_url('lecturers/create.php'); ?>'">Add Lecturer</span>
    </div>
    <div style="overflow-x:auto; padding:0 24px 24px;">
        <table style="width:100%; border-collapse:collapse; min-width:720px;">
            <thead>
                <tr style="border-bottom:2px solid var(--light-gray);">
                    <th style="padding:16px;text-align:left;">Lecturer</th>
                    <th style="padding:16px;text-align:left;">Email</th>
                    <th style="padding:16px;text-align:left;">Courses</th>
                    <th style="padding:16px;text-align:left;">Joined</th>
                    <th style="padding:16px;text-align:left;">Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($lecturers as $lecturer): ?>
                <tr style="border-bottom:1px solid var(--light-gray);">
                    <td style="padding:16px;font-weight:700;"><?php echo htmlspecialchars($lecturer['full_name']); ?></td>
                    <td style="padding:16px;"><?php echo htmlspecialchars($lecturer['email']); ?></td>
                    <td style="padding:16px;"><?php echo (int)$lecturer['course_count']; ?></td>
                    <td style="padding:16px;"><?php echo date('M j, Y', strtotime($lecturer['created_at'])); ?></td>
                    <td style="padding:16px;">
                        <button class="btn btn-secondary" onclick="window.location='<?php echo app_url('lecturers/edit.php?id=' . (int)$lecturer['id']); ?>'">Edit</button>
                        <button class="btn btn-secondary" onclick="if(confirm('Delete this lecturer? Their courses will become unassigned.')) window.location='<?php echo app_url('lecturers/delete.php?id=' . (int)$lecturer['id']); ?>'">Delete</button>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require_once __DIR__ . '/../templates/footer.php'; ?>
