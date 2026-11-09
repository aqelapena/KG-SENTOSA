
<?php
session_start();
include '../config.php';

/*
 SECURITY:
 Add your existing admin-session authentication check here.
 For example, ONLY if your loginadmin.php stores admin_id:

 if (!isset($_SESSION['admin_id'])) {
     header("Location: loginadmin.php");
     exit;
 }

 Do not deploy Edit/Delete without this check.
*/

if (empty($_SESSION['user_management_csrf'])) {
    $_SESSION['user_management_csrf'] =
        bin2hex(random_bytes(32));
}

function escape($value) {
    return htmlspecialchars(
        (string)$value,
        ENT_QUOTES,
        'UTF-8'
    );
}

function flashRedirect($message, $type = 'success') {
    $_SESSION['user_management_flash'] = [
        'message' => $message,
        'type' => $type
    ];

    header("Location: userview.php");
    exit;
}

// EDIT AND DELETE FUNCTIONS
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (
        !isset($_POST['csrf_token']) ||
        !hash_equals(
            $_SESSION['user_management_csrf'],
            $_POST['csrf_token']
        )
    ) {
        http_response_code(403);
        exit("Invalid security token.");
    }

    $action = $_POST['action'] ?? '';
    $id = filter_var(
        $_POST['id'] ?? null,
        FILTER_VALIDATE_INT
    );

    if (!$id || $id < 1) {
        flashRedirect("Invalid user ID.", "error");
    }

    // UPDATE USER
    if ($action === 'edit') {

        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');

        if ($username === '' || $email === '') {
            flashRedirect(
                "Username and email cannot be empty.",
                "error"
            );
        }

        if (strlen($username) > 100 ||
            strlen($email) > 254) {
            flashRedirect(
                "Username or email is too long.",
                "error"
            );
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            flashRedirect(
                "Please enter a valid email.",
                "error"
            );
        }

        // Duplicate email check
        $check = $conn->prepare(
            "SELECT id FROM users
             WHERE email = ? AND id != ?"
        );

        $check->bind_param("si", $email, $id);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $check->close();

            flashRedirect(
                "Email is already used by another user.",
                "error"
            );
        }

        $check->close();

        $stmt = $conn->prepare(
            "UPDATE users
             SET username = ?, email = ?
             WHERE id = ?"
        );

        $stmt->bind_param(
            "ssi",
            $username,
            $email,
            $id
        );

        if ($stmt->execute()) {
            $stmt->close();

            flashRedirect(
                "User information updated successfully."
            );
        } else {
            flashRedirect(
                "Failed to update user.",
                "error"
            );
        }
    }

    // DELETE USER
    elseif ($action === 'delete') {

        $stmt = $conn->prepare(
            "DELETE FROM users WHERE id = ?"
        );

        $stmt->bind_param("i", $id);

        if ($stmt->execute() && $stmt->affected_rows > 0) {
            $stmt->close();

            flashRedirect(
                "User deleted successfully."
            );
        } else {
            flashRedirect(
                "Failed to delete user.",
                "error"
            );
        }
    }

    else {
        flashRedirect("Invalid action.", "error");
    }
}

// FETCH USER DATA
$result = $conn->query(
    "SELECT id, username, email
     FROM users ORDER BY id ASC"
);

$users = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

$totalUsers = count($users);

