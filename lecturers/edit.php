<?php
require_once __DIR__ . '/../config/bootstrap.php';
$user = require_login($pdo);
require_role($user, ['admin']);

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT * FROM users WHERE id = ? AND role = "lecturer"');
$stmt->execute([$id]);
$lecturer = $stmt->fetch();
if (!$lecturer) redirect_to('lecturers/index.php');

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf_token();
    $fullName = sanitize($_POST['full_name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $newPassword = trim((string)($_POST['password'] ?? ''));

    try {
        if ($newPassword !== '') {
            $stmt = $pdo->prepare('UPDATE users SET full_name = ?, email = ?, avatar_initials = ?, password = ? WHERE id = ? AND role = "lecturer"');
            $stmt->execute([$fullName, $email, avatar_initials($fullName), password_hash($newPassword, PASSWORD_BCRYPT), $id]);
        } else {
            $stmt = $pdo->prepare('UPDATE users SET full_name = ?, email = ?, avatar_initials = ? WHERE id = ? AND role = "lecturer"');
            $stmt->execute([$fullName, $email, avatar_initials($fullName), $id]);
        }
        redirect_to('lecturers/index.php');
    } catch (PDOException $e) {
        $error = 'Unable to update lecturer. The email may already exist.';
    }
}

$pageTitle = 'Edit Lecturer';
require_once __DIR__ . '/../templates/header.php';
?>
<h1 class="page-title">Edit Lecturer</h1>
<p class="page-subtitle">Update account details or set a new password.</p>
<?php if ($error): ?><div class="card" style="padding:16px;margin-bottom:20px;color:#991b1b;background:#fee2e2;"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
<div class="card" style="padding:24px;">
    <form method="post">
        <?php echo csrf_field(); ?>
        <?php require __DIR__ . '/partials/lecturer_form.php'; ?>
        <div class="form-group">
            <label class="form-label">New Password</label>
            <input class="form-input" type="text" name="password" placeholder="Leave blank to keep current password">
        </div>
        <div style="display:flex; gap:12px;">
            <button class="btn btn-primary" type="submit">Save Lecturer</button>
            <button class="btn btn-secondary" type="button" onclick="window.location='<?php echo app_url('lecturers/index.php'); ?>'">Cancel</button>
        </div>
    </form>
</div>
<?php require_once __DIR__ . '/../templates/footer.php'; ?>
