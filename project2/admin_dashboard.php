<?php
session_start();

// Session timeout in seconds (1 hour)
$timeout_duration = 3600;

// Check if session exists and not expired
if (
    !isset($_SESSION['username']) || !isset($_SESSION['role']) || !isset($_SESSION['display_name']) ||
    !isset($_SESSION['last_activity']) ||
    (time() - $_SESSION['last_activity']) > $timeout_duration
) {
    // Destroy session and redirect to login
    session_unset();
    session_destroy();
    header("Location: admin_login.php");
    exit();
}

// Update last activity time
$_SESSION['last_activity'] = time();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description"
        content="This project is a collaboration between INTI International College Subang (Swinburne University of Technology program) and SHANGYA CONSULTANCY" />
    <meta name="keywords" content="SHANGYA CONSULTANCY, INTI College subang, swinburne" />
    <meta name="author" content="Techtonic" />
    <link rel="icon" type="image/x-icon" href="./images/shangya-logo.avif" />
    <title>SHANGYA - Admin Dashboard</title>
    <link rel="stylesheet" href="./styles/style.css" />
    <link rel="stylesheet" href="./styles/responsive.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>

<body>
    <header class="navbar">
        <section class="container nav-content">
            <div class="logo">
                <a href="./index.php" aria-label="Shangya Home">
                    <section class="logo">
                        <a href="./index.php"><img src="./images/shangya-logo.avif" alt="shangya-logo" /></a>
                    </section>
                </a>
            </div>
            <input type="checkbox" class="menu-toggle" id="menu-toggle" />
            <label for="menu-toggle" class="hamburger" aria-label="Toggle navigation menu">
                <span></span>
                <span></span>
                <span></span>
            </label>
            <nav class="nav-links">
                <a href="./index.php" class="active">Home</a>
                <a href="./jobs.php">Jobs</a>
                <a href="./about.php">JobApplications</a>
                <a href="./admin_dashboard.php" style="color: red !important"><?php echo htmlspecialchars($_SESSION['display_name']); ?></a>
                <a class="btn" href="admin_logout.php">Logout →</a>
            </nav>
        </section>
    </header>

    <section class="hero">
        <section class="hero-content fade-in">
            <h1>Welcome, <?php echo htmlspecialchars($_SESSION['display_name']); ?>!</h1>
            <p>Your role: <strong><?php echo htmlspecialchars($_SESSION['role']); ?></strong></p>
            <p>This is the admin dashboard.</p>
            <a href="logout.php">Logout</a>
        </section>
    </section>

    <footer class="footer">
        <section class="footer-grid">
            <section class="brand">
                <img src="./images/teamlogo_techtonic.png" alt="Team Logo" class="team-logo" />
                <h2>Team Techtonic</h2>
                <p class="tagline">Innovating with passion</p>
            </section>

            <section class="developers">
                <h3>Developers</h3>
                <ul>
                    <li>Thura Zaw</li>
                    <li>Sai Lyan Hein</li>
                    <li>Thet Hein Aung</li>
                    <li>Krisvyn</li>
                </ul>
            </section>

            <section class="socials">
                <h3>Follow Us</h3>
                <nav class="icons">
                    <a href="#" target="_blank" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" target="_blank" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="https://www.youtube.com/@techtonictz" target="_blank" aria-label="YouTube"><i
                            class="fab fa-youtube"></i></a>
                    <a href="#" target="_blank" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                </nav>
            </section>

            <section class="cta">
                <a href="./about.php" class="details-btn">View Developers' Details</a>
            </section>
        </section>

        <section class="disclaimer">
            <p>
                This project is a collaboration between
                <strong>INTI International College Subang</strong>
                (Swinburne University of Technology program) and
                <strong>SHANGYA CONSULTANCY</strong>.
            </p>
        </section>
    </footer>
</body>

</html>