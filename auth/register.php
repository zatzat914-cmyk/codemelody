<?php
require_once __DIR__ . '/../config/bootstrap.php';

$error = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf_token();
    $fullName = sanitize($_POST['full_name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $password = (string)($_POST['password'] ?? '');
    $matricNo = sanitize($_POST['matric_no'] ?? '');
    $program = sanitize($_POST['program'] ?? '');
    $level = (int)($_POST['level'] ?? 100);

    if ($fullName === '' || $email === '' || $password === '' || $matricNo === '') {
        $error = 'Please complete all required fields.';
    } else {
        try {
            $pdo->beginTransaction();
            $stmt = $pdo->prepare('INSERT INTO users (email, password, full_name, role, avatar_initials) VALUES (?, ?, ?, "student", ?)');
            $stmt->execute([$email, password_hash($password, PASSWORD_BCRYPT), $fullName, avatar_initials($fullName)]);
            $userId = (int)$pdo->lastInsertId();

            $stmt = $pdo->prepare('INSERT INTO students (user_id, matric_no, full_name, email, program, level) VALUES (?, ?, ?, ?, ?, ?)');
            $stmt->execute([$userId, $matricNo, $fullName, $email, $program, $level]);
            $pdo->commit();
            $success = 'Student account created. You can sign in now.';
        } catch (PDOException $e) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            $error = 'That email or matric number is already registered.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CodeMelody - Student Registration</title>
    <style>
        body { font-family: Inter, Arial, sans-serif; margin: 0; min-height: 100vh; display: grid; place-items: center; background: #f8fafc; color: #1e293b; }
        .auth-card { width: min(520px, calc(100% - 32px)); background: #fff; border: 1px solid #e2e8f0; border-radius: 18px; padding: 34px; box-shadow: 0 24px 60px rgba(15,23,42,.12); }
        h1 { margin: 0 0 8px; font-size: 28px; }
        p { margin: 0 0 22px; color: #64748b; }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
        label { display: block; margin-bottom: 8px; font-weight: 700; font-size: 14px; }
        input { width: 100%; box-sizing: border-box; border: 1px solid #cbd5e1; border-radius: 10px; padding: 12px 14px; margin-bottom: 14px; font: inherit; }
        button { width: 100%; border: 0; border-radius: 10px; padding: 14px; background: #0f766e; color: #fff; font-weight: 800; cursor: pointer; }
        .alert { border-radius: 10px; padding: 12px; margin-bottom: 16px; }
        .error { background: #fee2e2; color: #991b1b; }
        .success { background: #dcfce7; color: #166534; }
        .link { margin-top: 18px; text-align: center; font-size: 14px; }
        a { color: #0f766e; font-weight: 800; text-decoration: none; }
        @media (max-width: 640px) { .grid { grid-template-columns: 1fr; gap: 0; } }
    </style>
</head>
<body>
    <form class="auth-card" method="post">
        <h1>Create student account</h1>
        <p>Lecturer and admin accounts are created from the admin dashboard.</p>
        <?php if ($error): ?><div class="alert error"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
        <?php if ($success): ?><div class="alert success"><?php echo htmlspecialchars($success); ?></div><?php endif; ?>
        <label>Full Name</label>
        <input type="text" name="full_name" required>
        <div class="grid">
            <div><label>Email</label><input type="email" name="email" required></div>
            <div><label>Matric Number</label><input type="text" name="matric_no" required></div>
        </div>
        <div class="grid">
            <div><label>Program</label><input type="text" name="program" value="Computer Science"></div>
            <div><label>Level</label><input type="number" name="level" value="200" min="100" step="100"></div>
        </div>
        <label>Password</label>
        <input type="password" name="password" required>
        <?php echo csrf_field(); ?>
        <button type="submit">Register</button>
        <div class="link">Already registered? <a href="<?php echo app_url('auth/login.php'); ?>">Sign in</a></div>
    </form>
</body>
</html>
