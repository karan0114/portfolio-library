<?php
session_start();
include 'connect_db.php';

// Get data from form
$userName = $_POST['userName'];
$password = $_POST['password'];

// Check if user already exists
$check_sql = "SELECT * FROM candidates WHERE userName = ?";
$stmt = $conn->prepare($check_sql);
$stmt->bind_param("s", $userName);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
  echo "❌ Username already taken. <a href='register.php'>Try again</a>";
} else {
  // Insert new user
  $insert_sql = "INSERT INTO candidates (userName, password) VALUES (?, ?)";
  $stmt = $conn->prepare($insert_sql);
  $stmt->bind_param("ss", $userName, $password);

  if ($stmt->execute()) {
    echo "✅ Registration successful! <a href='login.php'>Login here</a>";
  } else {
    echo "❌ Registration failed. Please try again.";
  }
}

$stmt->close();
$conn->close();
?>
