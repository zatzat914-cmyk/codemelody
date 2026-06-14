<?php
require_once __DIR__ . '/../config/bootstrap.php';
$user = require_login($pdo);
require_role($user, ['admin']);

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare('DELETE FROM users WHERE id = ? AND role = "lecturer"');
$stmt->execute([$id]);

redirect_to('lecturers/index.php');
