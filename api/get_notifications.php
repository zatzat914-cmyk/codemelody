<?php
require_once __DIR__ . '/../config/bootstrap.php';
$user = require_login($pdo);

header('Content-Type: application/json');

$stmt = $pdo->prepare('
    SELECT id, title, message, type, is_read, created_at 
    FROM notifications 
    WHERE user_id = ? 
    ORDER BY created_at DESC 
    LIMIT 20
');
$stmt->execute([(int)$user['id']]);
$notifications = $stmt->fetchAll();

$stmt = $pdo->prepare('SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = FALSE');
$stmt->execute([(int)$user['id']]);
$unreadCount = (int)$stmt->fetchColumn();

echo json_encode([
    'success' => true,
    'notifications' => $notifications,
    'unread_count' => $unreadCount
]);
