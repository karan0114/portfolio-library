<<<<<<< HEAD
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

=======
<?php
require_once 'auth.php';
requireStudent();
require_once 'connect_db.php';

$userId   = $_SESSION['userId'];
$userName = $_SESSION['userName'];

// ── Fetch user + portfolio ──────────────────────────────────
$stmt = $conn->prepare(
    "SELECT u.*, p.headline, p.bio as port_bio, p.github, p.linkedin, p.website, p.resume_file
     FROM users u
     LEFT JOIN portfolios p ON p.user_id = u.id
     WHERE u.id = ?"
);
$stmt->bind_param("i", $userId);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// ── Fetch skills ────────────────────────────────────────────
$sk = $conn->prepare("SELECT * FROM skills WHERE user_id = ? ORDER BY id DESC");
$sk->bind_param("i", $userId);
$sk->execute();
$skills = $sk->get_result()->fetch_all(MYSQLI_ASSOC);

// ── Fetch projects ──────────────────────────────────────────
$pj = $conn->prepare("SELECT * FROM projects WHERE user_id = ? ORDER BY created_at DESC");
$pj->bind_param("i", $userId);
$pj->execute();
$projects = $pj->get_result()->fetch_all(MYSQLI_ASSOC);

// ── Handle POST actions ─────────────────────────────────────
$successMsg = '';
$errorMsg   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // ── Update Profile ──
    if ($action === 'update_profile') {
        $headline  = trim($_POST['headline'] ?? '');
        $bio       = trim($_POST['bio'] ?? '');
        $github    = trim($_POST['github'] ?? '');
        $linkedin  = trim($_POST['linkedin'] ?? '');
        $website   = trim($_POST['website'] ?? '');
        $firstName = trim($_POST['firstName'] ?? '');
        $lastName  = trim($_POST['lastName'] ?? '');

        $stmt = $conn->prepare("UPDATE users SET firstName=?, lastName=? WHERE id=?");
        $stmt->bind_param("ssi", $firstName, $lastName, $userId);
        $stmt->execute();

        $up = $conn->prepare(
            "UPDATE portfolios SET headline=?, bio=?, github=?, linkedin=?, website=? WHERE user_id=?"
        );
        $up->bind_param("sssssi", $headline, $bio, $github, $linkedin, $website, $userId);
        $up->execute();

        // Handle resume upload
        if (!empty($_FILES['resume']['name'])) {
            $ext = strtolower(pathinfo($_FILES['resume']['name'], PATHINFO_EXTENSION));
            if ($ext === 'pdf') {
                $dir = 'uploads/resumes/';
                if (!is_dir($dir)) mkdir($dir, 0755, true);
                $filename = 'resume_' . $userId . '_' . time() . '.pdf';
                if (move_uploaded_file($_FILES['resume']['tmp_name'], $dir . $filename)) {
                    $stmt = $conn->prepare("UPDATE portfolios SET resume_file=? WHERE user_id=?");
                    $stmt->bind_param("si", $filename, $userId);
                    $stmt->execute();
                }
            } else {
                $errorMsg = 'Resume must be a PDF file.';
            }
        }

        if (!$errorMsg) $successMsg = 'Profile updated successfully!';
        header("Location: dashboard.php?msg=profile_saved");
        exit();
    }

    // ── Add Skill ──
    if ($action === 'add_skill') {
        $skillName = trim($_POST['skill_name'] ?? '');
        $level     = $_POST['level'] ?? 'Intermediate';
        if ($skillName) {
            $ins = $conn->prepare("INSERT INTO skills (user_id, skill_name, level) VALUES (?,?,?)");
            $ins->bind_param("iss", $userId, $skillName, $level);
            $ins->execute();
        }
        header("Location: dashboard.php#skills");
        exit();
    }

    // ── Delete Skill ──
    if ($action === 'delete_skill') {
        $skillId = (int)($_POST['skill_id'] ?? 0);
        $del = $conn->prepare("DELETE FROM skills WHERE id=? AND user_id=?");
        $del->bind_param("ii", $skillId, $userId);
        $del->execute();
        header("Location: dashboard.php#skills");
        exit();
    }

    // ── Add/Edit Project ──
    if ($action === 'save_project') {
        $projectId  = (int)($_POST['project_id'] ?? 0);
        $title      = trim($_POST['title'] ?? '');
        $desc       = trim($_POST['description'] ?? '');
        $tech       = trim($_POST['tech_stack'] ?? '');
        $url        = trim($_POST['project_url'] ?? '');

        if (!$title) { $errorMsg = 'Project title is required.'; }
        else {
            $fileName = null;
            if (!empty($_FILES['project_file']['name'])) {
                $dir = 'uploads/projects/';
                if (!is_dir($dir)) mkdir($dir, 0755, true);
                $origName = basename($_FILES['project_file']['name']);
                $safeFile = $userId . '_' . time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $origName);
                move_uploaded_file($_FILES['project_file']['tmp_name'], $dir . $safeFile);
                $fileName = $safeFile;
            }

            if ($projectId) {
                // Update
                if ($fileName) {
                    $upd = $conn->prepare("UPDATE projects SET title=?,description=?,tech_stack=?,project_url=?,file_name=? WHERE id=? AND user_id=?");
                    $upd->bind_param("sssssii", $title, $desc, $tech, $url, $fileName, $projectId, $userId);
                } else {
                    $upd = $conn->prepare("UPDATE projects SET title=?,description=?,tech_stack=?,project_url=? WHERE id=? AND user_id=?");
                    $upd->bind_param("ssssii", $title, $desc, $tech, $url, $projectId, $userId);
                }
                $upd->execute();
            } else {
                // Insert
                $ins = $conn->prepare("INSERT INTO projects (user_id,title,description,tech_stack,project_url,file_name) VALUES (?,?,?,?,?,?)");
                $ins->bind_param("isssss", $userId, $title, $desc, $tech, $url, $fileName);
                $ins->execute();
            }
            header("Location: dashboard.php#projects");
            exit();
        }
    }

    // ── Delete Project ──
    if ($action === 'delete_project') {
        $projectId = (int)($_POST['project_id'] ?? 0);
        $del = $conn->prepare("DELETE FROM projects WHERE id=? AND user_id=?");
        $del->bind_param("ii", $projectId, $userId);
        $del->execute();
        header("Location: dashboard.php#projects");
        exit();
    }
}

