<?php
// Check if the 'id' parameter is set in the URL
if (isset($_GET['id'])) {
    // Define your database connection details
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "trms";

    // Create a database connection
    $conn = new mysqli($servername, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get the 'id' parameter from the URL
    $id = $_GET['id'];

    // Prepare and execute the SQL query to delete the row with the specified 'id'
    $sql = "DELETE FROM timetable_data WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        echo "Record deleted successfully";
        header("Location: teacherdashboard.php");
    } else {
        echo "Error deleting record: " . $conn->error;
    }

    // Close the database connection
    $conn->close();
} else {
    echo "Invalid request. Please provide a valid 'id' parameter.";
}
?>
