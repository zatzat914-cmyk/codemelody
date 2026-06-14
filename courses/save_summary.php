<?php
require_once __DIR__ . '/../config/bootstrap.php';
$user = require_login($pdo);
require_role($user, ['student']);

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$courseId = (int)($input['course_id'] ?? 0);
$summary = trim((string)($input['summary'] ?? ''));

if (!$courseId || !$summary) {
    echo json_encode(['success' => false, 'message' => 'Course ID and summary are required']);
    exit;
}

// Verify enrollment
$stmt = $pdo->prepare('SELECT 1 FROM enrollments e JOIN students s ON s.id = e.student_id WHERE s.user_id = ? AND e.course_id = ?');
$stmt->execute([(int)$user['id'], $courseId]);
if (!$stmt->fetch()) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'You are not enrolled in this course']);
    exit;
}

// Upsert summary
$stmt = $pdo->prepare('INSERT INTO summaries (user_id, course_id, content) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE content = VALUES(content), updated_at = CURRENT_TIMESTAMP');
$stmt->execute([(int)$user['id'], $courseId, $summary]);

echo json_encode(['success' => true, 'message' => 'Summary saved successfully']);
