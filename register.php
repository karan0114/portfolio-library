<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register</title>
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
      background-color: #2ecc71;
      color: white;
      font-weight: 600;
      font-size: 1rem;
      cursor: pointer;
      margin-top: 10px;
    }
    button:hover {
      background-color: #27ae60;
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
    <h2>Register</h2>
    <form action="reg.php" method="POST">
      <input type="text" name="userName" placeholder="Username" required><br>
      <input type="password" name="password" placeholder="Password" required><br>
      <button type="submit">Register</button>
      <p class="note">Already have an account? <a href="login.php">Login here</a>.</p>
    </form>
  </div>
</body>
</html>
