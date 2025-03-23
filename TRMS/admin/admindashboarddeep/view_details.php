<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Details</title>
    <style>
        *{
    margin: 0;
    font-weight: bold;
    background: rgb(26, 244, 255);
    

        }

        img {
    max-width: 100px;
    max-height: 100px;
}
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        
            width: 800px;
    height: 500px;
    background: orange;
    margin-top: 100px;
    margin-left: 300px;
    text-align: center;
    box-shadow: 5px 10px 50px 12px rgb(2, 2, 7);
        }

        .container {
            text-align: center;
            max-width: 600px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 5px 10px 50px 12px rgb(2, 2, 7);
           
        }
    </style>
</head>
<body>

<?php
// view_details.php

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "trms";

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the 'id' parameter is set in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Query to retrieve distinct selected departments and subjects for the specific teacher
    $sql = "SELECT departments.department_name, subjects.subject_name
            FROM selectedsubanddept
            JOIN departments ON selectedsubanddept.department_id = departments.department_id
            JOIN subjects ON selectedsubanddept.subject_id = subjects.subject_id
            WHERE selectedsubanddept.teacher_id = $id";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Display selected departments and subjects
        echo "<div class='container'>";
        echo "<h2>Selected Departments and Subjects</h2><br>";

        $displayedDepartments = []; // Keep track of displayed departments

        while ($row = $result->fetch_assoc()) {
            $departmentName = $row["department_name"];
            $subjectName = $row["subject_name"];

            // Display department only if not displayed already
            if (!in_array($departmentName, $displayedDepartments)) {
                echo "<p>Department: " . $departmentName . "</p><br>";
                $displayedDepartments[] = $departmentName; // Add to the displayed departments array
            }

            echo "<p>Subject: " . $subjectName . "</p><br>";
        }

        echo "<p><a href='javascript:history.back()'>Go back</a></p>";
        echo "</div>";
    } else {
        echo "<p>No selected departments and subjects for this teacher.</p>";
        echo "<p><a href='javascript:history.back()'>Go back</a></p>";
        echo "</div>";
    }
} else {
    echo "<p>Teacher ID not specified</p>";
}

$conn->close();
?>

</body>
</html>
