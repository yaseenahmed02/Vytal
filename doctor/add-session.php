<?php
// Start the session
session_start();

// Include the database connection file
include("../connection.php");

// Check if the user is logged in and is a doctor
if (!isset($_SESSION["user"]) || $_SESSION['usertype'] != 'd') {
    // Redirect to the login page if the user is not logged in or not a doctor
    header("Location: ../login.php");
    exit;
}

// Function to sanitize input data
function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input data
    $docid = test_input($_POST["docid"]); // Assuming this is actually the doctor's ID and should be an integer
    $title = test_input($_POST["title"]);
    $scheduledate = test_input($_POST["date"]);
    $scheduletime = test_input($_POST["time"]);
    $nop = test_input($_POST["nop"]);

    // Assuming `docid` should be an integer, validate it
    $docid = filter_var($docid, FILTER_VALIDATE_INT);

    // Prepare an insert statement
    $query = $database->prepare("INSERT INTO schedule (docid, title, scheduledate, scheduletime, nop) VALUES (?, ?, ?, ?, ?)");

    // Bind parameters
    $query->bind_param("isssi", $docid, $title, $scheduledate, $scheduletime, $nop);

    // Attempt to execute the prepared statement
    if ($query->execute()) {
        // Redirect to schedule page with success message
        header("Location: schedule.php?action=session-added&title=" . urlencode($title));
    } else {
        // Redirect back to the form or display an error message
        echo "Error: " . $query->error;
    }

    // Close statement
    $query->close();
}

// Close database connection
$database->close();
