<?php
require_once __DIR__ . '/config/bootstrap.php';
$user = require_login($pdo);
redirect_for_role($user['role']);
