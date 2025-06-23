<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once(__DIR__ . '/services/query.php');
require_once(__DIR__ . '/helpers/helpers.php');
$message = "";

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

// Role filter: only allow 'admin' or 'superadmin'
if (!in_array($_SESSION['role'], ['employer'])) {
    session_unset();
    session_destroy();
    header("Location: admin_login.php");
    exit();
}

// --- DELETE LOGIC ---
// if (isset($_GET['delete']) && !empty($_GET['delete'])) {
//     $usernameToDelete = $_GET['delete'];
//     if (delete_admin_soft($usernameToDelete)) {
//         $message = "<span style='color:green;'>Admin deleted successfully.</span>";
//     } else {
//         $message = "<span style='color:red;'>Failed to delete admin.</span>";
//     }
// }

// --- FIXED SEARCH BAR LOGIC ---
//$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$jobs = get_job_by_company($username = $_SESSION['username']);
// if (!$admins) {
//     $admins = [];
// }

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
    <title>SHANGYA - Employer EOI</title>
    <link rel="stylesheet" href="./styles/style.css" />
    <link rel="stylesheet" href="./styles/responsive.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        .admin-table-container {
            padding: 1rem 0;
            max-width: 90%;
            margin: 200px auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 16px rgba(0, 0, 0, 0.08);
            padding: 2rem;
        }

        .admin-table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .admin-table-header form {
            display: flex;
            flex-direction: row;
            align-items: stretch;
            gap: 0.5rem;
            width: auto;
        }

        .admin-table-header input[type="text"] {
            flex: 1 1 220px;
            min-width: 0;
        }

        .admin-table-header button,
        .admin-table-actions a {
            padding: 8px 16px;
            border-radius: 6px;
            border: none;
            background: #2563eb;
            color: #fff;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            white-space: nowrap
        }

        .admin-table-header button:hover,
        .admin-table-actions a:hover {
            background: #1a237e;
        }

        .admin-table {
            width: 100%;
            border-collapse: collapse;
        }

        .admin-table th,
        .admin-table td {
            padding: 12px 10px;
            border-bottom: 1px solid #eee;
            text-align: left;
        }

        .admin-table th {
            background: #f5f7fa;
        }

        .admin-table-actions {
            display: flex;
            gap: 0.5rem;
        }

        @media (max-width: 700px) {
            .admin-table-container {
                padding: 0.5rem;
            }

            .admin-table-header {
                flex-direction: column;
                align-items: stretch;
            }

            .admin-table th,
            .admin-table td {
                padding: 8px 4px;
                font-size: 0.95rem;
            }
        }
    </style>
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
                <a href="./employer_jobs.php" class="active">Jobs</a>
                <a href="./employer_create_job.php">Create Job</a>
                <a href="./employer_jobs.php" style="color: red !important"><?php echo htmlspecialchars($_SESSION['display_name']); ?></a>
                <a class="btn" href="admin_logout.php">Logout →</a>
            </nav>
        </section>
    </header>

    <section class="container card-section" style="margin-top: 15%;">
        <?php if ($jobs && count($jobs) > 0): ?>
        <?php foreach ($jobs as $job): ?>
            <section class="job-card">
                <article class="space-item job-card-header">
                    <img class="company-logo" src="<?php echo htmlspecialchars($job['company_photo_str']); ?>"
                        alt="company logo" />
                    <h3><?php echo htmlspecialchars($_SESSION['display_name']); ?></h3>
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
                        <hr/>
                        <h5>Preferable</h5>
                        <p><?php echo htmlspecialchars($job['requirement_preferable']); ?></p>
                    </article>
                </section>
                <section class="card-footer">
                    <p>Posted : <?php echo htmlspecialchars(time_ago($job['created_at'])); ?></p>
                    <p>Salary Range : <?php echo htmlspecialchars($job['salary_range']); ?></p>
                    <p>Job Reference Number : <?php echo htmlspecialchars($job['job_ref_number']); ?></p>
                </section>
                <details>
                    <summary>More Info - ⌄ </summary>
                    <article class="dropdown-content">
                        <h6>Job description</h6>
                        <p><?php echo nl2br(htmlspecialchars($job['description'])); ?></p>
                        <!-- Add more job details here if needed -->
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