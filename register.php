<<<<<<< HEAD
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
=======
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register | Portfolio Library</title>
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    :root {
      --ink: #0f1117;
      --sky: #3b82f6;
      --sky-light: #eff6ff;
      --sky-dark: #1d4ed8;
      --mint: #10b981;
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
      max-width: 960px;
      width: 100%;
      background: white;
      border-radius: 24px;
      overflow: hidden;
      box-shadow: 0 20px 60px rgba(0,0,0,0.1);
      border: 1px solid var(--border);
    }
    .side-panel {
      background: linear-gradient(145deg, #1e3a8a, #3b82f6);
      padding: 48px 40px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      color: white;
    }
    .side-panel .logo {
      font-family: 'Syne', sans-serif;
      font-size: 1.4rem;
      font-weight: 800;
      margin-bottom: 48px;
      text-decoration: none;
      color: white;
    }
    .side-panel h2 {
      font-family: 'Syne', sans-serif;
      font-size: 1.9rem;
      font-weight: 800;
      line-height: 1.2;
      margin-bottom: 20px;
    }
    .side-panel p {
      font-size: 0.95rem;
      opacity: 0.85;
      line-height: 1.7;
      margin-bottom: 36px;
    }
    .perk { display: flex; align-items: flex-start; gap: 12px; margin-bottom: 16px; font-size: 0.9rem; opacity: 0.9; }
    .perk-icon { font-size: 1.2rem; flex-shrink: 0; margin-top: 1px; }

    .form-panel { padding: 48px 40px; overflow-y: auto; }
    .back-link {
      display: inline-flex; align-items: center; gap: 6px;
      font-size: 0.85rem; color: var(--gray); text-decoration: none;
      margin-bottom: 32px;
      transition: color 0.2s;
    }
    .back-link:hover { color: var(--sky); }

    h3 {
      font-family: 'Syne', sans-serif;
      font-size: 1.6rem;
      font-weight: 800;
      margin-bottom: 6px;
    }
    .sub { color: var(--gray); font-size: 0.9rem; margin-bottom: 28px; }

    /* Role selector */
    .role-selector { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 24px; }
    .role-option { display: none; }
    .role-label {
      display: flex; align-items: center; gap: 10px;
      padding: 14px 16px;
      border: 2px solid var(--border);
      border-radius: 12px;
      cursor: pointer;
      font-size: 0.9rem;
      font-weight: 500;
      transition: all 0.2s;
    }
    .role-label:hover { border-color: var(--sky); background: var(--sky-light); }
    .role-option:checked + .role-label {
      border-color: var(--sky);
      background: var(--sky-light);
      color: var(--sky-dark);
    }
    .role-emoji { font-size: 1.4rem; }

    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
    .field { margin-bottom: 16px; }
    .field label { display: block; font-size: 0.85rem; font-weight: 500; margin-bottom: 6px; color: var(--ink); }
    .field input, .field select {
      width: 100%; padding: 11px 14px;
      border: 1.5px solid var(--border);
      border-radius: 10px;
      font-family: 'DM Sans', sans-serif;
      font-size: 0.95rem;
      color: var(--ink);
      background: white;
      transition: border-color 0.2s, box-shadow 0.2s;
      outline: none;
    }
    .field input:focus, .field select:focus {
      border-color: var(--sky);
      box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
    }

    .error-box {
      background: #fef2f2; border: 1px solid #fecaca;
      color: var(--red); border-radius: 10px;
      padding: 12px 16px; font-size: 0.85rem;
      margin-bottom: 20px; display: none;
    }
    .error-box.show { display: block; }

    .success-box {
      background: #f0fdf4; border: 1px solid #86efac;
      color: #166534; border-radius: 10px;
      padding: 12px 16px; font-size: 0.85rem;
      margin-bottom: 20px; display: none;
    }
    .success-box.show { display: block; }

    .btn-submit {
      width: 100%; padding: 13px;
      background: var(--sky); color: white;
      border: none; border-radius: 12px;
      font-family: 'DM Sans', sans-serif;
      font-size: 1rem; font-weight: 600;
      cursor: pointer; transition: all 0.2s;
      margin-top: 8px;
    }
    .btn-submit:hover { background: var(--sky-dark); transform: translateY(-1px); }

    .login-link { text-align: center; margin-top: 20px; font-size: 0.9rem; color: var(--gray); }
    .login-link a { color: var(--sky); text-decoration: none; font-weight: 500; }

    @media (max-width: 720px) {
      .page-wrap { grid-template-columns: 1fr; }
      .side-panel { display: none; }
      .form-panel { padding: 32px 24px; }
      .form-row { grid-template-columns: 1fr; }
    }
  </style>
</head>
<body>

<?php
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include 'connect_db.php';

    $firstName = trim($_POST['firstName'] ?? '');
    $lastName  = trim($_POST['lastName'] ?? '');
    $email     = trim($_POST['email'] ?? '');
    $userName  = trim($_POST['userName'] ?? '');
    $password  = $_POST['password'] ?? '';
    $confirm   = $_POST['confirm'] ?? '';
    $role      = $_POST['role'] ?? 'student';

    // Basic validation
    if (!$firstName || !$lastName || !$email || !$userName || !$password) {
        $error = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } elseif (!in_array($role, ['student', 'teacher'])) {
        $error = 'Invalid role selected.';
    } else {
        // Check duplicates
        $check = $conn->prepare("SELECT id FROM users WHERE userName = ? OR email = ?");
        $check->bind_param("ss", $userName, $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $error = 'Username or email already taken. Try a different one.';
        } else {
            // Hash password securely
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            $stmt = $conn->prepare(
                "INSERT INTO users (firstName, lastName, email, userName, password, role)
                 VALUES (?, ?, ?, ?, ?, ?)"
            );
            $stmt->bind_param("ssssss", $firstName, $lastName, $email, $userName, $hashedPassword, $role);

            if ($stmt->execute()) {
                $newUserId = $stmt->insert_id;
                // Create empty portfolio record for students
                if ($role === 'student') {
                    $port = $conn->prepare("INSERT INTO portfolios (user_id) VALUES (?)");
                    $port->bind_param("i", $newUserId);
                    $port->execute();
                }
                $success = 'Account created! <a href="login.php">Click here to login →</a>';
            } else {
                $error = 'Registration failed. Please try again.';
            }
        }
        $check->close();
    }
}
?>

  <div class="page-wrap">
    <div class="side-panel">
      <a href="index.php" class="logo">📁 PortfolioLib</a>
      <h2>Join the Portfolio Library</h2>
      <p>Create your account and start showcasing your work to teachers and classmates.</p>
      <div class="perk"><span class="perk-icon">📂</span><span>Upload projects with descriptions and tech stacks</span></div>
      <div class="perk"><span class="perk-icon">⚡</span><span>List your skills and proficiency levels</span></div>
      <div class="perk"><span class="perk-icon">📄</span><span>Attach your resume / CV for download</span></div>
      <div class="perk"><span class="perk-icon">💬</span><span>Get feedback directly from your teacher</span></div>
    </div>

    <div class="form-panel">
      <a href="index.php" class="back-link">← Back to home</a>
      <h3>Create Account</h3>
      <p class="sub">Fill in your details below to get started.</p>

      <?php if ($error): ?>
        <div class="error-box show"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <?php if ($success): ?>
        <div class="success-box show">✅ <?= $success ?></div>
      <?php endif; ?>

      <form method="POST" action="register.php">

        <!-- Role -->
        <div class="role-selector">
          <div>
            <input type="radio" name="role" id="role_student" class="role-option" value="student"
              <?= (!isset($_POST['role']) || ($_POST['role'] ?? '') === 'student') ? 'checked' : '' ?>>
            <label for="role_student" class="role-label">
              <span class="role-emoji">🧑‍🎓</span> Student
            </label>
          </div>
          <div>
            <input type="radio" name="role" id="role_teacher" class="role-option" value="teacher"
              <?= (($_POST['role'] ?? '') === 'teacher') ? 'checked' : '' ?>>
            <label for="role_teacher" class="role-label">
              <span class="role-emoji">👩‍🏫</span> Teacher
            </label>
          </div>
        </div>

        <div class="form-row">
          <div class="field">
            <label for="firstName">First Name</label>
            <input type="text" id="firstName" name="firstName" placeholder="e.g. Arjun"
              value="<?= htmlspecialchars($_POST['firstName'] ?? '') ?>" required>
          </div>
          <div class="field">
            <label for="lastName">Last Name</label>
            <input type="text" id="lastName" name="lastName" placeholder="e.g. Roy"
              value="<?= htmlspecialchars($_POST['lastName'] ?? '') ?>" required>
          </div>
        </div>

        <div class="field">
          <label for="email">Email Address</label>
          <input type="email" id="email" name="email" placeholder="you@email.com"
            value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
        </div>

        <div class="field">
          <label for="userName">Username</label>
          <input type="text" id="userName" name="userName" placeholder="e.g. arjun_dev"
            value="<?= htmlspecialchars($_POST['userName'] ?? '') ?>" required>
        </div>

        <div class="form-row">
          <div class="field">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Min 6 characters" required>
          </div>
          <div class="field">
            <label for="confirm">Confirm Password</label>
            <input type="password" id="confirm" name="confirm" placeholder="Repeat password" required>
          </div>
        </div>

        <button type="submit" class="btn-submit">Create Account →</button>
      </form>

      <p class="login-link">Already have an account? <a href="login.php">Login here</a></p>
    </div>
  </div>

</body>
</html>
>>>>>>> 0de22fa (First git commit)
