<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit;
}
require 'db.php';

function e($value) {
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}

$issueTypes = ['Potholes', 'Broken Facilities', 'Garbage Collection', 'Streetlights Issues', 'Flooding', 'Others (Custom Entry)'];
$error_message = '';
$form = ['fname' => '', 'address' => '', 'contact' => '', 'dropdown' => '', 'description' => ''];

if (empty($_SESSION['issue_csrf'])) {
    $_SESSION['issue_csrf'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($form as $field => $_) {
        $form[$field] = trim((string)($_POST[$field] ?? ''));
    }
    if (!hash_equals($_SESSION['issue_csrf'], (string)($_POST['issue_csrf'] ?? ''))) {
        $error_message = 'Invalid form session. Refresh the page and try again.';
    } elseif ($form['fname'] === '' || $form['address'] === '' || $form['contact'] === '' || $form['description'] === '') {
        $error_message = 'Please fill in all required fields.';
    } elseif (!in_array($form['dropdown'], $issueTypes, true)) {
        $error_message = 'Please select a valid issue type.';
    } elseif (mb_strlen($form['fname']) > 150 || mb_strlen($form['contact']) > 30) {
        $error_message = 'Please check the name and contact number length.';
    } else {
        $file_path = null;
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] !== UPLOAD_ERR_NO_FILE) {
            $photo = $_FILES['photo'];
            if ($photo['error'] !== UPLOAD_ERR_OK) {
                $error_message = 'Photo upload failed. Please try again.';
            } elseif ($photo['size'] > 5 * 1024 * 1024) {
                $error_message = 'Photo must be 5 MB or smaller.';
            } else {
                $mime = (new finfo(FILEINFO_MIME_TYPE))->file($photo['tmp_name']);
                $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
                if (!isset($allowed[$mime]) || @getimagesize($photo['tmp_name']) === false) {
                    $error_message = 'Upload a valid JPG, PNG or WEBP image.';
                } else {
                    $uploadDir = __DIR__ . '/uploads';
                    if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true)) {
                        $error_message = 'Unable to create photo upload directory.';
                    } else {
                        $fileName = bin2hex(random_bytes(16)) . '.' . $allowed[$mime];
                        $target = $uploadDir . '/' . $fileName;
                        if (move_uploaded_file($photo['tmp_name'], $target)) {
                            $file_path = 'uploads/' . $fileName;
                        } else {
                            $error_message = 'Unable to save the uploaded photo.';
                        }
                    }
                }
            }
        }
        if ($error_message === '') {
            $stmt = $conn->prepare('INSERT INTO issues (fname, address, contact, issue_type, description, photo) VALUES (?, ?, ?, ?, ?, ?)');
            if (!$stmt) {
                $error_message = 'Unable to prepare the report. Please try again.';
            } else {
                $stmt->bind_param('ssssss', $form['fname'], $form['address'], $form['contact'], $form['dropdown'], $form['description'], $file_path);
                if ($stmt->execute()) {
                    unset($_SESSION['issue_csrf']);
                    $_SESSION['issue_success'] = true;
                    $stmt->close();
                    $conn->close();
                    header('Location: issueuser.php');
                    exit;
                }
                $error_message = 'Could not submit your report. Please try again.';
                $stmt->close();
                if ($file_path !== null && is_file(__DIR__ . '/' . $file_path)) {
                    unlink(__DIR__ . '/' . $file_path);
                }
            }
        }
    }
}
$success = !empty($_SESSION['issue_success']);
unset($_SESSION['issue_success']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Report an Issue | Kampung Sentosa</title>
<link rel="icon" href="src/issue.png">
<link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<style>
:root{--gold:#b88b58;--gold-deep:#956b3c;--dark:#292d30;--cream:#f8f5ef;--ink:#343434;--border:#e9dfd3;--muted:#847e77}
*{box-sizing:border-box}
html{scroll-behavior:smooth}
body{margin:0;background:var(--cream);color:var(--ink);font-family:'DM Sans',sans-serif}
a{color:inherit;text-decoration:none}
.hero{height:490px;position:relative;color:white;background:linear-gradient(rgba(10,16,20,.67),rgba(10,16,20,.70)),url('src/kampung.jpeg') center center/cover no-repeat}
.nav{max-width:1190px;margin:auto;padding:28px 24px;display:flex;justify-content:space-between;align-items:center;gap:20px;position:relative;z-index:2}
.brand{font-size:22px;font-weight:700;white-space:nowrap}.brand span{color:#d9b687}
.navlinks{display:flex;align-items:center;gap:25px;flex-wrap:wrap;font-size:13px}.navlinks a,.welcome{color:#eee}.navlinks a:hover,.navlinks .active{color:#d6b283}.navlinks .logout{color:#ff9990}
.hero-content{position:absolute;inset:0;display:flex;justify-content:center;align-items:center;flex-direction:column;text-align:center;padding:95px 18px 0;pointer-events:none}
.script{font-family:'Great Vibes',cursive;color:#d1ad7d;font-size:76px;line-height:1.15}
.hero h1{font-size:clamp(29px,4.4vw,58px);text-transform:uppercase;letter-spacing:3px;margin:6px 0 14px;font-weight:700}
.hero p{font-size:14px;color:#e7e0d8;letter-spacing:.2px;margin:0}
.main{max-width:970px;margin:0 auto;padding:58px 22px 80px}
.intro{text-align:center;margin-bottom:30px}.eyebrow{color:var(--gold-deep);font-size:11px;letter-spacing:2px;font-weight:700;text-transform:uppercase}
.intro h2{font-size:30px;margin:11px 0 9px}.intro p{color:var(--muted);font-size:14px;margin:0;line-height:1.8}
.form-card{background:#fff;border:1px solid var(--border);border-radius:16px;box-shadow:0 18px 50px rgba(61,45,25,.065);overflow:hidden}
.card-header{padding:29px 36px 25px;background:linear-gradient(120deg,#fffdf9,#faf5ed);border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;gap:15px}
.card-header h3{font-size:20px;margin:0 0 7px}.card-header p{font-size:12px;color:var(--muted);margin:0}.card-icon{height:45px;width:45px;border-radius:50%;display:grid;place-items:center;background:#f1e3d1;color:var(--gold-deep)}
.form-body{padding:34px 36px 38px}.form-grid{display:grid;grid-template-columns:1fr 1fr;gap:0 22px}.field{margin-bottom:23px}.field.full{grid-column:1/-1}
.field label{display:block;font-size:12px;font-weight:700;margin-bottom:9px}.field label i{color:var(--gold);margin-right:7px;width:14px}.req{color:#c46b66}
.control{width:100%;padding:12px 14px;min-height:46px;border:1px solid #e3d9cd;border-radius:8px;background:#fffdfa;font:13px 'DM Sans',sans-serif;color:var(--ink);outline:none;transition:border-color .2s,box-shadow .2s}
.control:focus{border-color:var(--gold);box-shadow:0 0 0 3px rgba(184,139,88,.12)}
textarea.control{min-height:95px;resize:vertical;line-height:1.7}.hint{color:#968e85;font-size:11px;margin:7px 0 0}
.upload-zone{border:1.5px dashed #ccb394;border-radius:10px;background:#fcf8f2;padding:22px;text-align:center;position:relative}
.upload-zone i{font-size:22px;color:var(--gold);margin-bottom:8px}.upload-zone strong{display:block;font-size:13px}.upload-zone p{margin:6px 0 13px;font-size:11px;color:var(--muted)}
.upload-zone input{font-size:12px;max-width:100%}.preview{display:none;width:150px;max-height:120px;object-fit:cover;margin:15px auto 0;border-radius:8px;border:1px solid var(--border)}
.form-footer{border-top:1px solid var(--border);padding-top:25px;display:flex;align-items:center;justify-content:space-between;gap:16px}
.privacy{color:#968e85;font-size:11px;line-height:1.6;max-width:400px}.submit{background:var(--gold);color:#fff;border:0;border-radius:8px;font:700 13px 'DM Sans',sans-serif;padding:14px 24px;cursor:pointer;white-space:nowrap;transition:background .2s,transform .2s}.submit:hover{background:var(--gold-deep);transform:translateY(-1px)}
.alert{padding:14px 17px;border-radius:8px;margin:0 0 23px;font-size:13px;line-height:1.6}.alert-error{background:#fff0f0;border:1px solid #efcaca;color:#9e3939}.alert-success{max-width:926px;margin:0 auto 26px;background:#edf8f0;border:1px solid #b7dfc1;color:#246338}
.footer{background:var(--dark);color:#dad5cf;text-align:center;padding:28px 15px;font-size:12px}.footer strong{color:#d6b283;font-weight:500}.footer small{display:block;color:#a9a8a5;margin-top:7px}
@media(max-width:820px){.nav{align-items:flex-start}.navlinks{gap:10px 17px}.hero{height:440px}.script{font-size:59px}}
@media(max-width:600px){.nav{flex-direction:column;padding:18px}.navlinks{font-size:12px;gap:10px 15px}.hero{height:430px}.hero-content{padding-top:95px}.script{font-size:52px}.hero h1{letter-spacing:1px}.main{padding:38px 15px 54px}.card-header,.form-body{padding:24px 20px}.form-grid{grid-template-columns:1fr}.form-footer{align-items:stretch;flex-direction:column}.submit{width:100%}.intro h2{font-size:25px}}
</style>
</head>
<body>
<section class="hero">
  <nav class="nav" aria-label="Main navigation">
    <a href="about1.php" class="brand">Issues <span>Tracker</span></a>
    <div class="navlinks">
      <a href="about1.php">About us</a>
      <a href="issueuser.php" class="active" aria-current="page">Report issue</a>
      <a href="view_issues.php">View issue</a>
      <a href="edit_profile.php">Edit Profile</a>
      <span class="welcome">Welcome, <?php echo e($_SESSION['username']); ?></span>
      <a class="logout" href="logout.php">Logout</a>
    </div>
  </nav>
  <div class="hero-content">
    <div class="script">Kampung Sentosa</div>
    <h1>Report an Issue</h1>
    <p>Your voice matters. Help us make our community better.</p>
  </div>
</section>
<main class="main">
  <div class="intro">
    <div class="eyebrow">Community Care</div>
    <h2>Tell Us What Needs Attention</h2>
    <p>Share the details of a neighborhood issue using the form below.</p>
  </div>

  <?php if ($success): ?>
    <div class="alert alert-success" role="status"><i class="fa-solid fa-circle-check"></i> Your issue was successfully submitted.</div>
  <?php endif; ?>

  <div class="form-card">
    <div class="card-header">
      <div><h3>Issue Report Form</h3><p>Complete the information below to submit your report.</p></div>
      <div class="card-icon"><i class="fa-regular fa-clipboard"></i></div>
    </div>
    <div class="form-body">
      <?php if ($error_message !== ''): ?>
        <div class="alert alert-error" role="alert"><?php echo e($error_message); ?></div>
      <?php endif; ?>
      <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="issue_csrf" value="<?php echo e($_SESSION['issue_csrf']); ?>">
        <div class="form-grid">
          <div class="field">
            <label for="fname"><i class="fa-regular fa-user"></i> Full Name <span class="req">*</span></label>
            <input class="control" type="text" id="fname" name="fname" maxlength="150" required placeholder="Enter your full name" value="<?php echo e($form['fname']); ?>">
          </div>
          <div class="field">
            <label for="contact"><i class="fa-solid fa-phone"></i> Contact Number <span class="req">*</span></label>
            <input class="control" type="tel" id="contact" name="contact" maxlength="30" required placeholder="e.g. 0123456789" value="<?php echo e($form['contact']); ?>">
          </div>
          <div class="field full">
            <label for="address"><i class="fa-solid fa-location-dot"></i> Address <span class="req">*</span></label>
            <textarea class="control" id="address" name="address" rows="2" required placeholder="Enter the location or address of the issue"><?php echo e($form['address']); ?></textarea>
          </div>
          <div class="field full">
            <label for="dropdown"><i class="fa-solid fa-list"></i> Issue Type <span class="req">*</span></label>
            <select class="control" id="dropdown" name="dropdown" required>
              <option value="" disabled <?php echo $form['dropdown'] === '' ? 'selected' : ''; ?>>Select an issue category</option>
              <?php foreach ($issueTypes as $type): ?>
                <option value="<?php echo e($type); ?>" <?php echo $form['dropdown'] === $type ? 'selected' : ''; ?>><?php echo e($type); ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="field full">
            <label for="description"><i class="fa-regular fa-pen-to-square"></i> Issue Description <span class="req">*</span></label>
            <textarea class="control" id="description" name="description" rows="4" required placeholder="Describe the issue, when it happened, and any useful details"><?php echo e($form['description']); ?></textarea>
            <p class="hint">Clear descriptions help the team understand the issue.</p>
          </div>
          <div class="field full">
            <label for="photo"><i class="fa-regular fa-image"></i> Supporting Photo (Optional)</label>
            <div class="upload-zone">
              <i class="fa-solid fa-cloud-arrow-up"></i>
              <strong>Add a photo of the issue</strong>
              <p>JPG, PNG or WEBP — maximum 5 MB</p>
              <input id="photo" name="photo" type="file" accept="image/jpeg,image/png,image/webp" aria-label="Upload issue photo">
              <img id="photoPreview" class="preview" alt="Selected photo preview">
            </div>
          </div>
        </div>
        <div class="form-footer">
          <div class="privacy"><i class="fa-solid fa-circle-info"></i> Please check your information before submitting your community report.</div>
          <button class="submit" type="submit"><i class="fa-solid fa-paper-plane"></i> &nbsp; Submit Report</button>
        </div>
      </form>
    </div>
  </div>
</main>
<footer class="footer">
  &copy; <?php echo date('Y'); ?> <strong>Kampung Sentosa</strong> Issues Tracker. All rights reserved.
  <small>Building a safer, cleaner and more connected community.</small>
</footer>
<script>
const photoInput = document.getElementById('photo');
const preview = document.getElementById('photoPreview');
photoInput.addEventListener('change', function () {
  const file = this.files && this.files[0];
  if (!file || !file.type.startsWith('image/')) {
    preview.style.display = 'none';
    preview.removeAttribute('src');
    return;
  }
  const reader = new FileReader();
  reader.onload = function (event) {
    preview.src = event.target.result;
    preview.style.display = 'block';
  };
  reader.readAsDataURL(file);
});
</script>
</body>
</html>
<?php $conn->close(); ?>
