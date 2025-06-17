<?php
require_once(__DIR__ . '/../settings.php');

function get_admin_by_credentials($username, $password)
{
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

function get_job_with_filter($position_keyword, $location_keyword, $page_size, $page_number)
{
    global $host, $user, $pwd, $sql_db;
    $conn = @mysqli_connect($host, $user, $pwd, $sql_db);

    if (!$conn) {
        return null;
    }

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("SELECT * from jobs WHERE position = $position_keyword or location = $location_keyword");
    $stmt->execute();
    $result = $stmt->get_result();

    $admin = $result->fetch_assoc();

    $stmt->close();
    $conn->close();

    return $admin;
}

function create_job($job_ref_number, $title, $position, $location, $requirement_essential, $requirement_preferable, $salary_range, $description, $created_at, $created_by, $updated_at, $updated_by)
{
    global $host, $user, $pwd, $sql_db;
    $conn = @mysqli_connect($host, $user, $pwd, $sql_db);

    if (!$conn) {
        return null;
    }

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("INSERT into jobs (job_ref_lnumber, title, position, location, requirement_essential, requirement_preferable, salary_range, description, created_at, created_by, updated_at, updated_by) values (?,?,?,?,?,?,?,?,?,?,?,?)");
    $stmt->bind_param("issssssssss", $job_ref_number, $title, $position, $location, $requirement_essential, $requirement_preferable, $salary_range, $description, $created_at, $created_by, $updated_at, $updated_by);
    $stmt->execute();
    $result = $stmt->get_result();

    $admin = $result->fetch_assoc();

    $stmt->close();
    $conn->close();

    return $admin;
}

function get_job($position_keyword, $location_keyword, $page_size, $page_number)
{
    global $host, $user, $pwd, $sql_db;
    $conn = @mysqli_connect($host, $user, $pwd, $sql_db);

    if (!$conn) {
        return null;
    }

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("SELECT * from jobs");
    $stmt->execute();
    $result = $stmt->get_result();

    $admin = $result->fetch_assoc();

    $stmt->close();
    $conn->close();

    return $admin;
}

function update_job($id, $job_ref_number, $title, $position, $location, $requirement_essential, $requirement_preferable, $salary_range, $description, $created_at, $created_by, $updated_at, $updated_by)
{
    global $host, $user, $pwd, $sql_db;
    $conn = @mysqli_connect($host, $user, $pwd, $sql_db);

    if (!$conn) {
        return null;
    }

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("UPDATE jobs SET job_ref_lnumber = ?, title = ?, position = ?, location = ?, requirement_essential = ?, requirement_preferable = ?, salary_range = ?, description = ?, created_at = ?, created_by = ?, updated_at = ?, updated_by = ? WHERE id = ?");
    $stmt->bind_param("isssssssssssi", $job_ref_number, $title, $position, $location, $requirement_essential, $requirement_preferable, $salary_range, $description, $created_at, $created_by, $updated_at, $updated_by, $id);
    $stmt->execute();
    $result = $stmt->get_result();

    $admin = $result->fetch_assoc();

    $stmt->close();
    $conn->close();

    return $admin;
}
?>