
<?php
session_start();

// Existing session check
if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit();
}

include '../db.php';

function e($value) {
    return htmlspecialchars(
        (string)($value ?? ''),
        ENT_QUOTES,
        'UTF-8'
    );
}

// Fetch reported issues
$query = "SELECT id, fname, address, contact,
                 issue_type, description, photo, created_at
          FROM issues
          ORDER BY created_at DESC, id DESC";

$result = $conn->query($query);

$issues = [];
$categories = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $issues[] = $row;

        if (!empty($row['issue_type'])) {
            $categories[$row['issue_type']] = true;
        }
    }
}

$totalReports = count($issues);
$categoryNames = array_keys($categories);
sort($categoryNames);

// Safely encode data for JavaScript
$issuesJson = json_encode(
    $issues,
    JSON_HEX_TAG |
    JSON_HEX_AMP |
    JSON_HEX_APOS |
    JSON_HEX_QUOT
);

// Validate local image path for display.
// Adjust allowed folders if your upload structure differs.
function safePhoto($path) {
    $path = trim((string)$path);

    if ($path === '') return '';

    if (preg_match(
        '~^(https?://|//|data:|javascript:|/|\\\\)~i',
        $path
    )) {
        return '';
    }

    $path = str_replace('\\', '/', $path);

    if (str_contains($path, '..')) {
        return '';
    }

    return '../' . $path;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Reported Issues Management</title>

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
    padding: 0 5%;
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
    color: var(--navy);
    font-size: 19px;
    font-weight: 800;
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
    font-weight: 700;
    font-size: 13px;
    display: flex;
    align-items: center;
    gap: 9px;
    transition: .2s;
}

.back-btn:hover {
    background: #ded8ff;
}

/* MAIN CONTENT */
.container {
    width: 94%;
    max-width: 1450px;
    margin: 40px auto;
}

.page-heading {
    margin-bottom: 28px;
}

.page-heading h1 {
    color: var(--navy);
    font-size: 26px;
    font-weight: 800;
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
    border: 1px solid var(--border);
    padding: 25px;
    border-radius: 18px;
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
}

/* TABLE CARD */
.table-card {
    background: white;
    border-radius: 18px;
    border: 1px solid var(--border);
    padding: 28px;
    box-shadow: 0 5px 20px #20263a06;
}

.table-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
    margin-bottom: 25px;
}

.table-title h2 {
    font-size: 17px;
    font-weight: 800;
    margin-bottom: 7px;
}

.table-title p {
    font-size: 12px;
    color: var(--muted);
}

