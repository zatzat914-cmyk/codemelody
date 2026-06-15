<?php
require_once __DIR__ . '/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (IS_PRODUCTION && empty($_SERVER['HTTPS']) && ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') !== 'https') {
    header('Location: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    exit;
}

function app_url(string $path = ''): string {
    $base = defined('APP_BASE') ? APP_BASE : '';
    return rtrim($base, '/') . '/' . ltrim($path, '/');
}

function redirect_to(string $path): void {
    header('Location: ' . app_url($path));
    exit;
}

function csrf_token(): string {
    if (empty($_SESSION['_csrf_token'])) {
        $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['_csrf_token'];
}

function csrf_field(): string {
    return '<input type="hidden" name="_csrf_token" value="' . csrf_token() . '">';
}

function verify_csrf_token(): void {
    $token = $_POST['_csrf_token'] ?? '';
    if (empty($_SESSION['_csrf_token']) || !hash_equals($_SESSION['_csrf_token'], $token)) {
        http_response_code(419);
        die('Session expired or invalid request. Please go back and try again.');
    }
}

function sanitize($input): string {
    return htmlspecialchars(strip_tags(trim((string)$input)), ENT_QUOTES, 'UTF-8');
}

function avatar_initials(string $name): string {
    $parts = preg_split('/\s+/', trim($name));
    $first = $parts[0][0] ?? 'U';
    $last = count($parts) > 1 ? ($parts[count($parts) - 1][0] ?? '') : ($parts[0][1] ?? '');
    return strtoupper($first . $last);
}

function timeAgo($datetime): string {
    $diff = time() - strtotime($datetime);
    if ($diff < 60) return 'Just now';
    if ($diff < 3600) return floor($diff / 60) . ' minutes ago';
    if ($diff < 86400) return floor($diff / 3600) . ' hours ago';
    return floor($diff / 86400) . ' days ago';
}

function redirect_for_role(string $role): void {
    if ($role === 'admin') redirect_to('dashboard/admin.php');
    if ($role === 'student') redirect_to('dashboard/student.php');
    redirect_to('dashboard/lecturer.php');
}

function current_user(PDO $pdo): ?array {
    if (empty($_SESSION['user_id'])) return null;
    $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch() ?: null;
}

function require_login(PDO $pdo): array {
    $user = current_user($pdo);
    if (!$user) {
        session_destroy();
        redirect_to('auth/login.php');
    }
    $_SESSION['role'] = $user['role'];
    _rotate_session();
    return $user;
}

function require_role(array $user, array $roles): void {
    if (!in_array($user['role'], $roles, true)) {
        redirect_for_role($user['role']);
    }
}

function student_profile(PDO $pdo, int $userId): ?array {
    $stmt = $pdo->prepare('SELECT * FROM students WHERE user_id = ?');
    $stmt->execute([$userId]);
    return $stmt->fetch() ?: null;
}

function metrics(PDO $pdo): array {
    $data = ['students' => 0, 'courses' => 0, 'units' => 0, 'hours' => 0, 'enrollments' => 0, 'users' => 0];
    try {
        $data['students'] = (int)$pdo->query('SELECT COUNT(*) FROM students')->fetchColumn();
        $data['courses'] = (int)$pdo->query('SELECT COUNT(*) FROM courses WHERE is_active = TRUE')->fetchColumn();
        $data['units'] = (int)$pdo->query('SELECT COALESCE(SUM(credit_units),0) FROM courses WHERE is_active = TRUE')->fetchColumn();
        $data['hours'] = (int)$pdo->query('SELECT COALESCE(SUM(lecture_hours + practical_hours),0) FROM courses WHERE is_active = TRUE')->fetchColumn();
        $data['users'] = (int)$pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
        $data['enrollments'] = (int)$pdo->query('SELECT COUNT(*) FROM enrollments')->fetchColumn();
    } catch (PDOException $e) {
    }
    return $data;
}

function _rotate_session(): void {
    if (mt_rand(1, 5) === 1) {
        session_regenerate_id(true);
    }
}
