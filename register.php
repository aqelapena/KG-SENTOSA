<?php
session_start();
include 'config.php';

if (isset($_SESSION['username'])) {
    header('Location: index1.php');
    exit();
}

function e($value) {
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}

if (!isset($_SESSION['register_csrf'])) {
    $_SESSION['register_csrf'] = bin2hex(random_bytes(32));
}

$username = '';
$email = '';
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $cpassword = $_POST['cpassword'] ?? '';

    if (!hash_equals($_SESSION['register_csrf'], (string)($_POST['csrf_token'] ?? ''))) {
        $error = 'Your session expired. Refresh the page and try again.';
    } elseif ($username === '' || $email === '' || $password === '' || $cpassword === '') {
        $error = 'Please fill in all fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (strlen($password) < 8) {
        $error = 'Password must contain at least 8 characters.';
    } elseif ($password !== $cpassword) {
        $error = 'Passwords do not match.';
    } else {
        $check = $conn->prepare('SELECT email FROM users WHERE email = ? LIMIT 1');
        if (!$check) {
            $error = 'Unable to process registration. Try again.';
        } else {
            $check->bind_param('s', $email);
            $check->execute();
            $check->store_result();
            $exists = $check->num_rows > 0;
            $check->close();

            if ($exists) {
                $error = 'This email is already registered.';
            } else {
                // Compatibility with the current login implementation, which uses MD5.
                // Upgrade registration and login together to password_hash/password_verify.
                $passwordForExistingLogin = md5($password);
                $insert = $conn->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ?)');
                if (!$insert) {
                    $error = 'Unable to process registration. Try again.';
                } else {
                    $insert->bind_param('sss', $username, $email, $passwordForExistingLogin);
                    if ($insert->execute()) {
                        $success = 'Your account has been created successfully. You can now log in.';
                        $username = '';
                        $email = '';
                        $_SESSION['register_csrf'] = bin2hex(random_bytes(32));
                    } else {
                        $error = 'Registration failed. Please try another username or email.';
                    }
                    $insert->close();
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Resident Registration | Kampung Sentosa</title>
<link rel="icon" href="src/adminicon.png">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Great+Vibes&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<style>
:root{--gold:#b88b58;--gold-dark:#977045;--cream:#f8f5ef;--dark:#292d30;--border:#e8dfd3;--muted:#817b73}
*{box-sizing:border-box}
body{margin:0;min-height:100vh;display:flex;flex-direction:column;background:var(--cream);font-family:'DM Sans',sans-serif;color:var(--dark)}
a{color:inherit}
.site-header{background:var(--dark);padding:19px 24px}
.header-inner{max-width:1060px;margin:auto;display:flex;align-items:center;justify-content:space-between;gap:20px}
.brand{color:#fff;text-decoration:none;font-weight:700;font-size:20px}.brand span{color:#d4b58c}
.back-top{color:#e6ded3;text-decoration:none;font-size:12px}.back-top:hover{color:#d4b58c}
main{flex:1;padding:56px 20px 75px;background:radial-gradient(circle at 20% 15%,#f3e8d9 0,transparent 36%),var(--cream)}
.register-wrap{max-width:930px;margin:auto;display:grid;grid-template-columns:.85fr 1.15fr;border:1px solid var(--border);border-radius:18px;overflow:hidden;background:#fff;box-shadow:0 14px 45px rgba(67,48,28,.08)}
.intro-side{background:linear-gradient(160deg,rgba(33,39,42,.83),rgba(33,39,42,.92)),url('src/kampung.jpeg') center/cover;min-height:580px;padding:49px 35px;display:flex;flex-direction:column;justify-content:space-between;color:#fff}
.script{font:normal 48px/1.15 'Great Vibes',cursive;color:#d4b58c;margin:0 0 16px}
.intro-side h1{font-size:29px;line-height:1.25;margin:0 0 15px}
.intro-side p{font-size:13px;line-height:1.9;color:#e4ded8;margin:0}
.feature{display:flex;gap:12px;align-items:center;color:#eee3d5;font-size:12px;margin-top:13px}.feature i{color:#d4b58c;width:18px}
.form-side{padding:46px 47px}
.eyebrow{font-size:10px;letter-spacing:2px;text-transform:uppercase;color:var(--gold);font-weight:700;margin:0 0 7px}
.form-side h2{font-size:27px;margin:0 0 8px}.subtitle{font-size:13px;color:var(--muted);line-height:1.7;margin:0 0 27px}
.form-group{margin:0 0 19px}.form-group label{display:block;font-weight:700;font-size:12px;margin:0 0 9px}
.form-group label i{color:var(--gold);width:20px}
.input-wrap{position:relative}.field{font-family:inherit;width:100%;height:48px;border:1px solid var(--border);border-radius:9px;background:#fffdfa;padding:0 14px;font-size:13px;outline:none;color:#393a3b;transition:.2s}
.field.password-field{padding-right:48px}.field:focus{border-color:var(--gold);box-shadow:0 0 0 3px rgba(184,139,88,.12);background:#fff}
.toggle-pw{position:absolute;right:9px;top:50%;transform:translateY(-50%);height:34px;width:34px;border:0;background:transparent;color:#9c9185;cursor:pointer}
.hint{font-size:11px;color:#948c82;margin-top:6px}
.btn{font:700 13px 'DM Sans',sans-serif;min-height:47px;padding:12px 18px;border-radius:9px;text-decoration:none;display:inline-flex;align-items:center;justify-content:center;gap:9px;cursor:pointer;transition:.2s}
.btn-submit{background:var(--gold);color:#fff;border:1px solid var(--gold);width:100%;margin-top:6px}.btn-submit:hover{background:var(--gold-dark)}
.secondary-links{display:flex;justify-content:space-between;gap:12px;align-items:center;margin-top:24px;font-size:12px;color:#726c65;flex-wrap:wrap}
.secondary-links a{font-weight:700;color:#987044;text-decoration:none}.secondary-links a:hover{text-decoration:underline}
.message{border-radius:8px;padding:12px 14px;font-size:12px;line-height:1.6;margin-bottom:20px}.error{background:#fff1f1;color:#9e4242;border:1px solid #f0caca}.success{background:#f0f8ef;color:#347244;border:1px solid #d0e8d1}
footer{background:var(--dark);color:#d9d2ca;text-align:center;padding:21px;font-size:12px}footer span{color:#d4b58c}
@media(max-width:760px){main{padding:26px 14px 44px}.register-wrap{grid-template-columns:1fr}.intro-side{min-height:auto;padding:30px 25px}.intro-side h1{font-size:24px}.script{font-size:42px}.features{display:none}.form-side{padding:33px 24px}}
@media(max-width:380px){.header-inner{align-items:flex-start;flex-direction:column;gap:9px}.form-side{padding:28px 18px}}
</style>
</head>
<body>
<header class="site-header"><div class="header-inner">
  <a href="register1.php" class="brand">Issues <span>Tracker</span></a>
  <a href="register1.php" class="back-top"><i class="fa-solid fa-arrow-left"></i> Back to account selection</a>
</div></header>
<main>
  <div class="register-wrap">
    <section class="intro-side" aria-label="Kampung Sentosa introduction">
      <div><p class="script">Kampung Sentosa</p><h1>Be part of a better community.</h1><p>Create your resident account to report neighbourhood issues and make your voice heard.</p></div>
      <div class="features"><div class="feature"><i class="fa-solid fa-clipboard-check"></i> Submit community reports</div><div class="feature"><i class="fa-solid fa-eye"></i> View reported issues</div><div class="feature"><i class="fa-solid fa-people-group"></i> Support a stronger neighbourhood</div></div>
    </section>
    <section class="form-side">
      <p class="eyebrow">Resident Account</p><h2>Create Your Account</h2><p class="subtitle">Fill in your details below to get started.</p>
      <?php if ($error !== ''): ?><div class="message error" role="alert"><i class="fa-solid fa-circle-exclamation"></i> <?php echo e($error); ?></div><?php endif; ?>
      <?php if ($success !== ''): ?><div class="message success" role="status"><i class="fa-solid fa-circle-check"></i> <?php echo e($success); ?> <a href="index1.php">Log in now</a>.</div><?php endif; ?>
      <form method="POST" action="register.php">
        <input type="hidden" name="csrf_token" value="<?php echo e($_SESSION['register_csrf']); ?>">
        <div class="form-group"><label for="username"><i class="fa-regular fa-user"></i> Username</label><input class="field" id="username" type="text" name="username" autocomplete="username" maxlength="100" placeholder="Choose a username" value="<?php echo e($username); ?>" required></div>
        <div class="form-group"><label for="email"><i class="fa-regular fa-envelope"></i> Email Address</label><input class="field" id="email" type="email" name="email" autocomplete="email" placeholder="Enter your email address" value="<?php echo e($email); ?>" required></div>
        <div class="form-group"><label for="password"><i class="fa-solid fa-lock"></i> Password</label><div class="input-wrap"><input class="field password-field" id="password" type="password" name="password" autocomplete="new-password" placeholder="Create a password" minlength="8" required><button class="toggle-pw" type="button" aria-label="Show password" aria-pressed="false" data-target="password"><i class="fa-regular fa-eye"></i></button></div><div class="hint">Use at least 8 characters.</div></div>
        <div class="form-group"><label for="cpassword"><i class="fa-solid fa-shield-halved"></i> Confirm Password</label><div class="input-wrap"><input class="field password-field" id="cpassword" type="password" name="cpassword" autocomplete="new-password" placeholder="Re-enter your password" minlength="8" required><button class="toggle-pw" type="button" aria-label="Show confirmation password" aria-pressed="false" data-target="cpassword"><i class="fa-regular fa-eye"></i></button></div></div>
        <button type="submit" name="submit" value="1" class="btn btn-submit"><i class="fa-solid fa-user-plus"></i> Create Account</button>
      </form>
      <div class="secondary-links"><span>Already have an account? <a href="index1.php">Log In</a></span><a href="register1.php"><i class="fa-solid fa-arrow-left"></i> Back</a></div>
    </section>
  </div>
</main>
<footer>&copy; <?php echo date('Y'); ?> <span>Kampung Sentosa</span> Issues Tracker. All rights reserved.</footer>
<script>
document.querySelectorAll('.toggle-pw').forEach(function(button){
  button.addEventListener('click',function(){
    const input=document.getElementById(button.dataset.target);
    const show=input.type==='password';
    input.type=show?'text':'password';
    button.setAttribute('aria-pressed',String(show));
    button.setAttribute('aria-label',show?'Hide password':'Show password');
    button.innerHTML=show?'<i class="fa-regular fa-eye-slash"></i>':'<i class="fa-regular fa-eye"></i>';
  });
});
</script>
</body>
</html>
