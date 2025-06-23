<?php
session_start();
require_once(__DIR__ . '/services/query.php');
$message = "";

// Session timeout in seconds (1 hour)
$timeout_duration = 3600;


// call query function to get admin by credentials
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST['username'];
  $password = $_POST['password'];

  // Get admin by username (for attempt logic)
  $admin_row = get_admin_by_username($username);

  if ($admin_row) {
    // Check if locked out (10 min lockout after 3 failed attempts)
    if ($admin_row['login_attempts'] >= 3 && strtotime($admin_row['last_login_attempt']) > strtotime('-10 minutes')) {
      $message = "<p style='color:red; text-align:center;'>Account locked due to too many failed attempts. Please check your email for a new password.</p>";
    } else {
      // Check credentials
      $admin = get_admin_by_credentials($username, $password);
      if ($admin) {
        // Success: reset attempts
        reset_login_attempts($username);

        $_SESSION['username'] = $admin['username'];
        $_SESSION['role'] = $admin['role'];
        $_SESSION['display_name'] = $admin['display_name'];
        $_SESSION['last_activity'] = time();

        if ($admin['role'] == 'superadmin' || $admin['role'] == 'admin') {
          header("Location: admin_dashboard.php");
        } else if ($admin['role'] == 'employer') {
          header("Location: manage.php");
        }
        exit();
      } else {
        // Wrong password: increment attempts
        increment_login_attempts($username);

        // Fetch updated attempts
        $admin_row = get_admin_by_username($username);
        if ($admin_row['login_attempts'] >= 3) {
          // Generate new password and send email
          $new_password = generate_random_words(5);
          set_new_admin_password($username, $new_password);

          // Send email
          $subject = "Your SHANGYA Admin Password Has Been Reset";
          $body = "Dear {$admin_row['display_name']},\n\nYour password has been reset due to too many failed login attempts.\n\nNew Password: $new_password\n\nPlease login and change your password after logging in.";
          $email_result = send_email($admin_row['email'], $subject, $body);

          if ($email_result !== true) {
            // Show the error returned by send_email
            $message = "<p style='color:red; text-align:center;'>Failed to send email: $email_result</p>";
          } else {
            $message = "<p style='color:red; text-align:center;'>Too many failed attempts. A new password has been sent to your email.</p>";
          }
        } else {
          $message = "<p style='color:red; text-align:center;'>Invalid username or password.</p>";
        }
      }
    }
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
  <?php include 'header.inc'; ?>

  <section class="hero">
    <section class="hero-content fade-in">
      <form method="POST" action="admin_login.php" style="display: flex; flex-direction: column; gap: 16px; max-width: 320px; margin: 0 auto; background: #fff; padding: 32px 24px; border-radius: 12px; box-shadow: 0 2px 16px rgba(0,0,0,0.08);">
        <?php if ($message) echo $message; ?>
        <input name="username" type="text" placeholder="Username" required style="padding: 12px !important; border: 1px solid #ccc !important; border-radius: 6px !important; font-size: 1rem !important; box-sizing: border-box !important; height: 48px !important; background: #fff !important; line-height: 1.2 !important;" />
        <input name="password" type="password" placeholder="Password" required
          style="padding: 12px; border: 1px solid #ccc; border-radius: 6px; font-size: 1rem;" />
        <button type="submit"
          style="padding: 12px; background: #1a73e8; color: #fff; border: none; border-radius: 6px; font-size: 1rem; cursor: pointer; transition: background 0.2s;">Login</button>
        <button type="buttton" onclick="window.location.href='employer_signup.php'"
          style="padding: 12px; background: green; color: #fff; border: none; border-radius: 6px; font-size: 1rem; cursor: pointer; transition: background 0.2s;">Sign Up as Employer</button>
      </form>
      <section class="hero-image-container">
        <img
          src="https://static.vecteezy.com/system/resources/previews/008/991/625/non_2x/happy-successful-businesswoman-with-laptop-get-money-online-income-commerce-business-woman-joyful-person-makes-passive-profit-or-gain-get-investment-dividend-and-earning-coin-from-internet-vector.jpg"
          alt="Hero Image" class="hero-image" />
      </section>
    </section>
  </section>

  <?php include 'footer.inc'; ?>
</body>

</html>