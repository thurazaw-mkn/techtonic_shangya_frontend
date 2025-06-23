<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once(__DIR__ . '/services/query.php');
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
if (!in_array($_SESSION['role'], ['admin', 'superadmin'])) {
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
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$admins = get_admins($search);
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
    <title>SHANGYA - Admin Admins</title>
    <link rel="stylesheet" href="./styles/style.css" />
    <link rel="stylesheet" href="./styles/responsive.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        .admin-table-container { padding: 1rem 0; max-width: 90%; margin: 200px auto; background: #fff; border-radius: 12px; box-shadow: 0 2px 16px rgba(0,0,0,0.08); padding: 2rem; }
        .admin-table-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;}
        .admin-table-header form { display: flex;
    flex-direction: row;
    align-items: stretch;
    gap: 0.5rem;
    width: auto; }
        .admin-table-header input[type="text"] { flex: 1 1 220px;
    min-width: 0; }
        .admin-table-header button, .admin-table-actions a { padding: 8px 16px; border-radius: 6px; border: none; background: #2563eb; color: #fff; font-weight: 600; cursor: pointer; text-decoration: none; white-space: nowrap }
        .admin-table-header button:hover, .admin-table-actions a:hover { background: #1a237e; }
        .admin-table { width: 100%; border-collapse: collapse; }
        .admin-table th, .admin-table td { padding: 12px 10px; border-bottom: 1px solid #eee; text-align: left; }
        .admin-table th { background: #f5f7fa; }
        .admin-table-actions { display: flex; gap: 0.5rem; }
        @media (max-width: 700px) {
            .admin-table-container { padding: 0.5rem; }
            .admin-table-header { flex-direction: column; align-items: stretch; }
            .admin-table th, .admin-table td { padding: 8px 4px; font-size: 0.95rem; }
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
                <a href="./admin_dashboard.php">Home</a>
                <a href="./admin_admins.php" class="active">Admins</a>
                <a href="./admin_companies.php">Companies</a>
                <a href="./admin_dashboard.php" style="color: red !important"><?php echo htmlspecialchars($_SESSION['display_name']); ?></a>
                <a class="btn" href="admin_logout.php">Logout →</a>
            </nav>
        </section>
    </header>

    <section class="admin-table-container">
        <div class="admin-table-header">
            <form method="get" action="">
                <input type="text" name="search" placeholder="Search username or display name" value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit"><i class="fas fa-search"></i> Search</button>
            </form>
            <a href="admin_create.php" class="btn"><i class="fas fa-plus"></i> Create Admin</a>
        </div>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Display Name</th>
                    <th>Role</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($admins && count($admins) > 0): ?>
                    <?php foreach ($admins as $admin): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($admin['username']); ?></td>
                            <td><?php echo htmlspecialchars($admin['display_name']); ?></td>
                            <td><?php echo htmlspecialchars($admin['role']); ?></td>
                            <td><?php echo htmlspecialchars($admin['email']); ?></td>
                            <td class="admin-table-actions">
                                <a href="admin_update.php?username=<?php echo urlencode($admin['username']); ?>" title="Edit"><i class="fas fa-edit"></i> Edit</a>
                                <a href="admin_admins.php?delete=<?php echo urlencode($admin['username']); ?>" title="Delete" onclick="return confirm('Are you sure you want to delete this admin?');"><i class="fas fa-trash"></i> Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="4" style="text-align:center;">No admins found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </section>

    

    <?php include 'footer.inc'; ?>
</body>

</html>