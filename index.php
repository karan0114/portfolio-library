<<<<<<< HEAD
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
=======
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Portfolio Library</title>
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
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
    }

    body {
      font-family: 'DM Sans', sans-serif;
      background: var(--sand);
      color: var(--ink);
      min-height: 100vh;
      overflow-x: hidden;
    }

    /* ── NAV ── */
    nav {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 20px 48px;
      border-bottom: 1px solid var(--border);
      background: white;
      position: sticky;
      top: 0;
      z-index: 100;
    }

    .logo {
      font-family: 'Syne', sans-serif;
      font-weight: 800;
      font-size: 1.3rem;
      color: var(--ink);
      text-decoration: none;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .logo span {
      color: var(--sky);
    }

    .nav-links { display: flex; gap: 12px; }

    .btn {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 10px 22px;
      border-radius: 10px;
      font-family: 'DM Sans', sans-serif;
      font-weight: 500;
      font-size: 0.95rem;
      text-decoration: none;
      cursor: pointer;
      transition: all 0.2s;
      border: none;
    }

    .btn-primary {
      background: var(--sky);
      color: white;
    }

    .btn-primary:hover {
      background: var(--sky-dark);
      transform: translateY(-1px);
    }

    .btn-outline {
      background: transparent;
      color: var(--sky);
      border: 2px solid var(--sky);
    }

    .btn-outline:hover {
      background: var(--sky-light);
    }

    /* ── HERO ── */
    .hero {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 60px;
      align-items: center;
      padding: 100px 48px;
      max-width: 1200px;
      margin: 0 auto;
    }

    .hero-badge {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: var(--sky-light);
      color: var(--sky-dark);
      font-size: 0.8rem;
      font-weight: 500;
      padding: 6px 14px;
      border-radius: 99px;
      margin-bottom: 24px;
      border: 1px solid #bfdbfe;
    }

    .hero h1 {
      font-family: 'Syne', sans-serif;
      font-size: 3.4rem;
      font-weight: 800;
      line-height: 1.1;
      margin-bottom: 20px;
      color: var(--ink);
    }

    .hero h1 em {
      font-style: normal;
      color: var(--sky);
    }

    .hero p {
      font-size: 1.1rem;
      color: var(--gray);
      line-height: 1.7;
      margin-bottom: 36px;
      max-width: 460px;
    }

    .hero-actions { display: flex; gap: 14px; flex-wrap: wrap; }

    /* ── HERO VISUAL ── */
    .hero-visual {
      position: relative;
      height: 420px;
    }

    .card-stack {
      position: relative;
      width: 100%;
      height: 100%;
    }

    .portfolio-card {
      position: absolute;
      background: white;
      border-radius: 20px;
      padding: 24px;
      box-shadow: 0 8px 32px rgba(0,0,0,0.08);
      border: 1px solid var(--border);
      transition: transform 0.3s;
    }

    .portfolio-card:nth-child(1) {
      width: 280px;
      top: 20px;
      right: 40px;
      transform: rotate(3deg);
      animation: float1 4s ease-in-out infinite;
    }

    .portfolio-card:nth-child(2) {
      width: 260px;
      top: 120px;
      right: 20px;
      transform: rotate(-2deg);
      animation: float2 5s ease-in-out infinite;
      z-index: 2;
    }

    .portfolio-card:nth-child(3) {
      width: 240px;
      bottom: 40px;
      right: 80px;
      transform: rotate(1deg);
      animation: float1 6s ease-in-out infinite reverse;
    }

    @keyframes float1 {
      0%, 100% { transform: rotate(3deg) translateY(0); }
      50% { transform: rotate(3deg) translateY(-10px); }
    }
    @keyframes float2 {
      0%, 100% { transform: rotate(-2deg) translateY(0); }
      50% { transform: rotate(-2deg) translateY(-8px); }
    }

    .card-avatar {
      width: 40px; height: 40px;
      border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      font-weight: 700; font-size: 1rem; color: white;
      margin-bottom: 12px;
    }

    .card-name { font-family: 'Syne', sans-serif; font-weight: 700; font-size: 0.95rem; margin-bottom: 3px; }
    .card-role { font-size: 0.8rem; color: var(--gray); margin-bottom: 12px; }

    .card-skills { display: flex; gap: 6px; flex-wrap: wrap; }
    .skill-tag {
      font-size: 0.7rem;
      padding: 3px 10px;
      border-radius: 99px;
      background: var(--sky-light);
      color: var(--sky-dark);
      font-weight: 500;
    }

    /* ── FEATURES ── */
    .features {
      background: white;
      padding: 80px 48px;
      border-top: 1px solid var(--border);
      border-bottom: 1px solid var(--border);
    }

    .features-inner {
      max-width: 1100px;
      margin: 0 auto;
    }

    .section-label {
      font-size: 0.8rem;
      font-weight: 700;
      letter-spacing: 0.12em;
      text-transform: uppercase;
      color: var(--sky);
      margin-bottom: 12px;
    }

    .section-title {
      font-family: 'Syne', sans-serif;
      font-size: 2rem;
      font-weight: 800;
      margin-bottom: 48px;
    }

    .features-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 28px;
    }

    .feature-card {
      padding: 28px;
      border-radius: 16px;
      border: 1px solid var(--border);
      transition: all 0.25s;
    }

    .feature-card:hover {
      border-color: var(--sky);
      box-shadow: 0 4px 20px rgba(59,130,246,0.1);
      transform: translateY(-3px);
    }

    .feature-icon {
      font-size: 2rem;
      margin-bottom: 16px;
    }

    .feature-card h3 {
      font-family: 'Syne', sans-serif;
      font-size: 1.05rem;
      font-weight: 700;
      margin-bottom: 8px;
    }

    .feature-card p {
      font-size: 0.9rem;
      color: var(--gray);
      line-height: 1.6;
    }

    /* ── CTA ── */
    .cta {
      padding: 80px 48px;
      text-align: center;
      max-width: 600px;
      margin: 0 auto;
    }

    .cta h2 {
      font-family: 'Syne', sans-serif;
      font-size: 2.2rem;
      font-weight: 800;
      margin-bottom: 16px;
    }

    .cta p {
      color: var(--gray);
      font-size: 1rem;
      margin-bottom: 32px;
    }

    .cta-btns { display: flex; justify-content: center; gap: 14px; flex-wrap: wrap; }

    /* ── ROLE BADGES ── */
    .roles {
      display: flex;
      gap: 16px;
      margin-bottom: 32px;
      flex-wrap: wrap;
    }

    .role-badge {
      display: flex;
      align-items: center;
      gap: 8px;
      padding: 10px 18px;
      border-radius: 12px;
      border: 1px solid var(--border);
      font-size: 0.9rem;
      font-weight: 500;
    }

    .role-badge.student { background: #f0fdf4; border-color: #86efac; color: #166534; }
    .role-badge.teacher { background: #eff6ff; border-color: #93c5fd; color: #1e40af; }

    /* ── MOBILE ROLE INFO (shown only on small screens, hidden on desktop) ── */
    .mobile-roles {
      display: none;
      gap: 10px;
      margin: 0 20px 0;
      padding: 16px 20px;
      background: white;
      border-bottom: 1px solid var(--border);
    }
    .mobile-role {
      flex: 1;
      display: flex;
      align-items: center;
      gap: 8px;
      padding: 12px 14px;
      border-radius: 12px;
      font-size: 0.82rem;
      font-weight: 500;
    }
    .mobile-role.s { background: #f0fdf4; border: 1px solid #86efac; color: #166534; }
    .mobile-role.t { background: #eff6ff; border: 1px solid #93c5fd; color: #1e40af; }
    .mobile-role small { display: block; font-size: 0.72rem; opacity: 0.75; margin-top: 1px; font-weight: 400; }

    @media (max-width: 768px) {
      /* Nav */
      nav { padding: 14px 20px; }
      .nav-links .btn { padding: 8px 14px; font-size: 0.85rem; }

      /* Hero: stack vertically, hide floating cards, show simple visual */
      .hero {
        grid-template-columns: 1fr;
        padding: 40px 20px 32px;
        gap: 0;
      }
      .hero h1 { font-size: 2rem; }
      .hero p { font-size: 1rem; }
      .hero-visual { display: none; }

      /* Show a simple card list instead of absolute-positioned stack */
      .hero-content { text-align: center; }
      .roles { justify-content: center; }
      .hero-actions { justify-content: center; }

      /* Features */
      .features { padding: 48px 20px; }
      .features-grid { grid-template-columns: 1fr; gap: 16px; }
      .feature-card { padding: 20px; }
      .section-title { font-size: 1.6rem; margin-bottom: 28px; }

      /* CTA */
      .cta { padding: 48px 20px; }
      .cta h2 { font-size: 1.7rem; }
      .cta-btns { flex-direction: column; align-items: center; }
      .cta-btns .btn { width: 100%; max-width: 280px; justify-content: center; }

      /* Show mobile role strip */
      .mobile-roles { display: flex; }
    }

    @media (max-width: 420px) {
      .hero h1 { font-size: 1.75rem; }
      .hero-badge { font-size: 0.72rem; }
      .role-badge { font-size: 0.8rem; padding: 8px 12px; }
      .btn { font-size: 0.88rem; }
    }
  </style>
</head>
<body>

  <nav>
    <a href="index.php" class="logo">📁 Portfolio<span>Lib</span></a>
    <div class="nav-links">
      <a href="login.php" class="btn btn-outline">Login</a>
      <a href="register.php" class="btn btn-primary">Get Started</a>
    </div>
  </nav>

  <!-- Mobile-only role strip -->
  <div class="mobile-roles">
    <div class="mobile-role s">
      <span>🧑‍🎓</span>
      <div><span>Students</span><small>Build &amp; share your portfolio</small></div>
    </div>
    <div class="mobile-role t">
      <span>👩‍🏫</span>
      <div><span>Teachers</span><small>Track students &amp; give feedback</small></div>
    </div>
  </div>

  <section>
    <div class="hero">
      <div class="hero-content">
        <div class="hero-badge">🎓 For Students &amp; Teachers</div>
        <h1>Your <em>Academic</em> Portfolio, Organised.</h1>
        <p>Students build and share their work. Teachers track progress and give feedback — all in one place.</p>
        <div class="roles">
          <div class="role-badge student">🧑‍🎓 Student Dashboard</div>
          <div class="role-badge teacher">👩‍🏫 Teacher Overview</div>
        </div>
        <div class="hero-actions">
          <a href="register.php" class="btn btn-primary">Create Portfolio →</a>
          <a href="login.php" class="btn btn-outline">Sign In</a>
        </div>
      </div>

      <div class="hero-visual">
        <div class="card-stack">
          <div class="portfolio-card">
            <div class="card-avatar" style="background:#3b82f6">AR</div>
            <div class="card-name">Arjun Roy</div>
            <div class="card-role">Web Developer</div>
            <div class="card-skills">
              <span class="skill-tag">PHP</span>
              <span class="skill-tag">MySQL</span>
              <span class="skill-tag">JS</span>
            </div>
          </div>
          <div class="portfolio-card">
            <div class="card-avatar" style="background:#10b981">PS</div>
            <div class="card-name">Priya Sharma</div>
            <div class="card-role">UI/UX Designer</div>
            <div class="card-skills">
              <span class="skill-tag">Figma</span>
              <span class="skill-tag">CSS</span>
              <span class="skill-tag">React</span>
            </div>
          </div>
          <div class="portfolio-card">
            <div class="card-avatar" style="background:#f59e0b">MD</div>
            <div class="card-name">Mehul Das</div>
            <div class="card-role">Data Analyst</div>
            <div class="card-skills">
              <span class="skill-tag">Python</span>
              <span class="skill-tag">SQL</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="features">
    <div class="features-inner">
      <div class="section-label">What you get</div>
      <h2 class="section-title">Everything you need in one platform</h2>
      <div class="features-grid">
        <div class="feature-card">
          <div class="feature-icon">📂</div>
          <h3>Upload Projects</h3>
          <p>Students post their work with descriptions, tech stacks, and project files or links.</p>
        </div>
        <div class="feature-card">
          <div class="feature-icon">⚡</div>
          <h3>Skills & Bio</h3>
          <p>Build a complete profile with a headline, bio, skills, and proficiency levels.</p>
        </div>
        <div class="feature-card">
          <div class="feature-icon">📄</div>
          <h3>Resume Upload</h3>
          <p>Attach your CV/resume so teachers and classmates can download it directly.</p>
        </div>
        <div class="feature-card">
          <div class="feature-icon">👩‍🏫</div>
          <h3>Teacher Overview</h3>
          <p>Teachers see all students at a glance and can drill into each portfolio and project.</p>
        </div>
        <div class="feature-card">
          <div class="feature-icon">💬</div>
          <h3>Feedback System</h3>
          <p>Teachers leave comments on individual projects to guide improvement.</p>
        </div>
        <div class="feature-card">
          <div class="feature-icon">👥</div>
          <h3>Classmate Browsing</h3>
          <p>Students can view each other's profiles and get inspired by their peers' work.</p>
        </div>
      </div>
    </div>
  </section>

  <section class="cta">
    <h2>Ready to get started?</h2>
    <p>Create your portfolio in minutes — no experience needed.</p>
    <div class="cta-btns">
      <a href="register.php" class="btn btn-primary">Register as Student</a>
      <a href="login.php" class="btn btn-outline">Teacher Login</a>
    </div>
  </section>

</body>
</html>
>>>>>>> 0de22fa (First git commit)
