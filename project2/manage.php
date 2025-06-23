<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once(__DIR__ . '/services/query.php');
$message = "";

// Session timeout in seconds (1 hour)
$timeout_duration = 3600;
if (
    !isset($_SESSION['username']) || !isset($_SESSION['role']) || !isset($_SESSION['display_name']) ||
    !isset($_SESSION['last_activity']) ||
    (time() - $_SESSION['last_activity']) > $timeout_duration
) {
    session_unset();
    session_destroy();
    header("Location: admin_login.php");
    exit();
}
$_SESSION['last_activity'] = time();

// Role filter: only allow 'employer'
if (!in_array($_SESSION['role'], ['employer'])) {
    session_unset();
    session_destroy();
    header("Location: admin_login.php");
    exit();
}

$username = $_SESSION['username'];

// --- DELETE ALL EOIs FOR A JOB REF ---
if (isset($_GET['delete_job_ref']) && $_GET['delete_job_ref'] !== '') {
    $job_ref = $_GET['delete_job_ref'];
    if (delete_eois_by_job_ref($job_ref)) {
        $message = "<span style='color:green;'>All EOIs for job reference $job_ref deleted.</span>";
    } else {
        $message = "<span style='color:red;'>Failed to delete EOIs for $job_ref.</span>";
    }
}

// --- DELETE eoi by eoi number ---
if (isset($_POST['delete_eoi_number'])) {
    $eoi_number = $_POST['delete_eoi_number'];
    if (delete_eoi_by_number($eoi_number)) {
        $message = "<span style='color:green;'>EOI #$eoi_number deleted.</span>";
    } else {
        $message = "<span style='color:red;'>Failed to delete EOI #$eoi_number.</span>";
    }
}

// --- CHANGE STATUS OF AN EOI ---
if (isset($_POST['change_status_id']) && isset($_POST['new_status'])) {
    $eoi_id = $_POST['change_status_id'];
    $new_status = $_POST['new_status'];
    if (update_eoi_status_and_notify($eoi_id, $new_status)) {
        $message = "<span style='color:green;'>EOI status updated and applicant notified.</span>";
    } else {
        $message = "<span style='color:red;'>Failed to update EOI status.</span>";
    }
}

