
<!DOCTYPE html>
<html>
<head>
     <title>MESSAGE</title>
     <meta charset="UTF-8 "/>
     <link rel="stylesheet" href="../adminstyle/allteachers.css" />
</head>
    <body class="teachbackground" background="#">
        <div class="wholesession">
            <div class="navigators">
            <div class="homelogo">
                     <a href="../../index.html"> <img src="../../logoss/trmslogo.jpg" alt="TRMS" height="150" width="150" /></a>
                 </div>           
                 <div class="navdb">
                      <a href="../../admindashboard.php">  <h3 id="adt2">DASHBOARD</h3></a><br>      
                 </div>               
               
                 <div class="navhelp">
                     <a href="../../admindashboard.php" >  <h3 id="adt4">BACK</h3></a><br>
                 </div>
                 <div class="navhelp">
                     <a href="#" >  <h3 id="adt4">HELP</h3></a><br>
                 </div>
                

    <h2>Insert Data into SQL Table</h2>

    <form action="" method="post">
        <label for="textData">Text Data:</label>
        <input type="text" name="textData" id="textData" required>
        <br>
        <input type="submit" value="Insert Data">
    </form>
</div><br><br>
              
      </div>                
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

// Check if the form is submitted for inserting new data
if (isset($_POST['textData'])) {
    // Get teacher's ID and name from the teachers table
    session_start();
    $teacher_id = $_SESSION["user_id"];

    $teacher_query = "SELECT id, fullname FROM teachers WHERE id = '$teacher_id'";
    $teacher_result = $conn->query($teacher_query);

    if ($teacher_result->num_rows > 0) {
        // Fetch the teacher's ID and name
        $teacher_data = $teacher_result->fetch_assoc();
        $teacher_name = $teacher_data['fullname'];
    } else {
        // Handle the case where teacher data is not found
        echo "Teacher data not found";
        exit;
    }

    // Get data from the form
    $textData = $_POST['textData'];

    // Insert data into the tablereport table
    $insert_query = "INSERT INTO adminmessage (text_data) VALUES ('$textData')";

    if ($conn->query($insert_query) === TRUE) {
        echo "Data inserted successfully";
    } else {
        echo "Error: " . $insert_query . "<br>" . $conn->error;
    }
}

// Check if the form is submitted for deleting data
if (isset($_POST['record_id'])) {
    $record_id = $_POST['record_id'];

    // Delete the record from the tablereport table
    $delete_query = "DELETE FROM tablereport WHERE id = '$record_id'";
    
    if ($conn->query($delete_query) === TRUE) {
        echo "Record deleted successfully";
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

// Fetch data from tablereport and join with teachers table
// Fetch data from tablereport and join with teachers table
$query = "SELECT tablereport.id, tablereport.teacher_id, teachers.fullname AS teacher_name, tablereport.text_data
          FROM tablereport
          JOIN teachers ON tablereport.teacher_id = teachers.id";

$result = $conn->query($query);

if ($result->num_rows > 0) {
    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        
        echo "Teacher ID: " . $row["teacher_id"] . "<br>";
        echo "Teacher Name: " . $row["teacher_name"] . "<br>";
        echo "Text Data: " . $row["text_data"] . "<br>";

        // Add a form with a delete button for each record
        echo "<form action='' method='post'>";
        echo "<input type='hidden' name='record_id' value='" . $row['id'] . "'>";
        echo "<input type='submit' value='Delete'>";
        echo "<td><a href='../view_details.php?id=" . $row["teacher_id"] . "'>View Details</a></td>";
        echo "</form>";

        echo "<hr>";
    }
} else {
    echo "No records found";
}


// Close the connection
$conn->close();
?>


</body>
</html>


