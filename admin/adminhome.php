
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard</title>

<link rel="icon" href="../src/dadmin.png">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
:root {
    --navy: #17213b;
    --purple: #7161ef;
    --purple-light: #f0edff;
    --background: #f6f7fc;
    --text: #20263a;
    --muted: #8790a4;
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

/* SIDEBAR */
.sidebar {
    position: fixed;
    left: 0;
    top: 0;
    width: 255px;
    height: 100vh;
    background: var(--navy);
    padding: 30px 17px;
    display: flex;
    flex-direction: column;
    z-index: 100;
}

.brand {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 5px 12px 35px;
    color: white;
}

.brand-icon {
    width: 42px;
    height: 42px;
    border-radius: 13px;
    background: var(--purple);
    display: grid;
    place-items: center;
    font-size: 20px;
}

.brand h2 {
    font-size: 20px;
    font-weight: 800;
}

.brand span {
    display: block;
    font-size: 11px;
    color: #aab4cd;
    font-weight: 400;
    margin-top: 4px;
}

.menu-label {
    color: #77839f;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 2px;
    margin: 15px 15px;
}

.sidebar a {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px;
    margin-bottom: 7px;
    border-radius: 11px;
    color: #aeb8cf;
    text-decoration: none;
    font-size: 13px;
    font-weight: 500;
    transition: .2s;
}

.sidebar a i {
    width: 20px;
    text-align: center;
    font-size: 16px;
}

.sidebar a:hover {
    background: #273451;
    color: white;
}

.sidebar a.active {
    background: var(--purple);
    color: white;
    box-shadow: 0 5px 15px #7161ef35;
}

.sidebar-bottom {
    margin-top: auto;
    border-top: 1px solid #34405a;
    padding-top: 15px;
}

.sidebar-bottom a {
    color: #ffb9c3;
}

/* MAIN */
.main-content {
    margin-left: 255px;
    min-height: 100vh;
}

.header {
    background: white;
    height: 88px;
    padding: 0 45px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid var(--border);
}

.header h2 {
    font-size: 20px;
    font-weight: 800;
}

.header p {
    font-size: 12px;
    color: var(--muted);
    margin-top: 6px;
}

.admin-profile {
    display: flex;
    align-items: center;
    gap: 12px;
}

.admin-avatar {
    width: 42px;
    height: 42px;
    border-radius: 13px;
    background: var(--purple-light);
    color: var(--purple);
    display: grid;
    place-items: center;
    font-size: 19px;
}

.admin-info strong {
    font-size: 13px;
}

.admin-info p {
    font-size: 11px;
}

/* CONTENT */
.content {
    padding: 38px 45px;
    max-width: 1450px;
    margin: auto;
}

.welcome-banner {
    background: linear-gradient(110deg, #6554d9, #9886f5);
    border-radius: 20px;
    padding: 35px;
    color: white;
    position: relative;
    overflow: hidden;
    margin-bottom: 32px;
}

.welcome-banner::after {
    content: "";
    position: absolute;
    width: 250px;
    height: 250px;
    border-radius: 50%;
    background: #ffffff12;
    right: -35px;
    top: -100px;
}

.welcome-banner h1 {
    font-size: 25px;
    margin-bottom: 12px;
}

.welcome-banner p {
    font-size: 13px;
    line-height: 1.8;
    color: #f0ebff;
    max-width: 610px;
}

/* SECTION */
.section-title {
    font-size: 17px;
    font-weight: 800;
    margin-bottom: 20px;
}

.section-subtitle {
    font-size: 12px;
    color: var(--muted);
    margin-top: -12px;
    margin-bottom: 22px;
}

/* QUICK ACCESS */
.quick-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 20px;
    margin-bottom: 35px;
}

.quick-card {
    background: white;
    border: 1px solid var(--border);
    border-radius: 17px;
    padding: 25px;
    text-decoration: none;
    color: var(--text);
    transition: .25s;
    box-shadow: 0 4px 15px #20263a06;
}

.quick-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 30px #20263a12;
    border-color: #d9d2ff;
}

.quick-icon {
    width: 48px;
    height: 48px;
    border-radius: 13px;
    display: grid;
    place-items: center;
    font-size: 20px;
    margin-bottom: 20px;
}

.icon-purple {
    background: #eeeaff;
    color: #7161ef;
}

.icon-blue {
    background: #e9f3ff;
    color: #448de2;
}

