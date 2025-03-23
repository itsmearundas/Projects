<!DOCTYPE html>
<html>
<head>
     <title>DEPARTMENTANDSUBJECT</title>
     <meta charset="UTF-8"/>
     <link rel="stylesheet" href="deptandsub.css" />
</head>
<body class="teachbackground" style="background-image: url('../teachimg/1teacherback.jpg');">
    <div class="wholesession">
    <div class="navigators">
        <div class="l1">
            <a href="../../../index.html"> <img src="../../../logoss/trmslogo.jpg" alt="TRMS" height="100" width="100" /></a>
        </div>          
        <div class="n5">
            <a style="text-decoration: none;" href="#" >  <h4 id="adt5">HELP</h4></a><br>
        </div>
        <div class="n4">
            <a style="text-decoration: none;" href="../../teachdash/teacherdashboard.php" > <h4 id="adt4">GO BACK</h4></a><br>            
        </div>
       
    </div>    
        
                                            <div class="profile">
               
                
                   
                       
                       <div class="aboutquali">
                       <?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION["user_id"])) {
    // Redirect to the login page or take appropriate action
    header("Location: ../teacherdb/teacher_login.php"); // Change 'login.php' to your login page URL
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

// Retrieve teacher details based on the user_id from the session
$teacher_id = $_SESSION["user_id"];

$sql = "SELECT t.fullname AS teacher_name, s.department_id, d.department_name, s.subject_id, sb.subject_name
        FROM teachers t
        INNER JOIN selectedsubanddept s ON t.id = s.teacher_id
        INNER JOIN departments d ON s.department_id = d.department_id
        INNER JOIN subjects sb ON s.subject_id = sb.subject_id
        WHERE t.id = $teacher_id
        ORDER BY d.department_id, sb.subject_id";

$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error); // Add error handling here
}

if ($result->num_rows > 0) {
    $teacher_name = "";
    $current_department = "";

    while ($row = $result->fetch_assoc()) {
        $department_id = $row["department_id"];
        $department_name = $row["department_name"];
        $subject_id = $row["subject_id"];
        $subject_name = $row["subject_name"];

        // Display the teacher's name if it hasn't been displayed yet
        if (empty($teacher_name)) {
            $teacher_name = $row["teacher_name"];
            echo "<h2>Teacher: $teacher_name</h2>";
        }

        // Display the department if it hasn't been displayed yet
        if ($department_id !== $current_department) {
            echo "<h2>Department: $department_name     :    ID: $department_id</h2>";
            $current_department = $department_id;
            
            // Add delete button for each department
            echo "<form method='post' action='delete_department.php'>"; // create a separate PHP file for delete_department.php
            echo "<input type='hidden' name='department_id' value='$department_id'>";
            echo "<input type='submit' value='Delete Department'>";
            echo "</form>";
        }

        // Display the subject under the current department
        echo "<p>Subject ID: $subject_id, Subject Name: $subject_name</p>";
        
        // Add delete button for each subject
        echo "<form method='post' action='delete_subject.php'>"; // create a separate PHP file for delete_subject.php
        echo "<input type='hidden' name='subject_id' value='$subject_id'>";
        echo "<input type='submit' value='Delete Subject'>";
        echo "</form>";
    }
} else {
    echo "No results found for the teacher.";
}

// Close the database connection
$conn->close();
?>



                         </div>      <!--aboutquali-->
                    
                        <div class="qualification">

                        <?php
// Initialize $selectedDepartmentId to an empty value
$selectedDepartmentId = "";

// Check if the department selection form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["department_submit"])) {
    // Get the selected department
    $selectedDepartmentId = $_POST["department"];
}

// ... (previous code)

?>

<h1>Select Department and Subject</h1>
<form action="" method="POST">
    <h2><label for="department">Select Department:</label></h2>
    <select name="department" id="department">
        <option value="">Select a Department</option>
        <?php
        // Connect to your database (move this part outside the form)
        $servername = "localhost";
        $username = "root";
        $password = "";
        $database = "trms";

        $conn = new mysqli($servername, $username, $password, $database);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Fetch departments from the 'departments' table
        $query = "SELECT * FROM departments";
        $result = $conn->query($query);

        while ($row = $result->fetch_assoc()) {
            $departmentId = $row['department_id'];
            $departmentName = $row['department_name'];
            $selected = ($departmentId == $selectedDepartmentId) ? "selected" : "";
            echo "<option value='$departmentId' $selected>$departmentName</option>";
        }

        // Close the database connection
        $conn->close();
        ?>
    </select>
    <input type="submit" name="department_submit" value="Submit">
