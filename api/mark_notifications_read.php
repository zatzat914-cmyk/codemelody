<?php
require_once __DIR__ . '/../config/bootstrap.php';
$user = require_login($pdo);

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$ids = $input['ids'] ?? null;

if (is_array($ids) && count($ids) > 0) {
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $pdo->prepare("UPDATE notifications SET is_read = TRUE WHERE id IN ($placeholders) AND user_id = ?");
    $params = array_merge(array_map('intval', $ids), [(int)$user['id']]);
    $stmt->execute($params);
} else {
    $stmt = $pdo->prepare('UPDATE notifications SET is_read = TRUE WHERE user_id = ?');
    $stmt->execute([(int)$user['id']]);
}

echo json_encode(['success' => true]);
