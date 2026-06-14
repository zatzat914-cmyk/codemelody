<?php
require_once __DIR__ . '/../config/bootstrap.php';
$user = require_login($pdo);
require_role($user, ['student']);
$student = student_profile($pdo, (int)$user['id']);
if (!$student) redirect_to('students/edit.php');

$courseId = (int)($_POST['course_id'] ?? 0);
$action = $_POST['action'] ?? 'enroll';

if ($courseId > 0 && $action === 'unenroll') {
    $stmt = $pdo->prepare('DELETE FROM enrollments WHERE student_id = ? AND course_id = ?');
    $stmt->execute([$student['id'], $courseId]);
} elseif ($courseId > 0) {
    $stmt = $pdo->prepare('INSERT IGNORE INTO enrollments (student_id, course_id) VALUES (?, ?)');
    $stmt->execute([$student['id'], $courseId]);
}

redirect_to('dashboard/student.php');
