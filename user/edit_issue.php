
<?php
session_start();
include '../db.php';

function e($value) {
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}

if (!isset($_GET['id']) || !ctype_digit((string)$_GET['id'])) {
    http_response_code(400);
    exit('Invalid request!');
}

$id = (int)$_GET['id'];
$error = '';

$stmt = $conn->prepare("SELECT * FROM issues WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    http_response_code(404);
    exit('Record not found!');
}

$row = $result->fetch_assoc();
$stmt->close();

$issueTypes = [
    'Potholes',
    'Broken Facilities',
    'Garbage Collection',
    'Streetlights Issues',
    'Flooding',
    'Others (Custom Entry)'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $fname = trim($_POST['fname'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $contact = trim($_POST['contact'] ?? '');
    $issue_type = trim($_POST['issue_type'] ?? '');
    $description = trim($_POST['description'] ?? '');

    // Preserve submitted values if validation fails
    $row['fname'] = $fname;
    $row['address'] = $address;
    $row['contact'] = $contact;
    $row['issue_type'] = $issue_type;
    $row['description'] = $description;

    if (
        $fname === '' ||
        $address === '' ||
        $contact === '' ||
        $description === '' ||
        !in_array($issue_type, $issueTypes, true)
    ) {
        $error = 'Please complete all fields correctly.';
    } else {
        $stmt = $conn->prepare(
            "UPDATE issues
             SET fname = ?,
                 address = ?,
                 contact = ?,
                 issue_type = ?,
                 description = ?
             WHERE id = ?"
        );

        $stmt->bind_param(
            "sssssi",
            $fname,
            $address,
            $contact,
            $issue_type,
            $description,
            $id
        );

        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();
            header("Location: view_issues.php");
            exit;
        } else {
            $error = 'Unable to update the issue. Please try again.';
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="icon" href="../src/view.png">
<title>Edit Issue | Kampung Sentosa</title>

<link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<style>
:root {
    --gold: #b88b58;
    --gold-dark: #9d7549;
    --gold-light: #d4b58c;
    --dark: #292d30;
    --cream: #f8f5ef;
    --text: #35383c;
    --muted: #8c8b88;
    --border: #eae3d9;
}

* {
    box-sizing: border-box;
}

body {
    margin: 0;
    min-height: 100vh;
    background: var(--cream);
    font-family: 'DM Sans', sans-serif;
    color: var(--text);
    display: flex;
    flex-direction: column;
}

/* HEADER */
.header {
    background: var(--dark);
    padding: 21px 0;
}

.header-inner {
    max-width: 1100px;
    margin: auto;
    padding: 0 25px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
}

.brand {
    color: white;
    font-size: 21px;
    font-weight: 700;
    text-decoration: none;
}

.brand span {
    color: var(--gold-light);
}

.header-back {
    color: #ded7cb;
    font-size: 12px;
    text-decoration: none;
    transition: .2s;
}

.header-back:hover {
    color: var(--gold-light);
}

/* HERO */
.hero {
    text-align: center;
    background: linear-gradient(135deg,#fffdfa,#f6efe5);
    border-bottom: 1px solid #e9ded0;
    padding: 52px 20px 45px;
}

.hero-script {
    font-family: 'Great Vibes', cursive;
    color: var(--gold);
    font-size: 51px;
    line-height: 1.1;
}

.hero h1 {
    margin: 5px 0 12px;
    font-size: 34px;
    letter-spacing: -.7px;
    color: var(--dark);
}

.hero p {
    color: #827d76;
    font-size: 13px;
    line-height: 1.8;
    margin: auto;
    max-width: 480px;
}

.gold-divider {
    background: var(--gold);
    height: 2px;
    width: 57px;
    margin: 21px auto 0;
}

/* MAIN */
main {
    flex: 1;
    width: 100%;
    padding: 42px 20px 75px;
}

.form-container {
    max-width: 820px;
    margin: auto;
    background: white;
    border: 1px solid var(--border);
    border-radius: 15px;
    box-shadow: 0 10px 35px rgba(85,64,41,.055);
    overflow: hidden;
}

.form-heading {
    padding: 29px 35px 25px;
    border-bottom: 1px solid #f0eae2;
}

.form-heading-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
}

.form-label-small {
    color: var(--gold);
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 2px;
    text-transform: uppercase;
    margin-bottom: 8px;
}

.form-heading h2 {
    font-size: 22px;
    margin: 0 0 8px;
    color: var(--dark);
}

.form-heading p {
    color: var(--muted);
    margin: 0;
    font-size: 12px;
    line-height: 1.7;
}

.issue-badge {
    color: #a67a48;
    background: #fbf1e5;
    padding: 9px 13px;
    border-radius: 25px;
    font-size: 12px;
    font-weight: 700;
    white-space: nowrap;
}

/* FORM */
.form-body {
    padding: 32px 35px 35px;
}

.form-row {
    display: flex;
    gap: 20px;
}

.form-group {
    width: 100%;
    margin-bottom: 23px;
}

.form-group label {
    display: block;
    color: #46423f;
    font-weight: 700;
    font-size: 12px;
    margin-bottom: 9px;
}

.form-group label i {
    width: 18px;
    color: var(--gold);
    margin-right: 4px;
}

.required {
    color: #c27975;
}

.form-control {
    font-family: inherit;
    width: 100%;
    background: #fffdfa;
    border: 1px solid #e7ded3;
    border-radius: 7px;
    padding: 13px 14px;
    font-size: 13px;
    color: #44423f;
    outline: none;
    transition: .2s;
}

.form-control:focus {
    border-color: var(--gold);
    background: white;
    box-shadow: 0 0 0 3px rgba(184,139,88,.1);
}

.form-control::placeholder {
    color: #b4aca2;
}

textarea.form-control {
    resize: vertical;
    min-height: 85px;
    line-height: 1.7;
}

select.form-control {
    cursor: pointer;
    appearance: auto;
}

.field-hint {
    color: #aaa197;
    font-size: 11px;
    margin-top: 7px;
}

/* ACTIONS */
.form-actions {
    border-top: 1px solid #f0eae2;
    padding-top: 25px;
    margin-top: 5px;
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 12px;
}

.btn {
    font-family: inherit;
    border-radius: 7px;
    padding: 12px 22px;
    border: 1px solid transparent;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
    text-decoration: none;
    transition: .2s;
}

.btn-cancel {
    background: white;
    color: #77716a;
    border-color: #e7ded3;
}

.btn-cancel:hover {
    background: #f9f6f1;
    color: #51473d;
}

.btn-save {
    background: var(--gold);
    color: white;
    border-color: var(--gold);
}

.btn-save:hover {
    background: var(--gold-dark);
}

.btn i {
    margin-right: 7px;
}

/* ERROR */
.alert-error {
    background: #fff0f0;
    border: 1px solid #f1c7c7;
    border-radius: 7px;
    color: #a53e45;
    padding: 13px 15px;
    font-size: 12px;
    margin-bottom: 22px;
}

/* FOOTER */
.footer {
    text-align: center;
    background: var(--dark);
    color: #d7d2ca;
    padding: 22px 15px;
    font-size: 12px;
}

.footer p {
    margin: 0;
}

.footer span {
    color: var(--gold-light);
}

@media (max-width: 600px) {
    .hero {
        padding: 39px 15px;
    }

    .hero-script {
        font-size: 43px;
    }

    .hero h1 {
        font-size: 27px;
    }

    main {
        padding: 25px 15px 50px;
    }

    .form-heading,
    .form-body {
        padding: 24px 20px;
    }

    .form-row {
        flex-direction: column;
        gap: 0;
    }

    .form-actions {
        flex-direction: column-reverse;
    }

    .btn {
        width: 100%;
        text-align: center;
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

        <a href="view_issues.php" class="header-back">
            <i class="fa-solid fa-arrow-left"></i>
            Back to Reported Issues
        </a>
    </div>
</header>

<!-- HERO -->
<section class="hero">
    <div class="hero-script">Kampung Sentosa</div>
    <h1>Edit Reported Issue</h1>
    <p>
        Keep our community reports accurate and up to date.
        Make the necessary changes to your submitted issue below.
    </p>
    <div class="gold-divider"></div>
</section>

<!-- FORM -->
<main>
    <div class="form-container">

        <div class="form-heading">
            <div class="form-heading-top">
                <div>
                    <div class="form-label-small">Community Report</div>
                    <h2>Issue Information</h2>
                </div>

                <span class="issue-badge">
                    #<?php echo $id; ?>
                </span>
            </div>

            <p>
                Update the details below to keep the issue record accurate.
            </p>
        </div>

        <div class="form-body">

            <?php if ($error !== ''): ?>
                <div class="alert-error" role="alert">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <?php echo e($error); ?>
                </div>
            <?php endif; ?>

            <form method="POST">

                <!-- NAME AND CONTACT -->
                <div class="form-row">

                    <div class="form-group">
                        <label for="fname">
                            <i class="fa-regular fa-user"></i>
                            Full Name <span class="required">*</span>
                        </label>

                        <input type="text"
                               class="form-control"
                               id="fname"
                               name="fname"
                               value="<?php echo e($row['fname']); ?>"
                               required>
                    </div>

                    <div class="form-group">
                        <label for="contact">
                            <i class="fa-solid fa-phone"></i>
                            Contact Number <span class="required">*</span>
                        </label>

                        <input type="text"
                               class="form-control"
                               id="contact"
                               name="contact"
                               value="<?php echo e($row['contact']); ?>"
                               required>
                    </div>

                </div>

                <!-- ADDRESS -->
                <div class="form-group">
                    <label for="address">
                        <i class="fa-solid fa-location-dot"></i>
                        Address <span class="required">*</span>
                    </label>

                    <textarea class="form-control"
                              id="address"
                              name="address"
                              rows="3"
                              required><?php echo e($row['address']); ?></textarea>
                </div>

                <!-- ISSUE TYPE -->
                <div class="form-group">
                    <label for="issue_type">
                        <i class="fa-solid fa-list"></i>
                        Issue Type <span class="required">*</span>
                    </label>

                    <select class="form-control"
                            id="issue_type"
                            name="issue_type"
                            required>

                        <?php foreach ($issueTypes as $type): ?>
                            <option value="<?php echo e($type); ?>"
                                <?php echo $row['issue_type'] === $type
                                    ? 'selected'
                                    : ''; ?>>
                                <?php echo e($type); ?>
                            </option>
                        <?php endforeach; ?>

                    </select>

                    <div class="field-hint">
                        Select the category that best describes the reported issue.
                    </div>
                </div>

                <!-- DESCRIPTION -->
                <div class="form-group">
                    <label for="description">
                        <i class="fa-regular fa-pen-to-square"></i>
                        Issue Description <span class="required">*</span>
                    </label>

                    <textarea class="form-control"
                              id="description"
                              name="description"
                              rows="5"
                              required><?php echo e($row['description']); ?></textarea>

                    <div class="field-hint">
                        Provide clear details about the issue for better understanding.
                    </div>
                </div>

                <!-- BUTTONS -->
                <div class="form-actions">

                    <a href="view_issues.php" class="btn btn-cancel">
                        <i class="fa-solid fa-xmark"></i>
                        Cancel
                    </a>

                    <button type="submit" class="btn btn-save">
                        <i class="fa-solid fa-check"></i>
                        Save Changes
                    </button>

                </div>

            </form>
        </div>

    </div>
</main>

<!-- FOOTER -->
<footer class="footer">
    <p>
        &copy; <?php echo date('Y'); ?>
        <span>Kampung Sentosa</span> Issues Tracker.
        All rights reserved.
    </p>
</footer>

</body>
</html>
<?php $conn->close(); ?>
