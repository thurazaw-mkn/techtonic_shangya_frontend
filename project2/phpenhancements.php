<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="This project is a collaboration between INTI International College Subang (Swinburne University of Technology program) and SHANGYA CONSULTANCY" />
    <meta name="keywords" content="SHANGYA CONSULTANCY, INTI College subang, swinburne" />
    <meta name="author" content="Techtonic" />
    <link rel="icon" type="image/x-icon" href="./images/shangya-logo.avif" />
    <title>SHANGYA - PHPEnhancements</title>
    <link rel="stylesheet" href="./styles/style.css" />
    <link rel="stylesheet" href="./styles/responsive.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
                <a href="./index.php">Home</a>
                <a href="./jobs.php">Jobs</a>
                <a href="./about.php">About</a>
                <a href="./enhancements.php" class="active">Enhancements</a>
                <a class="btn" href="./apply.php">Apply Jobs →</a>
            </nav>
        </section>
    </header>

    <section class="enhancement-hero">
        <section class="container fade-in">
            <h1>Website PHP Enhancements</h1>
            <p class="subtitle">Discover our innovative features and improvements using PHP</p>
        </section>
    </section>
    <section class="enhancements-section">
        <div class="container">
            <div class="enhancement-grid">
                <article class="enhancement-card">
                    <div class="enhancement-icon">
                        <i class="fa-solid fa-lock"></i>
                    </div>
                    <h3>Security Features</h3>
                    <p>Protect user data and account access with advanced security mechanisms built into the system.</p>
                    <ul class="enhancement-features">
                        <li>Secure, time-limited tokens</li>
                        <li>Email-based reset link</li>
                        <li>Email-based username and password for employers</li>
                    </ul>
                </article>

                <article class="enhancement-card">
                    <div class="enhancement-icon">
                        <i class="fa-solid fa-eye"></i>
                    </div>
                    <h3>Access Control</h3>
                    <p>Access control ensures the right users can view and manage specific content securely</p>
                    <ul class="enhancement-features">
                        <li>Role-based permissions for Super Admin, Admins, and Employers</li>
                        <li>Employers can view job listings and post new job opportunities</li>
                        <li>Super Admin access is script-defined, bypassing database lookup</li>
                    </ul>
                </article>

                <article class="enhancement-card">
                    <div class="enhancement-icon">
                        <i class="fa-solid fa-database"></i>
                    </div>
                    <h3>Database Structure</h3>
                    <p>Normalized relational schema optimized for job listing and application tracking</p>
                    <ul class="enhancement-features">
                        <li>Separate tables for jobs, EOIs, companies, and admin roles</li>
                        <li>Foreign keys maintain consistency across related entities</li>
                        <li>Timestamp fields enable tracking of data creation and updates</li>
                    </ul>
                </article>

               
            </div>
        </div>
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