// Reload after GET redirect
if (!empty($_GET['msg'])) $successMsg = 'Profile updated successfully!';

$levelColors = [
    'Beginner'     => '#fef3c7:#92400e',
    'Intermediate' => '#dbeafe:#1e40af',
    'Advanced'     => '#dcfce7:#166534',
    'Expert'       => '#f3e8ff:#6b21a8',
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard | Portfolio Library</title>
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    :root {
      --ink: #0f1117; --sky: #3b82f6; --sky-light: #eff6ff; --sky-dark: #1d4ed8;
      --mint: #10b981; --sand: #f8fafc; --gray: #6b7280; --border: #e5e7eb;
      --red: #ef4444; --sidebar: 240px;
    }
    body { font-family: 'DM Sans', sans-serif; background: var(--sand); color: var(--ink); display: flex; min-height: 100vh; }

    /* ── SIDEBAR ── */
    .sidebar {
      width: var(--sidebar); background: #0f172a; color: white;
      display: flex; flex-direction: column;
      position: fixed; left: 0; top: 0; bottom: 0;
      padding: 28px 0; z-index: 50;
    }
    .sidebar-logo {
      font-family: 'Syne', sans-serif; font-weight: 800; font-size: 1.1rem;
      padding: 0 24px 28px; border-bottom: 1px solid rgba(255,255,255,0.08);
      color: white; text-decoration: none; display: block;
    }
    .sidebar-logo span { color: #60a5fa; }
    .nav-section { padding: 20px 0; flex: 1; }
    .nav-label { font-size: 0.7rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: #475569; padding: 0 24px; margin-bottom: 8px; }
    .nav-link {
      display: flex; align-items: center; gap: 10px;
      padding: 10px 24px; font-size: 0.9rem; color: #94a3b8;
      text-decoration: none; transition: all 0.2s; border-left: 3px solid transparent;
    }
    .nav-link:hover, .nav-link.active { background: rgba(59,130,246,0.1); color: white; border-left-color: #3b82f6; }
    .nav-link .icon { width: 20px; text-align: center; font-size: 1rem; }
    .sidebar-footer { padding: 20px 24px; border-top: 1px solid rgba(255,255,255,0.08); }
    .user-pill { display: flex; align-items: center; gap: 10px; }
    .user-avatar {
      width: 36px; height: 36px; border-radius: 50%;
      background: linear-gradient(135deg, #3b82f6, #1d4ed8);
      display: flex; align-items: center; justify-content: center;
      font-weight: 700; font-size: 0.9rem; color: white; flex-shrink: 0;
    }
    .user-name { font-size: 0.85rem; font-weight: 500; }
    .user-role { font-size: 0.75rem; color: #64748b; }
    .logout-btn {
      display: block; margin-top: 14px; width: 100%;
      padding: 9px; border-radius: 8px;
      background: rgba(239,68,68,0.15); color: #f87171;
      border: 1px solid rgba(239,68,68,0.2);
      font-size: 0.85rem; font-weight: 500; text-align: center;
      text-decoration: none; cursor: pointer; transition: all 0.2s;
    }
    .logout-btn:hover { background: rgba(239,68,68,0.25); }

    /* ── MAIN ── */
    .main { margin-left: var(--sidebar); flex: 1; padding: 36px 40px; max-width: calc(100vw - var(--sidebar)); }
    .page-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 32px; }
    .page-header h1 { font-family: 'Syne', sans-serif; font-size: 1.7rem; font-weight: 800; }
    .page-header p { color: var(--gray); font-size: 0.9rem; margin-top: 4px; }

    /* ── STATS ── */
    .stats-row { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 32px; }
    .stat-card { background: white; border: 1px solid var(--border); border-radius: 14px; padding: 20px; }
    .stat-number { font-family: 'Syne', sans-serif; font-size: 2rem; font-weight: 800; }
    .stat-label { font-size: 0.8rem; color: var(--gray); margin-top: 2px; }
    .stat-card.blue .stat-number { color: var(--sky); }
    .stat-card.green .stat-number { color: var(--mint); }
    .stat-card.purple .stat-number { color: #8b5cf6; }
    .stat-card.orange .stat-number { color: #f59e0b; }

    /* ── SECTIONS ── */
    .section { background: white; border: 1px solid var(--border); border-radius: 16px; padding: 28px; margin-bottom: 24px; }
    .section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 22px; }
    .section-header h2 { font-family: 'Syne', sans-serif; font-size: 1.15rem; font-weight: 700; }

    /* ── FORMS ── */
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
    .form-full { grid-column: 1 / -1; }
    .field { margin-bottom: 0; }
    .field label { display: block; font-size: 0.82rem; font-weight: 500; margin-bottom: 5px; color: var(--ink); }
    .field input, .field textarea, .field select {
      width: 100%; padding: 10px 12px;
      border: 1.5px solid var(--border); border-radius: 9px;
      font-family: 'DM Sans', sans-serif; font-size: 0.9rem;
      transition: border-color 0.2s, box-shadow 0.2s; outline: none;
    }
    .field input:focus, .field textarea:focus, .field select:focus {
      border-color: var(--sky); box-shadow: 0 0 0 3px rgba(59,130,246,0.08);
    }
    .field textarea { resize: vertical; min-height: 80px; }

    .btn { display: inline-flex; align-items: center; gap: 6px; padding: 9px 18px; border-radius: 9px; font-family: 'DM Sans', sans-serif; font-size: 0.88rem; font-weight: 600; border: none; cursor: pointer; transition: all 0.2s; text-decoration: none; }
    .btn-primary { background: var(--sky); color: white; }
    .btn-primary:hover { background: var(--sky-dark); }
    .btn-sm { padding: 6px 12px; font-size: 0.8rem; }
    .btn-danger { background: #fef2f2; color: var(--red); border: 1px solid #fecaca; }
    .btn-danger:hover { background: #fee2e2; }
    .btn-outline { background: transparent; border: 1.5px solid var(--border); color: var(--ink); }
    .btn-outline:hover { border-color: var(--sky); color: var(--sky); }
    .btn-ghost { background: transparent; color: var(--gray); }
    .btn-ghost:hover { color: var(--sky); }

    /* ── SKILLS ── */
    .skills-list { display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 20px; }
    .skill-chip {
      display: flex; align-items: center; gap: 8px;
      padding: 7px 14px; border-radius: 99px;
      font-size: 0.82rem; font-weight: 500;
    }
    .skill-chip button { background: none; border: none; cursor: pointer; font-size: 0.8rem; opacity: 0.6; padding: 0; transition: opacity 0.2s; }
    .skill-chip button:hover { opacity: 1; }
    .add-skill-row { display: flex; gap: 10px; }
    .add-skill-row input, .add-skill-row select { padding: 9px 12px; border: 1.5px solid var(--border); border-radius: 9px; font-family: 'DM Sans', sans-serif; font-size: 0.88rem; outline: none; }
    .add-skill-row input:focus, .add-skill-row select:focus { border-color: var(--sky); }

    /* ── PROJECTS ── */
    .projects-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; }
    .project-card { border: 1px solid var(--border); border-radius: 14px; padding: 20px; transition: box-shadow 0.2s; }
    .project-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,0.06); }
    .project-card h4 { font-family: 'Syne', sans-serif; font-size: 1rem; font-weight: 700; margin-bottom: 6px; }
    .project-card p { font-size: 0.85rem; color: var(--gray); line-height: 1.5; margin-bottom: 12px; }
    .tech-tags { display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 12px; }
    .tech-tag { font-size: 0.75rem; padding: 3px 10px; background: var(--sky-light); color: var(--sky-dark); border-radius: 99px; font-weight: 500; }
    .project-actions { display: flex; gap: 8px; }

    /* ── MODAL ── */
    .modal-overlay {
      display: none; position: fixed; inset: 0;
      background: rgba(0,0,0,0.4); z-index: 200;
      align-items: center; justify-content: center; padding: 24px;
    }
    .modal-overlay.open { display: flex; }
    .modal {
      background: white; border-radius: 20px; padding: 32px;
      width: 100%; max-width: 560px; max-height: 90vh; overflow-y: auto;
      box-shadow: 0 24px 60px rgba(0,0,0,0.2);
    }
    .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
    .modal-header h3 { font-family: 'Syne', sans-serif; font-size: 1.2rem; font-weight: 700; }
    .close-btn { background: none; border: none; font-size: 1.3rem; cursor: pointer; color: var(--gray); }

    /* ── ALERT ── */
    .alert { padding: 12px 16px; border-radius: 10px; font-size: 0.87rem; margin-bottom: 20px; }
    .alert-success { background: #f0fdf4; border: 1px solid #86efac; color: #166534; }
    .alert-error   { background: #fef2f2; border: 1px solid #fecaca; color: var(--red); }

    .empty-state { text-align: center; padding: 32px 20px; color: var(--gray); }
    .empty-state .empty-icon { font-size: 2.5rem; margin-bottom: 10px; }
    .empty-state p { font-size: 0.9rem; }

    /* ── LINKS ── */
    .profile-links { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 12px; }
    .profile-link {
      display: flex; align-items: center; gap: 6px;
      font-size: 0.82rem; color: var(--sky); text-decoration: none;
      padding: 5px 12px; border-radius: 99px;
      border: 1px solid #bfdbfe; background: var(--sky-light);
      transition: all 0.2s;
    }
    .profile-link:hover { background: var(--sky); color: white; border-color: var(--sky); }
  </style>
</head>
<body>

<!-- ── SIDEBAR ─────────────────────────────── -->
<aside class="sidebar">
  <a href="dashboard.php" class="sidebar-logo">📁 Portfolio<span>Lib</span></a>
  <nav class="nav-section">
    <div class="nav-label">Menu</div>
    <a href="#overview"  class="nav-link active"><span class="icon">🏠</span> Overview</a>
    <a href="#profile"   class="nav-link"><span class="icon">👤</span> My Profile</a>
    <a href="#skills"    class="nav-link"><span class="icon">⚡</span> Skills</a>
    <a href="#projects"  class="nav-link"><span class="icon">📂</span> Projects</a>
    <a href="classmates.php" class="nav-link"><span class="icon">👥</span> Classmates</a>
  </nav>
  <div class="sidebar-footer">
    <div class="user-pill">
      <div class="user-avatar"><?= strtoupper(substr($user['firstName'],0,1) . substr($user['lastName'],0,1)) ?></div>
      <div>
        <div class="user-name"><?= htmlspecialchars($user['firstName'] . ' ' . $user['lastName']) ?></div>
        <div class="user-role">🧑‍🎓 Student</div>
      </div>
    </div>
    <form method="POST" action="logout.php">
      <button class="logout-btn" type="submit">🚪 Logout</button>
    </form>
  </div>
</aside>

<!-- ── MAIN ─────────────────────────────────── -->
<main class="main">

  <?php if ($successMsg): ?>
    <div class="alert alert-success">✅ <?= htmlspecialchars($successMsg) ?></div>
  <?php endif; ?>
  <?php if ($errorMsg): ?>
    <div class="alert alert-error">❌ <?= htmlspecialchars($errorMsg) ?></div>
  <?php endif; ?>

  <!-- OVERVIEW -->
  <div id="overview" class="page-header">
    <div>
      <h1>Welcome back, <?= htmlspecialchars($user['firstName']) ?>! 👋</h1>
      <p>Manage your portfolio and showcase your work.</p>
    </div>
  </div>

  <div class="stats-row">
    <div class="stat-card blue">
      <div class="stat-number"><?= count($projects) ?></div>
      <div class="stat-label">📂 Projects</div>
    </div>
    <div class="stat-card green">
      <div class="stat-number"><?= count($skills) ?></div>
      <div class="stat-label">⚡ Skills</div>
    </div>
    <div class="stat-card purple">
      <div class="stat-number"><?= $user['resume_file'] ? '✓' : '—' ?></div>
      <div class="stat-label">📄 Resume</div>
    </div>
    <div class="stat-card orange">
      <div class="stat-number"><?= $user['headline'] ? '✓' : '—' ?></div>
      <div class="stat-label">👤 Profile</div>
    </div>
  </div>

  <!-- PROFILE SECTION -->
  <div id="profile" class="section">
    <div class="section-header">
      <h2>👤 My Profile</h2>
      <button class="btn btn-primary" onclick="document.getElementById('profileModal').classList.add('open')">Edit Profile</button>
    </div>

    <div style="display:grid; grid-template-columns: auto 1fr; gap: 24px; align-items: start;">
      <div class="user-avatar" style="width:64px;height:64px;font-size:1.4rem;background:linear-gradient(135deg,#3b82f6,#1d4ed8)">
        <?= strtoupper(substr($user['firstName'],0,1) . substr($user['lastName'],0,1)) ?>
      </div>
      <div>
        <h3 style="font-family:'Syne',sans-serif;font-size:1.2rem;font-weight:700">
          <?= htmlspecialchars($user['firstName'] . ' ' . $user['lastName']) ?>
        </h3>
        <p style="color:var(--sky);font-size:0.9rem;font-weight:500;margin:3px 0 8px">
          <?= htmlspecialchars($user['headline'] ?? 'No headline yet — edit your profile') ?>
        </p>
        <p style="font-size:0.88rem;color:var(--gray);line-height:1.6">
          <?= nl2br(htmlspecialchars($user['port_bio'] ?? 'No bio yet.')) ?>
        </p>
        <div class="profile-links">
          <?php if ($user['github']): ?>
            <a href="<?= htmlspecialchars($user['github']) ?>" class="profile-link" target="_blank">🐙 GitHub</a>
          <?php endif; ?>
          <?php if ($user['linkedin']): ?>
            <a href="<?= htmlspecialchars($user['linkedin']) ?>" class="profile-link" target="_blank">💼 LinkedIn</a>
          <?php endif; ?>
          <?php if ($user['website']): ?>
            <a href="<?= htmlspecialchars($user['website']) ?>" class="profile-link" target="_blank">🌐 Website</a>
          <?php endif; ?>
          <?php if ($user['resume_file']): ?>
            <a href="uploads/resumes/<?= htmlspecialchars($user['resume_file']) ?>" class="profile-link" download>📄 Resume</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <!-- SKILLS SECTION -->
  <div id="skills" class="section">
    <div class="section-header">
      <h2>⚡ Skills</h2>
    </div>

    <?php if ($skills): ?>
      <div class="skills-list">
        <?php foreach ($skills as $s):
          $colors = explode(':', $levelColors[$s['level']] ?? '#f1f5f9:#475569');
          $bg = $colors[0]; $fg = $colors[1];
        ?>
          <div class="skill-chip" style="background:<?=$bg?>;color:<?=$fg?>">
            <span><?= htmlspecialchars($s['skill_name']) ?></span>
            <span style="opacity:0.6;font-size:0.75rem"><?= $s['level'] ?></span>
            <form method="POST" style="display:inline">
              <input type="hidden" name="action" value="delete_skill">
              <input type="hidden" name="skill_id" value="<?= $s['id'] ?>">
              <button type="submit" title="Remove">✕</button>
            </form>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="empty-state"><div class="empty-icon">⚡</div><p>No skills added yet.</p></div>
    <?php endif; ?>

    <form method="POST" class="add-skill-row">
      <input type="hidden" name="action" value="add_skill">
      <input type="text" name="skill_name" placeholder="e.g. PHP, Python, Figma..." required style="flex:1">
      <select name="level">
        <option>Beginner</option>
        <option selected>Intermediate</option>
        <option>Advanced</option>
        <option>Expert</option>
      </select>
      <button type="submit" class="btn btn-primary">+ Add</button>
    </form>
  </div>

  <!-- PROJECTS SECTION -->
  <div id="projects" class="section">
    <div class="section-header">
      <h2>📂 Projects</h2>
      <button class="btn btn-primary" onclick="openProjectModal()">+ Add Project</button>
    </div>

    <?php if ($projects): ?>
      <div class="projects-grid">
        <?php foreach ($projects as $p): ?>
          <div class="project-card">
            <h4><?= htmlspecialchars($p['title']) ?></h4>
            <p><?= htmlspecialchars(substr($p['description'] ?? '', 0, 100)) ?><?= strlen($p['description'] ?? '') > 100 ? '…' : '' ?></p>
            <?php if ($p['tech_stack']): ?>
              <div class="tech-tags">
                <?php foreach (explode(',', $p['tech_stack']) as $t): ?>
                  <span class="tech-tag"><?= htmlspecialchars(trim($t)) ?></span>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
            <div class="project-actions">
              <?php if ($p['project_url']): ?>
                <a href="<?= htmlspecialchars($p['project_url']) ?>" target="_blank" class="btn btn-outline btn-sm">🔗 View</a>
              <?php endif; ?>
              <?php if ($p['file_name']): ?>
                <a href="uploads/projects/<?= htmlspecialchars($p['file_name']) ?>" download class="btn btn-outline btn-sm">📥 File</a>
              <?php endif; ?>
              <button class="btn btn-outline btn-sm" onclick='editProject(<?= json_encode($p) ?>)'>✏️ Edit</button>
              <form method="POST" style="display:inline" onsubmit="return confirm('Delete this project?')">
                <input type="hidden" name="action" value="delete_project">
                <input type="hidden" name="project_id" value="<?= $p['id'] ?>">
                <button type="submit" class="btn btn-danger btn-sm">🗑</button>
              </form>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="empty-state">
        <div class="empty-icon">📂</div>
        <p>No projects yet. Add your first one!</p>
      </div>
    <?php endif; ?>
  </div>

</main>

<!-- ── PROFILE MODAL ─────────────────────────── -->
<div class="modal-overlay" id="profileModal">
  <div class="modal">
    <div class="modal-header">
      <h3>Edit Profile</h3>
      <button class="close-btn" onclick="document.getElementById('profileModal').classList.remove('open')">✕</button>
    </div>
    <form method="POST" action="dashboard.php" enctype="multipart/form-data">
      <input type="hidden" name="action" value="update_profile">
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px">
        <div class="field"><label>First Name</label><input type="text" name="firstName" value="<?= htmlspecialchars($user['firstName']) ?>" required></div>
        <div class="field"><label>Last Name</label><input type="text" name="lastName" value="<?= htmlspecialchars($user['lastName']) ?>" required></div>
      </div>
      <div class="field" style="margin-bottom:12px"><label>Headline <small style="color:var(--gray)">(e.g. Full Stack Developer)</small></label><input type="text" name="headline" value="<?= htmlspecialchars($user['headline'] ?? '') ?>"></div>
      <div class="field" style="margin-bottom:12px"><label>Bio</label><textarea name="bio"><?= htmlspecialchars($user['port_bio'] ?? '') ?></textarea></div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px">
        <div class="field"><label>GitHub URL</label><input type="url" name="github" value="<?= htmlspecialchars($user['github'] ?? '') ?>" placeholder="https://github.com/..."></div>
        <div class="field"><label>LinkedIn URL</label><input type="url" name="linkedin" value="<?= htmlspecialchars($user['linkedin'] ?? '') ?>"></div>
      </div>
      <div class="field" style="margin-bottom:12px"><label>Website</label><input type="url" name="website" value="<?= htmlspecialchars($user['website'] ?? '') ?>"></div>
      <div class="field" style="margin-bottom:20px"><label>Upload Resume (PDF only)</label><input type="file" name="resume" accept=".pdf">
        <?php if ($user['resume_file']): ?><small style="color:var(--mint)">✓ Resume already uploaded</small><?php endif; ?>
      </div>
      <button type="submit" class="btn btn-primary" style="width:100%">Save Profile</button>
    </form>
  </div>
</div>

<!-- ── PROJECT MODAL ─────────────────────────── -->
<div class="modal-overlay" id="projectModal">
  <div class="modal">
    <div class="modal-header">
      <h3 id="projectModalTitle">Add Project</h3>
      <button class="close-btn" onclick="document.getElementById('projectModal').classList.remove('open')">✕</button>
    </div>
    <form method="POST" action="dashboard.php" enctype="multipart/form-data">
      <input type="hidden" name="action" value="save_project">
      <input type="hidden" name="project_id" id="editProjectId" value="">
      <div class="field" style="margin-bottom:12px"><label>Project Title *</label><input type="text" name="title" id="pTitle" required></div>
      <div class="field" style="margin-bottom:12px"><label>Description</label><textarea name="description" id="pDesc" placeholder="What did you build? What problem does it solve?"></textarea></div>
      <div class="field" style="margin-bottom:12px"><label>Tech Stack <small style="color:var(--gray)">(comma-separated)</small></label><input type="text" name="tech_stack" id="pTech" placeholder="e.g. PHP, MySQL, JavaScript"></div>
      <div class="field" style="margin-bottom:12px"><label>Project URL (optional)</label><input type="url" name="project_url" id="pUrl" placeholder="https://..."></div>
      <div class="field" style="margin-bottom:20px"><label>Upload File (optional)</label><input type="file" name="project_file"></div>
      <button type="submit" class="btn btn-primary" style="width:100%">Save Project</button>
    </form>
  </div>
</div>

<script>
function openProjectModal(reset = true) {
  if (reset) {
    document.getElementById('projectModalTitle').textContent = 'Add Project';
    document.getElementById('editProjectId').value = '';
    document.getElementById('pTitle').value = '';
    document.getElementById('pDesc').value = '';
    document.getElementById('pTech').value = '';
    document.getElementById('pUrl').value = '';
  }
  document.getElementById('projectModal').classList.add('open');
}

function editProject(p) {
  document.getElementById('projectModalTitle').textContent = 'Edit Project';
  document.getElementById('editProjectId').value = p.id;
  document.getElementById('pTitle').value = p.title || '';
  document.getElementById('pDesc').value = p.description || '';
  document.getElementById('pTech').value = p.tech_stack || '';
  document.getElementById('pUrl').value = p.project_url || '';
  document.getElementById('projectModal').classList.add('open');
}

// Close modals on overlay click
document.querySelectorAll('.modal-overlay').forEach(overlay => {
  overlay.addEventListener('click', e => {
    if (e.target === overlay) overlay.classList.remove('open');
  });
});

// Highlight active nav on scroll
const sections = document.querySelectorAll('[id]');
const navLinks = document.querySelectorAll('.nav-link');
window.addEventListener('scroll', () => {
  let current = '';
  sections.forEach(s => {
    if (window.scrollY >= s.offsetTop - 100) current = s.id;
  });
  navLinks.forEach(l => {
    l.classList.remove('active');
    if (l.getAttribute('href') === '#' + current) l.classList.add('active');
  });
});
</script>

</body>
</html>
>>>>>>> 0de22fa (First git commit)