.icon-orange {
    background: #fff2e5;
    color: #ef9b43;
}

.quick-card h3 {
    font-size: 15px;
    margin-bottom: 8px;
}

.quick-card p {
    font-size: 12px;
    color: var(--muted);
    line-height: 1.7;
}

.quick-link {
    display: flex;
    align-items: center;
    gap: 8px;
    color: var(--purple);
    font-size: 12px;
    font-weight: 700;
    margin-top: 20px;
}

/* CLOCK PANEL */
.clock-card {
    background: white;
    border: 1px solid var(--border);
    border-radius: 20px;
    padding: 30px;
    box-shadow: 0 4px 15px #20263a06;
}

.clock-layout {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 30px;
}

.clock-info h3 {
    font-size: 16px;
    margin-bottom: 10px;
}

.clock-info p {
    color: var(--muted);
    font-size: 12px;
}

.digital-clock {
    font-size: clamp(32px, 4vw, 48px);
    font-weight: 800;
    color: var(--navy);
    letter-spacing: 1px;
    font-variant-numeric: tabular-nums;
    margin: 22px 0 10px;
}

#current-date {
    color: var(--purple);
    font-size: 13px;
    font-weight: 600;
}

/* ANALOG CLOCK */
.analog-clock {
    position: relative;
    width: 150px;
    height: 150px;
    flex-shrink: 0;
    border-radius: 50%;
    background: #fafaff;
    border: 6px solid #e7e2ff;
    box-shadow: 0 0 0 5px #f8f6ff;
}

.clock-center {
    position: absolute;
    width: 11px;
    height: 11px;
    background: var(--purple);
    border-radius: 50%;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
    z-index: 5;
}

.hand {
    position: absolute;
    left: 50%;
    top: 50%;
    border-radius: 5px;
    transform-origin: 50% 100%;
}

.hour {
    width: 6px;
    height: 37px;
    background: var(--navy);
    margin-left: -3px;
    margin-top: -37px;
}

.minute {
    width: 4px;
    height: 51px;
    background: var(--purple);
    margin-left: -2px;
    margin-top: -51px;
}

.second {
    width: 2px;
    height: 58px;
    background: #f0a04b;
    margin-left: -1px;
    margin-top: -58px;
}

/* FOOTER */
.footer {
    text-align: center;
    font-size: 11px;
    color: #a1a8b8;
    padding: 25px;
}

/* RESPONSIVE */
@media (max-width: 950px) {
    .quick-grid {
        grid-template-columns: 1fr;
    }

    .content {
        padding: 25px;
    }

    .header {
        padding: 0 25px;
    }
}

@media (max-width: 650px) {
    .sidebar {
        width: 70px;
        padding: 20px 9px;
    }

    .brand {
        justify-content: center;
        padding: 5px 0 30px;
    }

    .brand h2,
    .menu-label,
    .sidebar a span {
        display: none;
    }

    .sidebar a {
        justify-content: center;
        padding: 15px 5px;
    }

    .sidebar a i {
        font-size: 18px;
    }

    .main-content {
        margin-left: 70px;
    }

    .header {
        height: 75px;
        padding: 0 18px;
    }

    .header h2 {
        font-size: 16px;
    }

    .admin-info {
        display: none;
    }

    .content {
        padding: 20px 15px;
    }

    .welcome-banner {
        padding: 25px 20px;
    }

    .welcome-banner h1 {
        font-size: 20px;
    }

    .clock-layout {
        flex-direction: column;
        align-items: flex-start;
    }

    .analog-clock {
        align-self: center;
    }
}
</style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">

    <div class="brand">
        <div class="brand-icon">
            <i class="fa-solid fa-shield-halved"></i>
        </div>
        <h2>Admin Panel
            <span>Management System</span>
        </h2>
    </div>

    <div class="menu-label">MAIN MENU</div>

    <a href="#" class="active">
        <i class="fa-solid fa-house"></i>
        <span>Dashboard</span>
    </a>

    <a href="adminview.php">
        <i class="fa-solid fa-user-shield"></i>
        <span>Admin Data</span>
    </a>

    <a href="userview.php">
        <i class="fa-solid fa-users"></i>
        <span>User Data</span>
    </a>

    <a href="report.php">
        <i class="fa-solid fa-chart-column"></i>
        <span>Reports Data</span>
    </a>

    <div class="sidebar-bottom">
        <a href="loginadmin.php">
            <i class="fa-solid fa-right-from-bracket"></i>
            <span>Logout</span>
        </a>
    </div>

