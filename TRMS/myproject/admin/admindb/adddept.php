<?php
// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$database = "trms";

// Create a connection
$conn = new mysqli($servername, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Process the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $department = strtoupper($_POST["department"]);

    // Check if the department already exists in the database
    $checkDepartmentSql = "SELECT department_id FROM departments WHERE department_name = '$department'";
    $departmentResult = $conn->query($checkDepartmentSql);

    if ($departmentResult->num_rows > 0) {
        // Department already exists, update the department
        $departmentRow = $departmentResult->fetch_assoc();
        $department_id = $departmentRow["department_id"];

        // Update the department name
        $updateDepartmentSql = "UPDATE departments SET department_name = '$department' WHERE department_id = $department_id";
        if ($conn->query($updateDepartmentSql) === TRUE) {
            // Continue to insert subjects
            handleSubjectsInsertion($conn, $department_id);
        } else {
            echo "Error updating department: " . $conn->error;
        }
    } else {
        // Department does not exist, insert a new department
        $insertDepartmentSql = "INSERT INTO departments (department_name) VALUES ('$department')";

        if ($conn->query($insertDepartmentSql) === TRUE) {
            // Get the department ID
            $department_id = $conn->insert_id;

            // Insert subjects into the database (from dynamic textareas)
            handleSubjectsInsertion($conn, $department_id);
        } else {
            echo "Error inserting department: " . $conn->error;
        }
    }

    // Redirect to the admin dashboard or display a success message
    header("Location: ../admindashboarddeep/displaydeptandsub.php");
    exit;
}

// Function to handle subjects insertion
function handleSubjectsInsertion($conn, $department_id) {
    if (isset($_POST["subject"]) && is_array($_POST["subject"])) {
        $existingSubjects = [];

        // Get existing subjects for this department
        $existingSubjectsSql = "SELECT subject_name FROM subjects WHERE department_id = $department_id";
        $existingSubjectsResult = $conn->query($existingSubjectsSql);

        if ($existingSubjectsResult->num_rows > 0) {
            while ($row = $existingSubjectsResult->fetch_assoc()) {
                $existingSubjects[] = $row["subject_name"];
            }
        }

        foreach ($_POST["subject"] as $subject) {
            $subject = strtoupper(trim($subject)); // Convert to uppercase and remove leading/trailing whitespace
            if (!empty($subject) && !in_array($subject, $existingSubjects)) {
                $sql = "INSERT INTO subjects (department_id, subject_name) VALUES ('$department_id', '$subject')";
                $conn->query($sql);
            }
        }
    }
}

// Close the database connection
$conn->close();
?>
