<?php
session_start();

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

// Check if the form is submitted
if (isset($_POST['textData'])) {
    // Get teacher's ID and name from the teachers table
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
   // Get data from the form
$textData = $_POST['textData'];

// Insert data into the tablereport table
$sql = "INSERT INTO tablereport (teacher_id, teacher_name, text_data) VALUES ('$teacher_id', '$teacher_name', '$textData')";

if ($conn->query($sql) === TRUE) {
    echo "Data inserted successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
     <title>REPORT</title>
     <meta charset="UTF-8 "/>
     <link rel="stylesheet" href="../adminstyle/allteachers.css" />
</head>
<body class="teachbackground" background="#">
    <div class="wholesession">
       

    <div class="navigators">
            <div class="homelogo">
                     <a href="../../../index.html"> <img src="../../../logoss/trmslogo.jpg" alt="TRMS" height="150" width="150" /></a>
                 </div>           
                 <div class="navdb">
                      <a href="../admindashboard.php">  <h3 id="adt2">DASHBOARD</h3></a><br>      
                 </div>               
               
                 <div class="navhelp">
                     <a href="../admindashboard.php" >  <h3 id="adt4">BACK</h3></a><br>
                 </div>
                 <div class="navhelp">
                     <a href="#" >  <h3 id="adt4">HELP</h3></a><br>
                 </div>


        <h2>Insert Data into SQL Table</h2>

        <form action="teachreport.php" method="post">
            <label for="textData">Text Data:</label>
            <input type="text" name="textData" id="textData" required>
            <br>
            <input type="submit" value="Insert Data">
        </form>
    </div>
</body>
</html>
