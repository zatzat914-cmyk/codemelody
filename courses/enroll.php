<?php
require_once __DIR__ . '/../config/bootstrap.php';
$user = require_login($pdo);
require_role($user, ['student']);
$student = student_profile($pdo, (int)$user['id']);
if (!$student) redirect_to('students/edit.php');

verify_csrf_token();

$courseId = (int)($_POST['course_id'] ?? 0);
$action = $_POST['action'] ?? '';

$allowedActions = ['enroll', 'unenroll'];
if (!in_array($action, $allowedActions, true)) {
    redirect_to('dashboard/student.php');
}

$stmt = $pdo->prepare('SELECT id, is_paid, price FROM courses WHERE id = ? AND is_active = TRUE');
$stmt->execute([$courseId]);
$course = $stmt->fetch();

if (!$course) {
    redirect_to('dashboard/student.php');
}

if ($action === 'unenroll') {
    $stmt = $pdo->prepare('DELETE FROM enrollments WHERE student_id = ? AND course_id = ?');
    $stmt->execute([$student['id'], $courseId]);
} elseif ($course['is_paid']) {
    redirect_to('courses/show.php?id=' . $courseId);
} else {
    $stmt = $pdo->prepare('INSERT IGNORE INTO enrollments (student_id, course_id) VALUES (?, ?)');
    $stmt->execute([$student['id'], $courseId]);
}

redirect_to('dashboard/student.php');
