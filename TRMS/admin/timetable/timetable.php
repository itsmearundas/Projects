<!DOCTYPE html>
<html>
<head>
    <title>ADMIN TIMETABLE</title>
    <meta charset="UTF-8"/>
    <link rel="stylesheet" href="timetable.css"/>
</head>
<body class="teachbackground" background="#">
<div class="wholesession">
<div class="navigators">
        <div class="homelogo">
            <a style="text-decoration: none;" href="../../index.html"> <img src="../../logoss/trmslogo.jpg" alt="TRMS" height="150" width="150"/></a>
        </div>
        <div class="navdb">
            <a style="text-decoration: none;" href="../admindashboard.php">  <h3 id="adt2">DASHBOARD</h3></a><br>
        </div>
        <div class="navhelp">
            <a style="text-decoration: none;" href="#">  <h3 id="adt4">HELP</h3></a><br>
        </div>
        <div class="search">
            <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <input class="search_query" type="text" id="search_query" name="search_query">
                <button class="search_query_submit" type="submit" value="Search" id="search_query_submit">SEARCH</button>
            </form>
        </div>
    </div>
    <div class="searchphp">
    <?php
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

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the search query from the form
    $search_query = $_POST["search_query"];

    if ($search_query == "all") {
        // Prepare and execute the SQL query to fetch all teachers with associated departments and subjects
        $sql = "SELECT DISTINCT t.id as teacher_id, t.fullname as teacher_name, t.phno as teacher_phone, t.email as teacher_email, t.gender as teacher_gender, t.dob as teacher_dob, t.address as teacher_address,
                d.department_name, s.subject_name
                FROM teachers t
                INNER JOIN selectedsubanddept sd ON t.id = sd.teacher_id
                INNER JOIN departments d ON sd.department_id = d.department_id
                INNER JOIN subjects s ON sd.subject_id = s.subject_id";
    } else {
        // Prepare and execute the SQL query for search
        $sql = "SELECT DISTINCT t.id as teacher_id, t.fullname as teacher_name, t.phno as teacher_phone, t.email as teacher_email, t.gender as teacher_gender, t.dob as teacher_dob, t.address as teacher_address,
                d.department_name, s.subject_name
                FROM teachers t
                LEFT JOIN selectedsubanddept sd ON t.id = sd.teacher_id
                LEFT JOIN departments d ON sd.department_id = d.department_id
                LEFT JOIN subjects s ON sd.subject_id = s.subject_id
                WHERE t.fullname LIKE '%$search_query%' OR
                d.department_name LIKE '%$search_query%' OR
                s.subject_name LIKE '%$search_query%'";
    }

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Initialize arrays to keep track of displayed teachers
        $displayedTeachers = [];

        // Display all teacher details matching the search query or all teachers
        while ($row = $result->fetch_assoc()) {
            // Check if this teacher has been displayed already
            if (!in_array($row["teacher_id"], $displayedTeachers)) {
                echo "<div class='teacherdetails'>";
                echo "<h3>Teacher Details</h3>";
                echo "<ul>";
                echo "<li><strong>Teacher Name:</strong> " . $row["teacher_name"] . "</li>";
                echo "<li><strong>Teacher Phone:</strong> " . $row["teacher_phone"] . "</li>";
                echo "<li><strong>Teacher Email:</strong> " . $row["teacher_email"] . "</li>";
                echo "<li><strong>Teacher Gender:</strong> " . $row["teacher_gender"] . "</li>";
                echo "<li><strong>Teacher DOB:</strong> " . $row["teacher_dob"] . "</li>";
                echo "<li><strong>Teacher Address:</strong> " . $row["teacher_address"] . "</li>";
                
                // Fetch and display complete departments for the current teacher
                $teacherDepartmentsResult = $conn->query("SELECT DISTINCT d.department_name FROM departments d
                                                         JOIN selectedsubanddept sd ON d.department_id = sd.department_id
                                                         WHERE sd.teacher_id = " . $row["teacher_id"]);

                $teacherDepartments = [];
                while ($departmentRow = $teacherDepartmentsResult->fetch_assoc()) {
                    $teacherDepartments[] = $departmentRow["department_name"];
                }

                echo "<strong>Departments:</strong> " . implode(", ", $teacherDepartments) . "<br>";
                
                // Fetch and display subjects for the current teacher
                echo "<strong>Subjects:</strong>";
                $subjectResult = $conn->query("SELECT DISTINCT s.subject_name FROM subjects s
                                              JOIN selectedsubanddept sd ON s.subject_id = sd.subject_id
                                              WHERE sd.teacher_id = " . $row["teacher_id"]);

                $subjects = [];
                while ($subjectRow = $subjectResult->fetch_assoc()) {
                    $subjects[] = $subjectRow["subject_name"];
                }

                echo implode(", ", $subjects);
                echo "<br><br>";

                echo "</ul>";

                ?>


                <div>
                    <div>
                    <a href="ttassign/ttassign.php?teacher_id=<?php echo urlencode($row["teacher_id"]); ?>&teacher_departments=<?php echo urlencode($row["department_name"]); ?>&teacher_subjects=<?php echo urlencode($row["subject_name"]); ?>">

    <button class="slogin" type="submit" value="View Profile" name="LogIn" id="slogin">VIEW</button>
</a>

                    </div>
                </div>
                <?php

                echo "<br></div>";
            }

            // Add teacher_id to the displayedTeachers array
            $displayedTeachers[] = $row["teacher_id"];
        }
    } else {
        echo "No results found.";
    }
}

// Close the database connection
$conn->close();
?>

    </div>
</div>
</body>
</html>
