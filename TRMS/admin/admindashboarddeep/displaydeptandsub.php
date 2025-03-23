<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <meta charset="UTF-8"/>
    <link rel="stylesheet" href="../adminstyle/displaydeptandsub.css"/>
    <style>

        
img {
    max-width: 100px;
    max-height: 100px;
}
        table {
            width: 80%;
            border-collapse: collapse;
            margin: 20px;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }
    </style>
</head>
<body class="teachbackground" background="#">
<div class="wholesession">
<div class="navigators">
                 <div class="l1">
                     <a style="text-decoration: none;" href="../../index.html"> <img src="../../logoss/trmslogo.jpg" alt="TRMS" height="150" width="150" /></a>
                 </div>          
                 <div class="n4">
                     <a style="text-decoration: none;" href="../admindashboard.php" >  <h3 id="adt5">BACK</h3></a><br>
                 </div>
                 <div class="n4">
                     <a style="text-decoration: none;" href="../teachhelp/teachhelp.html" >  <h3 id="adt5">HELP</h3></a><br>
                 </div>
            </div>    <!--navigator-->
    <div class="mainsection">
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

        // Query to fetch departments and their corresponding subjects
        $departments_sql = "SELECT department_id, department_name FROM departments";
        $departments_result = $conn->query($departments_sql);

        // Display departments and subjects in a table
        if ($departments_result !== false && $departments_result->num_rows > 0) {
            echo "<h2>Departments and Subjects</h2>";
            echo "<table>";
            echo "<tr><th>Department Name</th><th>Subjects</th></tr>";
            while ($department = $departments_result->fetch_assoc()) {
                echo "<tr>";
                $department_id = $department['department_id'];
                $subjects_sql = "SELECT subject_id, subject_name FROM subjects WHERE department_id = $department_id";
                $subjects_result = $conn->query($subjects_sql);

                echo "<td><a style='text-decoration: none;' href='edit.php?type=department&id=" . $department['department_id'] . "&name=" . urlencode($department['department_name']) . "'>" . $department['department_name'] . "</a></td>";

                echo "<td>";
                if ($subjects_result !== false && $subjects_result->num_rows > 0) {
                    while ($subject = $subjects_result->fetch_assoc()) {
                        echo "<a style='text-decoration: none;' href='edit.php?type=subject&id=" . $subject['subject_id'] . "&name=" . urlencode($subject['subject_name']) . "'>" . $subject['subject_name'] . "</a><br>";
                    }
                } else {
                    echo "No subjects found.";
                }
                echo "</td>";

                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "No departments found.";
        }

        // Close the database connection
        $conn->close();
        ?>
    </div> <!--dashboard-->
</div><!--mainsection-->
</body>
</html>
