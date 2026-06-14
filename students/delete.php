<?php
require_once __DIR__ . '/../config/bootstrap.php';
$user = require_login($pdo);
require_role($user, ['admin']);

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT user_id FROM students WHERE id = ?');
$stmt->execute([$id]);
$student = $stmt->fetch();

if ($student) {
    if (!empty($student['user_id'])) {
        $stmt = $pdo->prepare('DELETE FROM users WHERE id = ?');
        $stmt->execute([(int)$student['user_id']]);
    } else {
        $stmt = $pdo->prepare('DELETE FROM students WHERE id = ?');
        $stmt->execute([$id]);
    }
}

redirect_to('students/index.php');
