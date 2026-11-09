
<?php
session_start();
include '../db.php';

$sql = "SELECT * FROM issues ORDER BY created_at DESC";
$result = $conn->query($sql);

$issues = [];
$categories = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $issues[] = $row;
        $categories[] = $row['issue_type'];
    }
}

$categories = array_unique($categories);

function escape($value) {
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="icon" href="../src/view.png">
<title>Reported Issues | Kampung Sentosa</title>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<style>
:root {
    --gold: #b88b58;
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
    display: flex;
    flex-direction: column;
    background: var(--cream);
    font-family: 'DM Sans', sans-serif;
    color: var(--text);
}

/* NAVIGATION */
.navbar {
    background: #292d30;
    padding: 17px 0;
    box-shadow: 0 2px 15px rgba(0,0,0,.08);
}

.navbar-brand {
    font-weight: 700;
    font-size: 21px;
    color: white !important;
}

.brand-accent {
    color: var(--gold-light);
}

.navbar-nav .nav-link {
    color: #dedbd5 !important;
    font-size: 13px;
    margin-left: 17px;
    transition: .25s;
}

.navbar-nav .nav-link:hover,
.navbar-nav .active .nav-link {
    color: var(--gold-light) !important;
}

.navbar-nav .active .nav-link {
    border-bottom: 1px solid var(--gold-light);
}

/* HERO SECTION */
.hero {
    background:
        radial-gradient(circle at 85% 15%, rgba(184,139,88,.11), transparent 32%),
        linear-gradient(135deg, #fffdf9, #f6f0e8);
    padding: 64px 15px 61px;
    text-align: center;
    border-bottom: 1px solid #ebdfd0;
}

.hero-script {
    font-family: 'Great Vibes', cursive;
    color: var(--gold);
    font-size: 57px;
    line-height: 1;
    margin-bottom: 4px;
}

.hero h1 {
    font-weight: 700;
    font-size: 39px;
    letter-spacing: -.9px;
    color: #292d30;
    margin-bottom: 13px;
}

.hero p {
    color: #77736e;
    font-size: 14px;
    max-width: 540px;
    margin: auto;
    line-height: 1.9;
}

.gold-divider {
    width: 65px;
    height: 2px;
    background: var(--gold);
    margin: 24px auto 0;
}

/* MAIN */
main {
    flex: 1;
    padding: 48px 0 85px;
}

.section-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
    margin-bottom: 24px;
}

.section-subtitle {
    color: var(--gold);
    font-size: 11px;
    letter-spacing: 2px;
    text-transform: uppercase;
    font-weight: 700;
    margin-bottom: 7px;
}

.section-title {
    font-size: 24px;
    font-weight: 700;
    margin: 0;
}

.btn-report {
    background: var(--gold);
    color: white;
    border: 1px solid var(--gold);
    padding: 12px 19px;
    font-size: 12px;
    border-radius: 7px;
    font-weight: 700;
    text-decoration: none;
    transition: .25s;
    white-space: nowrap;
}

.btn-report:hover {
    background: #9d7549;
    color: white;
    text-decoration: none;
}

/* SUMMARY CARDS */
.summary-card {
    background: white;
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 23px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    transition: .25s;
}

.summary-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 26px rgba(85,64,41,.07);
}

.summary-label {
    color: #88837b;
    font-size: 12px;
    margin-bottom: 6px;
}

.summary-number {
    font-size: 28px;
    color: var(--dark);
    font-weight: 700;
    margin: 0;
}

