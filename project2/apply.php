<?php
$job_ref_number = isset($_GET['job_ref_number']) ? $_GET['job_ref_number'] : '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta
    name="description"
    content="This project is a collaboration between INTI International College Subang (Swinburne University of Technology program) and SHANGYA CONSULTANCY" />
  <meta
    name="keywords"
    content="SHANGYA CONSULTANCY, INTI College subang, swinburne" />
  <meta name="author" content="Techtonic" />
  <link rel="icon" type="image/x-icon" href="./images/shangya-logo.avif" />
  <title>SHANGYA - Apply</title>
  <link rel="stylesheet" href="./styles/style.css" />
  <link rel="stylesheet" href="./styles/responsive.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
  <?php include 'header.inc'; ?>

  <section class="apply-hero">
    <div class="apply-hero-bg"></div>
    <section class="container fade-in">
      <div class="apply-hero-content">
        <h1>Join with us</h1>
        <div class="hero-divider"></div>
        <p class="subtitle">20+ years of shaping careers and lives</p>
        <p class="hero-description">
          At ShangYa Consultancy, we're a dynamic full-service staffing provider
          and agency, offering a range of innovative solutions including
          recruitment, talent outsourcing, training, HR consulting, and more...
        </p>
        <div class="hero-stats">
          <div class="stat-item">
            <span class="stat-number">500+</span>
            <span class="stat-label">Companies</span>
          </div>
          <div class="stat-item">
            <span class="stat-number">10k+</span>
            <span class="stat-label">Jobs Filled</span>
          </div>
          <div class="stat-item">
            <span class="stat-number">98%</span>
            <span class="stat-label">Success Rate</span>
          </div>
        </div>
      </div>
    </section>
  </section>

  <section class="form-container container slide-up">
    <div class="form-title">Apply Jobs</div>
    <form method="post" action="./process_eoi.php" novalidate="novalidate">
      <?php if ($job_ref_number): ?>
        <input
          type="text"
          name="job_ref_number"
          placeholder="Job Reference Number"
          pattern="[A-Za-z0-9]{5}"
          required
          value="<?php echo htmlspecialchars($job_ref_number); ?>"
          readonly />
      <?php else: ?>
        <input
          type="text"
          name="job_ref_number"
          placeholder="Job Reference Number"
          pattern="[A-Za-z0-9]{5}"
          required />
      <?php endif; ?>
      <div class="name-fields">
        <input type="text" name="first_name" placeholder="First Name" maxlength="20" pattern="[A-Za-z]+" required />
        <input type="text" name="last_name" placeholder="Last Name" maxlength="20" pattern="[A-Za-z]+" required />
      </div>

      <input type="text" name="dob" placeholder="Date of Birth (dd/mm/yyyy)" pattern="\d{2}/\d{2}/\d{4}" required />

      <fieldset>
        <legend>Gender</legend>
        <div class="gender-options">
          <label><input type="radio" name="gender" value="male" required /> Male</label>
          <label><input type="radio" name="gender" value="female" required /> Female</label>
          <label><input type="radio" name="gender" value="other" required /> Other</label>
        </div>
      </fieldset>

      <input type="text" name="address_street" placeholder="Street Address" maxlength="40" required />

      <div class="grid-2">
        <input type="text" name="address_town" placeholder="Suburb/Town" maxlength="40" />
        <select required name="address_state">
          <option value="">Select State</option>
          <option value="Johor">Johor</option>
          <option value="Kedah">Kedah</option>
          <option value="Kelantan">Kelantan</option>
          <option value="Melaka">Melaka</option>
          <option value="Negeri Sembilan">Negeri Sembilan</option>
          <option value="Pahang">Pahang</option>
          <option value="Penang">Penang</option>
          <option value="Perak">Perak</option>
          <option value="Perlis">Perlis</option>
          <option value="Sabah">Sabah</option>
          <option value="Sarawak">Sarawak</option>
          <option value="Selangor">Selangor</option>
          <option value="Terengganu">Terengganu</option>
          <option value="Kuala Lumpur">Kuala Lumpur</option>
          <option value="Labuan">Labuan</option>
          <option value="Putrajaya">Putrajaya</option>
        </select>
      </div>

      <div class="grid-2">
        <input type="text" name="address_postcode" placeholder="Postcode" pattern="\d{4}" required />
        <input type="email" name="email" placeholder="Email Address" required />
      </div>

      <input type="text" name="phone" placeholder="Phone Number" pattern="[\d\s]{8,12}" required />

      <fieldset>
        <legend>Skills</legend>
        <div class="checkbox-group">
          <label><input type="checkbox" name="skills[]" value="communication" /> Communication</label>
          <label><input type="checkbox" name="skills[]" value="teamwork" /> Teamwork</label>
          <label><input type="checkbox" name="skills[]" value="problem-solving" /> Problem Solving</label>
          <label><input type="checkbox" name="skills[]" value="management" /> Management</label>
          <label><input type="checkbox" name="skills[]" value="other" /> Other skills...</label>
        </div>
      </fieldset>

      <textarea name="other_skills" placeholder="Describe your other skills"></textarea>

      <button type="submit" class="submit-btn">
        <span>SUBMIT APPLICATION</span>
      </button>
    </form>
  </section>

  <?php include 'footer.inc'; ?>
</body>

</html>