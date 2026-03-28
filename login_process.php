<<<<<<< HEAD
<?php
session_start();
include 'connect_db.php';
$userName = $_POST['username'];
$password = $_POST['password'];

?>
=======
<?php
session_start();
include 'connect_db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.php");
    exit();
}

$userName = trim($_POST['userName'] ?? '');
$password = $_POST['password'] ?? '';

if (!$userName || !$password) {
    header("Location: login.php?error=1");
    exit();
}

// Fetch user by username (get hashed password + role)
$stmt = $conn->prepare("SELECT id, userName, password, role FROM users WHERE userName = ?");
$stmt->bind_param("s", $userName);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    // Secure password check using password_verify
    if (password_verify($password, $user['password'])) {
        $_SESSION['userId']   = $user['id'];
        $_SESSION['userName'] = $user['userName'];
        $_SESSION['role']     = $user['role'];

        // Redirect based on role
        if ($user['role'] === 'teacher') {
            header("Location: teacher_dashboard.php");
        } else {
            header("Location: dashboard.php");
        }
        exit();
    }
}

// Wrong credentials
header("Location: login.php?error=1");
exit();
?>
>>>>>>> 0de22fa (First git commit)
