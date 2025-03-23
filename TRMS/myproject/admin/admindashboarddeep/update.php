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

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $type = isset($_POST['type']) ? $_POST['type'] : '';
    $id = isset($_POST['id']) ? $_POST['id'] : '';

    // If update button is clicked
    if (isset($_POST['update'])) {
        $name = isset($_POST['name']) ? $_POST['name'] : '';

        // Update query
        if ($type == 'department') {
            $sql_update = "UPDATE departments SET department_name='$name' WHERE department_id=$id";
        } elseif ($type == 'subject') {
            $sql_update = "UPDATE subjects SET subject_name='$name' WHERE subject_id=$id";
        }

        if ($conn->query($sql_update) === TRUE) {
            echo "Record updated successfully.";
            // Redirect to display.php after successful update
            header("Location: displaydeptandsub.php");
            exit();
        } else {
            echo "Error updating record: " . $conn->error;
        }
    }

    // If delete button is clicked
    elseif (isset($_POST['delete'])) {
        // Delete query
        if ($type == 'department') {
            $sql_delete = "DELETE FROM departments WHERE department_id=$id";
        } elseif ($type == 'subject') {
            $sql_delete = "DELETE FROM subjects WHERE subject_id=$id";
        }

        if ($conn->query($sql_delete) === TRUE) {
            echo "Record deleted successfully.";
            // Redirect to display.php after successful deletion
            header("Location: displaydeptandsub.php");
            exit();
        } else {
            echo "Error deleting record: " . $conn->error;
        }
    }
}

// Close the database connection
$conn->close();
?>
