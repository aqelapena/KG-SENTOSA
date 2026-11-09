<?php
session_start();
require_once '../config.php';

if (isset($_SESSION['username'])) {
    header('Location: about1.php');
    exit;
}

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

if (empty($_SESSION['login_csrf'])) {
    $_SESSION['login_csrf'] = bin2hex(random_bytes(32));
}

$email = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = (string)($_POST['password'] ?? '');
    $token = (string)($_POST['csrf_token'] ?? '');

    if (!hash_equals($_SESSION['login_csrf'], $token)) {
        $error = 'Your session has expired. Please refresh and try again.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
        $error = 'Please enter a valid email and password.';
    } else {
        $stmt = $conn->prepare('SELECT username, email, password FROM users WHERE email = ? LIMIT 1');
        if (!$stmt) {
            $error = 'Unable to sign in right now.';
        } else {
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
            $stmt->close();

            $verified = false;
            if ($user) {
                $stored = (string)$user['password'];
                // Compatibility with existing MD5 records; upgrade alongside register.php.
                $verified = password_verify($password, $stored) ||
                    (preg_match('/^[a-f0-9]{32}$/i', $stored) && hash_equals(strtolower($stored), md5($password)));
            }

            if ($verified) {
                session_regenerate_id(true);
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                unset($_SESSION['login_csrf']);
                header('Location: about1.php');
                exit;
            }
            $error = 'Incorrect email or password.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#292d30">
    <link rel="icon" href="../src/adminicon.png">
    <title>Resident Login | Kampung Sentosa</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Great+Vibes&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        :root { --gold: #b88b58; --gold-dark: #9b7346; --charcoal: #292d30; --cream: #f8f5ef; --border: #e8ded0; }
        * { box-sizing: border-box; }
        body { margin:0; min-height:100vh; font-family:'DM Sans',sans-serif; color:#343433; background:var(--cream); display:flex; flex-direction:column; }
        .topbar { background:var(--charcoal); padding:18px 5%; display:flex; align-items:center; justify-content:space-between; gap:12px; }
        .brand { color:white; font-size:20px; font-weight:700; text-decoration:none; }
        .brand span { color:#d4b58c; }
        .topbar-back { font-size:12px; text-decoration:none; color:#e4ddd2; }
        .topbar-back:hover { color:#d4b58c; }
        main { flex:1; display:flex; align-items:center; justify-content:center; padding:52px 20px; }
        .auth-shell { width:min(100%,980px); min-height:560px; display:grid; grid-template-columns:1fr 1fr; background:white; border-radius:18px; overflow:hidden; border:1px solid var(--border); box-shadow:0 24px 60px rgba(65,49,28,.12); }
        .visual { position:relative; background:linear-gradient(rgba(18,23,25,.46),rgba(18,23,25,.72)),url('../src/kampung.jpeg') center/cover no-repeat; display:flex; flex-direction:column; justify-content:flex-end; padding:45px; color:white; }
        .visual-script { font-family:'Great Vibes',cursive; color:#e1bd8e; font-size:54px; line-height:1.15; }
        .visual h2 { font-size:33px; line-height:1.3; margin:6px 0 13px; }
        .visual p { font-size:13px; line-height:1.9; color:#e6e2dc; max-width:350px; margin:0; }
        .visual-line { width:62px; height:2px; background:#d4b58c; margin-bottom:20px; }
        .form-panel { display:flex; flex-direction:column; justify-content:center; padding:52px 48px; }
        .eyebrow { color:var(--gold-dark); font-weight:700; font-size:11px; letter-spacing:2px; text-transform:uppercase; }
        h1 { font-size:29px; color:var(--charcoal); margin:10px 0 8px; line-height:1.2; }
        .subtitle { font-size:13px; color:#857d75; line-height:1.7; margin:0 0 30px; }
        label { display:block; font-size:12px; color:#514b43; font-weight:700; margin-bottom:9px; }
        .field { margin-bottom:21px; }
        .input-wrap { position:relative; }
        .field-icon { position:absolute; left:15px; top:50%; transform:translateY(-50%); color:#b88b58; font-size:13px; pointer-events:none; }
        input { width:100%; height:49px; padding:12px 44px; border:1px solid var(--border); border-radius:9px; background:#fffdfa; font:inherit; font-size:13px; outline:none; transition:.2s; }
        input:focus { border-color:var(--gold); background:white; box-shadow:0 0 0 3px rgba(184,139,88,.13); }
        .toggle { position:absolute; right:8px; top:50%; transform:translateY(-50%); border:0; background:transparent; color:#867e73; padding:10px; cursor:pointer; }
        .submit { margin-top:7px; width:100%; background:var(--gold); color:white; border:0; border-radius:9px; font:inherit; font-weight:700; font-size:13px; padding:15px; cursor:pointer; transition:.2s; }
        .submit:hover { background:var(--gold-dark); }
        .register-line { text-align:center; font-size:12px; margin:25px 0 0; color:#746e67; }
        .register-line a { color:#9b7346; font-weight:700; text-decoration:none; }
        .register-line a:hover { text-decoration:underline; }
        .error { background:#fff0f0; border:1px solid #e9c1c1; color:#a04343; font-size:12px; border-radius:8px; padding:13px; margin-bottom:20px; line-height:1.6; }
        footer { background:var(--charcoal); padding:19px 15px; text-align:center; color:#d6d0c7; font-size:11px; }
        footer span { color:#d4b58c; }
        @media(max-width:750px) { .auth-shell { grid-template-columns:1fr; max-width:490px; } .visual { min-height:240px; padding:30px; } .visual-script { font-size:43px; } .visual h2 { font-size:25px; } .form-panel { padding:38px 28px; } main { padding:25px 15px; } }
        @media(max-width:390px) { .form-panel { padding:30px 20px; } }
    </style>
</head>
<body>
    <header class="topbar">
        <a class="brand" href="../index.php">Issues <span>Tracker</span></a>
        <a class="topbar-back" href="../register1.php"><i class="fa-solid fa-arrow-left"></i> Back to Roles</a>
    </header>

    <main>
        <div class="auth-shell">
            <div class="visual">
                <div class="visual-line"></div>
                <div class="visual-script">Kampung Sentosa</div>
                <h2>A Better Community Starts With You.</h2>
                <p>Welcome back. Sign in to report neighbourhood issues and contribute to a safer, more connected community.</p>
            </div>
            <div class="form-panel">
                <div class="eyebrow">Resident Portal</div>
                <h1>Welcome Back</h1>
                <p class="subtitle">Sign in to your Kampung Sentosa account.</p>

                <?php if ($error !== ''): ?>
                    <div class="error" role="alert"><i class="fa-solid fa-circle-exclamation"></i> <?php echo e($error); ?></div>
                <?php endif; ?>

                <form method="POST" action="">
                    <input type="hidden" name="csrf_token" value="<?php echo e($_SESSION['login_csrf']); ?>">
                    <div class="field">
                        <label for="email">Email Address</label>
                        <div class="input-wrap">
                            <i class="field-icon fa-regular fa-envelope"></i>
                            <input id="email" type="email" name="email" autocomplete="email" placeholder="Enter your email" value="<?php echo e($email); ?>" required>
                        </div>
                    </div>
                    <div class="field">
                        <label for="password">Password</label>
                        <div class="input-wrap">
                            <i class="field-icon fa-solid fa-lock"></i>
                            <input id="password" type="password" name="password" autocomplete="current-password" placeholder="Enter your password" required>
                            <button class="toggle" type="button" id="togglePassword" aria-label="Show password" aria-pressed="false"><i class="fa-regular fa-eye"></i></button>
                        </div>
                    </div>
                    <button type="submit" name="submit" value="1" class="submit"><i class="fa-solid fa-right-to-bracket"></i> Sign In</button>
                </form>
                <p class="register-line">Don't have an account? <a href="register.php">Create an Account</a></p>
            </div>
        </div>
    </main>
    <footer>&copy; <?php echo date('Y'); ?> <span>Kampung Sentosa</span> Issues Tracker. All rights reserved.</footer>
    <script>
        const toggle = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        toggle.addEventListener('click', function() {
            const showing = passwordInput.type === 'password';
            passwordInput.type = showing ? 'text' : 'password';
            toggle.setAttribute('aria-pressed', String(showing));
            toggle.setAttribute('aria-label', showing ? 'Hide password' : 'Show password');
            toggle.innerHTML = showing ? '<i class="fa-regular fa-eye-slash"></i>' : '<i class="fa-regular fa-eye"></i>';
        });
    </script>
</body>
</html>
<?php $conn->close(); ?>