/* FILTERS */
.filters {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

.search-box {
    position: relative;
}

.search-box i {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #a2a8b8;
}

.search-box input,
.filter-select {
    padding: 13px;
    border: 1px solid #e5e8f0;
    border-radius: 10px;
    background: #fafbfe;
    outline: none;
    font-family: inherit;
    font-size: 12px;
    color: var(--text);
}

.search-box input {
    padding-left: 42px;
    width: 250px;
}

.filter-select {
    min-width: 170px;
    max-width: 240px;
}

.search-box input:focus,
.filter-select:focus {
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
    padding: 17px 14px;
    font-size: 11px;
    color: #657087;
    font-weight: 700;
    white-space: nowrap;
}

td {
    padding: 17px 14px;
    font-size: 12px;
    border-bottom: 1px solid #f0f1f5;
    vertical-align: middle;
}

tbody tr:hover {
    background: #fbfaff;
}

tbody tr:last-child td {
    border-bottom: none;
}

.id-badge {
    background: var(--purple-light);
    color: var(--purple);
    padding: 7px 10px;
    border-radius: 8px;
    font-weight: 700;
    font-size: 11px;
}

.name-cell {
    font-weight: 700;
    min-width: 100px;
}

.address-cell {
    color: #737d91;
    max-width: 190px;
    line-height: 1.6;
}

.contact-cell {
    white-space: nowrap;
}

.issue-badge {
    background: #e9f3ff;
    color: #448de2;
    border-radius: 8px;
    padding: 8px 11px;
    display: inline-block;
    font-size: 11px;
    font-weight: 700;
    line-height: 1.5;
    max-width: 180px;
}

.description-cell {
    max-width: 190px;
    color: #737d91;
    line-height: 1.6;
}

.truncate {
    max-width: 180px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.photo-preview {
    width: 65px;
    height: 55px;
    object-fit: cover;
    border-radius: 9px;
    border: 1px solid var(--border);
    cursor: pointer;
    transition: .2s;
}

.photo-preview:hover {
    transform: scale(1.06);
}

.no-photo {
    color: #a1a8b8;
    font-size: 11px;
}

.date-cell {
    white-space: nowrap;
    line-height: 1.8;
    font-size: 11px;
    color: #737d91;
}

.date-cell strong {
    color: var(--text);
    font-size: 12px;
}

/* BUTTONS */
.btn {
    border: none;
    cursor: pointer;
    padding: 10px 14px;
    border-radius: 9px;
    font-family: inherit;
    font-weight: 700;
    font-size: 11px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    transition: .2s;
    white-space: nowrap;
}

.btn-view {
    color: var(--purple);
    background: var(--purple-light);
}

.btn-view:hover {
    background: #ded7ff;
}

.btn-close {
    color: #555e70;
    background: #f1f2f6;
}

.btn-close:hover {
    background: #e4e6ed;
}

/* MODAL */
.modal {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(17,24,39,.6);
    align-items: center;
    justify-content: center;
    z-index: 999;
    padding: 20px;
}

.modal.show {
    display: flex;
}

.modal-content {
    width: 100%;
    max-width: 650px;
    max-height: 90vh;
    overflow-y: auto;
    background: white;
    border-radius: 20px;
    padding: 30px;
    box-shadow: 0 20px 60px #0002;
}

.modal-heading {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
    margin-bottom: 25px;
}

.modal-heading h2 {
    font-size: 19px;
    font-weight: 800;
}

.close-icon {
    width: 34px;
    height: 34px;
    border: none;
    border-radius: 9px;
    background: #f3f4f8;
    color: #677088;
    font-size: 17px;
    cursor: pointer;
    flex-shrink: 0;
}

.detail-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 18px;
}

.detail-item {
    min-width: 0;
}

.detail-item.full {
    grid-column: 1 / -1;
}

.detail-item label {
    display: block;
    font-size: 11px;
    font-weight: 700;
    color: var(--muted);
    margin-bottom: 9px;
}

.detail-value {
    background: #f8f9fd;
    border: 1px solid var(--border);
    padding: 13px;
    border-radius: 10px;
    font-size: 13px;
    line-height: 1.7;
    overflow-wrap: anywhere;
    white-space: pre-wrap;
}

.detail-image {
    max-width: 100%;
    max-height: 300px;
    object-fit: contain;
    border-radius: 12px;
    cursor: pointer;
}

.modal-actions {
    display: flex;
    justify-content: flex-end;
    margin-top: 25px;
}

/* PHOTO VIEWER */
.image-modal-content {
    max-width: 850px;
    text-align: center;
}

.full-image {
    max-width: 100%;
    max-height: 70vh;
    object-fit: contain;
    border-radius: 12px;
}

/* EMPTY STATE */
.empty-message {
    padding: 35px;
    text-align: center;
    color: var(--muted);
    font-size: 13px;
}

.footer {
    text-align: center;
    padding: 30px;
    color: #a1a8b8;
    font-size: 11px;
}

@media(max-width: 900px) {
    .table-header {
        flex-direction: column;
        align-items: stretch;
    }

    .filters {
        width: 100%;
    }

    .search-box {
        flex: 1;
    }

    .search-box input {
        width: 100%;
    }

    table {
        min-width: 1080px;
    }
}

@media(max-width: 600px) {
    .navbar {
        padding: 0 15px;
    }

    .logo {
        font-size: 15px;
    }

    .back-btn {
        padding: 10px;
        font-size: 11px;
    }

    .container {
        margin: 25px auto;
    }

    .page-heading h1 {
        font-size: 21px;
    }

    .table-card {
        padding: 18px;
    }

    .filters {
        flex-direction: column;
    }

    .filter-select {
        max-width: none;
        width: 100%;
    }

    .detail-grid {
        grid-template-columns: 1fr;
    }

    .detail-item.full {
        grid-column: auto;
    }

    .modal-content {
        padding: 20px;
    }
}
</style>

</head>

<body>

<!-- NAVBAR -->
<nav class="navbar">

    <div class="logo">
        <div class="logo-icon">
            <i class="fa-solid fa-file-circle-exclamation"></i>
        </div>
        Reports Management
    </div>

    <a href="adminhome.php" class="back-btn">
        <i class="fa-solid fa-arrow-left"></i>
        Back to Dashboard
    </a>

</nav>

<main class="container">

    <!-- HEADING -->
    <div class="page-heading">
        <h1>Reported Issues</h1>
        <p>
            View and monitor community complaints
            submitted through the system.
        </p>
    </div>

    <!-- SUMMARY -->
    <div class="summary-card">
        <div class="summary-icon">
            <i class="fa-solid fa-clipboard-list"></i>
        </div>

        <div>
            <p>Total Reported Issues</p>
            <h2><?= $totalReports ?></h2>
        </div>
    </div>

    <!-- TABLE CARD -->
    <div class="table-card">

        <div class="table-header">

            <div class="table-title">
                <h2>Issue Records</h2>
                <p>
                    Browse and review all submitted complaints.
                </p>
            </div>

            <div class="filters">

                <div class="search-box">
                    <i class="fa-solid fa-magnifying-glass"></i>

                    <input
                        type="search"
                        id="searchInput"
                        placeholder="Search reports..."
                        oninput="filterReports()"
                    >
                </div>

                <select
                    class="filter-select"
                    id="typeFilter"
                    onchange="filterReports()"
                >
                    <option value="">All Issue Types</option>

                    <?php foreach ($categoryNames as $category): ?>
                        <option value="<?= e($category) ?>">
                            <?= e($category) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

            </div>

        </div>

        <div class="table-responsive">

            <table id="reportsTable">

                <thead>
                    <tr>
                        <th>ISSUE ID</th>
                        <th>NAME</th>
                        <th>ADDRESS</th>
                        <th>CONTACT</th>
                        <th>ISSUE TYPE</th>
                        <th>DESCRIPTION</th>
                        <th>PHOTO</th>
                        <th>REPORTED DATE</th>
                        <th>ACTION</th>
                    </tr>
                </thead>

                <tbody>

                <?php if ($totalReports > 0): ?>

                <?php foreach ($issues as $index => $issue): ?>

                <?php
                    $photo = safePhoto($issue['photo']);
                    $timestamp = strtotime($issue['created_at']);
                ?>

                <tr class="report-row"
                    data-type="<?= e($issue['issue_type']) ?>">

                    <td>
                        <span class="id-badge">
                            #<?= (int)$issue['id'] ?>
                        </span>
                    </td>

                    <td class="name-cell">
                        <?= e($issue['fname']) ?>
                    </td>

                    <td class="address-cell">
                        <div class="truncate"
                             title="<?= e($issue['address']) ?>">
                            <?= e($issue['address']) ?>
                        </div>
                    </td>

                    <td class="contact-cell">
                        <?= e($issue['contact']) ?>
                    </td>

                    <td>
                        <span class="issue-badge">
                            <?= e($issue['issue_type']) ?>
                        </span>
                    </td>

                    <td class="description-cell">
                        <div class="truncate"
                             title="<?= e($issue['description']) ?>">
                            <?= e($issue['description']) ?>
                        </div>
                    </td>

                    <td>
                        <?php if ($photo !== ''): ?>

                            <img
                                src="<?= e($photo) ?>"
                                alt="Issue Photo"
                                class="photo-preview"
                                loading="lazy"
                                onclick="openImage(this.src)"
                            >

                        <?php else: ?>

                            <span class="no-photo">
                                <i class="fa-regular fa-image"></i>
                                No photo
                            </span>

                        <?php endif; ?>
                    </td>

                    <td class="date-cell">
                        <?php if ($timestamp): ?>
                            <strong>
                                <?= date('d M Y', $timestamp) ?>
                            </strong>
                            <div>
                                <?= date('h:i A', $timestamp) ?>
                            </div>
                        <?php else: ?>
                            <?= e($issue['created_at']) ?>
                        <?php endif; ?>
                    </td>

                    <td>
                        <button
                            type="button"
                            class="btn btn-view"
                            onclick="openDetails(<?= $index ?>)"
                        >
                            <i class="fa-solid fa-eye"></i>
                            View
                        </button>
                    </td>

                </tr>

                <?php endforeach; ?>

                <?php else: ?>

                <tr>
                    <td colspan="9" class="empty-message">
                        No issues have been reported yet.
                    </td>
                </tr>

                <?php endif; ?>

                </tbody>

            </table>

            <div
                id="noResults"
                class="empty-message"
                style="display:none"
            >
                No matching reports found.
            </div>

        </div>

    </div>

</main>

<!-- DETAILS MODAL -->
<div class="modal" id="detailsModal" role="dialog"
     aria-modal="true" aria-labelledby="detailsHeading">

    <div class="modal-content">

        <div class="modal-heading">
            <h2 id="detailsHeading">Issue Details</h2>
            <button
                class="close-icon"
                type="button"
                aria-label="Close"
                onclick="closeModals()"
            >&times;</button>
        </div>

        <div class="detail-grid">

            <div class="detail-item">
                <label>Issue ID</label>
                <div class="detail-value" id="detailId"></div>
            </div>

            <div class="detail-item">
                <label>Issue Type</label>
                <div class="detail-value" id="detailType"></div>
            </div>

            <div class="detail-item">
                <label>Reported By</label>
                <div class="detail-value" id="detailName"></div>
            </div>

            <div class="detail-item">
                <label>Contact Number</label>
                <div class="detail-value" id="detailContact"></div>
            </div>

            <div class="detail-item full">
                <label>Address</label>
                <div class="detail-value" id="detailAddress"></div>
            </div>

            <div class="detail-item full">
                <label>Description</label>
                <div class="detail-value" id="detailDescription"></div>
            </div>

            <div class="detail-item full">
                <label>Date Reported</label>
                <div class="detail-value" id="detailDate"></div>
            </div>

            <div class="detail-item full">
                <label>Attached Photo</label>

                <div id="detailPhotoContainer"
                     class="detail-value">
                </div>
            </div>

        </div>

        <div class="modal-actions">
            <button
                type="button"
                class="btn btn-close"
                onclick="closeModals()"
            >
                Close
            </button>
        </div>

    </div>
</div>

<!-- PHOTO MODAL -->
<div class="modal" id="imageModal" role="dialog"
     aria-modal="true" aria-label="Issue photo">

    <div class="modal-content image-modal-content">

        <div class="modal-heading">
            <h2>Issue Photo</h2>

            <button
                type="button"
                class="close-icon"
                aria-label="Close"
                onclick="closeImage()"
            >&times;</button>
        </div>

        <img
            id="fullImage"
            class="full-image"
            alt="Full issue photo"
        >

    </div>
</div>

<div class="footer">
    &copy; <?= date('Y') ?>
    Community Issues Management System.
    All rights reserved.
</div>

<script>
const reports = <?= $issuesJson ?: '[]' ?>;

// Text content helper
function setText(elementId, value) {
    document.getElementById(elementId).textContent =
        value == null || value === '' ? 'N/A' : String(value);
}

// MATCH PHP IMAGE PATH VALIDATION
function safeImagePath(value) {
    if (typeof value !== 'string') return '';

    const path = value.trim().replace(/\\/g, '/');

    if (
        !path ||
        path.includes('..') ||
        /^(https?:|\/\/|data:|javascript:|\/)/i.test(path)
    ) {
        return '';
    }

    return '../' + path;
}

// OPEN DETAILS
function openDetails(index) {
    const report = reports[index];

    if (!report) return;

    setText('detailId', '#' + report.id);
    setText('detailType', report.issue_type);
    setText('detailName', report.fname);
    setText('detailContact', report.contact);
    setText('detailAddress', report.address);
    setText('detailDescription', report.description);
    setText('detailDate', report.created_at);

    const container =
        document.getElementById('detailPhotoContainer');

    container.replaceChildren();

    const photo = safeImagePath(report.photo);

    if (photo) {
        const img = document.createElement('img');
        img.src = photo;
        img.alt = 'Issue Photo';
        img.className = 'detail-image';
        img.onclick = () => openImage(img.src);
        container.appendChild(img);
    } else {
        container.textContent = 'No photo attached.';
    }

    document.getElementById('detailsModal')
        .classList.add('show');
}

// OPEN PHOTO VIEWER
function openImage(src) {
    document.getElementById('fullImage').src = src;

    document.getElementById('imageModal')
        .classList.add('show');
}

// CLOSE IMAGE
function closeImage() {
    document.getElementById('imageModal')
        .classList.remove('show');

    document.getElementById('fullImage')
        .removeAttribute('src');
}

// CLOSE ALL MODALS
function closeModals() {
    document.getElementById('detailsModal')
        .classList.remove('show');

    closeImage();
}

// CLOSE BY CLICKING OUTSIDE
window.addEventListener('click', function(event) {
    if (event.target.classList.contains('modal')) {
        if (event.target.id === 'imageModal') {
            closeImage();
        } else {
            closeModals();
        }
    }
});

// ESCAPE KEY
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeModals();
    }
});

// SEARCH AND FILTER
function filterReports() {
    const search = document.getElementById('searchInput')
        .value.toLowerCase().trim();

    const selectedType =
        document.getElementById('typeFilter').value;

    const rows =
        document.querySelectorAll('.report-row');

    let visible = 0;

    rows.forEach(function(row) {
        const searchable = row.textContent.toLowerCase();

        const type = row.dataset.type;

        const matchesSearch =
            searchable.includes(search);

        const matchesType =
            selectedType === '' || type === selectedType;

        const match = matchesSearch && matchesType;

        row.style.display = match ? '' : 'none';

        if (match) visible++;
    });

    document.getElementById('noResults')
        .style.display =
            (visible === 0 && rows.length > 0)
                ? 'block'
                : 'none';
}
</script>

</body>
</html>
