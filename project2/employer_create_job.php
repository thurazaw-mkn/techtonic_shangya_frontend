<?php
require_once(__DIR__ . '/services/query.php');
$message = "";

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

// Role filter: only allow 'employer'
if (!in_array($_SESSION['role'], ['employer'])) {
    session_unset();
    session_destroy();
    header("Location: admin_login.php");
    exit();
}


// generate a random job reference number
function generate_job_ref_number() {
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $length = 5; // Length of the job reference number
    $job_ref_number = '';
    for ($i = 0; $i < $length; $i++) {
        $job_ref_number .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $job_ref_number;
}

// call query function to get admin by credentials
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $job_ref_number = $_POST['job_ref_number'];
    $title = $_POST['title'];
    $position = $_POST['position'];
    $location = $_POST['location'];
    $requirement_essential = $_POST['requirement_essential'];
    $requirement_preferable = $_POST['requirement_preferable'];
    $salary_range = $_POST['salary_range'];
    $description = $_POST['description'];
    $created_by = $_SESSION['username'];

    $create_job = create_job($created_by,$job_ref_number, $title, $position, $location, $requirement_essential, $requirement_preferable, $salary_range, $description, $created_by);

    if ($create_job) {
        $message = "<p style='color:blue; text-align:center;'>successfully create job.</p>";
        header("Location: employer_create_job.php");
        exit();
    } else {
        $message = "<p style='color:red; text-align:center;'>Invalid username or password.</p>";
    }
}
?>

<!-- HTML Form -->
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description"
        content="This project is a collaboration between INTI International College Subang (Swinburne University of Technology program) and SHANGYA CONSULTANCY" />
    <meta name="keywords" content="SHANGYA CONSULTANCY, INTI College subang, swinburne" />
    <meta name="author" content="Techtonic" />
    <link rel="icon" type="image/x-icon" href="./images/shangya-logo.avif" />
    <title>SHANGYA - Admin Create Job</title>
    <link rel="stylesheet" href="./styles/style.css" />
    <link rel="stylesheet" href="./styles/responsive.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <!-- <link rel="stylesheet" type="text/css" href="styles/create_job.css"> -->

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
                <a href="./manage.php">EOI</a>
                <a href="./employer_jobs.php">Jobs</a>
                <a href="./employer_create_job.php" class="active">Create Job</a>
                <a href="./employer_jobs.php" style="color: red !important"><?php echo htmlspecialchars($_SESSION['display_name']); ?></a>
                <a class="btn" href="admin_logout.php">Logout →</a>
            </nav>
        </section>
    </header>

    <section class="apply-hero">
        <div class="apply-hero-bg"></div>
        <section class="container fade-in">
            <div class="apply-hero-content">
                <h1>Create for your needed!</h1>
                <div class="hero-divider"></div>
                <p class="subtitle">20+ years of shaping careers and lives</p>
                <p class="hero-description">
                    At ShangYa Consultancy, we're a dynamic full-service staffing provider
                    and agency, offering a range of innovative solutions including
                    recruitment, talent outsourcing, training, HR consulting, and more...
                </p>
            </div>
        </section>
    </section>

    <section class="form-container container slide-up">
        <div class="form-title">Create Jobs Application</div>
        <form method="post" action="employer_create_job.php">
            <input type="text" name="job_ref_number" placeholder="Job Reference Number" value="<?php echo generate_job_ref_number(); ?>"
                required readonly />

            <div class="name-fields">
                <input type="text" name="title" placeholder="Title" required />
                <input type="text" name="position" placeholder="Positin" required />
            </div>

            <input type="text" name="location" placeholder="Location" required />
            
            <input type="text" name="requirement_essential" placeholder="Requirement (Essential)"
                required />

            <input type="text" name="requirement_preferable" placeholder="Requirement (Preferable)"
                required />

            <input type="text" name="salary_range" placeholder="Salary Range"
                required />

            <textarea name="description" placeholder="Describe your Position"></textarea>

            <button type="submit" class="submit-btn">
                <span>Create Job Application</span>
            </button>
        </form>
    </section>

    <?php include 'footer.inc'; ?>
</body>

</html>