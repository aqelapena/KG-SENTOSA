<?php 

session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link
        href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Poppins:wght@100;200;300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/animate.css">
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">

    <link rel="stylesheet" href="css/aos.css">

    <link rel="stylesheet" href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

    <link rel="stylesheet" href="css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="css/jquery.timepicker.css">


    <link rel="stylesheet" href="css/flaticon.css">
    <link rel="stylesheet" href="css/icomoon.css">
    <link rel="stylesheet" href="style.css">
	<link rel="icon" href="src/icon.png" />
    <title>User Home</title>
</head>


<body>
 
<nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
    <div class="container">
        <a class="navbar-brand">Issues Tracker</a>

        <div class="collapse navbar-collapse" id="ftco-nav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active"><a href="about1.php" class="nav-link">About us</a></li>
                <li class="nav-item"><a href="issueuser.php" class="nav-link">Report issue</a></li>
                <li class="nav-item"><a href="view_issues.php" class="nav-link">View issue</a></li>
                <li class="nav-item"><a href="edit_profile.php" class="nav-link">Edit Profile</a></li>
                <?php if (isset($_SESSION['username'])): ?>
                    <li class="nav-item">
                        <span class="nav-link text-white">Welcome, <?php echo $_SESSION['username']; ?></span>
                    </li>
                    <li class="nav-item">
                    <a href="logout.php" class="nav-link active" style="color: #d51b0f;">Logout</a>

                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>


    <section class="home-slider owl-carousel js-fullheight">
        <div class="slider-item js-fullheight" style="background-image: url(src/kampung.jpeg);">
            <div class="overlay"></div>
            <div class="container">
                <div class="row slider-text js-fullheight justify-content-center align-items-center"
                    data-scrollax-parent="true">

                    <div class="col-md-12 col-sm-12 text-center ftco-animate">
                        <span class="subheading">Kampung Sentosa</span>
                        <h1 class="mb-4">"Where Everyone Feels at Home"</h1>
                    </div>

                </div>
            </div>
        </div>

    </section>
	
 <section class="ftco-wrap-about" id="about">
        <div class="intro">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="intro_content">
                            <div class="intro_title">
                                <h2>Issues Tracker</h2>
                            </div>
                            <div class="intro_text">
                                <p>The Kampung Sentosa Issues Tracker is a community-driven platform that enables residents to easily report neighborhood 
                                    problems, such as potholes, garbage, broken facilities, and other issues. For the local authorities, it provides a robust 
                                    system to efficiently manage, prioritize, and resolve these issues, ensuring transparency and accountability. This collaborative 
                                    tool fosters a stronger connection between residents and local teams, ultimately working together to maintain a clean, safe, and 
                                    well-functioning community.</p>
                            </div>
								
                        </div>
                        <div class="row">
                            <div class="col-xl-4 col-md-6 intro_col">
                                <div class="intro_image"><img src="src/gambar.jpg" alt="intro"></div>
                            </div>
                     
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

	<br><br>


    <section class="ftco-section" id="chef">
        <div class="container">
            <div class="row justify-content-center mb-5 pb-2">
                <div class="col-md-12 text-center heading-section ftco-animate">
                    <span class="subheading">Our</span>
                    <h2 class="mb-4">Vision, Mission and Objectives</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-lg-3 ftco-animate">
                    <div class="staff">
                        <div class="text pt-4">
                            <h3>Mission</h3>
                            <span class="position mb-2">To provide a streamlined, efficient, and collaborative platform for identifying, 
                                managing, and resolving issues, ensuring transparency, accountability, and continuous improvement in workflows</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 ftco-animate">
                    <div class="staff">
                        <div class="text pt-4">
                            <h3>Vision</h3>
                            <span class="position mb-2">To be the most trusted and user-friendly issue-tracking solution, empowering teams worldwide to achieve operational 
                            excellence through effective problem-solving and seamless collaboration.</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 ftco-animate">
                    <div class="staff">
                        <div class="text pt-4">
                            <h3>Objective</h3>
                            <span class="position mb-2"><b>Streamline Issue Management</b></span>
                            <span class="position mb-2">Simplify the process of logging, categorizing, 
                                and prioritizing issues to enhance team productivity and focus on critical tasks.</span>

                                <span class="position mb-2"><b>Increase Efficiency</b></span>
                            <span class="position mb-2">Reduce time to resolution by implementing automated workflows, 
                                notifications, and prioritization mechanisms.</span>
                                
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 ftco-animate">
                    <div class="staff">
                        <div class="text pt-4">
                        <span class="position mb-2"><b>Facilitate Continuous Improvement</b></span>
                            <span class="position mb-2">Enable data-driven decision-making by tracking trends, 
                                analyzing performance metrics, and generating actionable insights from historical data.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
 
    <section class="ftco-footer ftco-bg-dark ftco-section">
        <div class="container">
        <?php
// Footer for Kampung Sentosa Issues Tracker
?>

<footer style="background-color: #f8f9fa; padding: 20px; text-align: center; border-top: 1px solid #e0e0e0;">
    <p style="margin: 0; font-size: 14px; color: #6c757d;">
        &copy; <?php echo date('Y'); ?> Kampung Sentosa Issues Tracker. All rights reserved.
    </p>
    <p style="margin: 5px 0 0; font-size: 12px; color: #6c757d;">
        Designed for seamless issue management and collaboration.
    </p>
</footer>
        </div>
    </section>

	
	    <!-- scripts -->
    <script src="js/jquery.min.js"></script>
    <script src="js/jquery-migrate-3.0.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.waypoints.min.js"></script>
    <script src="js/jquery.stellar.min.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/jquery.magnific-popup.min.js"></script>
    <script src="js/aos.js"></script>
    <script src="js/jquery.animateNumber.min.js"></script>
    <script src="js/bootstrap-datepicker.js"></script>
    <script src="js/jquery.timepicker.min.js"></script>
    <script src="js/scrollax.min.js"></script>
    <script src="main.js"></script>
</body>

</html>

