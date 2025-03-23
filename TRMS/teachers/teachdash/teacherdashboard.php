<!DOCTYPE html>
<html>
<head>
  <title>TEACHERS DASHBOARD</title>
  <meta charset="UTF-8 "/>
  <link rel="stylesheet" href="teachdashstyle/teachdash.css" />
  
 
</head>
<body class="teachbackground">
   <section class="wholesection">
      <div class="containernav" alt="containernav">
        
              <div class="homelogo">
                <a href="../../index.html"> <img src="../../logoss/trmslogo.jpg" alt="TRMS" height="150" width="150" /></a>
              </div>          
             
              <div class="homelogo">
                <a href="../teachtimetable/teachtimetable.php">  <h1 id="adt2">TIME TABLE</h1></a><br>               
              </div>
             
              <div class="homelogo">
                <a href="../teachdash/deptandsub/deptandsub.php" > <h1 id="adt4">DEPT AND SUB</h1></a><br>            
             </div>
             <div class="homelogo">
                <a href="../teachprofile/teachprofile.php" > <h1 id="adt4">PROFILE</h1></a><br>            
             </div>
             <div class="homelogo">
                <a href="report/teachreport.php" > <h1 id="adt4">REPORT</h1></a><br>            
             </div>
             <div class="homelogo">
                <a href="../teachhelp/teachnoti.php" > <h1 id="adt4">NOTIFICATION</h1></a><br>            
             </div>
              <div class="homelogo">
                 <a href="../teachhelp/teachhelp.php" > <h1 id="adt5">HELP</h1></a><br>
              </div>
        
      </div> 
      <div class="todayinfodiv">
        <section class="todayinfo">
          <div class="containertoday" alt="containertoday">
           
               
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

// Generate the current month in character format (e.g., "May")
$currentMonth = date("F"); // "F" represents the full month name
// Generate the current day in character format (e.g., "Fri")
$currentDay = date("D"); // "D" represents the abbreviated day name

// Fetch data from the timetable_data table using user_id, month, and day
$user_id = $_SESSION["user_id"];
$sql = "SELECT id, department, subject, which, time_slot FROM timetable_data WHERE teacher_id = $user_id AND selected_month = '$currentMonth' AND day_of_week = '$currentDay'";

$result = $conn->query($sql);

if ($result) {
    if ($result->num_rows > 0) {
        echo '<table>';
        echo '<tr>';
        echo '<th>ID</th>';
        echo '<th>Department</th>';
        echo '<th>Subject</th>';
        echo '<th>Which</th>';
        echo '<th>Time Slot</th>';
        echo '<th>Delete</th>'; // New column for delete button
        echo '</tr>';

        // Output data of each row
        while ($row = $result->fetch_assoc()) {
            // Access data from the row
            $id = $row["id"];
            $department = $row["department"];
            $subject = $row["subject"];
            $which = $row["which"];
            $time_slot = $row["time_slot"];

            // Output data in table rows
            echo '<tr>';
            echo "<td>$id</td>";
            echo "<td>$department</td>";
            echo "<td>$subject</td>";
            echo "<td>$which</td>";
            echo "<td>$time_slot</td>";

            // Add a delete button for each row
            echo "<td><a href='delete_row.php?id=$id'>Delete</a></td>"; // replace 'delete_row.php' with your actual delete script
            echo '</tr>';
        }

        echo '</table>';
    } else {
        echo "No records found for the current month: $currentMonth and day: $currentDay";
    }
} else {
    echo "Query failed: " . $conn->error;
}

// Close the database connection
$conn->close();
?>



           
          </div>

          
       </section>
    </div>
   </section>  
</body>

</html>











