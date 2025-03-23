<!DOCTYPE html>
<html>
<head>
     <title>TEACHERS PROFILE</title>
     <meta charset="UTF-8"/>
     <link rel="stylesheet" href="../teachprofile/teachprofile.css" />
</head>
<body class="teachbackground" style="background-image: url('../teachimg/1teacherback.jpg');">
    <div class="wholesession">
    <div class="navigators">
        <div class="l1">
            <a href="../../index.html"> <img src="../../logoss/trmslogo.jpg" alt="TRMS" height="150" width="150" /></a>
        </div>          
        <div class="n5">
            <a href="../teachhelp/teachhelp.php" > <h4 id="adt5">HELP</h4></a><br>
        </div>
        <div class="n4">
            <a href="../teachdash/teacherdashboard.php" >  <h4 id="adt4">GO BACK</h4></a><br>            
        </div>
       
    </div>    
        
                                            <div class="profile">
               
                  
                                <?php
                                                session_start();

                                                if (!isset($_SESSION["user_id"])) {
                                                    // If the user is not authenticated, redirect them to the login page
                                                    header("Location: ../teachlsf/teacherlogin.html");
                                                    exit();
                                                }

                                                $servername = "localhost";
                                                $username = "root";
                                                $password = "";
                                                $database = "trms";

                                                // Create a database connection
                                                $connection = new mysqli($servername, $username, $password, $database);

                                                // Check if the connection was successful
                                                if ($connection->connect_error) {
                                                    die("Database connection failed: " . $connection->connect_error);
                                                }

                                                $user_id = $_SESSION["user_id"];

                                                // Retrieve teacher details
                                                $sql = "SELECT * FROM teachers WHERE id = ?";
                                                $stmt = $connection->prepare($sql);
                                                $stmt->bind_param("i", $user_id);

                                                if ($stmt->execute()) {
                                                    $result = $stmt->get_result();

                                                    if ($result->num_rows == 1) {
                                                        // Output user details
                                                        $row = $result->fetch_assoc();
                                                        echo "<h1>" . $row["fullname"] . "</h1><br><br>";
                                            
                                                        echo "ID: " . $row["id"] . "<br><br>";
                                                        echo "Full Name: " . $row["fullname"] . "<br><br>";
                                                        echo "Phone Number: " . $row["phno"] . "<br><br>";
                                                        echo "Email: " . $row["email"] . "<br><br>";
                                                        echo "Gender: " . $row["gender"] . "<br><br>";
                                                        echo "Date of Birth: " . $row["dob"] . "<br><br>";
                                                        echo "Address: " . $row["address"] . "<br><br>";
                                                        echo "EDIT PROFILE<a href='edit_teacher.php?field=fullname&id=" . $row["id"] . "'>Edit</a><br><br>";

                                                    }
                                                } else {
                                                    echo "Error fetching teacher details: " . $stmt->error;
                                                }

                                                // Close the database connection
                                                $stmt->close();
                                                $connection->close();
                                                ?>
                           </div><!--aboutme-->
                       
                       
                         
</body>
</html>
