<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta
      name="description"
      content="This project is a collaboration between INTI International College Subang (Swinburne University of Technology program) and SHANGYA CONSULTANCY"
    />
    <meta
      name="keywords"
      content="SHANGYA CONSULTANCY, INTI College subang, swinburne"
    />
    <meta name="author" content="Techtonic" />
    <link rel="icon" type="image/x-icon" href="./images/shangya-logo.avif" />
    <title>SHANGYA - About Company</title>
    <link rel="stylesheet" href="./styles/style.css" />
    <link rel="stylesheet" href="./styles/responsive.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
    />
  </head>
  <body>
    <header class="navbar">
      <section class="container nav-content">
        <section class="logo">
          <a href="./index.php" aria-label="Shangya Home">
            <section class="logo">
              <a href="./index.php"
                ><img src="./images/shangya-logo.avif" alt="shangya-logo"
              /></a>
            </section>
          </a>
        </section>
        <input type="checkbox" class="menu-toggle" id="menu-toggle" />
        <label
          for="menu-toggle"
          class="hamburger"
          aria-label="Toggle navigation menu"
        >
          <span></span>
          <span></span>
          <span></span>
        </label>
        <nav class="nav-links">
          <a href="./index.php">Home</a>
          <a href="./jobs.php">Jobs</a>
          <a href="./about.php">About</a>
          <a href="./enhancements.php">Enhancements</a>
          <a class="btn" href="./apply.php">Apply Jobs →</a>
        </nav>
      </section>
    </header>

    <section class="about-about-hero">
      <section class="container fade-in">
        <h1>About Us</h1>
        <p class="subtitle">20+ years of shaping careers and lives</p>
        <p>
          At ShangYa Consultancy, we're a dynamic full-service staffing provider
          and agency, offering a range of innovative solutions including
          recruitment, talent outsourcing, training, HR consulting, and more...
        </p>
      </section>
    </section>

    <section class="what-we-do slide-up">
      <section class="container">
        <h2>What We Do</h2>
        <p>
          Drawing from our well-proven track record, we are proud to emphasize
          that our services and business practices are substantially enriched by
          these key components...
        </p>
      </section>
    </section>

    <section class="services fade-in">
      <section class="container">
        <h2>Our Services</h2>
        <section class="service-cards">
          <section class="card">
            <h3>01 Executive Search</h3>
            <p>
              Custom-tailored recruitment for niche roles, sourcing top-tier
              C-level talent and exceptional professionals.
            </p>
          </section>
          <section class="card">
            <h3>02 General Staffing</h3>
            <p>
              Comprehensive service for junior to mid-level positions, including
              experienced professionals and fresh graduates.
            </p>
          </section>
          <section class="card">
            <h3>03 Bulk Hiring</h3>
            <p>
              Rapid onboarding for high-turnover industries like BPO, retail,
              and F&B.
            </p>
          </section>
          <section class="card">
            <h3>04 Payroll Services</h3>
            <p>
              Precise payroll management, tax compliance, and financial insights
              for operational efficiency.
            </p>
          </section>
          <section class="card">
            <h3>05 Training & Development</h3>
            <p>
              Customized programs to enhance workforce skills and ensure
              professional growth.
            </p>
          </section>
          <section class="card">
            <h3>06 Recruitment Process Outsourcing (RPO)</h3>
            <p>
              End-to-end recruitment process management to optimize hiring while
              maintaining the human touch.
            </p>
          </section>
        </section>
      </section>
    </section>

    <?php include 'footer.inc'; ?>
  </body>
</html>