// --- FILTER LOGIC ---
$job_reference_number = isset($_GET['job_reference_number']) ? trim($_GET['job_reference_number']) : '';
$first_name = isset($_GET['first_name']) ? trim($_GET['first_name']) : '';
$last_name = isset($_GET['last_name']) ? trim($_GET['last_name']) : '';
$eois = get_eoi_with_filter($username, $job_reference_number, $first_name, $last_name);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Manage EOIs</title>
    <link rel="stylesheet" href="./styles/style.css" />
    <link rel="stylesheet" href="./styles/responsive.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        .eoi-table-container {
            padding-top: 100px;
            max-width: 95%;
            margin: 40px auto 10px auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 16px rgba(0, 0, 0, 0.08);
            padding: 2rem;
        }

        .eoi-table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .eoi-table-header form {
            display: flex;
            flex-direction: row;
            align-items: stretch;
            gap: 0.5rem;
            width: auto;
        }

        .eoi-table-header input[type="text"] {
            flex: 1 1 120px;
            min-width: 0;
        }

        .eoi-table-header button,
        .eoi-table-actions a {
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

        .eoi-table-header button:hover,
        .eoi-table-actions a:hover {
            background: #1a237e;
        }

        .eoi-table {
            width: 100%;
            border-collapse: collapse;
        }

        .eoi-table th,
        .eoi-table td {
            padding: 10px 8px;
            border-bottom: 1px solid #eee;
            text-align: left;
        }

        .eoi-table th {
            background: #f5f7fa;
        }

        .eoi-table-actions {
            display: flex;
            gap: 0.5rem;
        }

        .status-select {
            padding: 4px 8px;
        }

        @media (max-width: 700px) {
            .eoi-table-container {
                padding: 0.5rem;
            }

            .eoi-table-header {
                flex-direction: column;
                align-items: stretch;
            }

            .eoi-table th,
            .eoi-table td {
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
                <a href="./manage.php" class="active">EOI</a>
                <a href="./employer_jobs.php">Jobs</a>
                <a href="./employer_create_job.php">Create Job</a>
                <a href="./employer_jobs.php" style="color: red !important"><?php echo htmlspecialchars($_SESSION['display_name']); ?></a>
                <a class="btn" href="admin_logout.php">Logout →</a>
            </nav>
        </section>
    </header>

    <section class="apply-hero">
        <div class="apply-hero-bg"></div>
        <section class="container fade-in">
            <div class="apply-hero-content">
                <p class="subtitle">20+ years of shaping careers and lives</p>
                <p class="hero-description">
                    At ShangYa Consultancy, we're a dynamic full-service staffing provider
                    and agency, offering a range of innovative solutions including
                    recruitment, talent outsourcing, training, HR consulting, and more...
                </p>
            </div>
        </section>
    </section>

    <section class="eoi-table-container">
        <?php if ($message) echo "<div style='margin-bottom:1rem;'>$message</div>"; ?>
        <div class="eoi-table-header">
            <form method="get" action="">
                <input type="text" name="job_reference_number" placeholder="Job Ref #" value="<?php echo htmlspecialchars($job_reference_number); ?>">
                <input type="text" name="first_name" placeholder="First Name" value="<?php echo htmlspecialchars($first_name); ?>">
                <input type="text" name="last_name" placeholder="Last Name" value="<?php echo htmlspecialchars($last_name); ?>">
                <button type="submit"><i class="fas fa-search"></i> Filter</button>
            </form>
            <br/>
            <form method="get" action="" style="display:inline;">
                <input type="text" name="delete_job_ref" placeholder="Delete all by Job Ref #" required>
                <button type="submit" onclick="return confirm('Delete all EOIs for this job reference?');" style="background:#e53935;">
                    <i class="fas fa-trash"></i> Delete All
                </button>
            </form>
            <!-- <form method="get" action="" style="display:inline;">
                <input type="text" name="delete_job_ref" placeholder="Delete by Job Ref #" required>
                <button type="submit" onclick="return confirm('Delete all EOIs for this job reference?');" style="background:#e53935;"><i class="fas fa-trash"></i> Delete All</button>
            </form> -->
        </div>
        <table class="eoi-table">
            <thead>
                <tr>
                    <th>EOI Number</th>
                    <th>Job Ref</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Phone</th>
                    <th>Skills</th>
                    <th>Created</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($eois && count($eois) > 0): ?>
                    <?php foreach ($eois as $eoi): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($eoi['eoi_number']); ?></td>
                            <td><?php echo htmlspecialchars($eoi['job_ref_number']); ?></td>
                            <td><?php echo htmlspecialchars($eoi['first_name'] . ' ' . $eoi['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($eoi['email']); ?></td>
                            <td>
                                <form method="post" action="" style="display:inline;">
                                    <input type="hidden" name="change_status_id" value="<?php echo $eoi['eoi_number']; ?>">
                                    <select name="new_status" class="status-select" onchange="this.form.submit()">
                                        <option value="New" <?php if ($eoi['status'] == 'New') echo 'selected'; ?>>New</option>
                                        <option value="Current" <?php if ($eoi['status'] == 'Current') echo 'selected'; ?>>Current</option>
                                        <option value="Final" <?php if ($eoi['status'] == 'Final') echo 'selected'; ?>>Final</option>
                                    </select>
                                </form>
                            </td>
                            <td><?php echo htmlspecialchars($eoi['phone']); ?></td>
                            <td><?php echo htmlspecialchars($eoi['skills']); ?></td>
                            <td><?php echo htmlspecialchars($eoi['created_at']); ?></td>
                            <td>
                                <form method="post" action="" onsubmit="return confirm('Delete this EOI?');" style="display:inline;">
                                    <input type="hidden" name="delete_eoi_number" value="<?php echo $eoi['eoi_number']; ?>">
                                    <button type="submit" style="background:#e53935;color:#fff;border:none;padding:6px 12px;border-radius:4px;cursor:pointer;">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" style="text-align:center;">No EOIs found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </section>
    <?php include 'footer.inc'; ?>
</body>

</html>