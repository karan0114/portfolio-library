<<<<<<< HEAD
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
=======
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login | Portfolio Library</title>
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    :root {
      --ink: #0f1117;
      --sky: #3b82f6;
      --sky-light: #eff6ff;
      --sky-dark: #1d4ed8;
      --sand: #fafaf8;
      --gray: #6b7280;
      --border: #e5e7eb;
      --red: #ef4444;
    }
    body {
      font-family: 'DM Sans', sans-serif;
      background: var(--sand);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 24px;
    }
    .page-wrap {
      display: grid;
      grid-template-columns: 1fr 1fr;
      max-width: 840px;
      width: 100%;
      background: white;
      border-radius: 24px;
      overflow: hidden;
      box-shadow: 0 20px 60px rgba(0,0,0,0.1);
      border: 1px solid var(--border);
    }
    .side-panel {
      background: linear-gradient(145deg, #0f172a, #1e3a8a);
      padding: 48px 40px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      color: white;
    }
    .logo {
      font-family: 'Syne', sans-serif;
      font-size: 1.3rem;
      font-weight: 800;
      margin-bottom: 60px;
      text-decoration: none;
      color: white;
    }
    .side-panel h2 {
      font-family: 'Syne', sans-serif;
      font-size: 1.8rem;
      font-weight: 800;
      line-height: 1.2;
      margin-bottom: 16px;
    }
    .side-panel p { font-size: 0.9rem; opacity: 0.75; line-height: 1.6; }

    .side-roles { margin-top: 40px; }
    .side-role {
      display: flex; align-items: center; gap: 12px;
      padding: 14px 18px;
      border-radius: 12px;
      margin-bottom: 12px;
      font-size: 0.9rem;
    }
    .side-role.s { background: rgba(16,185,129,0.15); border: 1px solid rgba(16,185,129,0.3); }
    .side-role.t { background: rgba(59,130,246,0.15); border: 1px solid rgba(59,130,246,0.3); }
    .side-role span { font-weight: 500; }
    .side-role small { display: block; opacity: 0.7; font-size: 0.8rem; margin-top: 1px; }

    .form-panel { padding: 48px 40px; }
    .back-link {
      display: inline-flex; align-items: center; gap: 6px;
      font-size: 0.85rem; color: var(--gray); text-decoration: none;
      margin-bottom: 32px; transition: color 0.2s;
    }
    .back-link:hover { color: var(--sky); }

    h3 { font-family: 'Syne', sans-serif; font-size: 1.7rem; font-weight: 800; margin-bottom: 6px; }
    .sub { color: var(--gray); font-size: 0.9rem; margin-bottom: 32px; }

    .field { margin-bottom: 18px; }
    .field label { display: block; font-size: 0.85rem; font-weight: 500; margin-bottom: 6px; }
    .field input {
      width: 100%; padding: 12px 14px;
      border: 1.5px solid var(--border);
      border-radius: 10px;
      font-family: 'DM Sans', sans-serif;
      font-size: 0.95rem;
      outline: none;
      transition: border-color 0.2s, box-shadow 0.2s;
    }
    .field input:focus {
      border-color: var(--sky);
      box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
    }

    .error-box {
      background: #fef2f2; border: 1px solid #fecaca;
      color: var(--red); border-radius: 10px;
      padding: 12px 16px; font-size: 0.85rem;
      margin-bottom: 20px;
    }

    .btn-submit {
      width: 100%; padding: 13px;
      background: var(--sky); color: white;
      border: none; border-radius: 12px;
      font-family: 'DM Sans', sans-serif;
      font-size: 1rem; font-weight: 600;
      cursor: pointer; transition: all 0.2s;
    }
    .btn-submit:hover { background: var(--sky-dark); transform: translateY(-1px); }

    .register-link { text-align: center; margin-top: 20px; font-size: 0.9rem; color: var(--gray); }
    .register-link a { color: var(--sky); text-decoration: none; font-weight: 500; }

    /* Mobile role banner - shown only when side panel is hidden */
    .mobile-role-banner {
      display: none;
      background: linear-gradient(135deg, #0f172a, #1e3a8a);
      padding: 24px 24px 20px;
      color: white;
    }
    .mobile-role-banner .m-logo {
      font-family: 'Syne', sans-serif; font-weight: 800; font-size: 1.1rem;
      color: white; text-decoration: none; display: block; margin-bottom: 16px;
    }
    .mobile-role-banner h2 {
      font-family: 'Syne', sans-serif; font-size: 1.25rem; font-weight: 800; margin-bottom: 14px;
    }
    .mobile-roles-row { display: flex; gap: 10px; }
    .m-role {
      flex: 1; display: flex; align-items: flex-start; gap: 8px;
      padding: 10px 12px; border-radius: 10px; font-size: 0.8rem;
    }
    .m-role.s { background: rgba(16,185,129,0.15); border: 1px solid rgba(16,185,129,0.3); }
    .m-role.t { background: rgba(59,130,246,0.15); border: 1px solid rgba(59,130,246,0.3); }
    .m-role b { display: block; font-size: 0.82rem; }
    .m-role small { opacity: 0.7; font-size: 0.72rem; display: block; margin-top: 2px; }

    @media (max-width: 640px) {
      body { padding: 0; align-items: flex-start; background: white; }
      .page-wrap {
        grid-template-columns: 1fr; border-radius: 0;
        box-shadow: none; border: none; min-height: 100vh;
      }
      .side-panel { display: none; }
      .mobile-role-banner { display: block; }
      .form-panel { padding: 28px 24px 40px; }
      h3 { font-size: 1.4rem; }
    }

    @media (max-width: 380px) {
      .mobile-roles-row { flex-direction: column; }
      .form-panel { padding: 24px 18px 36px; }
    }
  </style>
</head>
<body>

<?php
session_start();
// If already logged in, redirect to appropriate dashboard
if (isset($_SESSION['userId'])) {
    header("Location: dashboard.php");
    exit();
}

$error = (isset($_GET['error']) && $_GET['error'] === '1');
?>

  <div class="page-wrap">
    <div class="side-panel">
      <a href="index.php" class="logo">📁 PortfolioLib</a>
      <h2>Welcome back!</h2>
      <p>Log in to access your portfolio dashboard.</p>
      <div class="side-roles">
        <div class="side-role s">
          <span>🧑‍🎓</span>
          <div><span>Students</span><small>Manage your portfolio & projects</small></div>
        </div>
        <div class="side-role t">
          <span>👩‍🏫</span>
          <div><span>Teachers</span><small>View all students & give feedback</small></div>
        </div>
      </div>
    </div>

    <!-- Shown only on mobile when side panel is hidden -->
    <div class="mobile-role-banner">
      <a href="index.php" class="m-logo">📁 PortfolioLib</a>
      <h2>Welcome back!</h2>
      <div class="mobile-roles-row">
        <div class="m-role s">
          <span>🧑‍🎓</span>
          <div><b>Students</b><small>Manage your portfolio &amp; projects</small></div>
        </div>
        <div class="m-role t">
          <span>👩‍🏫</span>
          <div><b>Teachers</b><small>View students &amp; give feedback</small></div>
        </div>
      </div>
    </div>

    <div class="form-panel">
      <a href="index.php" class="back-link">← Back to home</a>
      <h3>Sign In</h3>
      <p class="sub">Enter your username and password to continue.</p>

      <?php if ($error): ?>
        <div class="error-box">❌ Invalid username or password. Please try again.</div>
      <?php endif; ?>

      <form method="POST" action="login_process.php">
        <div class="field">
          <label for="userName">Username</label>
          <input type="text" id="userName" name="userName" placeholder="Your username" required autofocus>
        </div>
        <div class="field">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" placeholder="Your password" required>
        </div>
        <button type="submit" class="btn-submit">Sign In →</button>
      </form>

      <p class="register-link">Don't have an account? <a href="register.php">Register here</a></p>
    </div>
  </div>

</body>
</html>
>>>>>>> 0de22fa (First git commit)
