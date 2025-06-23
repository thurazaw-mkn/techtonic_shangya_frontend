<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
require_once(__DIR__ . '/../settings.php');
require_once(__DIR__ . '/../helpers/helpers.php');

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

// Get admin by username (for login attempt logic)
function get_admin_by_username($username) {
    global $host, $user, $pwd, $sql_db;
    $conn = @mysqli_connect($host, $user, $pwd, $sql_db);
    if (!$conn) return null;
    $stmt = $conn->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();
    $stmt->close();
    $conn->close();
    return $admin;
}

// Increment login attempts and set last attempt time
function increment_login_attempts($username) {
    global $host, $user, $pwd, $sql_db;
    $conn = @mysqli_connect($host, $user, $pwd, $sql_db);
    if (!$conn) return false;
    $stmt = $conn->prepare("UPDATE admins SET login_attempts = login_attempts + 1, last_login_attempt = NOW() WHERE username = ?");
    $stmt->bind_param("s", $username);
    $success = $stmt->execute();
    $stmt->close();
    $conn->close();
    return $success;
}

// Reset login attempts after successful login or password reset
function reset_login_attempts($username) {
    global $host, $user, $pwd, $sql_db;
    $conn = @mysqli_connect($host, $user, $pwd, $sql_db);
    if (!$conn) return false;
    $stmt = $conn->prepare("UPDATE admins SET login_attempts = 0, last_login_attempt = NULL WHERE username = ?");
    $stmt->bind_param("s", $username);
    $success = $stmt->execute();
    $stmt->close();
    $conn->close();
    return $success;
}

// Set new password for admin and reset attempts
function set_new_admin_password($username, $new_password) {
    global $host, $user, $pwd, $sql_db;
    $conn = @mysqli_connect($host, $user, $pwd, $sql_db);
    if (!$conn) return false;
    $hashed = hash('sha256', $new_password);
    $stmt = $conn->prepare("UPDATE admins SET password = ?, login_attempts = 0, last_login_attempt = NULL WHERE username = ?");
    $stmt->bind_param("ss", $hashed, $username);
    $success = $stmt->execute();
    $stmt->close();
    $conn->close();
    return $success;
}

function companies_signup($name, $industry, $email, $description, $head_office_address, $photo_str)
{
    global $host, $user, $pwd, $sql_db;
    $conn = @mysqli_connect($host, $user, $pwd, $sql_db);

    if (!$conn) {
        return "Database connection failed.";
    }

    // 1. Check duplicate name in companies
    $stmt = $conn->prepare("SELECT id FROM companies WHERE name = ?");
    if (!$stmt) {
        return "Prepare failed: " . $conn->error;
    }
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->close();
        $conn->close();
        return "Company name already exists.";
    }
    $stmt->close();

    // 1. Check duplicate email in admins
    $stmt = $conn->prepare("SELECT id FROM admins WHERE email = ?");
    if (!$stmt) {
        return "Prepare failed: " . $conn->error;
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->close();
        $conn->close();
        return "Company email already exists.";
    }
    $stmt->close();

    // 2. Insert into companies
    $created_at = date('Y-m-d H:i:s');
    $stmt = $conn->prepare("INSERT INTO companies (name, industry, description, head_office_address, created_at, is_deleted, photo_str) VALUES (?, ?, ?, ?, ?, false, ?)");
    if (!$stmt) {
        return "Prepare failed: " . $conn->error;
    }
    $stmt->bind_param("ssssss", $name, $industry, $description, $head_office_address, $created_at, $photo_str);
    if (!$stmt->execute()) {
        $error = $stmt->error;
        $stmt->close();
        $conn->close();
        return "Failed to create company: $error";
    }
    $company_id = $stmt->insert_id;
    $stmt->close();

    // 3. Generate username and password
    $username = strtolower(str_replace(' ', '', $name));
    $password = generate_random_words(5); // Helper function below
    $hashed_password = hash('sha256', $password);

    // 4. Insert into admins (with company_id and email)
    $stmt = $conn->prepare("INSERT INTO admins (username, password, email, role, display_name, company_id, created_at, is_deleted) VALUES (?, ?, ?, 'employer', ?, ?, ?, false)");
    if (!$stmt) {
        return "Prepare failed: " . $conn->error;
    }
    $stmt->bind_param("ssssss", $username, $hashed_password, $email, $name, $company_id, $created_at);
    if (!$stmt->execute()) {
        $stmt->close();
        $conn->close();
        return "Failed to create admin account.";
    }
    $stmt->close();

    // 5. Send email using PHPMailer
    $send_result = send_signup_email($email, $username, $password, $name);
    $conn->close();

    if ($send_result === true) {
        return "Signup complete, please check your email to get username and password.";
    } else {
        return "Signup complete, but email could not be sent. $send_result";
    }
}

