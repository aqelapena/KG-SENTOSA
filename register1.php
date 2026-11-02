<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Kampung Sentosa Issues Tracker</title>
    <link rel="icon" href="src/regis1.png">
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        :root { --charcoal:#292d30; --gold:#b78b59; --cream:#f8f5ef; --border:#eadfce; --muted:#77736f; }
        * { box-sizing:border-box; }
        body { margin:0; font-family:'Poppins',sans-serif; background:var(--cream); color:var(--charcoal); }
        .site-navbar { position:absolute; z-index:5; width:100%; top:0; left:0; padding:22px 0; background:rgba(25,27,29,.28); }
        .site-navbar .navbar-brand { font-size:20px; font-weight:700; color:white; }
        .site-navbar .nav-link { font-size:13px; color:#f0eee9 !important; margin-left:20px; }
        .site-navbar .nav-link:hover, .site-navbar .active .nav-link { color:#d5b489 !important; }
        .site-navbar .navbar-toggler { border-color:rgba(255,255,255,.5); }
        .hero { min-height:550px; display:flex; align-items:center; justify-content:center; padding:130px 20px 85px; text-align:center; background:linear-gradient(rgba(14,19,23,.70),rgba(14,19,23,.72)),url('src/kampung.jpeg') center/cover no-repeat; }
        .hero-inner { max-width:900px; }
        .hero-script { font-family:'Great Vibes',cursive; font-size:76px; line-height:1.15; color:#d7b486; }
        .hero h1 { color:white; font-size:37px; font-weight:700; letter-spacing:.3px; line-height:1.35; margin:12px 0 16px; }
        .hero p { color:#e4e2de; font-size:14px; line-height:1.9; max-width:650px; margin:auto; }
        .gold-line { height:2px; width:64px; background:#d3ae7b; margin:25px auto 0; }
        .choices { padding:75px 15px 95px; }
        .eyebrow { text-transform:uppercase; letter-spacing:2.4px; color:#b08454; font-weight:600; font-size:11px; text-align:center; }
        .choices h2 { font-size:29px; font-weight:700; text-align:center; margin:11px 0 10px; }
        .choices-sub { text-align:center; color:var(--muted); font-size:13px; margin:0 auto 38px; max-width:580px; line-height:1.8; }
        .role-grid { max-width:860px; margin:auto; }
        .role-card { background:#fff; border:1px solid var(--border); border-radius:17px; padding:38px 30px 32px; height:100%; text-align:center; box-shadow:0 12px 38px rgba(68,47,24,.055); transition:transform .2s,box-shadow .2s,border-color .2s; }
        .role-card:hover { transform:translateY(-5px); border-color:#d3b88f; box-shadow:0 18px 40px rgba(68,47,24,.10); }
        .role-icon { width:82px; height:82px; margin:0 auto 21px; display:flex; align-items:center; justify-content:center; border-radius:50%; color:#af8454; background:#f8eee1; font-size:31px; }
        .role-card h3 { font-size:20px; font-weight:700; margin-bottom:10px; }
        .role-card p { font-size:13px; color:var(--muted); line-height:1.8; min-height:69px; margin-bottom:24px; }
        .role-link { display:inline-flex; justify-content:center; align-items:center; gap:10px; width:100%; max-width:290px; padding:13px 18px; border-radius:8px; background:var(--gold); color:white; text-decoration:none; font-weight:600; font-size:13px; transition:background .2s; }
        .role-link:hover { background:#987044; color:white; text-decoration:none; }
        .role-link.secondary { background:#303237; }
        .role-link.secondary:hover { background:#151719; }
        .site-footer { background:var(--charcoal); text-align:center; padding:28px 16px; color:#e0d8cb; }
        .site-footer p { font-size:12px; margin:0; line-height:1.8; }
        .site-footer small { color:#b4ada5; font-size:11px; }
        @media(max-width:767px) { .site-navbar { background:#292d30; padding:11px 0; } .site-navbar .nav-link { margin-left:0; padding:10px 0; } .hero { min-height:460px; padding-top:145px; } .hero-script { font-size:56px; } .hero h1 { font-size:27px; } .choices { padding:55px 10px 65px; } .choices h2 { font-size:24px; } .role-card { padding:31px 25px; } }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark site-navbar">
  <div class="container">
    <a class="navbar-brand" href="index.php">Issues Tracker</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#siteNavigation" aria-controls="siteNavigation" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
    <div class="collapse navbar-collapse" id="siteNavigation">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item"><a href="index.php" class="nav-link">About us</a></li>
        <li class="nav-item active"><a href="register1.php" class="nav-link" aria-current="page">Register</a></li>
        <li class="nav-item"><a href="contactus.php" class="nav-link">Contact Us</a></li>
      </ul>
    </div>
  </div>
</nav>
<section class="hero">
  <div class="hero-inner">
    <div class="hero-script">Kampung Sentosa</div>
    <h1>Be Part of a Better Community</h1>
    <p>Create a resident account to report neighbourhood concerns, or sign in as a committee administrator to manage community issues.</p>
    <div class="gold-line"></div>
  </div>
</section>
<main class="choices">
  <div class="container">
    <div class="eyebrow">Get Started</div>
    <h2>Choose Your Access</h2>
    <p class="choices-sub">Select the option that matches your role in Kampung Sentosa.</p>
    <div class="row role-grid">
      <div class="col-md-6 mb-4 mb-md-0">
        <article class="role-card">
          <div class="role-icon" aria-hidden="true"><i class="fa-solid fa-people-group"></i></div>
          <h3>Resident</h3>
          <p>Register an account to report neighbourhood issues and keep your community informed.</p>
          <a class="role-link" href="register.php">Register as Resident <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
        </article>
      </div>
      <div class="col-md-6">
        <article class="role-card">
          <div class="role-icon" aria-hidden="true"><i class="fa-solid fa-user-shield"></i></div>
          <h3>Committee Admin</h3>
          <p>Already an authorised committee member? Sign in to manage and review community reports.</p>
          <a class="role-link secondary" href="loginadmin.php">Admin Login <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
        </article>
      </div>
    </div>
  </div>
</main>
<footer class="site-footer">
  <p>&copy; <?php echo date('Y'); ?> Kampung Sentosa Issues Tracker. All rights reserved.</p>
  <small>Building a safer, cleaner and more connected community.</small>
</footer>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
