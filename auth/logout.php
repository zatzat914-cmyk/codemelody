<?php
require_once __DIR__ . '/../config/bootstrap.php';
$_SESSION = [];
$params = session_get_cookie_params();
setcookie(session_name(), '', time() - 86400, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
session_destroy();
redirect_to('auth/login.php');