.summary-icon {
    background: #f9f1e7;
    color: var(--gold);
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

/* TABLE PANEL */
.table-panel {
    background: white;
    border-radius: 14px;
    border: 1px solid var(--border);
    box-shadow: 0 8px 35px rgba(85,64,41,.045);
    overflow: hidden;
}

.table-panel-header {
    padding: 25px 25px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
}

.table-panel-header h3 {
    font-size: 17px;
    font-weight: 700;
    margin: 0 0 6px;
}

.table-panel-header p {
    font-size: 12px;
    color: var(--muted);
    margin: 0;
}

.count-badge {
    background: #f8f0e5;
    color: #a77b49;
    border-radius: 20px;
    padding: 7px 13px;
    font-size: 11px;
    font-weight: 700;
    white-space: nowrap;
}

/* SEARCH */
.filter-bar {
    display: flex;
    gap: 12px;
    padding: 0 25px 25px;
}

.search-wrapper {
    position: relative;
    flex: 1;
}

.search-wrapper i {
    position: absolute;
    top: 50%;
    left: 15px;
    transform: translateY(-50%);
    color: #b5a897;
    font-size: 13px;
}

.search-wrapper input {
    width: 100%;
    padding: 12px 15px 12px 40px;
}

.search-wrapper input,
.filter-select {
    height: 44px;
    outline: none;
    border-radius: 7px;
    border: 1px solid #e9e1d7;
    background: #fffdfa;
    font-size: 12px;
    color: #514d49;
}

.search-wrapper input:focus,
.filter-select:focus {
    border-color: var(--gold);
    box-shadow: 0 0 0 3px rgba(184,139,88,.1);
}

.filter-select {
    min-width: 190px;
    padding: 0 13px;
}

/* TABLE */
.issues-table {
    width: 100%;
    margin: 0;
    font-size: 12px;
}

.issues-table thead {
    background: #f8f2ea;
}

.issues-table th {
    color: #8a6b49;
    padding: 19px 13px;
    font-size: 11px;
    font-weight: 700;
    border-top: 1px solid #eee3d6;
    border-bottom: 1px solid #eee3d6;
    white-space: nowrap;
}

.issues-table td {
    padding: 19px 13px;
    vertical-align: middle;
    line-height: 1.75;
    border-top: 1px solid #f1ece5;
    color: #55534f;
    overflow-wrap: anywhere;
}

.issues-table tbody tr:hover {
    background: #fdfaf6;
}

.id-badge {
    background: #f9f0e6;
    color: #a47b4c;
    border-radius: 5px;
    padding: 6px 9px;
    font-weight: 700;
}

.person-name {
    font-weight: 700;
    color: #323438;
}

.issue-type {
    color: #9b764c;
    background: #fbf2e7;
    border: 1px solid #f0e0ce;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    padding: 6px 10px;
    display: inline-block;
}

.address-cell {
    min-width: 150px;
    max-width: 200px;
}

.description-cell {
    min-width: 190px;
    max-width: 290px;
}

.contact-cell {
    white-space: nowrap;
}

.issue-image {
    width: 82px;
    height: 62px;
    object-fit: cover;
    border-radius: 7px;
    border: 1px solid #eee1d2;
    transition: .25s;
}

.issue-image:hover {
    transform: scale(1.06);
}

.no-photo {
    color: #aaa39a;
    font-size: 11px;
}

.date-main {
    font-weight: 700;
    white-space: nowrap;
}

.date-time {
    color: #a19b93;
    font-size: 11px;
    white-space: nowrap;
}

/* ACTIONS */
.action-buttons {
    display: flex;
    gap: 7px;
}

.action-btn {
    width: 35px;
    height: 35px;
    display: inline-flex;
    justify-content: center;
    align-items: center;
    border-radius: 7px;
    font-size: 12px;
    text-decoration: none;
    transition: .2s;
}

.edit-btn {
    background: #f7eddc;
    color: #ae8145;
}

.edit-btn:hover {
    background: #e9d2af;
    color: #795324;
}

.delete-btn {
    background: #fcebec;
    color: #c85b60;
}

.delete-btn:hover {
    background: #f2d0d2;
    color: #a93a42;
}

/* EMPTY STATE */
.empty-state {
    padding: 55px 20px;
    text-align: center;
    color: var(--muted);
}

.empty-state i {
    font-size: 36px;
    color: var(--gold-light);
    margin-bottom: 15px;
}

.empty-state h4 {
    color: var(--dark);
    font-size: 17px;
}

/* FOOTER */
footer {
    background: var(--dark);
    color: #d7d2ca;
    text-align: center;
    padding: 23px;
    font-size: 12px;
}

footer p {
    margin: 0;
}

footer span {
    color: var(--gold-light);
}

@media (max-width: 768px) {
    .hero {
        padding: 45px 20px;
    }

    .hero-script {
        font-size: 48px;
    }

    .hero h1 {
        font-size: 30px;
    }

    main {
        padding: 30px 0 55px;
    }

    .section-top {
        align-items: flex-start;
        flex-direction: column;
    }

    .filter-bar {
        flex-direction: column;
        padding: 0 17px 20px;
    }

    .filter-select {
        width: 100%;
    }

    .table-panel-header {
        padding: 20px 17px;
    }

    .issues-table {
        min-width: 1080px;
    }

    .navbar-nav .active .nav-link {
        border-bottom: none;
    }
}
</style>
</head>

<body>

<!-- NAVIGATION -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">

        <a class="navbar-brand" href="about1.php">
            Issues <span class="brand-accent">Tracker</span>
        </a>

        <button class="navbar-toggler" type="button"
            data-toggle="collapse"
            data-target="#navbarResponsive"
            aria-controls="navbarResponsive"
            aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto align-items-lg-center">

                <li class="nav-item">
                    <a href="about1.php" class="nav-link">About us</a>
                </li>

                <li class="nav-item">
                    <a href="issueuser.php" class="nav-link">Report issue</a>
                </li>

                <li class="nav-item active">
                    <a href="view_issues.php" class="nav-link">View issue</a>
                </li>

                <?php if (isset($_SESSION['username'])): ?>
                    <li class="nav-item">
                        <span class="nav-link">
                            Welcome, <?php echo escape($_SESSION['username']); ?>
                        </span>
                    </li>

                    <li class="nav-item">
                        <a href="../logout.php" class="nav-link"
                           style="color:#e77777 !important;">
                            Logout
                        </a>
                    </li>
                <?php endif; ?>

            </ul>
        </div>
    </div>
</nav>

<!-- HERO -->
<section class="hero">
    <div class="hero-script">Kampung Sentosa</div>
    <h1>Reported Issues</h1>
    <p>
        A better community begins with every voice.
        Explore and manage reported neighborhood issues
        to help build a cleaner, safer and more connected Kampung Sentosa.
    </p>
    <div class="gold-divider"></div>
</section>

<!-- MAIN -->
<main>
<div class="container-fluid" style="max-width:1380px;padding:0 25px;">

    <div class="section-top">
        <div>
            <div class="section-subtitle">Community Management</div>
            <h2 class="section-title">Community Issue Overview</h2>
        </div>

        <a href="issueuser.php" class="btn-report">
            <i class="fa-solid fa-plus mr-2"></i>
            Report New Issue
        </a>
    </div>

    <!-- SUMMARY CARDS -->
    <div class="row">

        <div class="col-md-4">
            <div class="summary-card">
                <div>
                    <div class="summary-label">Total Reported Issues</div>
                    <h2 class="summary-number">
                        <?php echo count($issues); ?>
                    </h2>
                </div>
                <div class="summary-icon">
                    <i class="fa-regular fa-clipboard"></i>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="summary-card">
                <div>
                    <div class="summary-label">Issue Categories</div>
                    <h2 class="summary-number">
                        <?php echo count($categories); ?>
                    </h2>
                </div>
                <div class="summary-icon">
                    <i class="fa-solid fa-layer-group"></i>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="summary-card">
                <div>
                    <div class="summary-label">Reports with Photos</div>
                    <h2 class="summary-number">
                        <?php
                        echo count(array_filter($issues, function($issue) {
                            return !empty($issue['photo']);
                        }));
                        ?>
                    </h2>
                </div>
                <div class="summary-icon">
                    <i class="fa-regular fa-image"></i>
                </div>
            </div>
        </div>

    </div>

    <!-- ISSUES TABLE -->
    <div class="table-panel">

        <div class="table-panel-header">
            <div>
                <h3>All Reported Issues</h3>
                <p>A complete record of submitted community concerns.</p>
            </div>

            <span class="count-badge" id="recordCount">
                <?php echo count($issues); ?> Records
            </span>
        </div>

        <!-- SEARCH AND FILTER -->
        <div class="filter-bar">
            <div class="search-wrapper">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="searchInput"
                       placeholder="Search issues, names or addresses..."
                       aria-label="Search issues">
            </div>

            <select id="categoryFilter" class="filter-select"
                    aria-label="Filter issue category">
                <option value="">All Issue Types</option>

                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo escape($category); ?>">
                        <?php echo escape($category); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="table-responsive">
            <table class="table issues-table" id="issuesTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Reported By</th>
                        <th>Address</th>
                        <th>Contact</th>
                        <th>Issue Type</th>
                        <th>Description</th>
                        <th>Photo</th>
                        <th>Submitted At</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                <?php foreach ($issues as $row): ?>
                    <tr class="issue-row"
                        data-category="<?php echo escape($row['issue_type']); ?>">

                        <td>
                            <span class="id-badge">
                                #<?php echo (int)$row['id']; ?>
                            </span>
                        </td>

                        <td>
                            <span class="person-name">
                                <?php echo escape($row['fname']); ?>
                            </span>
                        </td>

                        <td class="address-cell">
                            <?php echo escape($row['address']); ?>
                        </td>

                        <td class="contact-cell">
                            <?php echo escape($row['contact']); ?>
                        </td>

                        <td>
                            <span class="issue-type">
                                <?php echo escape($row['issue_type']); ?>
                            </span>
                        </td>

                        <td class="description-cell">
                            <?php echo nl2br(escape($row['description'])); ?>
                        </td>

                        <td>
                            <?php if (!empty($row['photo'])): ?>
                                <a href="../<?php echo escape($row['photo']); ?>"
                                   target="_blank" rel="noopener noreferrer">
                                    <img src="../<?php echo escape($row['photo']); ?>"
                                         class="issue-image"
                                         alt="Reported issue photo">
                                </a>
                            <?php else: ?>
                                <span class="no-photo">
                                    <i class="fa-regular fa-image mr-1"></i>
                                    No photo
                                </span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <?php $timestamp = strtotime($row['created_at']); ?>

                            <?php if ($timestamp !== false): ?>
                                <div class="date-main">
                                    <?php echo date("d M Y", $timestamp); ?>
                                </div>
                                <div class="date-time">
                                    <?php echo date("h:i A", $timestamp); ?>
                                </div>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>

                        <td>
                            <div class="action-buttons">

                                <a href="edit_issue.php?id=<?php echo (int)$row['id']; ?>"
                                   class="action-btn edit-btn"
                                   title="Edit Issue"
                                   aria-label="Edit issue <?php echo (int)$row['id']; ?>">
                                    <i class="fa-solid fa-pen"></i>
                                </a>

                                <a href="delete_issue.php?id=<?php echo (int)$row['id']; ?>"
                                   class="action-btn delete-btn"
                                   title="Delete Issue"
                                   aria-label="Delete issue <?php echo (int)$row['id']; ?>"
                                   onclick="return confirm('Are you sure you want to delete this issue?');">
                                    <i class="fa-solid fa-trash"></i>
                                </a>

                            </div>
                        </td>

                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="empty-state" id="emptyState"
             style="<?php echo count($issues) > 0 ? 'display:none;' : ''; ?>">
            <i class="fa-regular fa-folder-open"></i>
            <h4>No Issues Found</h4>
            <p>There are no matching community reports.</p>
        </div>

    </div>
</div>
</main>

<!-- FOOTER -->
<footer>
    <p>
        &copy; <?php echo date("Y"); ?>
        <span>Kampung Sentosa</span> Issues Tracker.
        All rights reserved.
    </p>
</footer>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const search = document.getElementById('searchInput');
    const filter = document.getElementById('categoryFilter');
    const rows = document.querySelectorAll('.issue-row');
    const counter = document.getElementById('recordCount');
    const empty = document.getElementById('emptyState');
    const table = document.getElementById('issuesTable');

    function updateResults() {
        let count = 0;

        const keyword = search.value.toLowerCase().trim();
        const category = filter.value.toLowerCase();

        rows.forEach(function(row) {
            const text = row.textContent.toLowerCase();
            const type = row.dataset.category.toLowerCase();

            const matchSearch = text.includes(keyword);
            const matchCategory = !category || type === category;

            const visible = matchSearch && matchCategory;

            row.style.display = visible ? '' : 'none';

            if (visible) count++;
        });

        counter.textContent = count + (count === 1 ? ' Record' : ' Records');
        empty.style.display = count === 0 ? 'block' : 'none';
        table.style.display = count === 0 ? 'none' : 'table';
    }

    search.addEventListener('input', updateResults);
    filter.addEventListener('change', updateResults);
});
</script>

</body>
</html>

<?php $conn->close(); ?>
