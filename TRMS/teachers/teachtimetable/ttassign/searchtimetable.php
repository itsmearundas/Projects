<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TEACHERS PROFILE</title>
    <link rel="stylesheet" href="../teachprofile/teachprofile.css" />
</head>
<body class="teachbackground" style="background-image: url('../teachimg/1teacherback.jpg');">
    <div class="wholesession">
        <div class="navigators">
            <div class="l1">
                <a href="../../index.html">
                    <img src="../../logoss/trmslogo.jpg" alt="TRMS" height="150" width="150" />
                </a>
            </div>          

            <div class="n4">
                <a href="ttassign.php">
                    <h4 id="adt4">GO BACK</h4>
                </a><br>            
            </div>
            <div class="search">
                <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <input class="search_query" type="date" id="search_query" name="search_query">
                    <button class="search_query_submit" type="submit" value="Search" id="search_query_submit">SEARCH</button>
                </form>
            </div>
        </div>    
        
        <div class="profile">
            <?php
            // Check if the form has been submitted
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Include your database connection configuration here
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

                // Get the search query from the form
                $searchQuery = $_POST['search_query'];

                // Convert the selected date to the desired format
                $formattedDate = date('d-F-Y', strtotime($searchQuery));

                // Fetch data from timetable_data table based on the search query (date)
                $sql = "SELECT * FROM timetable_data WHERE date LIKE ?";
                $stmt = $conn->prepare($sql);

                if (!$stmt) {
                    die("Error preparing statement: " . $conn->error);
                }

                // Add % around the formatted date to perform a partial match
                $searchQuery = '%' . $formattedDate . '%';
                $stmt->bind_param("s", $searchQuery);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    echo "<table border='1'>";
                    echo "<tr><th>ID</th><th>Selection ID</th><th>Teacher ID</th><th>Teacher Name</th><th>Department</th><th>Subject</th><th>Which</th><th>Time Slot</th><th>Date</th><th>Selected Month</th><th>Day of Week</th><th>Year</th></tr>";

                    // Output data of each row
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . $row['selection_id'] . "</td>";
                        echo "<td>" . $row['teacher_id'] . "</td>";
                        echo "<td>" . $row['teacher_name'] . "</td>";
                        echo "<td>" . $row['department'] . "</td>";
                        echo "<td>" . $row['subject'] . "</td>";
                        echo "<td>" . $row['which'] . "</td>";
                        echo "<td>" . $row['time_slot'] . "</td>";
                        echo "<td>" . $row['date'] . "</td>";
                        echo "<td>" . $row['selected_month'] . "</td>";
                        echo "<td>" . $row['day_of_week'] . "</td>";
                        echo "<td>" . $row['year'] . "</td>";
                        echo "</tr>";
                    }

                    echo "</table>";
                } else {
                    echo "No results found for the specified date.";
                }

                // Close the database connection
                $conn->close();
            }
            ?>
        </div><!--profile-->
    </div>
</body>
</html>
