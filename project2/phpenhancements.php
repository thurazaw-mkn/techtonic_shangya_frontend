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
    <section class="file-structure">
        <div class="container">
            <h2>Project Structure</h2>
            <div class="structure-container">
                <div class="structure-card">
                    <div class="structure-icon">
                        <i class="fas fa-folder-tree"></i>
                    </div>
                    <h3>File Organization</h3>
                    <pre class="file-tree">
project2/
├── .vscode/
├── helpers/
│   ├── constants.php
│   └── helpers.php
├── images/
│   ├── clientlogo_agmo.png
│   ├── clientlogo_arb-logo.png
│   ├── clientlogo_attrelogix.png
│   ├── clientlogo_ctos.png
│   ├── clientlogo_globalklara.png
│   ├── clientlogo_KKV.svg
│   ├── clientlogo_santan.png
│   ├── office_work.jpg
│   ├── shangya-logo.avif
│   ├── team-group-photo.png
│   ├── teamlogo_techtonix.png
│   └── yahar_dashboard.png
├── services/
│   └── query.php
├── styles/
│   ├── images/
│   │   └── crossline.png
│   ├── create_job.css
│   ├── responsive.css
│   └── style.css
└── vendor/
    ├── about_company.php
    ├── about.php
    ├── admin_admins.php
    ├── admin_companies.php
    ├── admin_dashboard.php
    ├── admin_login.php
    ├── admin_logout.php
    ├── apply.php
    ├── composer.json
    ├── composer.lock
    ├── employer_create_job.php
    ├── employer_edit.php
    ├── employer_jobs.php
    ├── employer_signup.php
    ├── enhancements.php
    ├── enhancements2.php
    ├── header.inc
    ├── footer.inc
    ├── index.php
    ├── jobs.php
    ├── phpenhancements.php
    ├── process_eoi.php
    └── settings.php
</pre>
                </div>

                <div class="structure-card">
                    <div class="structure-icon">
                        <i class="fas fa-code"></i>
                    </div>
                    <h3>CSS Variables (:root)</h3>
                    <pre class="css-root">
:root {
  /* Colors */
  --primary-color: #455a64;
  --secondary-color: #cfd8dc;
  --text-color: #333;
  --heading-color: #222;
  --white-color: #fff;
  --background-color: #f9f9f9;

  /* Typography */
  --font-heading: "Poppins", sans-serif;
  --font-body: "DM Sans", sans-serif;

  /* Spacing */
  --container-width: 1200px;
  --spacing-sm: 0.5rem;
  --spacing-md: 1rem;
  --spacing-lg: 2rem;

  /* Shadows */
  --card-shadow: 0 4px 20px rgba(0,0,0,0.05);
  --hover-shadow: 0 20px 40px rgba(0,0,0,0.1);

  /* Transitions */
  --transition-speed: 0.3s;
  --transition-ease: cubic-bezier(0.4, 0, 0.2, 1);
}</pre>
                </div>
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