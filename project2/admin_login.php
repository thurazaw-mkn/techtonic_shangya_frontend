<?php
session_start();
require_once(__DIR__ . '/services/query.php');
$message = "";

// Session timeout in seconds (1 hour)
$timeout_duration = 3600;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $admin = get_admin_by_credentials($username, $password);

    if ($admin) {
        $_SESSION['username'] = $admin['username'];
        $_SESSION['role'] = $admin['role'];
        $_SESSION['display_name'] = $admin['display_name'];
        $_SESSION['last_activity'] = time();
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $message = "<p style='color:red; text-align:center;'>Invalid username or password.</p>";
    }
}
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
  <title>SHANGYA - Admin Login</title>
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
        <a href="./about.php">About</a>
        <a href="./enhancements.php">Enhancements</a>
        <a class="btn" href="./apply.php">Apply Jobs →</a>
      </nav>
    </section>
  </header>

  <section class="hero">
    <section class="hero-content fade-in">
      <form method="POST" action="admin_login.php" style="display: flex; flex-direction: column; gap: 16px; max-width: 320px; margin: 0 auto; background: #fff; padding: 32px 24px; border-radius: 12px; box-shadow: 0 2px 16px rgba(0,0,0,0.08);">
        <?php if ($message) echo $message; ?>
        <input name="username" type="text" placeholder="Username" required style="padding: 12px !important; border: 1px solid #ccc !important; border-radius: 6px !important; font-size: 1rem !important; box-sizing: border-box !important; height: 48px !important; background: #fff !important; line-height: 1.2 !important;" />
        <input name="password" type="password" placeholder="Password" required
          style="padding: 12px; border: 1px solid #ccc; border-radius: 6px; font-size: 1rem;" />
        <button type="submit"
          style="padding: 12px; background: #1a73e8; color: #fff; border: none; border-radius: 6px; font-size: 1rem; cursor: pointer; transition: background 0.2s;">Login</button>
      </form>
      <section class="hero-image-container">
        <img
          src="https://static.vecteezy.com/system/resources/previews/008/991/625/non_2x/happy-successful-businesswoman-with-laptop-get-money-online-income-commerce-business-woman-joyful-person-makes-passive-profit-or-gain-get-investment-dividend-and-earning-coin-from-internet-vector.jpg"
          alt="Hero Image" class="hero-image" />
      </section>
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