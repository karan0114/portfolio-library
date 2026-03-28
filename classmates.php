<?php
require_once 'auth.php';
requireStudent();
require_once 'connect_db.php';

$userId = $_SESSION['userId'];

// Fetch all students except self
$stmt = $conn->prepare(
    "SELECT u.id, u.firstName, u.lastName, u.userName,
            p.headline, p.github, p.linkedin, p.website,
            (SELECT COUNT(*) FROM projects WHERE user_id = u.id) AS project_count,
            (SELECT COUNT(*) FROM skills  WHERE user_id = u.id) AS skill_count
     FROM users u
     LEFT JOIN portfolios p ON p.user_id = u.id
     WHERE u.role = 'student' AND u.id != ?
     ORDER BY u.firstName ASC"
);
$stmt->bind_param("i", $userId);
$stmt->execute();
$classmates = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// View individual classmate
$viewId = isset($_GET['view']) ? (int)$_GET['view'] : 0;
$viewPerson = null;
$viewProjects = [];
$viewSkills = [];

if ($viewId && $viewId !== $userId) {
    $vs = $conn->prepare(
        "SELECT u.*, p.headline, p.bio as port_bio, p.github, p.linkedin, p.website, p.resume_file
         FROM users u LEFT JOIN portfolios p ON p.user_id = u.id
         WHERE u.id = ? AND u.role = 'student'"
    );
    $vs->bind_param("i", $viewId);
    $vs->execute();
    $viewPerson = $vs->get_result()->fetch_assoc();

    if ($viewPerson) {
        $vp = $conn->prepare("SELECT * FROM projects WHERE user_id = ? ORDER BY created_at DESC");
        $vp->bind_param("i", $viewId);
        $vp->execute();
        $viewProjects = $vp->get_result()->fetch_all(MYSQLI_ASSOC);

        $vsk = $conn->prepare("SELECT * FROM skills WHERE user_id = ?");
        $vsk->bind_param("i", $viewId);
        $vsk->execute();
        $viewSkills = $vsk->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}

$avatarColors = ['#3b82f6','#8b5cf6','#10b981','#f59e0b','#ef4444','#06b6d4','#84cc16','#f97316'];
function ac($id) { global $avatarColors; return $avatarColors[$id % count($avatarColors)]; }

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
  <title>Classmates | Portfolio Library</title>
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    :root { --ink:#0f1117;--sky:#3b82f6;--sky-light:#eff6ff;--sky-dark:#1d4ed8;--mint:#10b981;--sand:#f8fafc;--gray:#6b7280;--border:#e5e7eb;--sidebar:240px; }
    body { font-family:'DM Sans',sans-serif;background:var(--sand);color:var(--ink);display:flex;min-height:100vh; }
    .sidebar { width:var(--sidebar);background:#0f172a;color:white;display:flex;flex-direction:column;position:fixed;left:0;top:0;bottom:0;padding:28px 0;z-index:50; }
    .sidebar-logo { font-family:'Syne',sans-serif;font-weight:800;font-size:1.1rem;padding:0 24px 28px;border-bottom:1px solid rgba(255,255,255,0.08);color:white;text-decoration:none;display:block; }
    .sidebar-logo span { color:#60a5fa; }
    .nav-section { padding:20px 0;flex:1; }
    .nav-label { font-size:0.7rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#475569;padding:0 24px;margin-bottom:8px; }
    .nav-link { display:flex;align-items:center;gap:10px;padding:10px 24px;font-size:0.9rem;color:#94a3b8;text-decoration:none;transition:all .2s;border-left:3px solid transparent; }
    .nav-link:hover,.nav-link.active { background:rgba(59,130,246,.1);color:white;border-left-color:#3b82f6; }
    .nav-link .icon { width:20px;text-align:center;font-size:1rem; }
    .sidebar-footer { padding:20px 24px;border-top:1px solid rgba(255,255,255,0.08); }
    .user-pill { display:flex;align-items:center;gap:10px;margin-bottom:12px; }
    .user-avatar { width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#3b82f6,#1d4ed8);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.9rem;color:white;flex-shrink:0; }
    .user-name { font-size:.85rem;font-weight:500; }
    .user-role { font-size:.75rem;color:#64748b; }
    .logout-btn { display:block;width:100%;padding:9px;border-radius:8px;background:rgba(239,68,68,.15);color:#f87171;border:1px solid rgba(239,68,68,.2);font-size:.85rem;font-weight:500;text-align:center;cursor:pointer;transition:all .2s; }
    .logout-btn:hover { background:rgba(239,68,68,.25); }
    .main { margin-left:var(--sidebar);flex:1;padding:36px 40px;max-width:calc(100vw - var(--sidebar)); }
    .page-header { margin-bottom:28px; }
    .page-header h1 { font-family:'Syne',sans-serif;font-size:1.7rem;font-weight:800; }
    .page-header p { color:var(--gray);font-size:.9rem;margin-top:4px; }
    .search-bar { display:flex;gap:10px;margin-bottom:24px; }
    .search-bar input { flex:1;padding:10px 14px;border:1.5px solid var(--border);border-radius:10px;font-family:'DM Sans',sans-serif;font-size:.9rem;outline:none; }
    .search-bar input:focus { border-color:var(--sky); }
    .classmates-grid { display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:18px; }
    .classmate-card { background:white;border:1px solid var(--border);border-radius:16px;padding:22px;transition:all .2s;cursor:pointer; }
    .classmate-card:hover { box-shadow:0 6px 24px rgba(0,0,0,.08);transform:translateY(-2px); }
    .card-top { display:flex;align-items:center;gap:14px;margin-bottom:14px; }
    .c-avatar { width:50px;height:50px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:1.1rem;color:white;flex-shrink:0; }
    .c-name { font-family:'Syne',sans-serif;font-weight:700;font-size:.98rem; }
    .c-username { font-size:.78rem;color:var(--gray); }
    .c-headline { font-size:.84rem;color:var(--sky);font-weight:500;margin-bottom:12px; }
    .c-stats { display:flex;gap:12px; }
    .c-stat { font-size:.78rem;color:var(--gray); }
    .btn { display:inline-flex;align-items:center;gap:6px;padding:8px 14px;border-radius:8px;font-family:'DM Sans',sans-serif;font-size:.83rem;font-weight:600;border:none;cursor:pointer;transition:all .2s;text-decoration:none; }
    .btn-primary { background:var(--sky);color:white; }
    .btn-primary:hover { background:var(--sky-dark); }
    .btn-outline { background:transparent;border:1.5px solid var(--border);color:var(--ink); }
    .btn-outline:hover { border-color:var(--sky);color:var(--sky); }
    .section { background:white;border:1px solid var(--border);border-radius:16px;padding:28px;margin-bottom:20px; }
    .section-header { display:flex;justify-content:space-between;align-items:center;margin-bottom:20px; }
    .section-header h2 { font-family:'Syne',sans-serif;font-size:1.1rem;font-weight:700; }
    .profile-top { display:flex;gap:20px;align-items:flex-start; }
    .profile-avatar-lg { width:70px;height:70px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:1.4rem;color:white;flex-shrink:0; }
    .profile-links { display:flex;gap:8px;flex-wrap:wrap;margin-top:10px; }
    .profile-link { display:flex;align-items:center;gap:5px;font-size:.8rem;color:var(--sky);text-decoration:none;padding:4px 10px;border-radius:99px;border:1px solid #bfdbfe;background:var(--sky-light);transition:all .2s; }
    .profile-link:hover { background:var(--sky);color:white;border-color:var(--sky); }
    .skills-list { display:flex;flex-wrap:wrap;gap:8px; }
    .skill-chip { padding:5px 12px;border-radius:99px;font-size:.8rem;font-weight:500; }
    .projects-grid { display:grid;grid-template-columns:repeat(2,1fr);gap:14px; }
    .project-card { border:1px solid var(--border);border-radius:12px;padding:18px; }
    .project-card h4 { font-family:'Syne',sans-serif;font-size:.95rem;font-weight:700;margin-bottom:6px; }
    .project-card p { font-size:.83rem;color:var(--gray);line-height:1.5;margin-bottom:10px; }
    .tech-tags { display:flex;flex-wrap:wrap;gap:5px;margin-bottom:10px; }
    .tech-tag { font-size:.72rem;padding:2px 8px;background:var(--sky-light);color:var(--sky-dark);border-radius:99px;font-weight:500; }
    .empty-state { text-align:center;padding:40px 20px;color:var(--gray); }
    .empty-icon { font-size:2.5rem;margin-bottom:10px; }
    .back-btn { margin-bottom:20px; }
  </style>
</head>
<body>

<?php
// Get current user info for sidebar
$cu = $conn->prepare("SELECT firstName, lastName FROM users WHERE id=?");
$cu->bind_param("i", $userId);
$cu->execute();
$me = $cu->get_result()->fetch_assoc();
?>

<aside class="sidebar">
  <a href="dashboard.php" class="sidebar-logo">📁 Portfolio<span>Lib</span></a>
  <nav class="nav-section">
    <div class="nav-label">Menu</div>
    <a href="dashboard.php"  class="nav-link"><span class="icon">🏠</span> Overview</a>
    <a href="dashboard.php#profile"  class="nav-link"><span class="icon">👤</span> My Profile</a>
    <a href="dashboard.php#skills"   class="nav-link"><span class="icon">⚡</span> Skills</a>
    <a href="dashboard.php#projects" class="nav-link"><span class="icon">📂</span> Projects</a>
    <a href="classmates.php" class="nav-link active"><span class="icon">👥</span> Classmates</a>
  </nav>
  <div class="sidebar-footer">
    <div class="user-pill">
      <div class="user-avatar"><?= strtoupper(substr($me['firstName'],0,1).substr($me['lastName'],0,1)) ?></div>
      <div>
        <div class="user-name"><?= htmlspecialchars($me['firstName'].' '.$me['lastName']) ?></div>
        <div class="user-role">🧑‍🎓 Student</div>
      </div>
    </div>
    <form method="POST" action="logout.php">
      <button class="logout-btn" type="submit">🚪 Logout</button>
    </form>
  </div>
</aside>

<main class="main">

  <?php if ($viewPerson): ?>
    <!-- Individual classmate view -->
    <div class="back-btn">
      <a href="classmates.php" class="btn btn-outline">← All Classmates</a>
    </div>

    <div class="page-header">
      <h1><?= htmlspecialchars($viewPerson['firstName'].' '.$viewPerson['lastName']) ?></h1>
      <p><?= htmlspecialchars($viewPerson['headline'] ?? 'Student Portfolio') ?></p>
    </div>

    <div class="section">
      <div class="section-header"><h2>👤 Profile</h2></div>
      <div class="profile-top">
        <div class="profile-avatar-lg" style="background:<?= ac($viewPerson['id']) ?>">
          <?= strtoupper(substr($viewPerson['firstName'],0,1).substr($viewPerson['lastName'],0,1)) ?>
        </div>
        <div>
          <h3 style="font-family:'Syne',sans-serif;font-size:1.15rem;font-weight:700">
            <?= htmlspecialchars($viewPerson['firstName'].' '.$viewPerson['lastName']) ?>
          </h3>
          <p style="color:var(--sky);font-size:.88rem;font-weight:500;margin:3px 0">
            <?= htmlspecialchars($viewPerson['headline'] ?? '') ?>
          </p>
          <p style="color:var(--gray);font-size:.83rem">@<?= htmlspecialchars($viewPerson['userName']) ?></p>
          <?php if ($viewPerson['port_bio']): ?>
            <p style="font-size:.86rem;color:#374151;margin-top:10px;line-height:1.6"><?= nl2br(htmlspecialchars($viewPerson['port_bio'])) ?></p>
          <?php endif; ?>
          <div class="profile-links">
            <?php if ($viewPerson['github']): ?>
              <a href="<?= htmlspecialchars($viewPerson['github']) ?>" class="profile-link" target="_blank">🐙 GitHub</a>
            <?php endif; ?>
            <?php if ($viewPerson['linkedin']): ?>
              <a href="<?= htmlspecialchars($viewPerson['linkedin']) ?>" class="profile-link" target="_blank">💼 LinkedIn</a>
            <?php endif; ?>
            <?php if ($viewPerson['website']): ?>
              <a href="<?= htmlspecialchars($viewPerson['website']) ?>" class="profile-link" target="_blank">🌐 Website</a>
            <?php endif; ?>
            <?php if ($viewPerson['resume_file']): ?>
              <a href="uploads/resumes/<?= htmlspecialchars($viewPerson['resume_file']) ?>" class="profile-link" download>📄 Resume</a>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>

    <div class="section">
      <div class="section-header"><h2>⚡ Skills</h2></div>
      <?php if ($viewSkills): ?>
        <div class="skills-list">
          <?php foreach ($viewSkills as $s):
            $c = explode(':', $levelColors[$s['level']] ?? '#f1f5f9:#475569');
          ?>
            <div class="skill-chip" style="background:<?=$c[0]?>;color:<?=$c[1]?>">
              <?= htmlspecialchars($s['skill_name']) ?>
              <span style="opacity:.6;font-size:.72rem;margin-left:4px"><?= $s['level'] ?></span>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <p style="color:var(--gray);font-size:.88rem">No skills listed.</p>
      <?php endif; ?>
    </div>

    <div class="section">
      <div class="section-header"><h2>📂 Projects</h2></div>
      <?php if ($viewProjects): ?>
        <div class="projects-grid">
          <?php foreach ($viewProjects as $p): ?>
            <div class="project-card">
              <h4><?= htmlspecialchars($p['title']) ?></h4>
              <p><?= htmlspecialchars(substr($p['description'] ?? '', 0, 120)) ?><?= strlen($p['description'] ?? '') > 120 ? '…' : '' ?></p>
              <?php if ($p['tech_stack']): ?>
                <div class="tech-tags">
                  <?php foreach (explode(',', $p['tech_stack']) as $t): ?>
                    <span class="tech-tag"><?= htmlspecialchars(trim($t)) ?></span>
                  <?php endforeach; ?>
                </div>
              <?php endif; ?>
              <div style="display:flex;gap:8px">
                <?php if ($p['project_url']): ?>
                  <a href="<?= htmlspecialchars($p['project_url']) ?>" target="_blank" class="btn btn-outline">🔗 View</a>
                <?php endif; ?>
                <?php if ($p['file_name']): ?>
                  <a href="uploads/projects/<?= htmlspecialchars($p['file_name']) ?>" download class="btn btn-outline">📥 File</a>
                <?php endif; ?>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <div class="empty-state"><div class="empty-icon">📂</div><p>No projects shared yet.</p></div>
      <?php endif; ?>
    </div>

  <?php else: ?>
    <!-- Classmate list -->
    <div class="page-header">
      <h1>👥 Classmates</h1>
      <p>Browse your classmates' portfolios for inspiration.</p>
    </div>

    <div class="search-bar">
      <input type="text" id="searchInput" placeholder="🔍 Search classmates…" oninput="filter()">
    </div>

    <?php if ($classmates): ?>
      <div class="classmates-grid" id="grid">
        <?php foreach ($classmates as $c): ?>
          <div class="classmate-card" data-name="<?= strtolower($c['firstName'].' '.$c['lastName'].' '.($c['headline']??'')) ?>"
               onclick="window.location='classmates.php?view=<?= $c['id'] ?>'">
            <div class="card-top">
              <div class="c-avatar" style="background:<?= ac($c['id']) ?>">
                <?= strtoupper(substr($c['firstName'],0,1).substr($c['lastName'],0,1)) ?>
              </div>
              <div>
                <div class="c-name"><?= htmlspecialchars($c['firstName'].' '.$c['lastName']) ?></div>
                <div class="c-username">@<?= htmlspecialchars($c['userName']) ?></div>
              </div>
            </div>
            <?php if ($c['headline']): ?>
              <div class="c-headline"><?= htmlspecialchars($c['headline']) ?></div>
            <?php endif; ?>
            <div class="c-stats">
              <span class="c-stat">📂 <?= $c['project_count'] ?> project<?= $c['project_count'] != 1 ? 's' : '' ?></span>
              <span class="c-stat">⚡ <?= $c['skill_count'] ?> skill<?= $c['skill_count'] != 1 ? 's' : '' ?></span>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="empty-state">
        <div class="empty-icon">👥</div>
        <p>No classmates registered yet.</p>
      </div>
    <?php endif; ?>
  <?php endif; ?>

</main>

<script>
function filter() {
  const q = document.getElementById('searchInput').value.toLowerCase();
  document.querySelectorAll('.classmate-card').forEach(c => {
    c.style.display = c.dataset.name.includes(q) ? '' : 'none';
  });
}
</script>

</body>
</html>
