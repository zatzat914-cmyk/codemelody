<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$isProduction = (bool)getenv('RENDER');

define('IS_PRODUCTION', $isProduction);
define('APP_BASE', $isProduction ? '' : (getenv('APP_BASE') ?: '/codemelody'));

define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_PORT', getenv('DB_PORT') ?: '3306');
define('DB_NAME', getenv('DB_NAME') ?: 'codemelody');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');

if ($isProduction) {
    error_reporting(0);
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');
    ini_set('session.use_only_cookies', '1');
    ini_set('session.cookie_httponly', '1');
    ini_set('session.cookie_samesite', 'Lax');
    ini_set('session.cookie_secure', getenv('RENDER') ? '1' : '0');
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
}

try {
    $dsn = 'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=utf8mb4';
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (PDOException $e) {
    error_log('CRITICAL DATABASE ERROR: ' . $e->getMessage());
    http_response_code(500);
    die('An internal error occurred. Please try again later.');
}
