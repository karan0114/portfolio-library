<?php
require_once 'auth.php';
requireTeacher();
require_once 'connect_db.php';

$userId   = $_SESSION['userId'];
$userName = $_SESSION['userName'];

// ── Fetch teacher info ──────────────────────────────────────
$t = $conn->prepare("SELECT * FROM users WHERE id = ?");
$t->bind_param("i", $userId);
$t->execute();
$teacher = $t->get_result()->fetch_assoc();

// ── Handle teacher comment submission ───────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'add_comment') {
        $projectId = (int)($_POST['project_id'] ?? 0);
        $comment   = trim($_POST['comment'] ?? '');
        if ($projectId && $comment) {
            $ins = $conn->prepare("INSERT INTO comments (project_id, teacher_id, comment) VALUES (?,?,?)");
            $ins->bind_param("iis", $projectId, $userId, $comment);
            $ins->execute();
        }
        header("Location: teacher_dashboard.php?student=" . ($_GET['student'] ?? ''));
        exit();
    }
    if ($_POST['action'] === 'delete_comment') {
        $commentId = (int)($_POST['comment_id'] ?? 0);
        $del = $conn->prepare("DELETE FROM comments WHERE id=? AND teacher_id=?");
        $del->bind_param("ii", $commentId, $userId);
        $del->execute();
        header("Location: teacher_dashboard.php?student=" . ($_GET['student'] ?? ''));
        exit();
    }
}

// ── Fetch all students ──────────────────────────────────────
$sq = $conn->query(
    "SELECT u.id, u.firstName, u.lastName, u.userName, u.email, u.created_at,
            p.headline, p.resume_file,
            (SELECT COUNT(*) FROM projects WHERE user_id = u.id) AS project_count,
            (SELECT COUNT(*) FROM skills  WHERE user_id = u.id) AS skill_count
     FROM users u
     LEFT JOIN portfolios p ON p.user_id = u.id
     WHERE u.role = 'student'
     ORDER BY u.created_at DESC"
);
$students = $sq->fetch_all(MYSQLI_ASSOC);

// ── View specific student ────────────────────────────────────
$viewStudent  = null;
$viewProjects = [];
$viewSkills   = [];

