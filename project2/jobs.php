<?php
require_once(__DIR__ . '/services/query.php');
require_once(__DIR__ . '/helpers/helpers.php');

// Get search keywords from GET or POST if you want to support search
$position_keyword = isset($_GET['position']) ? $_GET['position'] : '';
$location_keyword = isset($_GET['location']) ? $_GET['location'] : '';

// Get jobs from database
$jobs = get_job($position_keyword, $location_keyword);
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="This project is a collaboration between INTI International College Subang (Swinburne University of Technology program) and SHANGYA CONSULTANCY">
  <meta name="keywords" content="SHANGYA CONSULTANCY, INTI College subang, swinburne">
  <meta name="author" content="Techtonic">
  <link rel="icon" type="image/x-icon" href="./images/shangya-logo.avif">
  <title>SHANGYA _ Jobs</title>
  <link rel="stylesheet" href="./styles/style.css" />
  <link rel="stylesheet" href="./styles/responsive.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>

<body>
  <?php include 'header.inc'; ?>

  <section class="find-job-section">
    <section class="search-section">
      <article class="center-item">
        <h1 style="font-size: 2.5rem;">Find Jobs</h1>
      </article>

      <form class="space-search-bar" method="get" action="jobs.php">
        <section class="center-item">
          <section class="space-search-bar">
            <input name="position" class="search-bar" type="text" placeholder="Search Jobs..." />
            <input name="location" class="search-bar" type="text" placeholder="Search Location..." />
          </section>
        </section>

        <section class="center-item space-item tag-section">
          <label>
            <input type="checkbox" />
            <span class="find-job-section-tag">Freelance</span>
          </label>
          <label>
            <input type="checkbox" />
            <span class="find-job-section-tag">Fulltime</span> </label><label>
            <input type="checkbox" />
            <span class="find-job-section-tag">Part-Time</span></label>
          <label>
            <input type="checkbox" />
            <span class="find-job-section-tag">Internship</span>
          </label>
          <button class="search-btn" type="submit">Search</button>
        </section>
      </form>
    </section>
  </section>

  <section class="container card-section">
    <?php if ($jobs && count($jobs) > 0): ?>
      <?php foreach ($jobs as $job): ?>
        <section class="job-card">
          <article class="space-item job-card-header">
            <img class="company-logo" src="https://cdn.theorg.com/0aca310b-49eb-408c-803d-43fe89a84cc7_thumb.jpg"
              alt="company logo" />
            <h3><?php echo htmlspecialchars($job['company_name'] ?? ''); ?></h3>
          </article>
          <section class="space-card-text">
            <article class="card-text">
              <h4>Offer position</h4>
              <p><?php echo htmlspecialchars($job['position']); ?></p>
            </article>
            <article class="card-text">
              <h4>Location</h4>
              <p><?php echo htmlspecialchars($job['location']); ?></p>
            </article>
            <article class="card-text">
              <h4>Requirement</h4>
              <h5>Essential</h5>
              <p><?php echo htmlspecialchars($job['requirement_essential']); ?></p>
              <hr />
              <h5>Preferable</h5>
              <p><?php echo htmlspecialchars($job['requirement_preferable']); ?></p>
            </article>
          </section>
          <section class="card-footer">
            <p>Posted : <?php echo htmlspecialchars(time_ago($job['created_at'])); ?></p>
            <p>Salary Range : <?php echo htmlspecialchars($job['salary_range']); ?></p>
            <p>Job Reference Number : <?php echo htmlspecialchars($job['job_ref_number']); ?></p>
            <a href="./apply.php?job_ref_number=<?php echo urlencode($job['job_ref_number']); ?>" class="apply-button">Apply Now</a>
          </section>
          <details>
            <summary>More Info - ⌄ </summary>
            <article class="dropdown-content">
              <h6>Job description</h6>
              <p><?php echo nl2br(htmlspecialchars($job['description'])); ?></p>
            </article>
          </details>
        </section>
      <?php endforeach; ?>
    <?php else: ?>
      <p style="text-align:center;">No jobs found.</p>
    <?php endif; ?>
  </section>

  <?php include 'footer.inc'; ?>
</body>

</html>