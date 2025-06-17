<?php
// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection
    $conn = new mysqli("localhost", "username", "password", "database_name");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get POST data
    $job_ref_number = $_POST['job_ref_number'];
    $title = $_POST['title'];
    $position = $_POST['position'];
    $location = $_POST['location'];
    $requirement_essential = $_POST['requirement_essential'];
    $requirement_preferable = $_POST['requirement_preferable'];
    $salary_range = $_POST['salary_range'];
    $description = $_POST['description'];
    $created_at = $_POST['created_at'];
    $created_by = $_POST['created_by'];
    $updated_at = $_POST['updated_at'];
    $updated_by = $_POST['updated_by'];
    $job_id = $_POST['job_id']; // Optional if not auto-increment

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO jobs (job_ref_lnumber, title, position, location, requirement_essential, requirement_preferable, salary_range, description, created_at, created_by, updated_at, updated_by, job_id)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssssssssssi", $job_ref_number, $title, $position, $location, $requirement_essential, $requirement_preferable, $salary_range, $description, $created_at, $created_by, $updated_at, $updated_by, $job_id);

    if ($stmt->execute()) {
        echo "Job submitted successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!-- HTML Form -->
<!DOCTYPE html>
<html>
<head>
    <title>Submit Job</title>
    <link rel="stylesheet" type="text/css" href="styles/create_job.css">

</head>
<body>
    <h1>Submit Job Posting</h1>
    <form method="POST" action="">
        <label>Job Ref Number: <input type="number" name="job_ref_number" required></label><br>
        <label>Title: <input type="text" name="title" required></label><br>
        <label>Position: <input type="text" name="position" required></label><br>
        <label>Location: <input type="text" name="location" required></label><br>
        <label>Essential Requirements:<br><textarea name="requirement_essential" required></textarea></label><br>
        <label>Preferable Requirements:<br><textarea name="requirement_preferable"></textarea></label><br>
        <label>Salary Range: <input type="text" name="salary_range" required></label><br>
        <label>Description:<br><textarea name="description" required></textarea></label><br>
        <label>Created At: <input type="datetime-local" name="created_at" required></label><br>
        <label>Created By: <input type="text" name="created_by" required></label><br>
        <label>Updated At: <input type="datetime-local" name="updated_at"></label><br>
        <label>Updated By: <input type="text" name="updated_by"></label><br>
        <label>Job ID: <input type="number" name="job_id" required></label><br><br>
        <button type="submit">Create Job</button>
    </form>
</body>
</html>
