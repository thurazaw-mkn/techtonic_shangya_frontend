<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Prevent direct access: only allow POST with required data
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['job_ref_number'])) {
    header('Location: apply.php');
    exit;
}

require_once(__DIR__ . '/services/query.php');
require_once(__DIR__ . '/helpers/helpers.php');

// ...rest of your code...

require_once(__DIR__ . '/services/query.php');
require_once(__DIR__ . '/helpers/helpers.php');

// Helper: Malaysian postcode by state (partial, add more as needed)
$state_postcode_map = [
    "Johor" => "/^8\d{3}|^7\d{3}/",
    "Kedah" => "/^05\d{2}|^06\d{2}|^08\d{2}|^09\d{2}/",
    "Kelantan" => "/^15\d{2}|^16\d{2}/",
    "Melaka" => "/^75\d{2}|^76\d{2}/",
    "Negeri Sembilan" => "/^70\d{2}|^71\d{2}|^72\d{2}|^73\d{2}/",
    "Pahang" => "/^26\d{2}|^27\d{2}|^28\d{2}/",
    "Penang" => "/^10\d{2}|^11\d{2}|^12\d{2}/",
    "Perak" => "/^30\d{2}|^31\d{2}|^32\d{2}|^33\d{2}|^34\d{2}|^35\d{2}/",
    "Perlis" => "/^01\d{2}/",
    "Sabah" => "/^88\d{2}|^89\d{2}/",
    "Sarawak" => "/^93\d{2}|^94\d{2}|^96\d{2}|^98\d{2}/",
    "Selangor" => "/^40\d{2}|^41\d{2}|^42\d{2}|^43\d{2}|^47\d{2}|^48\d{2}|^62\d{2}/",
    "Terengganu" => "/^20\d{2}|^21\d{2}|^22\d{2}|^23\d{2}|^24\d{2}/",
    "Kuala Lumpur" => "/^50\d{2}|^51\d{2}|^52\d{2}|^53\d{2}|^54\d{2}|^55\d{2}|^56\d{2}|^57\d{2}|^58\d{2}|^59\d{2}/",
    "Labuan" => "/^87\d{2}/",
    "Putrajaya" => "/^62\d{2}/"
];

// Collect POST data
$job_ref_number = $_POST['job_ref_number'] ?? '';
$first_name = $_POST['first_name'] ?? '';
$last_name = $_POST['last_name'] ?? '';
$dob = $_POST['dob'] ?? '';
$gender = $_POST['gender'] ?? '';
$address_street = $_POST['address_street'] ?? '';
$address_town = $_POST['address_town'] ?? '';
$address_state = $_POST['address_state'] ?? '';
$address_postcode = $_POST['address_postcode'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$skills = isset($_POST['skills']) ? implode(',', $_POST['skills']) : '';
$other_skills = $_POST['other_skills'] ?? '';
$created_at = get_datetime_now();
$created_by = $first_name . ' ' . $last_name;
$status = 'New';

// Validation
$errors = [];

// job_ref_number: exactly 5 alphanumeric
if (!preg_match('/^[A-Za-z0-9]{5}$/', $job_ref_number)) {
    $errors[] = "Job Reference Number must be exactly 5 alphanumeric characters.";
}

// first_name/last_name: max 20 alpha
if (!preg_match('/^[A-Za-z]{1,20}$/', $first_name)) {
    $errors[] = "First name must be max 20 alphabetic characters.";
}
if (!preg_match('/^[A-Za-z]{1,20}$/', $last_name)) {
    $errors[] = "Last name must be max 20 alphabetic characters.";
}

// dob: dd/mm/yyyy, age 15-80
if (!preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $dob)) {
    $errors[] = "Date of Birth must be in dd/mm/yyyy format.";
} else {
    [$day, $month, $year] = explode('/', $dob);
    if (!checkdate((int)$month, (int)$day, (int)$year)) {
        $errors[] = "Date of Birth is not a valid date.";
    } else {
        $age = date('Y') - $year - ((date('md') < $month . $day) ? 1 : 0);
        if ($age < 15 || $age > 80) {
            $errors[] = "Age must be between 15 and 80.";
        }
    }
}

// gender: required
if (!in_array($gender, ['male', 'female', 'other'])) {
    $errors[] = "Gender must be selected.";
}

// address_street: max 40
if (strlen($address_street) > 40) {
    $errors[] = "Street Address must be max 40 characters.";
}

// address_town: max 40
if (strlen($address_town) > 40) {
    $errors[] = "Suburb/Town must be max 40 characters.";
}

// address_state: must be valid
$valid_states = array_keys($state_postcode_map);
if (!in_array($address_state, $valid_states)) {
    $errors[] = "State must be a valid Malaysian state.";
}

// address_postcode: exactly 4 digits, matches state
if (!preg_match('/^\d{4}$/', $address_postcode)) {
    $errors[] = "Postcode must be exactly 4 digits.";
} elseif (!empty($address_state) && isset($state_postcode_map[$address_state]) && !preg_match($state_postcode_map[$address_state], $address_postcode)) {
    $errors[] = "Postcode does not match the selected state.";
}

// email: valid format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Email address is not valid.";
}

// phone: 8-12 digits or spaces
if (!preg_match('/^[\d\s]{8,12}$/', $phone)) {
    $errors[] = "Phone number must be 8 to 12 digits or spaces.";
}

// other_skills: not empty if "other" checked
if (strpos($skills, 'other') !== false && trim($other_skills) === '') {
    $errors[] = "Please describe your other skills.";
}

// If errors, show and stop
if ($errors) {
    echo "<h2>Submission Error</h2><ul>";
    foreach ($errors as $error) {
        echo "<li>" . htmlspecialchars($error) . "</li>";
    }
    echo "</ul><a href='apply.php'>Go Back</a>";
    exit;
}

// If valid, insert
$success = process_eoi(
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
    $dob,
    $gender
);

if ($success === true) {
    echo "<h2>Application submitted successfully!</h2><a href='jobs.php'>Back to Jobs</a>";
} else {
    echo "<h2>Submission failed:</h2><p>" . htmlspecialchars($success) . "</p><a href='apply.php'>Go Back</a>";
}
?>