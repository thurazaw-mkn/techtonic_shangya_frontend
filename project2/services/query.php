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

function create_eoi($job_reference_number, $first_name, $last_name, $address_street, $address_city, $address_state, $address_postcode, $email, $phone, $skills, $other_skills, $status, $created_at, $created_by, $updated_at, $updated_by) 
{
    global $host, $user, $pwd, $sql_db;
    $conn = @mysqli_connect($host, $user, $pwd, $sql_db);

    if (!$conn) {
        return null;
    }

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO eoi (job_reference_number, first_name, last_name, address_street, address_city, address_state, address_postcode, email, phone, skills, other_skills, status, created_at, created_by, updated_at, updated_by) 
    values (?,?,?,?, ?, ?, ?, ?, ?, ?,?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssssssssss", $job_reference_number, $first_name, $last_name, $address_street, $address_city, $address_state, $address_postcode, $email, $phone, $skills, $other_skills, $status, $created_at, $created_by, $updated_at, $updated_by);
    $stmt->execute();
    $result = $stmt->get_result();

    $admin = $result->fetch_assoc();

    $stmt->close();
    $conn->close();

    return $admin;
}

function get_eoi() 
{
    global $host, $user, $pwd, $sql_db;
    $conn = @mysqli_connect($host, $user, $pwd, $sql_db);

    if (!$conn) {
        return null;
    }

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare(" SELECT * FROM eoi");

    $stmt->execute();
    $result = $stmt->get_result();

    $admin = $result->fetch_assoc();

    $stmt->close();
    $conn->close();

    return $admin;
}

function get_eoi_with_filter($job_reference_number, $first_name, $last_name) 
{
    global $host, $user, $pwd, $sql_db;
    $conn = @mysqli_connect($host, $user, $pwd, $sql_db);

    if (!$conn) {
        return null;
    }

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM eoi WHERE job_reference_number = ? OR first_name = ? OR last_name = ?");
    $stmt->bind_param("sss", $job_reference_number, $first_name, $last_name);
    $stmt->execute();
    $result = $stmt->get_result();

    $admin = $result->fetch_assoc();

    $stmt->close();
    $conn->close();

    return $admin;
}






?>