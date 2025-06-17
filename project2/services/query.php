<?php
require_once(__DIR__ . '/../settings.php');

function get_admin_by_credentials($username, $password) {
    global $host, $user, $pwd, $sql_db;
    $conn = @mysqli_connect($host, $user, $pwd, $sql_db);

    if (!$conn) {
        return null;
    }

    // Hash the input password using SHA-256
    $hashed_password = hash('sha256', $password);

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("SELECT username, role, display_name FROM admins WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $hashed_password);
    $stmt->execute();
    $result = $stmt->get_result();

    $admin = $result->fetch_assoc();

    $stmt->close();
    $conn->close();

    return $admin;
}
?>