// FLASH MESSAGE
$flash = $_SESSION['user_management_flash'] ?? null;
unset($_SESSION['user_management_flash']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>User Management</title>

<link rel="icon" href="../src/dadmin.png">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
rel="stylesheet">

<style>
:root {
    --navy: #17213b;
    --purple: #7161ef;
    --purple-light: #f0edff;
    --background: #f6f7fc;
    --text: #252b40;
    --muted: #8992a7;
    --border: #edf0f6;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Inter', sans-serif;
    background: var(--background);
    color: var(--text);
}

/* NAVBAR */
.navbar {
    height: 78px;
    padding: 0 7%;
    background: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid var(--border);
}

.logo {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 19px;
    font-weight: 800;
    color: var(--navy);
}

.logo-icon {
    width: 42px;
    height: 42px;
    background: var(--purple);
    color: white;
    border-radius: 12px;
    display: grid;
    place-items: center;
}

.back-btn {
    background: var(--purple-light);
    color: var(--purple);
    text-decoration: none;
    padding: 11px 19px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 9px;
    transition: .2s;
}

.back-btn:hover {
    background: #ded8ff;
}

/* CONTENT */
.container {
    width: 90%;
    max-width: 1250px;
    margin: 45px auto;
}

.page-heading {
    margin-bottom: 28px;
}

.page-heading h1 {
    font-size: 26px;
    font-weight: 800;
    color: var(--navy);
    margin-bottom: 10px;
}

.page-heading p {
    color: var(--muted);
    font-size: 13px;
    line-height: 1.7;
}

/* SUMMARY CARD */
.summary-card {
    background: white;
    padding: 25px;
    border-radius: 18px;
    border: 1px solid var(--border);
    box-shadow: 0 5px 20px #20263a06;
    display: flex;
    align-items: center;
    gap: 20px;
    margin-bottom: 27px;
}

.summary-icon {
    width: 60px;
    height: 60px;
    border-radius: 16px;
    background: var(--purple-light);
    color: var(--purple);
    display: grid;
    place-items: center;
    font-size: 25px;
}

.summary-card p {
    font-size: 12px;
    color: var(--muted);
    margin-bottom: 7px;
}

.summary-card h2 {
    font-size: 29px;
    font-weight: 800;
    color: var(--navy);
}

/* TABLE CARD */
.table-card {
    background: white;
    border-radius: 18px;
    border: 1px solid var(--border);
    box-shadow: 0 5px 20px #20263a06;
    padding: 28px;
}

.table-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 18px;
    margin-bottom: 25px;
}

.table-title h2 {
    font-size: 17px;
    font-weight: 800;
    margin-bottom: 7px;
}

.table-title p {
    color: var(--muted);
    font-size: 12px;
}

/* SEARCH */
.search-box {
    position: relative;
}

.search-box i {
    position: absolute;
    top: 50%;
    left: 15px;
    transform: translateY(-50%);
    color: #a2a8b8;
}

.search-box input {
    width: 280px;
    padding: 13px 15px 13px 42px;
    border: 1px solid #e5e8f0;
    border-radius: 10px;
    outline: none;
    background: #fafbfe;
    font-family: inherit;
    font-size: 12px;
    transition: .2s;
}

.search-box input:focus {
    border-color: var(--purple);
    background: white;
}

/* TABLE */
.table-responsive {
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
    text-align: left;
}

thead {
    background: #f7f7fd;
}

th {
    padding: 18px;
    font-size: 12px;
    font-weight: 700;
    color: #657087;
    white-space: nowrap;
}

td {
    padding: 18px;
    font-size: 13px;
    border-bottom: 1px solid #f0f1f5;
}

tbody tr:hover {
    background: #fbfaff;
}

tbody tr:last-child td {
    border-bottom: none;
}

.id-badge {
    background: #f0edff;
    color: var(--purple);
    padding: 7px 11px;
    border-radius: 8px;
    font-weight: 700;
    font-size: 12px;
}

.user-cell {
    display: flex;
    align-items: center;
    gap: 12px;
}

.user-avatar {
    width: 35px;
    height: 35px;
    border-radius: 10px;
    background: #e9f3ff;
    color: #448de2;
    display: grid;
    place-items: center;
    font-weight: 700;
    flex-shrink: 0;
}

.username {
    font-weight: 600;
}

.email {
    color: #737d91;
}

/* ACTION BUTTONS */
.actions {
    display: flex;
    gap: 9px;
}

.btn {
    border: none;
    cursor: pointer;
    padding: 10px 14px;
    border-radius: 9px;
    font-family: inherit;
    font-size: 12px;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    transition: .2s;
}

