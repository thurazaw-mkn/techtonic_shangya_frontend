<?php
session_start();
require_once(__DIR__ . '/services/query.php');

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


$eoi_data = get_eoi();

if (empty($eoi_data)) {
    $message = "<p style='color:red; text-align:center;'>No EOI submissions found.</p>";
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
    <title>SHANGYA - Admin EOI Management</title>
    <link rel="stylesheet" href="./styles/style.css" />
    <link rel="stylesheet" href="./styles/responsive.css" />
    <link rel="stylesheet" href="./styles/admin_eoi.css" />
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
                <a href="./index.php">Home</a>
                <a href="./jobs.php">Jobs</a>
                <a href="./admin_eoi.php" class="active">EOI Management</a>
                <a href="./admin_dashboard.php" style="color: red !important"><?php echo htmlspecialchars($_SESSION['display_name']); ?></a>
                <a class="btn" href="admin_logout.php">Logout →</a>
            </nav>
        </section>
    </header>

    <section class="eoi-container">
        <h1>Expression of Interest Management</h1>
        <p>Welcome, <?php echo htmlspecialchars($_SESSION['display_name']); ?>! Here you can view and manage all EOI submissions.</p>
        
        <div class="eoi-table-container">
            <?php if (empty($eoi_data)): ?>
                <div class="no-data">No EOI submissions found.</div>
            <?php else: ?>
                <table class="eoi-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Job Reference</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($eoi_data as $eoi): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($eoi['eoi_number']); ?></td>
                                <td><?php echo htmlspecialchars($eoi['job_ref_number']); ?></td>
                                <td><?php echo htmlspecialchars($eoi['first_name'] . ' ' . $eoi['last_name']); ?></td>
                                <td><?php echo htmlspecialchars($eoi['email']); ?></td>
                                <td><?php echo htmlspecialchars($eoi['phone']); ?></td>
                                <td>
                                    <span class="status-badge status-<?php echo strtolower(htmlspecialchars($eoi['status'])); ?>">
                                        <?php echo htmlspecialchars($eoi['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo htmlspecialchars($eoi['created_at']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
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

    <!-- Modal for viewing EOI details -->
    <div class="modal" id="eoi-modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <h2>EOI Details</h2>
            <div id="eoi-details"></div>
        </div>
    </div>

    <script>
        // Simple JavaScript for modal functionality
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('eoi-modal');
            const closeModal = document.querySelector('.close-modal');
            const viewButtons = document.querySelectorAll('.view-btn');
            const editButtons = document.querySelectorAll('.edit-btn');
            const eoiDetails = document.getElementById('eoi-details');
            
            // Close modal when clicking X
            closeModal.addEventListener('click', function() {
                modal.style.display = 'none';
            });
            
            // Close modal when clicking outside
            window.addEventListener('click', function(event) {
                if (event.target === modal) {
                    modal.style.display = 'none';
                }
            });
            
            // View EOI details
            viewButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    // In a real application, you would fetch the details via AJAX
                    // For now, we'll just show a placeholder
                    eoiDetails.innerHTML = `<p>Loading details for EOI #${id}...</p>`;
                    modal.style.display = 'block';
                });
            });
            
            // Edit EOI
            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    alert(`Edit functionality for EOI #${id} would be implemented here.`);
                });
            });
        });
    </script>
</body>

</html>