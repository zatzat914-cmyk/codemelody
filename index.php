<?php
/**
 * index.php - Root entry point for CodeMelody
 * Redirects to the correct dashboard based on session role,
 * or to the login page if not authenticated.
 */

session_start();

if (isset($_SESSION['user_id']) && isset($_SESSION['role'])) {
    switch ($_SESSION['role']) {
        case 'admin':
            header('Location: dashboard/admin.php');
            exit;
        case 'lecturer':
            header('Location: dashboard/lecturer.php');
            exit;
        case 'student':
            header('Location: dashboard/student.php');
            exit;
        default:
            header('Location: auth/login.php');
            exit;
    }
} else {
    header('Location: auth/login.php');
    exit;
}
?>