.btn-edit {
    color: #6653e7;
    background: #eeeaff;
}

.btn-edit:hover {
    background: #ded7ff;
}

.btn-delete {
    color: #e14e65;
    background: #fff0f2;
}

.btn-delete:hover {
    background: #ffdde3;
}

/* ALERT */
.alert {
    padding: 16px 20px;
    border-radius: 11px;
    margin-bottom: 24px;
    font-size: 13px;
}

.alert-success {
    background: #e7f8ef;
    color: #147c54;
}

.alert-error {
    background: #fff0f2;
    color: #b5324c;
}

/* MODAL */
.modal {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(17, 24, 39, .55);
    align-items: center;
    justify-content: center;
    z-index: 999;
    padding: 20px;
}

.modal.show {
    display: flex;
}

.modal-content {
    background: white;
    width: 100%;
    max-width: 460px;
    padding: 30px;
    border-radius: 20px;
    box-shadow: 0 20px 60px #0002;
}

.modal-heading {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}

.modal-heading h2 {
    font-size: 19px;
    font-weight: 800;
}

.close-btn {
    width: 33px;
    height: 33px;
    border: none;
    border-radius: 9px;
    background: #f3f4f8;
    color: #677088;
    cursor: pointer;
    font-size: 17px;
}

/* FORM */
.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    font-size: 12px;
    font-weight: 700;
    margin-bottom: 9px;
}

.form-group input {
    width: 100%;
    padding: 14px;
    border: 1px solid #e5e8f0;
    border-radius: 10px;
    background: #fafbfe;
    outline: none;
    font-size: 13px;
    font-family: inherit;
}

.form-group input:focus {
    border-color: var(--purple);
}

.modal-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 25px;
}

.btn-cancel {
    background: #f1f2f6;
    color: #555e70;
}

.btn-save {
    background: var(--purple);
    color: white;
}

.btn-save:hover {
    background: #5b49d8;
}

/* DELETE CONFIRMATION */
.delete-content {
    text-align: center;
}

.delete-icon {
    width: 65px;
    height: 65px;
    border-radius: 50%;
    background: #fff0f2;
    color: #e14e65;
    display: grid;
    place-items: center;
    margin: 0 auto 18px;
    font-size: 26px;
}

.delete-content h2 {
    font-size: 20px;
    margin-bottom: 12px;
}

.delete-content p {
    color: var(--muted);
    font-size: 13px;
    line-height: 1.8;
}

.btn-confirm-delete {
    background: #e14e65;
    color: white;
}

.btn-confirm-delete:hover {
    background: #c83d54;
}

/* EMPTY MESSAGE */
.empty-message {
    text-align: center;
    color: var(--muted);
    font-size: 13px;
    padding: 35px;
}

/* FOOTER */
.footer {
    padding: 30px;
    text-align: center;
    color: #a1a8b8;
    font-size: 11px;
}

/* RESPONSIVE */
@media(max-width: 700px) {
    .navbar {
        padding: 0 20px;
    }

    .logo {
        font-size: 15px;
    }

    .back-btn {
        font-size: 11px;
        padding: 10px;
    }

    .container {
        width: 94%;
        margin: 25px auto;
    }

    .page-heading h1 {
        font-size: 21px;
    }

    .table-card {
        padding: 18px;
    }

    .search-box,
    .search-box input {
        width: 100%;
    }

    table {
        min-width: 750px;
    }

    .modal-content {
        padding: 23px;
    }
}
</style>

</head>

<body>

<!-- NAVBAR -->
<nav class="navbar">

    <div class="logo">
        <div class="logo-icon">
            <i class="fa-solid fa-users"></i>
        </div>
        User Management
    </div>

    <a href="adminhome.php" class="back-btn">
        <i class="fa-solid fa-arrow-left"></i>
        Back to Dashboard
    </a>

</nav>