</div>

<!-- MAIN CONTENT -->
<div class="main-content">

    <!-- HEADER -->
    <header class="header">

        <div>
            <h2>Dashboard Overview</h2>
            <p>Manage and monitor your system</p>
        </div>

        <div class="admin-profile">
            <div class="admin-info">
                <strong>Administrator</strong>
                <p>System Admin</p>
            </div>

            <div class="admin-avatar">
                <i class="fa-solid fa-user-tie"></i>
            </div>
        </div>

    </header>

    <main class="content">

        <!-- WELCOME -->
        <div class="welcome-banner">
            <h1>Welcome Back, Admin! 👋</h1>
            <p>
                Here's your administration dashboard.
                Manage administrator accounts, monitor
                registered users, and access system reports
                all in one place.
            </p>
        </div>

        <!-- QUICK ACCESS -->
        <h2 class="section-title">Quick Access</h2>
        <p class="section-subtitle">
            Easily navigate to your management sections.
        </p>

        <div class="quick-grid">

            <a href="adminview.php" class="quick-card">
                <div class="quick-icon icon-purple">
                    <i class="fa-solid fa-user-shield"></i>
                </div>

                <h3>Admin Management</h3>
                <p>
                    View and manage administrator
                    accounts and information.
                </p>

                <div class="quick-link">
                    Manage Admins
                    <i class="fa-solid fa-arrow-right"></i>
                </div>
            </a>

            <a href="userview.php" class="quick-card">
                <div class="quick-icon icon-blue">
                    <i class="fa-solid fa-users"></i>
                </div>

                <h3>User Management</h3>
                <p>
                    Access registered user records
                    and manage user information.
                </p>

                <div class="quick-link">
                    Manage Users
                    <i class="fa-solid fa-arrow-right"></i>
                </div>
            </a>

            <a href="report.php" class="quick-card">
                <div class="quick-icon icon-orange">
                    <i class="fa-solid fa-file-lines"></i>
                </div>

                <h3>Reports Management</h3>
                <p>
                    View system reports and
                    access reporting information.
                </p>

                <div class="quick-link">
                    View Reports
                    <i class="fa-solid fa-arrow-right"></i>
                </div>
            </a>

        </div>

        <!-- DATE & TIME -->
        <h2 class="section-title">Date & Time</h2>

        <div class="clock-card">
            <div class="clock-layout">

                <div class="clock-info">
                    <h3>
                        <i class="fa-regular fa-clock"
                           style="color:#7161ef;margin-right:8px">
                        </i>
                        Current Time
                    </h3>

                    <p>Your current local date and time</p>

                    <div class="digital-clock" id="digital-clock">
                        00:00:00
                    </div>

                    <div id="current-date">
                        Loading date...
                    </div>
                </div>

                <div class="analog-clock">
                    <div class="hand hour" id="hour-hand"></div>
                    <div class="hand minute" id="minute-hand"></div>
                    <div class="hand second" id="second-hand"></div>
                    <div class="clock-center"></div>
                </div>

            </div>
        </div>

    </main>

    <div class="footer">
        &copy; <span id="footer-year"></span>
        Admin Management System. All rights reserved.
    </div>

</div>

<script>
function updateClock() {
    const now = new Date();

    const hours = now.getHours();
    const minutes = now.getMinutes();
    const seconds = now.getSeconds();

    // Digital Clock
    const time =
        String(hours).padStart(2, '0') + ':' +
        String(minutes).padStart(2, '0') + ':' +
        String(seconds).padStart(2, '0');

    document.getElementById('digital-clock').textContent = time;

    // Date
    document.getElementById('current-date').textContent =
        now.toLocaleDateString('en-US', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });

    // Analog Clock
    const hourAngle = (hours % 12) * 30 + minutes * 0.5;
    const minuteAngle = minutes * 6 + seconds * 0.1;
    const secondAngle = seconds * 6;

    document.getElementById('hour-hand').style.transform =
        `rotate(${hourAngle}deg)`;

    document.getElementById('minute-hand').style.transform =
        `rotate(${minuteAngle}deg)`;

    document.getElementById('second-hand').style.transform =
        `rotate(${secondAngle}deg)`;
}

document.getElementById('footer-year').textContent =
    new Date().getFullYear();

updateClock();
setInterval(updateClock, 1000);
</script>

</body>
</html>