$studentId = isset($_GET['student']) ? (int)$_GET['student'] : 0;
if ($studentId) {
    $vs = $conn->prepare(
        "SELECT u.*, p.headline, p.bio as port_bio, p.github, p.linkedin, p.website, p.resume_file
         FROM users u LEFT JOIN portfolios p ON p.user_id = u.id
         WHERE u.id = ? AND u.role = 'student'"
    );
    $vs->bind_param("i", $studentId);
    $vs->execute();
    $viewStudent = $vs->get_result()->fetch_assoc();

    if ($viewStudent) {
        $vp = $conn->prepare(
            "SELECT pr.*, 
                    (SELECT COUNT(*) FROM comments c WHERE c.project_id = pr.id) as comment_count
             FROM projects pr WHERE pr.user_id = ? ORDER BY pr.created_at DESC"
        );
        $vp->bind_param("i", $studentId);
        $vp->execute();
        $viewProjects = $vp->get_result()->fetch_all(MYSQLI_ASSOC);

        $vsk = $conn->prepare("SELECT * FROM skills WHERE user_id = ?");
        $vsk->bind_param("i", $studentId);
        $vsk->execute();
        $viewSkills = $vsk->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}

// ── For project comments modal ───────────────────────────────
$projectComments = [];
$commentProjectId = isset($_GET['comments']) ? (int)$_GET['comments'] : 0;
if ($commentProjectId) {
    $cm = $conn->prepare(
        "SELECT c.*, u.firstName, u.lastName FROM comments c
         JOIN users u ON u.id = c.teacher_id
         WHERE c.project_id = ? ORDER BY c.created_at ASC"
    );
    $cm->bind_param("i", $commentProjectId);
    $cm->execute();
    $projectComments = $cm->get_result()->fetch_all(MYSQLI_ASSOC);
}

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
  <title>Teacher Dashboard | Portfolio Library</title>
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    :root {
      --ink: #0f1117; --sky: #3b82f6; --sky-light: #eff6ff; --sky-dark: #1d4ed8;
      --mint: #10b981; --sand: #f8fafc; --gray: #6b7280; --border: #e5e7eb;
      --red: #ef4444; --amber: #f59e0b; --sidebar: 240px;
    }
    body { font-family: 'DM Sans', sans-serif; background: var(--sand); color: var(--ink); display: flex; min-height: 100vh; }

    /* Sidebar */
    .sidebar {
      width: var(--sidebar); background: #0c1a2e; color: white;
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
    .teacher-badge {
      margin: 16px 24px 0; padding: 8px 12px;
      background: rgba(251,191,36,0.1); border: 1px solid rgba(251,191,36,0.25);
      border-radius: 8px; font-size: 0.78rem; color: #fbbf24; font-weight: 500;
    }
    .nav-section { padding: 20px 0; flex: 1; overflow-y: auto; }
    .nav-label { font-size: 0.7rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: #475569; padding: 0 24px; margin-bottom: 8px; }
    .nav-link {
      display: flex; align-items: center; gap: 10px;
      padding: 10px 24px; font-size: 0.9rem; color: #94a3b8;
      text-decoration: none; transition: all 0.2s; border-left: 3px solid transparent;
    }
    .nav-link:hover, .nav-link.active { background: rgba(59,130,246,0.1); color: white; border-left-color: #3b82f6; }
    .nav-link .icon { width: 20px; text-align: center; font-size: 1rem; }
    .student-nav-link {
      display: flex; align-items: center; gap: 10px;
      padding: 8px 24px; font-size: 0.82rem; color: #94a3b8;
      text-decoration: none; transition: all 0.2s;
    }
    .student-nav-link:hover, .student-nav-link.active { color: #60a5fa; background: rgba(59,130,246,0.08); }
    .student-nav-avatar {
      width: 28px; height: 28px; border-radius: 50%;
      background: linear-gradient(135deg, #3b82f6, #8b5cf6);
      display: flex; align-items: center; justify-content: center;
      font-size: 0.7rem; font-weight: 700; color: white; flex-shrink: 0;
    }
    .sidebar-footer { padding: 20px 24px; border-top: 1px solid rgba(255,255,255,0.08); }
    .user-pill { display: flex; align-items: center; gap: 10px; margin-bottom: 12px; }
    .user-avatar { width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, #f59e0b, #d97706); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.9rem; color: white; flex-shrink: 0; }
    .user-name { font-size: 0.85rem; font-weight: 500; }
    .user-role { font-size: 0.75rem; color: #64748b; }
    .logout-btn { display: block; width: 100%; padding: 9px; border-radius: 8px; background: rgba(239,68,68,0.15); color: #f87171; border: 1px solid rgba(239,68,68,0.2); font-size: 0.85rem; font-weight: 500; text-align: center; text-decoration: none; cursor: pointer; transition: all 0.2s; }
    .logout-btn:hover { background: rgba(239,68,68,0.25); }

    /* Main */
    .main { margin-left: var(--sidebar); flex: 1; padding: 36px 40px; max-width: calc(100vw - var(--sidebar)); }
    .page-header { margin-bottom: 28px; }
    .page-header h1 { font-family: 'Syne', sans-serif; font-size: 1.7rem; font-weight: 800; }
    .page-header p { color: var(--gray); font-size: 0.9rem; margin-top: 4px; }

    /* Stats */
    .stats-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 28px; }
    .stat-card { background: white; border: 1px solid var(--border); border-radius: 14px; padding: 20px; }
    .stat-number { font-family: 'Syne', sans-serif; font-size: 2rem; font-weight: 800; }
    .stat-label { font-size: 0.8rem; color: var(--gray); margin-top: 2px; }
    .stat-card.blue .stat-number { color: var(--sky); }
    .stat-card.green .stat-number { color: var(--mint); }
    .stat-card.amber .stat-number { color: var(--amber); }

    /* Student list */
    .section { background: white; border: 1px solid var(--border); border-radius: 16px; padding: 28px; margin-bottom: 24px; }
    .section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 22px; }
    .section-header h2 { font-family: 'Syne', sans-serif; font-size: 1.15rem; font-weight: 700; }

    .search-bar { display: flex; gap: 10px; margin-bottom: 20px; }
    .search-bar input { flex: 1; padding: 10px 14px; border: 1.5px solid var(--border); border-radius: 10px; font-family: 'DM Sans', sans-serif; font-size: 0.9rem; outline: none; }
    .search-bar input:focus { border-color: var(--sky); }

    .student-table { width: 100%; border-collapse: collapse; }
    .student-table th { font-size: 0.78rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.06em; color: var(--gray); padding: 10px 14px; text-align: left; border-bottom: 1px solid var(--border); }
    .student-table td { padding: 14px; border-bottom: 1px solid #f1f5f9; font-size: 0.88rem; vertical-align: middle; }
    .student-table tr:last-child td { border-bottom: none; }
    .student-table tr:hover td { background: #fafcff; }

    .student-name-cell { display: flex; align-items: center; gap: 12px; }
    .s-avatar { width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.85rem; color: white; flex-shrink: 0; }

    .badge { display: inline-block; padding: 3px 10px; border-radius: 99px; font-size: 0.75rem; font-weight: 600; }
    .badge-blue { background: var(--sky-light); color: var(--sky-dark); }
    .badge-green { background: #f0fdf4; color: #166534; }
    .badge-gray { background: #f1f5f9; color: #475569; }

    .btn { display: inline-flex; align-items: center; gap: 6px; padding: 7px 14px; border-radius: 8px; font-family: 'DM Sans', sans-serif; font-size: 0.82rem; font-weight: 600; border: none; cursor: pointer; transition: all 0.2s; text-decoration: none; }
    .btn-primary { background: var(--sky); color: white; }
    .btn-primary:hover { background: var(--sky-dark); }
    .btn-outline { background: transparent; border: 1.5px solid var(--border); color: var(--ink); }
    .btn-outline:hover { border-color: var(--sky); color: var(--sky); }
    .btn-danger { background: #fef2f2; color: var(--red); border: 1px solid #fecaca; }
    .btn-danger:hover { background: #fee2e2; }

    /* Student profile view */
    .profile-view { display: none; }
    .profile-view.open { display: block; }
    .profile-top { display: flex; gap: 20px; align-items: flex-start; margin-bottom: 24px; }
    .profile-avatar-lg { width: 72px; height: 72px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1.5rem; color: white; flex-shrink: 0; }
    .profile-links { display: flex; gap: 8px; flex-wrap: wrap; margin-top: 10px; }
    .profile-link { display: flex; align-items: center; gap: 5px; font-size: 0.8rem; color: var(--sky); text-decoration: none; padding: 4px 10px; border-radius: 99px; border: 1px solid #bfdbfe; background: var(--sky-light); transition: all 0.2s; }
    .profile-link:hover { background: var(--sky); color: white; border-color: var(--sky); }

    .skills-list { display: flex; flex-wrap: wrap; gap: 8px; }
    .skill-chip { display: flex; align-items: center; gap: 6px; padding: 5px 12px; border-radius: 99px; font-size: 0.8rem; font-weight: 500; }

    .projects-list { display: grid; gap: 16px; }
    .project-card { border: 1px solid var(--border); border-radius: 14px; padding: 20px; }
    .project-card h4 { font-family: 'Syne', sans-serif; font-size: 1rem; font-weight: 700; margin-bottom: 6px; }
    .project-card p { font-size: 0.85rem; color: var(--gray); line-height: 1.5; margin-bottom: 10px; }
    .tech-tags { display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 12px; }
    .tech-tag { font-size: 0.72rem; padding: 2px 9px; background: var(--sky-light); color: var(--sky-dark); border-radius: 99px; font-weight: 500; }
    .project-footer { display: flex; justify-content: space-between; align-items: center; }

    /* Comments */
    .comments-section { margin-top: 16px; border-top: 1px solid var(--border); padding-top: 14px; }
    .comment-item { background: #f8fafc; border-radius: 8px; padding: 10px 14px; margin-bottom: 8px; font-size: 0.85rem; }
    .comment-meta { font-size: 0.75rem; color: var(--gray); margin-bottom: 4px; }
    .comment-form { display: flex; gap: 8px; margin-top: 10px; }
    .comment-form input { flex: 1; padding: 8px 12px; border: 1.5px solid var(--border); border-radius: 8px; font-family: 'DM Sans', sans-serif; font-size: 0.85rem; outline: none; }
    .comment-form input:focus { border-color: var(--sky); }

    .empty-state { text-align: center; padding: 24px; color: var(--gray); font-size: 0.9rem; }

    .back-btn { margin-bottom: 20px; }

    /* Color pool for avatars */
  </style>
</head>
<body>

<?php
// Avatar background colors
$avatarColors = ['#3b82f6','#8b5cf6','#10b981','#f59e0b','#ef4444','#06b6d4','#84cc16','#f97316'];
function avatarColor($id) {
    global $avatarColors;
    return $avatarColors[$id % count($avatarColors)];
}
$totalProjects = array_sum(array_column($students, 'project_count'));
?>

<!-- SIDEBAR -->
<aside class="sidebar">
  <a href="teacher_dashboard.php" class="sidebar-logo">📁 Portfolio<span>Lib</span></a>
  <div class="teacher-badge">👩‍🏫 Teacher View</div>
  <nav class="nav-section">
    <div class="nav-label" style="margin-top:20px">Overview</div>
    <a href="teacher_dashboard.php" class="nav-link <?= !$studentId ? 'active' : '' ?>"><span class="icon">📊</span> All Students</a>

    <?php if ($students): ?>
      <div class="nav-label" style="margin-top:16px">Students</div>
      <?php foreach ($students as $s): ?>
        <a href="teacher_dashboard.php?student=<?= $s['id'] ?>"
           class="student-nav-link <?= $studentId == $s['id'] ? 'active' : '' ?>">
          <div class="student-nav-avatar" style="background:<?= avatarColor($s['id']) ?>">
            <?= strtoupper(substr($s['firstName'],0,1).substr($s['lastName'],0,1)) ?>
          </div>
          <span><?= htmlspecialchars($s['firstName'] . ' ' . $s['lastName']) ?></span>
        </a>
      <?php endforeach; ?>
    <?php endif; ?>
  </nav>
  <div class="sidebar-footer">
    <div class="user-pill">
      <div class="user-avatar"><?= strtoupper(substr($teacher['firstName'],0,1).substr($teacher['lastName'],0,1)) ?></div>
      <div>
        <div class="user-name"><?= htmlspecialchars($teacher['firstName'] . ' ' . $teacher['lastName']) ?></div>
        <div class="user-role">👩‍🏫 Teacher</div>
      </div>
    </div>
    <form method="POST" action="logout.php">
      <button class="logout-btn" type="submit">🚪 Logout</button>
    </form>
  </div>
</aside>

<!-- MAIN -->
<main class="main">

  <?php if ($studentId && $viewStudent): ?>
    <!-- ── INDIVIDUAL STUDENT VIEW ── -->
    <div class="back-btn">
      <a href="teacher_dashboard.php" class="btn btn-outline">← All Students</a>
    </div>

    <div class="page-header">
      <h1>Student Portfolio</h1>
      <p>Viewing <?= htmlspecialchars($viewStudent['firstName'] . ' ' . $viewStudent['lastName']) ?>'s portfolio</p>
    </div>

    <!-- Profile -->
    <div class="section">
      <div class="section-header"><h2>👤 Profile</h2></div>
      <div class="profile-top">
        <div class="profile-avatar-lg" style="background:<?= avatarColor($viewStudent['id']) ?>">
          <?= strtoupper(substr($viewStudent['firstName'],0,1).substr($viewStudent['lastName'],0,1)) ?>
        </div>
        <div>
          <h3 style="font-family:'Syne',sans-serif;font-size:1.2rem;font-weight:700">
            <?= htmlspecialchars($viewStudent['firstName'] . ' ' . $viewStudent['lastName']) ?>
          </h3>
          <p style="color:var(--sky);font-size:0.9rem;font-weight:500;margin:3px 0">
            <?= htmlspecialchars($viewStudent['headline'] ?? 'No headline') ?>
          </p>
          <p style="color:var(--gray);font-size:0.85rem">📧 <?= htmlspecialchars($viewStudent['email']) ?> &nbsp;|&nbsp; 🔑 @<?= htmlspecialchars($viewStudent['userName']) ?></p>
          <?php if ($viewStudent['port_bio']): ?>
            <p style="font-size:0.88rem;color:#374151;margin-top:10px;line-height:1.6"><?= nl2br(htmlspecialchars($viewStudent['port_bio'])) ?></p>
          <?php endif; ?>
          <div class="profile-links">
            <?php if ($viewStudent['github']): ?>
              <a href="<?= htmlspecialchars($viewStudent['github']) ?>" class="profile-link" target="_blank">🐙 GitHub</a>
            <?php endif; ?>
            <?php if ($viewStudent['linkedin']): ?>
              <a href="<?= htmlspecialchars($viewStudent['linkedin']) ?>" class="profile-link" target="_blank">💼 LinkedIn</a>
            <?php endif; ?>
            <?php if ($viewStudent['website']): ?>
              <a href="<?= htmlspecialchars($viewStudent['website']) ?>" class="profile-link" target="_blank">🌐 Website</a>
            <?php endif; ?>
            <?php if ($viewStudent['resume_file']): ?>
              <a href="uploads/resumes/<?= htmlspecialchars($viewStudent['resume_file']) ?>" class="profile-link" download>📄 Download Resume</a>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>

    <!-- Skills -->
    <div class="section">
      <div class="section-header"><h2>⚡ Skills (<?= count($viewSkills) ?>)</h2></div>
      <?php if ($viewSkills): ?>
        <div class="skills-list">
          <?php foreach ($viewSkills as $s):
            $colors = explode(':', $levelColors[$s['level']] ?? '#f1f5f9:#475569');
          ?>
            <div class="skill-chip" style="background:<?=$colors[0]?>;color:<?=$colors[1]?>">
              <?= htmlspecialchars($s['skill_name']) ?>
              <span style="opacity:0.6;font-size:0.72rem"><?= $s['level'] ?></span>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <div class="empty-state">No skills added yet.</div>
      <?php endif; ?>
    </div>

    <!-- Projects + Comments -->
    <div class="section">
      <div class="section-header"><h2>📂 Projects (<?= count($viewProjects) ?>)</h2></div>
      <?php if ($viewProjects): ?>
        <div class="projects-list">
          <?php foreach ($viewProjects as $p): ?>
            <div class="project-card">
              <h4><?= htmlspecialchars($p['title']) ?></h4>
              <?php if ($p['description']): ?>
                <p><?= nl2br(htmlspecialchars($p['description'])) ?></p>
              <?php endif; ?>
              <?php if ($p['tech_stack']): ?>
                <div class="tech-tags">
                  <?php foreach (explode(',', $p['tech_stack']) as $t): ?>
                    <span class="tech-tag"><?= htmlspecialchars(trim($t)) ?></span>
                  <?php endforeach; ?>
                </div>
              <?php endif; ?>
              <div class="project-footer">
                <div style="display:flex;gap:8px">
                  <?php if ($p['project_url']): ?>
                    <a href="<?= htmlspecialchars($p['project_url']) ?>" target="_blank" class="btn btn-outline">🔗 View</a>
                  <?php endif; ?>
                  <?php if ($p['file_name']): ?>
                    <a href="uploads/projects/<?= htmlspecialchars($p['file_name']) ?>" download class="btn btn-outline">📥 File</a>
                  <?php endif; ?>
                </div>
                <span style="font-size:0.78rem;color:var(--gray)">
                  💬 <?= $p['comment_count'] ?> comment<?= $p['comment_count'] != 1 ? 's' : '' ?>
                </span>
              </div>

              <!-- Comments -->
              <div class="comments-section">
                <?php
                $cm2 = $conn->prepare(
                    "SELECT c.*, u.firstName, u.lastName FROM comments c
                     JOIN users u ON u.id = c.teacher_id
                     WHERE c.project_id = ? ORDER BY c.created_at ASC"
                );
                $cm2->bind_param("i", $p['id']);
                $cm2->execute();
                $pComments = $cm2->get_result()->fetch_all(MYSQLI_ASSOC);
                foreach ($pComments as $cm): ?>
                  <div class="comment-item">
                    <div class="comment-meta">
                      👩‍🏫 <?= htmlspecialchars($cm['firstName'] . ' ' . $cm['lastName']) ?>
                      · <?= date('M j, Y', strtotime($cm['created_at'])) ?>
                      <?php if ($cm['teacher_id'] == $userId): ?>
                        <form method="POST" style="display:inline;margin-left:8px">
                          <input type="hidden" name="action" value="delete_comment">
                          <input type="hidden" name="comment_id" value="<?= $cm['id'] ?>">
                          <button type="submit" style="background:none;border:none;color:var(--red);cursor:pointer;font-size:0.75rem">✕ delete</button>
                        </form>
                      <?php endif; ?>
                    </div>
                    <?= nl2br(htmlspecialchars($cm['comment'])) ?>
                  </div>
                <?php endforeach; ?>

                <form method="POST" action="teacher_dashboard.php?student=<?= $studentId ?>" class="comment-form">
                  <input type="hidden" name="action" value="add_comment">
                  <input type="hidden" name="project_id" value="<?= $p['id'] ?>">
                  <input type="text" name="comment" placeholder="Leave feedback on this project…" required>
                  <button type="submit" class="btn btn-primary">💬 Post</button>
                </form>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <div class="empty-state">This student hasn't added any projects yet.</div>
      <?php endif; ?>
    </div>

  <?php else: ?>
    <!-- ── ALL STUDENTS OVERVIEW ── -->
    <div class="page-header">
      <h1>👩‍🏫 Teacher Dashboard</h1>
      <p>Overview of all registered students and their portfolios.</p>
    </div>

    <div class="stats-row">
      <div class="stat-card blue">
        <div class="stat-number"><?= count($students) ?></div>
        <div class="stat-label">🧑‍🎓 Total Students</div>
      </div>
      <div class="stat-card green">
        <div class="stat-number"><?= $totalProjects ?></div>
        <div class="stat-label">📂 Total Projects</div>
      </div>
      <div class="stat-card amber">
        <div class="stat-number"><?= count(array_filter($students, fn($s) => $s['resume_file'])) ?></div>
        <div class="stat-label">📄 Resumes Uploaded</div>
      </div>
    </div>

    <div class="section">
      <div class="section-header">
        <h2>All Students</h2>
        <span style="font-size:0.85rem;color:var(--gray)"><?= count($students) ?> registered</span>
      </div>

      <div class="search-bar">
        <input type="text" id="searchInput" placeholder="🔍 Search by name, username, or headline…" oninput="filterStudents()">
      </div>

      <?php if ($students): ?>
        <table class="student-table" id="studentTable">
          <thead>
            <tr>
              <th>Student</th>
              <th>Headline</th>
              <th>Projects</th>
              <th>Skills</th>
              <th>Resume</th>
              <th>Joined</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($students as $s): ?>
              <tr data-name="<?= strtolower($s['firstName'].' '.$s['lastName'].' '.$s['userName'].' '.($s['headline'] ?? '')) ?>">
                <td>
                  <div class="student-name-cell">
                    <div class="s-avatar" style="background:<?= avatarColor($s['id']) ?>">
                      <?= strtoupper(substr($s['firstName'],0,1).substr($s['lastName'],0,1)) ?>
                    </div>
                    <div>
                      <div style="font-weight:500"><?= htmlspecialchars($s['firstName'].' '.$s['lastName']) ?></div>
                      <div style="font-size:0.78rem;color:var(--gray)">@<?= htmlspecialchars($s['userName']) ?></div>
                    </div>
                  </div>
                </td>
                <td style="max-width:180px">
                  <span style="font-size:0.84rem;color:var(--gray)"><?= htmlspecialchars($s['headline'] ?? '—') ?></span>
                </td>
                <td><span class="badge badge-blue"><?= $s['project_count'] ?></span></td>
                <td><span class="badge badge-green"><?= $s['skill_count'] ?></span></td>
                <td>
                  <?php if ($s['resume_file']): ?>
                    <a href="uploads/resumes/<?= htmlspecialchars($s['resume_file']) ?>" download class="badge badge-green">📄 Download</a>
                  <?php else: ?>
                    <span class="badge badge-gray">—</span>
                  <?php endif; ?>
                </td>
                <td style="font-size:0.82rem;color:var(--gray)"><?= date('M j, Y', strtotime($s['created_at'])) ?></td>
                <td><a href="teacher_dashboard.php?student=<?= $s['id'] ?>" class="btn btn-primary">View Portfolio</a></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php else: ?>
        <div class="empty-state">
          <div style="font-size:3rem;margin-bottom:10px">🧑‍🎓</div>
          <p>No students registered yet.</p>
        </div>
      <?php endif; ?>
    </div>
  <?php endif; ?>

</main>

<script>
function filterStudents() {
  const q = document.getElementById('searchInput').value.toLowerCase();
  document.querySelectorAll('#studentTable tbody tr').forEach(row => {
    row.style.display = row.dataset.name.includes(q) ? '' : 'none';
  });
}
</script>

</body>
</html>
