<?php
require_once __DIR__ . '/../config/bootstrap.php';

if (isset($_SESSION['user_id'], $_SESSION['role'])) {
    redirect_for_role($_SESSION['role']);
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email'] ?? '');
    $password = (string)($_POST['password'] ?? '');

    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $account = $stmt->fetch();

    if ($account && password_verify($password, $account['password'])) {
        $_SESSION['user_id'] = $account['id'];
        $_SESSION['role'] = $account['role'];
        redirect_for_role($account['role']);
    }

    $error = 'Invalid email or password.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CodeMelody - Login</title>
    <style>
        body { font-family: Inter, Arial, sans-serif; margin: 0; min-height: 100vh; display: grid; place-items: center; background: #eef2ff; color: #1e293b; }
        .auth-card { width: min(420px, calc(100% - 32px)); background: #fff; border: 1px solid #e2e8f0; border-radius: 18px; padding: 36px; box-shadow: 0 24px 60px rgba(15,23,42,.12); }
        .logo { width: 56px; height: 56px; border-radius: 14px; display: grid; place-items: center; background: linear-gradient(135deg,#6366f1,#14b8a6); color: #fff; font-weight: 800; font-size: 26px; margin-bottom: 18px; }
        h1 { margin: 0 0 8px; font-size: 28px; }
        p { margin: 0 0 24px; color: #64748b; }
        label { display: block; margin-bottom: 8px; font-weight: 700; font-size: 14px; }
        input { width: 100%; box-sizing: border-box; border: 1px solid #cbd5e1; border-radius: 10px; padding: 13px 14px; margin-bottom: 16px; font: inherit; }
        button { width: 100%; border: 0; border-radius: 10px; padding: 14px; background: #4f46e5; color: #fff; font-weight: 800; cursor: pointer; }
        .alert { background: #fee2e2; color: #991b1b; border-radius: 10px; padding: 12px; margin-bottom: 16px; }
        .link { margin-top: 18px; text-align: center; font-size: 14px; }
        a { color: #4f46e5; font-weight: 800; text-decoration: none; }
    </style>
</head>
<body>
    <form class="auth-card" method="post">
        <div class="logo">C</div>
        <h1>Welcome back</h1>
        <p>Sign in as a student, lecturer, or admin.</p>
        <?php if ($error): ?><div class="alert"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
        <label>Email</label>
        <input type="email" name="email" required autofocus>
        <label>Password</label>
        <input type="password" name="password" required>
        <button type="submit">Sign In</button>
        <div class="link">New student? <a href="<?php echo app_url('auth/register.php'); ?>">Create an account</a></div>
    </form>
</body>
</html>