<!-- MAIN CONTENT -->
<div class="container">

    <div class="page-heading">
        <h1>User Management</h1>
        <p>
            View, edit and manage registered
            user accounts in your system.
        </p>
    </div>

    <!-- SUCCESS / ERROR MESSAGE -->
    <?php if ($flash): ?>
        <div class="alert <?= $flash['type'] === 'error' ? 'alert-error' : 'alert-success' ?>">
            <i class="fa-solid fa-circle-info"></i>
            <?= escape($flash['message']) ?>
        </div>
    <?php endif; ?>

    <!-- USER COUNT -->
    <div class="summary-card">

        <div class="summary-icon">
            <i class="fa-solid fa-users"></i>
        </div>

        <div>
            <p>Total Registered Users</p>
            <h2><?= $totalUsers ?></h2>
        </div>

    </div>

    <!-- USER RECORDS -->
    <div class="table-card">

        <div class="table-header">

            <div class="table-title">
                <h2>Registered User Records</h2>
                <p>
                    Manage user accounts and information
                </p>
            </div>

            <div class="search-box">
                <i class="fa-solid fa-magnifying-glass"></i>

                <input
                    type="text"
                    id="searchInput"
                    placeholder="Search username or email..."
                    oninput="searchUsers()"
                >
            </div>

        </div>

        <div class="table-responsive">

            <table id="userTable">

                <thead>
                    <tr>
                        <th>USER ID</th>
                        <th>USERNAME</th>
                        <th>EMAIL ADDRESS</th>
                        <th>ACTIONS</th>
                    </tr>
                </thead>

                <tbody>

                <?php if ($totalUsers > 0): ?>

                    <?php foreach ($users as $user): ?>

                    <tr class="user-row">

                        <td>
                            <span class="id-badge">
                                #<?= (int)$user['id'] ?>
                            </span>
                        </td>

                        <td>
                            <div class="user-cell">

                                <div class="user-avatar">
                                    <?= escape(strtoupper(
                                        substr($user['username'], 0, 1)
                                    )) ?>
                                </div>

                                <span class="username">
                                    <?= escape($user['username']) ?>
                                </span>

                            </div>
                        </td>

                        <td>
                            <span class="email">
                                <?= escape($user['email']) ?>
                            </span>
                        </td>

                        <td>
                            <div class="actions">

                                <button
                                    type="button"
                                    class="btn btn-edit"
                                    data-id="<?= (int)$user['id'] ?>"
                                    data-username="<?= escape($user['username']) ?>"
                                    data-email="<?= escape($user['email']) ?>"
                                    onclick="openEdit(this)"
                                >
                                    <i class="fa-solid fa-pen"></i>
                                    Edit
                                </button>

                                <button
                                    type="button"
                                    class="btn btn-delete"
                                    data-id="<?= (int)$user['id'] ?>"
                                    data-username="<?= escape($user['username']) ?>"
                                    onclick="openDelete(this)"
                                >
                                    <i class="fa-solid fa-trash"></i>
                                    Delete
                                </button>

                            </div>
                        </td>

                    </tr>

                    <?php endforeach; ?>

                <?php else: ?>

                    <tr>
                        <td colspan="4" class="empty-message">
                            No user records found.
                        </td>
                    </tr>

                <?php endif; ?>

                </tbody>

            </table>

            <div
                id="noSearchResults"
                class="empty-message"
                style="display:none"
            >
                No matching users found.
            </div>

        </div>

    </div>

</div>

