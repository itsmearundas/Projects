<!DOCTYPE html>
<html>
<head>
  <title>TIME TABLE</title>
  <meta charset="UTF-8 "/>
  <link rel="stylesheet" href="teachtimetable.css" />
</head>
<body class="homebackground" background="imgs/home.jpg">
  <div class="wholecontainer">
   
        
         <div class="navigators">
                <div class="l1">
                    <a href="../../index.html"> <img src="../../logoss/trmslogo.jpg" alt="TRMS" height="150" width="150" /></a>
                </div>          
               
                <div class="n2">
                    <a href="ttassign/ttassign.php"> <h4 id="adt2"> TIME TABLE</h4></a><br>               
                </div>
                <div class="n3">
                    <a href="#" >  <h4 id="adt5">HELP</h4></a><br>
                </div>
                <div class="n1">
                    <a href="../teachdash/teacherdashboard.php">    <h4 id="adt1">GO BACK</h4></a><br>      
                </div>
        </div> 
        <div class="todaytimetable">
        <?php
// Start the session
session_start();

// Check if the user_id is set in the session
if (!isset($_SESSION["user_id"])) {
    echo "User ID not set in the session.";
    exit; // Exit the script if user_id is not set
}

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

// Get the current month in character format (e.g., "May," "June")
$currentMonth = date("F"); // Format: "F" represents the full month name

// Fetch data from the timetable_data table using user_id and selected month
$user_id = $_SESSION["user_id"];
$sql = "SELECT * FROM timetable_data WHERE teacher_id = $user_id AND selected_month = '$currentMonth'";

$result = $conn->query($sql);

if ($result) {
    if ($result->num_rows > 0) {
        echo '<table>';
echo '<tr>';
echo '<th>ID</th>';
echo '<th>Selection ID</th>';
echo '<th>Teacher Name</th>';
echo '<th>Department</th>';
echo '<th>Subject</th>';
echo '<th>Which</th>';
echo '<th>Time Slot</th>';
echo '<th>Date</th>';
echo '<th>Selected Month</th>';
echo '<th>Day of Week</th>';
echo '<th>Year</th>';
echo '<th>Delete</th>'; // New column for delete button
echo '</tr>';
        // Output data of each row
        while ($row = $result->fetch_assoc()) {
            // Access data from the row
            $id = $row["id"];
            $selection_id = $row["selection_id"];
            $teacher_name = $row["teacher_name"];
            $department = $row["department"];
            $subject = $row["subject"];
            $which = $row["which"];
            $time_slot = $row["time_slot"];
            $date = $row["date"];
            $selected_month = $row["selected_month"];
            $day_of_week = $row["day_of_week"];
            $year = $row["year"];

            // Output data in table rows
            echo '<tr>';
            echo "<td>$id</td>";
            echo "<td>$selection_id</td>";
            echo "<td>$teacher_name</td>";
            echo "<td>$department</td>";
            echo "<td>$subject</td>";
            echo "<td>$which</td>";
            echo "<td>$time_slot</td>";
            echo "<td>$date</td>";
            echo "<td>$selected_month</td>";
            echo "<td>$day_of_week</td>";
            echo "<td>$year</td>";
            echo "<td><a href='delete_row.php?id=$id'>Delete</a></td>"; // replace 'delete_row.php' with your actual delete script
            echo '</tr>';
        }
        echo '</table>';
    } else {
        echo "No records found for the current month: $currentMonth";
    }
} else {
    echo "Query failed: " . $conn->error;
}

// Close the database connection
$conn->close();
?>


                              
                            
                            
                            
                            </div>

                                    
                                
  </div>   
</body>

</html>