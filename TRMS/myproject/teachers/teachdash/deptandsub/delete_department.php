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
    // Get the department_id and teacher_id from the form
    $department_id = $_POST["department_id"];
    $teacher_id = $_SESSION["user_id"]; // Assuming teacher_id is stored in the session

    // Validate the department_id and teacher_id (add additional validation if needed)

    // Delete the department from the selectedsubanddept table for the specific teacher
    $delete_sql = "DELETE FROM selectedsubanddept WHERE teacher_id = $teacher_id AND department_id = $department_id";
    
    if ($conn->query($delete_sql) === TRUE) {
        echo "Department deleted successfully from selectedsubanddept.";
        header("Location: deptandsub.php");
    } else {
        echo "Error deleting department from selectedsubanddept: " . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>