</form>

<?php
// Display available subjects as checkboxes if they exist
if (isset($selectedDepartmentId)) {
    // Connect to your database again
    $conn = new mysqli($servername, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch subjects based on the selected department
    $query = "SELECT * FROM subjects WHERE department_id = '$selectedDepartmentId'";
    $result = $conn->query($query);

    $availableSubjects = array();
    while ($row = $result->fetch_assoc()) {
        $subjectId = $row['subject_id'];
        $subjectName = $row['subject_name'];
        $availableSubjects[$subjectId] = $subjectName;
    }

    $conn->close();

    echo "<h2>Available Subjects:</h2>";
    echo "<form action='' method='POST'>";
    foreach ($availableSubjects as $subjectId => $subjectName) {
        echo "<label><input type='checkbox' name='subject[]' value='$subjectId'>$subjectName</label><br>";
    }
    echo "<input type='hidden' name='selected_department_id' value='$selectedDepartmentId'>";
    echo "<input type='submit' name='subject_submit' value='Submit Subjects'>";
    echo "</form>";
}
// Check if the form has been submitted successfully

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["subject_submit"])) {
    $selectedSubjects = isset($_POST["subject"]) ? $_POST["subject"] : array();

    if (empty($selectedSubjects)) {
        echo "<p>No subjects selected.</p>";
    } else {
        // Check if the session variable 'user_id' is set
        if (isset($_SESSION["user_id"])) {
            $teacherId = $_SESSION["user_id"];

            // Connect to your database again
            $conn = new mysqli($servername, $username, $password, $database);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Retrieve teacher details based on teacher_id
            $teacherQuery = "SELECT * FROM teachers WHERE id = '$teacherId'";
            $teacherResult = $conn->query($teacherQuery);

            if ($teacherResult->num_rows == 1) {
                $teacherRow = $teacherResult->fetch_assoc();
                $teacherName = $teacherRow['fullname'];
                $teacherPhone = $teacherRow['phno'];
                $teacherEmail = $teacherRow['email'];
                $teacherGender = $teacherRow['gender'];
                $teacherDOB = $teacherRow['dob'];
                $teacherAddress = $teacherRow['address'];
                

                // Retrieve the selected department ID from the hidden input
                $selectedDepartmentId = $_POST["selected_department_id"];

                // Check if the combination of teacher, department, and subject already exists
                foreach ($selectedSubjects as $subjectId) {
                    $checkQuery = "SELECT * FROM selectedsubanddept WHERE teacher_id = '$teacherId' AND department_id = '$selectedDepartmentId' AND subject_id = '$subjectId'";
                    $checkResult = $conn->query($checkQuery);

                    if ($checkResult->num_rows == 0) {
                        // Fetch department and subject details
                        $deptQuery = "SELECT * FROM departments WHERE department_id = '$selectedDepartmentId'";
                        $deptResult = $conn->query($deptQuery);
                        $subjectQuery = "SELECT * FROM subjects WHERE subject_id = '$subjectId'";
                        $subjectResult = $conn->query($subjectQuery);

                        if ($deptResult->num_rows == 1 && $subjectResult->num_rows == 1) {
                            $deptRow = $deptResult->fetch_assoc();
                            $subjectRow = $subjectResult->fetch_assoc();
                            $departmentName = $deptRow['department_name'];
                            $subjectName = $subjectRow['subject_name'];

                            // Insert data into 'selectedsubanddept' table
                            $query = "INSERT INTO selectedsubanddept (
                                teacher_id, department_id, subject_id
                            ) VALUES (
                                '$teacherId', '$selectedDepartmentId', '$subjectId'
                            )";

                            if ($conn->query($query) !== TRUE) {
                                // Handle the error here
                                echo "Error inserting data: " . $conn->error;
                            }
                        }
                    } else {
                        // Subject already selected
                        echo "<br>You have already selected the subject <br>";
                    }
                }

                // Check for successful insertion
                if ($conn->affected_rows > 0) {
                    echo "<br> Data inserted successfully!";
                } elseif (empty($selectedSubjects)) {
                    echo "<p>No subjects selected.</p>";
                } else {
                    echo "No data inserted.";
                }
            } else {
                echo "Teacher not found.";
            }

            // Close the database connection
            $conn->close();
        } else {
            echo "Please log in first.";
        }
    

    }
}
?>




         </div><!--qualification-->
           
         </div><!--profile-->
</body>
</html>
