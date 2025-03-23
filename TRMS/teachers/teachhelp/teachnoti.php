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

        
    </div><br><br>

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
  

    // Fetch data from adminmessage table in descending order of id
    $query = "SELECT id, text_data FROM adminmessage ORDER BY id DESC";

    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Output data of each row
        while ($row = $result->fetch_assoc()) {
            echo  " NOTIFICATION<br><br>";
            echo "Text Data: " . $row["text_data"] . "<br><br>";

            // Add a form with a delete button for each record
            echo "<form action='' method='post'>";
            echo "<input type='hidden' name='record_id' value='" . $row['id'] . "'>";
            echo "<input type='submit' value='Delete'>";
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
