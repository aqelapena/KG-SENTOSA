
<?php
session_start();
include 'config.php';

if (!isset($_SESSION['username'])) {
    header("Location: index1.php");
    exit();
}

function e($value) {
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$username = $_SESSION['username'];
$email = $_SESSION['email'] ?? '';
$error = '';
$success = isset($_GET['updated']) && $_GET['updated'] === '1';

// Fetch current profile
$stmt = $conn->prepare(
    "SELECT username, email FROM users WHERE username = ? LIMIT 1"
);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $stmt->close();
    header("Location: index1.php");
    exit();
}

$user = $result->fetch_assoc();
$username = $user['username'];
$email = $user['email'];
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (
        !isset($_POST['csrf_token']) ||
        !hash_equals(
            $_SESSION['csrf_token'],
            (string)$_POST['csrf_token']
        )
    ) {
        $error = 'Invalid request. Please refresh the page.';
    } elseif (isset($_POST['submit'])) {

        $new_username = trim($_POST['username'] ?? '');
        $new_email = trim($_POST['email'] ?? '');

        if ($new_username === '' || $new_email === '') {
            $error = 'Please complete all fields.';
        } elseif (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Please enter a valid email address.';
        } else {

            $stmt = $conn->prepare(
                "UPDATE users
                 SET username = ?, email = ?
                 WHERE username = ?"
            );

            $stmt->bind_param(
                "sss",
                $new_username,
                $new_email,
                $username
            );

            if ($stmt->execute()) {
                $_SESSION['username'] = $new_username;
                $_SESSION['email'] = $new_email;

                $stmt->close();

                header("Location: edit_profile.php?updated=1");
                exit();
            } else {
                $error = 'Unable to update profile. The username or email may already be in use.';
                $stmt->close();
            }
        }

    } elseif (isset($_POST['delete'])) {

        $stmt = $conn->prepare(
            "DELETE FROM users WHERE username = ?"
        );
        $stmt->bind_param("s", $username);

        if ($stmt->execute()) {
            $stmt->close();
            $_SESSION = [];
            session_destroy();

            header("Location: index1.php");
            exit();
        } else {
            $error = 'Unable to delete account.';
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="icon" href="src/editprof.png">
<title>Edit Profile | Kampung Sentosa</title>

<link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<style>
:root {
    --gold: #b88b58;
    --gold-light: #d4b58c;
    --dark: #292d30;
    --cream: #f8f5ef;
    --border: #eae3d9;
    --muted: #8c8b88;
}

* {
    box-sizing: border-box;
}

body {
    margin: 0;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    font-family: 'DM Sans', sans-serif;
    background: var(--cream);
    color: #35383c;
}

/* HEADER */
.header {
    background: var(--dark);
    padding: 22px 0;
}

.header-inner {
    max-width: 1000px;
    margin: auto;
    padding: 0 25px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
}

.brand {
    text-decoration: none;
    color: white;
    font-size: 21px;
    font-weight: 700;
}

.brand span {
    color: var(--gold-light);
}

.back-link {
    color: #d7d2ca;
    font-size: 12px;
    text-decoration: none;
}

.back-link:hover {
    color: var(--gold-light);
}

/* HERO */
.hero {
    text-align: center;
    padding: 52px 20px 48px;
    background: linear-gradient(135deg, #fffdfa, #f6efe5);
    border-bottom: 1px solid #e9ded0;
}

.hero-script {
    font-family: 'Great Vibes', cursive;
    font-size: 52px;
    color: var(--gold);
    line-height: 1.1;
}

.hero h1 {
    color: var(--dark);
    font-size: 34px;
    margin: 5px 0 12px;
}

.hero p {
    font-size: 13px;
    color: #827d76;
    line-height: 1.8;
    margin: auto;
}

.gold-divider {
    width: 58px;
    height: 2px;
    background: var(--gold);
    margin: 22px auto 0;
}

/* MAIN */
main {
    flex: 1;
    padding: 45px 20px 75px;
}

.profile-card {
    max-width: 740px;
    background: white;
    border: 1px solid var(--border);
    border-radius: 15px;
    margin: 0 auto;
    overflow: hidden;
    box-shadow: 0 10px 35px rgba(85,64,41,.06);
}

.profile-heading {
    padding: 31px 35px;
    border-bottom: 1px solid #f0eae2;
    display: flex;
    align-items: center;
    gap: 17px;
}

.profile-avatar {
    width: 65px;
    height: 65px;
    background: #f9f0e5;
    color: var(--gold);
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 26px;
    flex-shrink: 0;
}

.profile-heading h2 {
    font-size: 20px;
    color: var(--dark);
    margin: 0 0 7px;
}

.profile-heading p {
    font-size: 12px;
    color: var(--muted);
    margin: 0;
    line-height: 1.7;
}

/* FORM */
.profile-body {
    padding: 34px 35px;
}

.section-label {
    color: var(--gold);
    text-transform: uppercase;
    letter-spacing: 1.8px;
    font-size: 10px;
    font-weight: 700;
    margin-bottom: 22px;
}

.form-group {
    margin-bottom: 24px;
}

.form-group label {
    display: block;
    font-size: 12px;
    font-weight: 700;
    margin-bottom: 10px;
    color: #4d4944;
}

.form-group label i {
    color: var(--gold);
    margin-right: 8px;
    width: 14px;
}

.form-input {
    width: 100%;
    height: 47px;
    padding: 12px 15px;
    background: #fffdfa;
    border: 1px solid #e7ded3;
    border-radius: 8px;
    font-family: inherit;
    font-size: 13px;
    color: #44423f;
    outline: none;
    transition: .2s;
}

.form-input:focus {
    background: white;
    border-color: var(--gold);
    box-shadow: 0 0 0 3px rgba(184,139,88,.1);
}

.form-hint {
    font-size: 11px;
    color: #a9a096;
    margin-top: 8px;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    padding-top: 10px;
}

.btn {
    font-family: inherit;
    font-size: 12px;
    font-weight: 700;
    padding: 13px 20px;
    border-radius: 7px;
    text-decoration: none;
    cursor: pointer;
    border: 1px solid transparent;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: .2s;
}

.btn-gold {
    background: var(--gold);
    color: white;
}

.btn-gold:hover {
    background: #9d7549;
}

.btn-outline {
    background: white;
    color: #77716a;
    border-color: #e7ded3;
}

.btn-outline:hover {
    background: #f9f6f1;
}

/* DANGER ZONE */
.danger-zone {
    border-top: 1px solid #f0eae2;
    padding: 28px 35px 33px;
    background: #fffcfb;
}

.danger-heading {
    display: flex;
    align-items: center;
    gap: 10px;
    color: #ad5656;
    font-weight: 700;
    font-size: 14px;
    margin-bottom: 10px;
}

.danger-zone p {
    font-size: 12px;
    color: #8c8580;
    line-height: 1.8;
    margin-bottom: 19px;
}

.btn-delete {
    background: #fff;
    color: #bc5a5a;
    border: 1px solid #e8bcbc;
}

.btn-delete:hover {
    background: #fff0f0;
}

/* ALERTS */
.alert {
    border-radius: 8px;
    padding: 13px 15px;
    font-size: 12px;
    margin-bottom: 23px;
    line-height: 1.6;
}

.alert-success {
    background: #eef8ef;
    color: #31794a;
    border: 1px solid #c9e8cf;
}

.alert-error {
    background: #fff0f0;
    color: #aa4646;
    border: 1px solid #f0c5c5;
}

/* FOOTER */
.footer {
    background: var(--dark);
    padding: 23px 15px;
    text-align: center;
    color: #d7d2ca;
    font-size: 12px;
}

.footer span {
    color: var(--gold-light);
}

@media (max-width: 600px) {
    .hero {
        padding: 40px 15px;
    }

    .hero-script {
        font-size: 43px;
    }

    .hero h1 {
        font-size: 28px;
    }

    main {
        padding: 25px 15px 50px;
    }

    .profile-heading,
    .profile-body,
    .danger-zone {
        padding: 24px 20px;
    }

    .form-actions {
        flex-direction: column-reverse;
    }

    .form-actions .btn,
    .btn-delete {
        width: 100%;
    }
}
</style>
</head>

<body>

<!-- HEADER -->
<header class="header">
    <div class="header-inner">
        <a href="about1.php" class="brand">
            Issues <span>Tracker</span>
        </a>

        <a href="about1.php" class="back-link">
            <i class="fa-solid fa-arrow-left"></i>
            Back to Dashboard
        </a>
    </div>
</header>

<!-- HERO -->
<section class="hero">
    <div class="hero-script">Kampung Sentosa</div>
    <h1>Account Settings</h1>
    <p>
        Manage your personal information and keep
        your community account up to date.
    </p>
    <div class="gold-divider"></div>
</section>

<!-- MAIN -->
<main>

<div class="profile-card">

    <!-- PROFILE HEADING -->
    <div class="profile-heading">

        <div class="profile-avatar">
            <i class="fa-regular fa-user"></i>
        </div>

        <div>
            <h2>My Profile</h2>
            <p>
                Welcome, <?php echo e($username); ?>.
                Update your personal account details below.
            </p>
        </div>

    </div>

    <!-- PROFILE FORM -->
    <div class="profile-body">

        <div class="section-label">
            Personal Information
        </div>

        <?php if ($success): ?>
            <div class="alert alert-success">
                <i class="fa-solid fa-circle-check"></i>
                Your profile has been updated successfully!
            </div>
        <?php endif; ?>

        <?php if ($error !== ''): ?>
            <div class="alert alert-error">
                <i class="fa-solid fa-circle-exclamation"></i>
                <?php echo e($error); ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST">

            <input type="hidden"
                   name="csrf_token"
                   value="<?php echo e($_SESSION['csrf_token']); ?>">

            <div class="form-group">
                <label for="username">
                    <i class="fa-regular fa-user"></i>
                    Username
                </label>

                <input type="text"
                       id="username"
                       name="username"
                       class="form-input"
                       value="<?php echo e($username); ?>"
                       placeholder="Enter your username"
                       required>

                <div class="form-hint">
                    This name will be displayed on your account.
                </div>
            </div>

            <div class="form-group">
                <label for="email">
                    <i class="fa-regular fa-envelope"></i>
                    Email Address
                </label>

                <input type="email"
                       id="email"
                       name="email"
                       class="form-input"
                       value="<?php echo e($email); ?>"
                       placeholder="Enter your email"
                       required>

                <div class="form-hint">
                    Make sure your email address is accurate.
                </div>
            </div>

            <div class="form-actions">

                <a href="about1.php" class="btn btn-outline">
                    <i class="fa-solid fa-arrow-left"></i>
                    Back to Dashboard
                </a>

                <button type="submit"
                        name="submit"
                        value="1"
                        class="btn btn-gold">
                    <i class="fa-solid fa-check"></i>
                    Save Changes
                </button>

            </div>
        </form>

    </div>

    <!-- DANGER ZONE -->
    <div class="danger-zone">

        <div class="danger-heading">
            <i class="fa-solid fa-triangle-exclamation"></i>
            Danger Zone
        </div>

        <p>
            Deleting your account is a permanent action.
            Your account will be removed and you will no longer
            be able to access it.
        </p>

        <form method="POST"
              onsubmit="return confirm('Are you sure you want to permanently delete your account? This action cannot be undone.');">

            <input type="hidden"
                   name="csrf_token"
                   value="<?php echo e($_SESSION['csrf_token']); ?>">

            <button type="submit"
                    name="delete"
                    value="1"
                    class="btn btn-delete">

                <i class="fa-regular fa-trash-can"></i>
                Delete My Account

            </button>

        </form>

    </div>

</div>

</main>

<!-- FOOTER -->
<footer class="footer">
    &copy; <?php echo date('Y'); ?>
    <span>Kampung Sentosa</span> Issues Tracker.
    All rights reserved.
</footer>

</body>
</html>
