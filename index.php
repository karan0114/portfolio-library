<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Portfolio Library</title>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(135deg, #eaf4ff, #f9fbfd);
      color: #2c3e50;
      text-align: center;
    }
    .hero {
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      flex-direction: column;
      padding: 0 20px;
    }
    h1 {
      font-size: 3rem;
      color: #1b1f3b;
      margin-bottom: 10px;
    }
    p {
      font-size: 1.2rem;
      margin-bottom: 30px;
      color: #555;
    }
    .btn {
      padding: 12px 25px;
      border: none;
      border-radius: 8px;
      margin: 0 10px;
      font-weight: 600;
      text-decoration: none;
      font-size: 1rem;
      transition: all 0.3s;
      cursor: pointer;
    }
    .btn-login {
      padding: 12px 25px;
      margin: 12px;
      border: 2px solid #3498db;
      background-color: #3498db;
      color: white;
    }
    .btn-login:hover {
      background-color: #2980b9;
    }
    .btn-register {
      border: 2px solid #3498db;
      color: #3498db;
      background-color: transparent;
    }
    .btn-register:hover {
      background-color: #3498db;
      color: white;
    }
  </style>
</head>
<body>

  <div class="hero">
    <h1>📁 Portfolio Library</h1>
    <p>Showcase and explore your classmates' portfolios.</p>
    <a href="login.php" class="btn btn-login">Login</a>
    <a href="register.php" class="btn btn-register">Register</a>
  </div>
</body>
</html>
