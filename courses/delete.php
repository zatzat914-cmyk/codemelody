<?php
require_once __DIR__ . '/../config/bootstrap.php';
$user = require_login($pdo);
require_role($user, ['lecturer', 'admin']);
$id = (int)($_GET['id'] ?? 0);

$sql = $user['role'] === 'admin'
    ? 'UPDATE courses SET is_active = FALSE WHERE id = ?'
    : 'UPDATE courses SET is_active = FALSE WHERE id = ? AND lecturer_id = ?';
$stmt = $pdo->prepare($sql);
$stmt->execute($user['role'] === 'admin' ? [$id] : [$id, $user['id']]);
redirect_to('courses/index.php');
