<?php
// auth.php — include at the top of every protected page

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Require any logged-in user. Redirects to login if not authenticated.
 */
function requireLogin() {
    if (!isset($_SESSION['userId'])) {
        header("Location: login.php");
        exit();
    }
}

/**
 * Require the logged-in user to be a teacher.
 */
function requireTeacher() {
    requireLogin();
    if ($_SESSION['role'] !== 'teacher') {
        header("Location: dashboard.php");
        exit();
    }
}

/**
 * Require the logged-in user to be a student.
 */
function requireStudent() {
    requireLogin();
    if ($_SESSION['role'] !== 'student') {
        header("Location: teacher_dashboard.php");
        exit();
    }
}
?>
