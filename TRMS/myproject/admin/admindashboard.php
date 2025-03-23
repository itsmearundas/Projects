<!DOCTYPE html>
<html>
<head>
     <title>admin dashboard</title>
     <meta charset="UTF-8 "/>
     <link rel="stylesheet" href="adminstyle/adminstyle.css" />
</head>
    <body class="teachbackground" background="#">
        <div class="wholesession">
            <div class="navigators">
                 <div class="l1">
                     <a style="text-decoration: none;" href="../index.html"> <img src="../logoss/trmslogo.jpg" alt="TRMS" height="150" width="150" /></a>
                 </div>          
                
                 <div class="n4">
                     <a style="text-decoration: none;" href="../teachhelp/teachhelp.html" >  <h3 id="adt5">HELP</h3></a><br>
                 </div>
            </div>    <!--navigator-->
            <div class="complete">
                 <div class="mainsection">

                 <a style="text-decoration: none;" href="timetable/timetable.php">
                 <div class="displaydeptanddept"><h2><br><br>TIME TABLE</h2></div></a>
                               
                <a style="text-decoration: none;" href="admindashboarddeep/addingdeptandsub.php"> 
                <div  class="addsubanddept"><h2><br>ADD<br> NEW <br>DEPATMENT AND SUBJECT</h2></div></a>

                <a style="text-decoration: none;" href="admindashboarddeep/displaydeptandsub.php" > 
                <div class="displaydeptanddept"><h2><br> VIEW <br><br> DEPATMENT AND SUBJECT</h2></div></a>

                <a style="text-decoration: none;" href="admindashboarddeep/allteachers.php" > 
                <div class="displaydeptanddept"><h2><br>ALL<br><br> TEACHERS</h2></div></a>
                
                <a style="text-decoration: none;" href="admindashboarddeep/message/message.php" >
                 <div class="displaydeptanddept"><h2><br><br>MESSAGES</h2></div></a>
                

                 
                      <br>               
                 

                  </div> 
                   
            <div class="info">
                <div class="teachers">
              <h1>Teacher Details</h1>
                 <?php
                    // Database configuration
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

                    $sql = "SELECT * FROM teachers";
                    $result = $conn->query($sql);

                    // Check if the query was successful
                    if ($result) {
                        // Get the total count of rows
                        $row_count = $result->num_rows;
                        
                        // Display the total count of rows
                        echo "<br><br>Total number of Teachers   :   " . $row_count;
                        echo "<br><br>";

                        // Close the result and database connection
                        $result->close();
                        $conn->close();
                    }
                     else 
                     {
                        echo "Error executing the query: " . $conn->error;
                    }
            ?>
                </div>
                <div class="nodept">
                <h1>Number Of Department </h1>
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

                                // Query to count the number of rows in the "departments" table
                                $sql = "SELECT COUNT(*) as department_count FROM departments";
                                $result = $conn->query($sql);

                                if ($result->num_rows > 0) {
                                    $row = $result->fetch_assoc();
                                    $departmentCount = $row["department_count"];
                                    echo "<br><br>Total departments  :   " . $departmentCount;
                                    echo "<br><br>";
                                } else {
                                    echo "No departments found.";
                                }

                                // Close the database connection
                                $conn->close();
                                ?>

                </div>
                <div class="nosubj">
                <h1>Number Of Subject </h1>
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

                            // Query to count the rows in the "subjects" table
                            $sql = "SELECT COUNT(*) AS subject_count FROM subjects";

                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                $row = $result->fetch_assoc();
                                $subjectCount = $row["subject_count"];
                                echo "<br><br>Total Subjects   :  $subjectCount ";
                                echo "<br><br>";
                            } else {
                                echo "No data found in the 'subjects' table.";
                            }

                            // Close the database connection
                            $conn->close();
                            ?>

                </div>

    
         </div><!--info-->
        </div><!--mainsection-->
      </div>                
    </body>
</html>