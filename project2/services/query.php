<?php
require_once(__DIR__ . '/../settings.php');
require_once(__DIR__ . './helpers.php');

// Admin authentication (SHA-256 password)
function get_admin_by_credentials($username, $password)
{
    global $host, $user, $pwd, $sql_db;
    $conn = @mysqli_connect($host, $user, $pwd, $sql_db);

    if (!$conn) {
        return null;
    }

    $hashed_password = hash('sha256', $password);

    $stmt = $conn->prepare("SELECT username, role, display_name FROM admins WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $hashed_password);
    $stmt->execute();
    $result = $stmt->get_result();

    $admin = $result->fetch_assoc();

    $stmt->close();
    $conn->close();

    return $admin;
}

// Create EOI
function create_eoi($job_reference_number, $first_name, $last_name, $address_street, $address_city, $address_state, $address_postcode, $email, $phone, $skills, $other_skills, $status, $created_at, $created_by, $updated_at, $updated_by) 
{
    global $host, $user, $pwd, $sql_db;
    $conn = @mysqli_connect($host, $user, $pwd, $sql_db);

    if (!$conn) {
        return null;
    }

    $stmt = $conn->prepare("INSERT INTO eoi (job_reference_number, first_name, last_name, address_street, address_city, address_state, address_postcode, email, phone, skills, other_skills, status, created_at, created_by, updated_at, updated_by) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssssssssss", $job_reference_number, $first_name, $last_name, $address_street, $address_city, $address_state, $address_postcode, $email, $phone, $skills, $other_skills, $status, $created_at, $created_by, $updated_at, $updated_by);
    $success = $stmt->execute();

    $stmt->close();
    $conn->close();

    return $success;
}

// Get all EOIs
function get_eoi() 
{
    global $host, $user, $pwd, $sql_db;
    $conn = @mysqli_connect($host, $user, $pwd, $sql_db);

    if (!$conn) {
        return null;
    }

    $stmt = $conn->prepare("SELECT * FROM eoi");
    $stmt->execute();
    $result = $stmt->get_result();

    $eois = [];
    while ($row = $result->fetch_assoc()) {
        $eois[] = $row;
    }

    $stmt->close();
    $conn->close();

    return $eois;
}

// Get EOIs with filter
function get_eoi_with_filter($job_reference_number, $first_name, $last_name) 
{
    global $host, $user, $pwd, $sql_db;
    $conn = @mysqli_connect($host, $user, $pwd, $sql_db);

    if (!$conn) {
        return null;
    }

    $stmt = $conn->prepare("SELECT * FROM eoi WHERE job_reference_number = ? OR first_name = ? OR last_name = ?");
    $stmt->bind_param("sss", $job_reference_number, $first_name, $last_name);
    $stmt->execute();
    $result = $stmt->get_result();

    $eois = [];
    while ($row = $result->fetch_assoc()) {
        $eois[] = $row;
    }

    $stmt->close();
    $conn->close();

    return $eois;
}

// Get jobs with filter and pagination
function get_job_with_filter($position_keyword, $location_keyword, $page_size, $page_number)
{
    global $host, $user, $pwd, $sql_db;
    $conn = @mysqli_connect($host, $user, $pwd, $sql_db);

    if (!$conn) {
        return null;
    }

    $offset = ($page_number - 1) * $page_size;
    $position_keyword = "%$position_keyword%";
    $location_keyword = "%$location_keyword%";

    $stmt = $conn->prepare("SELECT * FROM jobs WHERE position LIKE ? OR location LIKE ? LIMIT ? OFFSET ?");
    $stmt->bind_param("ssii", $position_keyword, $location_keyword, $page_size, $offset);
    $stmt->execute();
    $result = $stmt->get_result();

    $jobs = [];
    while ($row = $result->fetch_assoc()) {
        $jobs[] = $row;
    }

    $stmt->close();
    $conn->close();

    return $jobs;
}

// Create job
function create_job($job_ref_number, $title, $position, $location, $requirement_essential, $requirement_preferable, $salary_range, $description)
{
    global $host, $user, $pwd, $sql_db;
    $conn = @mysqli_connect($host, $user, $pwd, $sql_db);

    if (!$conn) {
        return null;
    }

    $created_at = $_POST[get_datetime_now()];
    $created_by = $_POST[$_SESSION['display_name']];

    $stmt = $conn->prepare("INSERT INTO jobs (job_ref_number, title, position, location, requirement_essential, requirement_preferable, salary_range, description, created_at, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssssssss", $job_ref_number, $title, $position, $location, $requirement_essential, $requirement_preferable, $salary_range, $description, $created_at, $created_by);
    $success = $stmt->execute();

    $stmt->close();
    $conn->close();

    return $success;
}

// Get jobs (basic)
function get_job($position_keyword, $location_keyword, $page_size, $page_number)
{
    global $host, $user, $pwd, $sql_db;
    $conn = @mysqli_connect($host, $user, $pwd, $sql_db);

    if (!$conn) {
        return null;
    }

    $offset = ($page_number - 1) * $page_size;
    $position_keyword = "%$position_keyword%";
    $location_keyword = "%$location_keyword%";

    $stmt = $conn->prepare("SELECT * FROM jobs WHERE position LIKE ? OR location LIKE ? LIMIT ? OFFSET ?");
    $stmt->bind_param("ssii", $position_keyword, $location_keyword, $page_size, $offset);
    $stmt->execute();
    $result = $stmt->get_result();

    $jobs = [];
    while ($row = $result->fetch_assoc()) {
        $jobs[] = $row;
    }

    $stmt->close();
    $conn->close();

    return $jobs;
}

// Update job
function update_job($id, $job_ref_number, $title, $position, $location, $requirement_essential, $requirement_preferable, $salary_range, $description, $created_at, $created_by, $updated_at, $updated_by)
{
    global $host, $user, $pwd, $sql_db;
    $conn = @mysqli_connect($host, $user, $pwd, $sql_db);

    if (!$conn) {
        return null;
    }

    $stmt = $conn->prepare("UPDATE jobs SET job_ref_lnumber = ?, title = ?, position = ?, location = ?, requirement_essential = ?, requirement_preferable = ?, salary_range = ?, description = ?, created_at = ?, created_by = ?, updated_at = ?, updated_by = ? WHERE id = ?");
    $stmt->bind_param("isssssssssssi", $job_ref_number, $title, $position, $location, $requirement_essential, $requirement_preferable, $salary_range, $description, $created_at, $created_by, $updated_at, $updated_by, $id);
    $success = $stmt->execute();

    $stmt->close();
    $conn->close();

    return $success;
}
?>