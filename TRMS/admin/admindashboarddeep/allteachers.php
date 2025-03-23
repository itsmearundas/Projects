
<!DOCTYPE html>
<html>
<head>
     <title>ADMIN TIMETABLE</title>
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
                      <a href="../admindashboard.php">  <h3 id="adt2">DASHBOARD</h3></a><br>      
                 </div>               
               
                 <div class="navhelp">
                     <a href="../admindashboard.php" >  <h3 id="adt4">BACK</h3></a><br>
                 </div>
                 <div class="navhelp">
                     <a href="#" >  <h3 id="adt4">HELP</h3></a><br>
                 </div>
                 <div class="search">
                 <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
   
    <input   class="search_query" type="text" id="search_query" name="search_query">
    
    <button class="search_query_submit" type="submit" value="Search"  id="search_query_submit">SEARCH</button>
</form>

                </div> 
            </div>  
            <div class="searchphp">
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

// Function to delete a teacher profile
function deleteTeacher($id, $conn) {
    $deleteSql = "DELETE FROM teachers WHERE id=$id";
    if ($conn->query($deleteSql) === TRUE) {
        echo "Record deleted successfully";
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

// Check if a delete request is made
if (isset($_GET['delete']) && isset($_GET['id'])) {
    $idToDelete = $_GET['id'];
    deleteTeacher($idToDelete, $conn);
}

// Check if a search query is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $searchQuery = $_POST["search_query"];
    
    // Modify the SQL query based on the search query
    if (strtolower($searchQuery) === 'all') {
        $sql = "SELECT * FROM teachers";
    } else {
        $sql = "SELECT * FROM teachers WHERE fullname LIKE '%$searchQuery%'";
    }
} else {
    // Default query to retrieve all teachers
    $sql = "SELECT * FROM teachers";
}

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data of each row
    echo "<table border='1'>
            <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>Phone Number</th>
                <th>Email</th>
                <th>Gender</th>
                <th>Date of Birth</th>
                <th>Address</th>
                
                <th>Delete</th>
                <th>View</th>
            </tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . $row["id"] . "</td>
                <td>" . $row["fullname"] . "</td>
                <td>" . $row["phno"] . "</td>
                <td>" . $row["email"] . "</td>
                <td>" . $row["gender"] . "</td>
                <td>" . $row["dob"] . "</td>
                <td>" . $row["address"] . "</td>
                
                <td><a href='?delete=true&id=" . $row["id"] . "'>Delete</a></td>
                <td><a href='view_details.php?id=" . $row["id"] . "'>View Details</a></td>
              </tr>";
    }

    echo "</table>";
} else {
    echo "0 results";
}

$conn->close();
?>

            </div>

      </div>                
    </body>
</html>