// Helper function to generate 5 random words (letters only)
function generate_random_words($count = 5)
{
    return substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, $count);
}

function send_signup_email($to, $username, $password, $company_name)
{
    $subject = 'Your Company Admin Account';
    $body = "Dear $company_name,\n\nYour admin account has been created.\nUsername: $username\nPassword: $password\n\nPlease login and change your password after first login.";
    return send_email($to, $subject, $body);
}

// Get admins
function get_admins($search = "")
{
    global $host, $user, $pwd, $sql_db;
    $conn = @mysqli_connect($host, $user, $pwd, $sql_db);
    if (!$conn) return [];
    if ($search) {
        $search = "%$search%";
        $stmt = $conn->prepare("SELECT id, username, display_name, role, email FROM admins WHERE (role = 'admin' || role = 'superadmin') && is_deleted = false AND (username LIKE ? OR display_name LIKE ?)");
        $stmt->bind_param("ss", $search, $search);
    } else {
        $stmt = $conn->prepare("SELECT id, username, display_name, role, email FROM admins WHERE (role = 'admin' || role = 'superadmin') && is_deleted = false");
    }
    if (!$stmt) {
        die("Prepare failed: " . $conn->error); // Debugging line
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $admins = [];
    while ($row = $result->fetch_assoc()) {
        $admins[] = $row;
    }
    $stmt->close();
    $conn->close();
    return $admins;
}

// Get admins
function get_companies($search = "")
{
    global $host, $user, $pwd, $sql_db;
    $conn = @mysqli_connect($host, $user, $pwd, $sql_db);
    if (!$conn) return [];
    if ($search) {
        $search = "%$search%";
        $stmt = $conn->prepare("SELECT id, name, industry, head_office_address, description FROM companies WHERE is_deleted = false AND (name LIKE ? OR industry LIKE ?)");
        $stmt->bind_param("ss", $search, $search);
    } else {
        $stmt = $conn->prepare("SELECT id, name, industry, head_office_address, description FROM companies WHERE is_deleted = false");
    }
    if (!$stmt) {
        die("Prepare failed: " . $conn->error); // Debugging line
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $companies = [];
    while ($row = $result->fetch_assoc()) {
        $companies[] = $row;
    }
    $stmt->close();
    $conn->close();
    return $companies;
}

//delete admin (soft delete)
// function delete_admin_soft($username) {
//     global $host, $user, $pwd, $sql_db;
//     $conn = @mysqli_connect($host, $user, $pwd, $sql_db);
//     if (!$conn) return false;
//     $stmt = $conn->prepare("UPDATE admins SET is_delete = 1 WHERE username = ?");
//     $stmt->bind_param("s", $username);
//     $success = $stmt->execute();
//     $stmt->close();
//     $conn->close();
//     return $success;
// }

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
function get_eoi_with_filter($username, $job_reference_number, $first_name, $last_name, $sort_field = 'created_at', $sort_order = 'desc') {
    global $host, $user, $pwd, $sql_db;
    $conn = @mysqli_connect($host, $user, $pwd, $sql_db);

    $allowed_fields = ['created_at', 'first_name', 'last_name', 'status'];
    $allowed_orders = ['asc', 'desc'];
    if (!in_array($sort_field, $allowed_fields)) $sort_field = 'created_at';
    if (!in_array(strtolower($sort_order), $allowed_orders)) $sort_order = 'desc';

    // ...existing filter logic...

    $sql = "SELECT eoi.* FROM eoi
            JOIN jobs ON eoi.job_ref_number = jobs.job_ref_number
            JOIN admins ON jobs.company_id = admins.company_id
            WHERE admins.username = ?";
    $params = [$username];
    $types = "s";

    if ($job_reference_number !== '') {
        $sql .= " AND eoi.job_ref_number = ?";
        $params[] = $job_reference_number;
        $types .= "s";
    }
    if ($first_name !== '') {
        $sql .= " AND eoi.first_name LIKE ?";
        $params[] = "%$first_name%";
        $types .= "s";
    }
    if ($last_name !== '') {
        $sql .= " AND eoi.last_name LIKE ?";
        $params[] = "%$last_name%";
        $types .= "s";
    }

    $sql .= " ORDER BY eoi.$sort_field $sort_order";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
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
function create_job($username, $job_ref_number, $title, $position, $location, $requirement_essential, $requirement_preferable, $salary_range, $description, $created_by)
{
    global $host, $user, $pwd, $sql_db;
    $conn = @mysqli_connect($host, $user, $pwd, $sql_db);

    if (!$conn) {
        return null;
    }

    // Get company_id from admins table using username
    $stmt = $conn->prepare("SELECT company_id FROM admins WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($company_id);
    if (!$stmt->fetch()) {
        $stmt->close();
        $conn->close();
        return null; // No such user or company_id
    }
    $stmt->close();

    $created_at = get_datetime_now();

    $stmt = $conn->prepare("INSERT INTO jobs (company_id, job_ref_number, title, position, location, requirement_essential, requirement_preferable, salary_range, description, created_at, created_by, is_deleted) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, false)");
    if (!$stmt) {
        $error = $conn->error;
        $conn->close();
        return "Prepare failed: $error";
    }
    $stmt->bind_param("issssssssss", $company_id, $job_ref_number, $title, $position, $location, $requirement_essential, $requirement_preferable, $salary_range, $description, $created_at, $created_by);
    $success = $stmt->execute();

    if (!$success) {
        $error = $stmt->error;
        $stmt->close();
        $conn->close();
        return "Insert failed: $error";
    }
    $stmt->close();
    $conn->close();
    return $success;
}

// Delete all EOIs for a job reference number
function delete_eois_by_job_ref($job_ref) {
    global $host, $user, $pwd, $sql_db;
    $conn = @mysqli_connect($host, $user, $pwd, $sql_db);
    if (!$conn) return false;
    $stmt = $conn->prepare("DELETE FROM eoi WHERE job_ref_number = ?");
    $stmt->bind_param("s", $job_ref);
    $success = $stmt->execute();
    $stmt->close();
    $conn->close();
    return $success;
}

// Delete a single EOI by eoi_number
function delete_eoi_by_number($eoi_number) {
    global $host, $user, $pwd, $sql_db;
    $conn = @mysqli_connect($host, $user, $pwd, $sql_db);
    if (!$conn) return false;
    $stmt = $conn->prepare("DELETE FROM eoi WHERE eoi_number = ?");
    $stmt->bind_param("i", $eoi_number);
    $success = $stmt->execute();
    $stmt->close();
    $conn->close();
    return $success;
}

// Change EOI status and notify applicant
function update_eoi_status_and_notify($eoi_number, $new_status) {
    global $host, $user, $pwd, $sql_db;
    $conn = @mysqli_connect($host, $user, $pwd, $sql_db);
    if (!$conn) return "DB connection failed";
    // Update status
    $stmt = $conn->prepare("UPDATE eoi SET status = ? WHERE eoi_number = ?");
    $stmt->bind_param("si", $new_status, $eoi_number);
    if (!$stmt->execute()) {
        $stmt->close();
        $conn->close();
        return false;
    }
    $stmt->close();
    // Fetch applicant email and name
    $stmt = $conn->prepare("SELECT email, first_name, last_name FROM eoi WHERE eoi_number = ?");
    $stmt->bind_param("i", $eoi_number);
    $stmt->execute();
    $stmt->bind_result($email, $first_name, $last_name);
    $notified = false;
    if ($stmt->fetch()) {
        $subject = "Your EOI Status Has Been Updated";
        $body = "Dear $first_name $last_name,\n\nYour Expression of Interest status has been updated to: $new_status.\n\nThank you for your application.\n\nBest regards,\nShangya Techtonic";
        send_email($email, $subject, $body);
        $notified = true;
    }
    $stmt->close();
    $conn->close();
    return $notified;
}

// Get jobs (basic)
function get_job($position_keyword = '', $location_keyword = '', $page_size = 10, $page_number = 1)
{
    global $host, $user, $pwd, $sql_db;
    $conn = @mysqli_connect($host, $user, $pwd, $sql_db);

    if (!$conn) {
        return null;
    }

    $offset = ($page_number - 1) * $page_size;
    $where = "jobs.is_deleted = false";
    $params = [];
    $types = "";

    if ($position_keyword !== '') {
        $where .= " AND jobs.position LIKE ?";
        $params[] = "%$position_keyword%";
        $types .= "s";
    }
    if ($location_keyword !== '') {
        $where .= " AND jobs.location LIKE ?";
        $params[] = "%$location_keyword%";
        $types .= "s";
    }

    $sql = "SELECT jobs.*, companies.name AS company_name 
            FROM jobs 
            LEFT JOIN companies ON jobs.company_id = companies.id 
            WHERE $where 
            LIMIT ? OFFSET ?";
    $params[] = $page_size;
    $params[] = $offset;
    $types .= "ii";

    $stmt = $conn->prepare($sql);

    // Dynamically bind params
    $stmt->bind_param($types, ...$params);

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

// process eoi
function process_eoi($job_ref_number, $first_name, $last_name, $address_street, $address_town, $address_state, $address_postcode, $email, $phone, $skills, $other_skills, $dob, $gender)
{
    global $host, $user, $pwd, $sql_db;
    $conn = @mysqli_connect($host, $user, $pwd, $sql_db);

    if (!$conn) {
        return null;
    }

    // 0. Create EOI table if it does not exist
    $createTableSQL = "
        CREATE TABLE IF NOT EXISTS eoi (
            eoi_number INT AUTO_INCREMENT PRIMARY KEY,
            job_ref_number VARCHAR(32) NOT NULL,
            first_name VARCHAR(64) NOT NULL,
            last_name VARCHAR(64) NOT NULL,
            address_street VARCHAR(128) NOT NULL,
            address_town VARCHAR(64) NOT NULL,
            address_state VARCHAR(64) NOT NULL,
            address_postcode VARCHAR(16) NOT NULL,
            email VARCHAR(128) NOT NULL,
            phone VARCHAR(32) NOT NULL,
            skills TEXT,
            other_skills TEXT,
            status VARCHAR(32) DEFAULT 'New',
            created_at DATETIME,
            created_by VARCHAR(128),
            updated_at DATETIME,
            updated_by VARCHAR(128),
            dob VARCHAR(32),
            gender VARCHAR(16),
            is_deleted BOOLEAN DEFAULT FALSE
        )
    ";
    $conn->query($createTableSQL);

    // Get company email by joining jobs and companies
    $stmt = $conn->prepare(
        "SELECT admins.email 
         FROM jobs 
         JOIN admins ON jobs.company_id = admins.company_id 
         WHERE jobs.job_ref_number = ?"
    );
    if (!$stmt) {
        $error = $conn->error;
        $conn->close();
        return "Prepare failed: $error";
    }
    $stmt->bind_param("s", $job_ref_number);
    $stmt->execute();
    $stmt->bind_result($company_email);
    if (!$stmt->fetch() || empty($company_email)) {
        $stmt->close();
        $conn->close();
        return "Invalid Job Reference Number or company email not found.";
    }
    $stmt->close();

    // Insert EOI
    $created_at = get_datetime_now();
    $created_by = $first_name . ' ' . $last_name;
    $status = 'New';

    $stmt = $conn->prepare("INSERT INTO eoi (job_ref_number, first_name, last_name, address_street, address_town, address_state, address_postcode, email, phone, skills, other_skills, status, created_at, created_by, dob, gender, is_deleted) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, false)");
    if (!$stmt) {
        $error = $conn->error;
        $conn->close();
        return "Prepare failed: $error";
    }
    $stmt->bind_param(
        "ssssssssssssssss",
        $job_ref_number,
        $first_name,
        $last_name,
        $address_street,
        $address_town,
        $address_state,
        $address_postcode,
        $email,
        $phone,
        $skills,
        $other_skills,
        $status,
        $created_at,
        $created_by,
        $dob,
        $gender
    );
    $success = $stmt->execute();

    if (!$success) {
        $error = $stmt->error;
        $stmt->close();
        $conn->close();
        return "Insert failed: $error";
    }
    $stmt->close();
    $conn->close();

    // Send email to applicant
    $subject = "Your Expression of Interest Submission";
    $body = "Dear $first_name $last_name,\n\nThank you for your application for job reference $job_ref_number. We have received your EOI and will be in touch if you are shortlisted.\n\nBest regards,\nShangya Techtonic";
    send_email($email, $subject, $body);

    // Send email to company
    $subject_company = "New EOI Received for Job $job_ref_number";
    $body_company = "A new Expression of Interest has been submitted for job reference $job_ref_number.\n\nApplicant: $first_name $last_name\nEmail: $email\nPhone: $phone\n\nPlease log in to your dashboard to review.";
    send_email($company_email, $subject_company, $body_company);

    return true;
}

//get job
function get_job_by_company($username)
{
    global $host, $user, $pwd, $sql_db;
    $conn = @mysqli_connect($host, $user, $pwd, $sql_db);

    if (!$conn) {
        return null;
    }

    // 1. Get company_id from admins table using username
    $stmt = $conn->prepare("SELECT company_id FROM admins WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($company_id);
    if (!$stmt->fetch()) {
        $stmt->close();
        $conn->close();
        return []; // No such user or company_id
    }
    $stmt->close();

    // 2. Get jobs for this company_id, including photo_str from companies
    $stmt = $conn->prepare(
        "SELECT jobs.*, companies.photo_str as company_photo_str
         FROM jobs 
         JOIN companies ON jobs.company_id = companies.id 
         WHERE jobs.company_id = ? AND jobs.is_deleted = false"
    );
    $stmt->bind_param("i", $company_id);
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
