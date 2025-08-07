<?php
session_start();

// If user not logged in, redirect to login page
if (!isset($_SESSION['userName'])) {
    header("Location: login.php");
    exit();
}

$userName = $_SESSION['userName'];

// DB connection (update these with your real credentials)
$conn = new mysqli("localhost", "root", "", "portfolio_db");

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Fetch user data
$sql = "SELECT * FROM candidates WHERE userName = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $userName);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard | Portfolio Library</title>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #f4f8fb;
    }

    .navbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background-color: #3498db;
      color: white;
      padding: 15px 30px;
    }

    .navbar h1 {
      margin: 0;
      font-size: 1.5rem;
    }

    .logout-btn {
      background-color: white;
      color: #3498db;
      border: none;
      padding: 8px 16px;
      font-weight: bold;
      border-radius: 8px;
      cursor: pointer;
      transition: background 0.3s;
    }

    .logout-btn:hover {
      background-color: #f1f1f1;
    }

    .container {
      padding: 40px 30px;
    }

    .card {
      background: white;
      border-radius: 12px;
      padding: 20px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
      margin-bottom: 20px;
    }

    .card h2 {
      margin-top: 0;
      color: #2c3e50;
    }

    .card p {
      color: #555;
    }

    .actions {
      display: flex;
      gap: 15px;
      flex-wrap: wrap;
    }

    .actions a {
      padding: 10px 16px;
      background-color: #3498db;
      color: white;
      text-decoration: none;
      border-radius: 8px;
      transition: 0.3s;
    }

    .actions a:hover {
      background-color: #2d83c1;
    }
  </style>
</head>
<body>

  <div class="navbar">
    <h1>Welcome, <?php echo htmlspecialchars($user['userName']); ?>!</h1>
    <form action="logout.php" method="POST">
      <button class="logout-btn">Logout</button>
    </form>
  </div>

  <div class="container">
    <div class="card">
      <h2>Your Profile</h2>
      <p><strong>Full Name:</strong> <?php echo htmlspecialchars($user['fullName'] ?? 'N/A'); ?></p>
      <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email'] ?? 'N/A'); ?></p>
      <p><strong>Username:</strong> <?php echo htmlspecialchars($user['userName']); ?></p>
    </div>

    <div class="card">
      <h2>Portfolio Entries</h2>
      <p>This section will list your uploaded projects (we'll build this next).</p>
    </div>

    <div class="card">
      <h2>Quick Actions</h2>
      <div class="actions">
        <a href="#">Edit Profile</a>
        <a href="#">Upload Resume</a>
        <a href="#">Add Project</a>
        <a href="#">View Classmates</a>
      </div>
    </div>
  </div>

</body>
</html>

