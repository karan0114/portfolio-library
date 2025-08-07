<?php
$error = isset($_GET['error']) ? $_GET['error'] : '1';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(135deg, #eaf4ff, #f9fbfd);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .top-left {
  position: absolute;
  top: 20px;
  left: 20px;
}
.top-left a {
  text-decoration: none;
  font-weight: bold;
  background-color: #e0f0ff;
  color: #3498db;
  padding: 8px 12px;
  border-radius: 8px;
  transition: 0.3s;
}
.top-left a:hover {
  background-color: #3498db;
  color: white;
}

    .box {
      background: white;
      padding: 40px;
      border-radius: 16px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.1);
      text-align: center;
      width: 100%;
      max-width: 400px;
    }
    h2 {
      margin-bottom: 25px;
      color: #1b1f3b;
    }
    input {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 1rem;
    }
    button {
      width: 100%;
      padding: 12px;
      border: none;
      border-radius: 8px;
      background-color: #3498db;
      color: white;
      font-weight: 600;
      font-size: 1rem;
      cursor: pointer;
      margin-top: 10px;
    }
    button:hover {
      background-color: #2980b9;
    }
    .note {
      margin-top: 15px;
      font-size: 0.9rem;
    }
  </style>
</head>
<body>
  <div class="top-left">
  <a href="index.php">🏠 Home</a>
</div>

  <div class="box">
    <H1>Welcome</H1><h2>To</h2>
    <h2>Login Page</h2>
        <?php if ($error): 1 ?>
      <div class="error">Invalid username or password</div>
    <?php endif; ?>
    <form action="login_process.php" method="POST">
      <p style="float:left;"padding:0;>Enter username & password</p>
      <input type="text" name="userName" placeholder="Username" required><br>
      <input type="password" name="password" placeholder="Password" required><br>
      <button type="submit">Login</button>
      <p class="note">Don't have an account? <a href="register.php">Register here</a>.</p>
    </form>
  </div>
</body>
</html>