<!-- EDIT USER MODAL -->
<div class="modal" id="editModal">

    <div class="modal-content">

        <div class="modal-heading">
            <h2>Edit User Information</h2>

            <button
                type="button"
                class="close-btn"
                onclick="closeModals()"
            >
                &times;
            </button>
        </div>

        <form method="POST" action="userview.php">

            <input
                type="hidden"
                name="csrf_token"
                value="<?= escape($_SESSION['user_management_csrf']) ?>"
            >

            <input
                type="hidden"
                name="action"
                value="edit"
            >

            <input
                type="hidden"
                name="id"
                id="editId"
            >

            <div class="form-group">
                <label>Username</label>

                <input
                    type="text"
                    name="username"
                    id="editUsername"
                    maxlength="100"
                    required
                >
            </div>

            <div class="form-group">
                <label>Email Address</label>

                <input
                    type="email"
                    name="email"
                    id="editEmail"
                    maxlength="254"
                    required
                >
            </div>

            <div class="modal-actions">

                <button
                    type="button"
                    class="btn btn-cancel"
                    onclick="closeModals()"
                >
                    Cancel
                </button>

                <button
                    type="submit"
                    class="btn btn-save"
                >
                    <i class="fa-solid fa-floppy-disk"></i>
                    Save Changes
                </button>

            </div>

        </form>

    </div>

</div>

<!-- DELETE CONFIRMATION MODAL -->
<div class="modal" id="deleteModal">

    <div class="modal-content delete-content">

        <div class="delete-icon">
            <i class="fa-solid fa-trash-can"></i>
        </div>

        <h2>Delete User?</h2>

        <p>
            Are you sure you want to delete
            <strong id="deleteUsername"></strong>?
            <br>
            This action cannot be undone.
        </p>

        <form method="POST" action="userview.php">

            <input
                type="hidden"
                name="csrf_token"
                value="<?= escape($_SESSION['user_management_csrf']) ?>"
            >

            <input
                type="hidden"
                name="action"
                value="delete"
            >

            <input
                type="hidden"
                name="id"
                id="deleteId"
            >

            <div class="modal-actions"
                 style="justify-content:center">

                <button
                    type="button"
                    class="btn btn-cancel"
                    onclick="closeModals()"
                >
                    Cancel
                </button>

                <button
                    type="submit"
                    class="btn btn-confirm-delete"
                >
                    <i class="fa-solid fa-trash"></i>
                    Yes, Delete
                </button>

            </div>

        </form>

    </div>

</div>

<div class="footer">
    &copy; <?= date('Y') ?>
    User Management System. All rights reserved.
</div>

<script>

// OPEN EDIT MODAL
function openEdit(button) {

    document.getElementById('editId').value =
        button.dataset.id;

    document.getElementById('editUsername').value =
        button.dataset.username;

    document.getElementById('editEmail').value =
        button.dataset.email;

    document.getElementById('editModal')
        .classList.add('show');
}

// OPEN DELETE MODAL
function openDelete(button) {

    document.getElementById('deleteId').value =
        button.dataset.id;

    document.getElementById('deleteUsername')
        .textContent = button.dataset.username;

    document.getElementById('deleteModal')
        .classList.add('show');
}

// CLOSE MODALS
function closeModals() {

    document.getElementById('editModal')
        .classList.remove('show');

    document.getElementById('deleteModal')
        .classList.remove('show');
}

// CLICK OUTSIDE MODAL
window.addEventListener('click', function(event) {

    if (event.target.classList.contains('modal')) {
        closeModals();
    }

});

// ESCAPE KEY
document.addEventListener('keydown', function(event) {

    if (event.key === 'Escape') {
        closeModals();
    }

});

// SEARCH USERS
function searchUsers() {

    const input = document
        .getElementById('searchInput')
        .value.toLowerCase().trim();

    const rows = document.querySelectorAll('.user-row');

    let visible = 0;

    rows.forEach(function(row) {

        const username = row
            .querySelector('.username')
            .textContent.toLowerCase();

        const email = row
            .querySelector('.email')
            .textContent.toLowerCase();

        const match =
            username.includes(input) ||
            email.includes(input);

        row.style.display = match ? '' : 'none';

        if (match) {
            visible++;
        }

    });

    document.getElementById('noSearchResults')
        .style.display =
            (visible === 0 && rows.length > 0)
                ? 'block'
                : 'none';
}

</script>

</body>
</html>
