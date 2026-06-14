<?php
require_once __DIR__ . '/config/bootstrap.php';
$user = require_login($pdo);

header('Content-Type: application/json');

$query = trim($_GET['q'] ?? '');

if (strlen($query) < 1) {
    echo json_encode(['success' => true, 'results' => []]);
    exit;
}

$escaped = str_replace(['%', '_'], ['\\%', '\\_'], $query);
$like = '%' . $escaped . '%';
$stmt = $pdo->prepare('
    SELECT id, code, title, color_class  
    FROM courses 
    WHERE is_active = TRUE 
      AND (code LIKE ? OR title LIKE ?)
    ORDER BY 
      CASE 
        WHEN code LIKE ? THEN 0 
        WHEN title LIKE ? THEN 1 
        ELSE 2 
      END,
      code ASC
    LIMIT 10
');
$stmt->execute([$like, $like, $like, $like]);
$results = $stmt->fetchAll();

echo json_encode(['success' => true, 'results' => $results]);
