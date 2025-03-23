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
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit</title>
    <meta charset="UTF-8"/>
    <link rel="stylesheet" href="../adminstyle/displaydeptandsub.css"/>
</head>
<body class="teachbackground" background="#">
<div class="wholesession">
<div class="navigators">
                 <div class="l1">
                     <a style="text-decoration: none;" href="../../index.html"> <img src="../../logoss/trmslogo.jpg" alt="TRMS" height="150" width="150" /></a>
                 </div>          
                 <div class="n4">
                     <a style="text-decoration: none;" href="displaydeptandsub.php" >  <h3 id="adt5">BACK</h3></a><br>
                 </div>
                 <div class="n4">
                     <a style="text-decoration: none;" href="#" >  <h3 id="adt5">HELP</h3></a><br>
                 </div>
            </div>    <!--navigator-->
    <div class="mainsection">
        <?php
        // Check if type and id are set in the URL
        if (isset($_GET['type']) && isset($_GET['id'])) {
            $type = $_GET['type'];
            $id = $_GET['id'];

            // Retrieve the name from the URL
            $name = isset($_GET['name']) ? urldecode($_GET['name']) : '';

            // Construct the table name dynamically
            $table_name = $type === 'department' ? 'departments' : 'subjects';

            // Execute the select query
            $sql = "SELECT * FROM $table_name WHERE {$type}_id = $id";
            $result = $conn->query($sql);

            if ($result !== false) {
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    echo "<h2>Edit " . ucfirst($type) . "</h2>";
                    echo "<form method='post' action='update.php'>";
                    echo "<input type='hidden' name='type' value='$type'>";
                    echo "<input type='hidden' name='id' value='$id'>";
                    echo "<label for='name'>Name:</label>";
                    echo "<input type='text' id='name' name='name' value='$name' required><br>";

                    // Add Delete button
                    echo "<input type='submit' name='update' value='Update'>";
                    echo "<input type='submit' name='delete' value='Delete'>";
                    echo "</form>";
                } else {
                    echo "Record not found for query: $sql";
                }
            } else {
                echo "Error executing query: " . $conn->error;
            }
        } else {
            echo "Invalid URL.";
        }

        // Close the database connection
        $conn->close();
        ?>
    </div> <!--dashboard-->
</div><!--mainsection-->
</body>
</html>
