<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION["user_id"])) {
    // Redirect to the login page or take appropriate action
    header("Location: ../teacherdb/teacher_login.php");
    exit();
}

// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$database = "trms";

// Create a database connection
$conn = new mysqli($servername, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the subject_id and teacher_id from the form
    $subject_id = $_POST["subject_id"];
    $teacher_id = $_SESSION["user_id"]; // Assuming teacher_id is stored in the session

    // Validate the subject_id and teacher_id (add additional validation if needed)

    // Delete the subject from the selectedsubanddept table for the specific teacher
    $delete_sql = "DELETE FROM selectedsubanddept WHERE teacher_id = $teacher_id AND subject_id = $subject_id";
    
    if ($conn->query($delete_sql) === TRUE) {
        echo "Subject deleted successfully from selectedsubanddept.";
        header("Location: deptandsub.php");
    } else {
        echo "Error deleting subject from selectedsubanddept: " . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>
