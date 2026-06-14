<?php
require_once __DIR__ . '/../config/bootstrap.php';
$user = require_login($pdo);
require_role($user, ['student']);

header('Content-Type: application/json');

$courseId = (int)($_GET['course_id'] ?? 0);

if (!$courseId) {
    echo json_encode(['success' => false, 'message' => 'Course ID is required']);
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

$stmt = $pdo->prepare('SELECT content FROM summaries WHERE user_id = ? AND course_id = ?');
$stmt->execute([(int)$user['id'], $courseId]);
$row = $stmt->fetch();

echo json_encode(['success' => true, 'summary' => $row ? $row['content'] : null]);
