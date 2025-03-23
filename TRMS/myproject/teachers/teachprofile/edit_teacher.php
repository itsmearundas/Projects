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

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Update all fields in the database
    $update_sql = "UPDATE teachers SET fullname = ?, phno = ?, email = ?, gender = ?, dob = ?, address = ? WHERE id = ?";
    $update_stmt = $connection->prepare($update_sql);
    $update_stmt->bind_param("sssssss", $_POST["fullname"], $_POST["phno"], $_POST["email"], $_POST["gender"], $_POST["dob"], $_POST["address"], $user_id);

    if ($update_stmt->execute()) {
        echo "Update successful!";
        header("Location: teachprofile.php"); // Redirect to the same page
        exit();
    } else {
        echo "Error updating record: " . $update_stmt->error;
    }

    $update_stmt->close();
}

// Retrieve teacher details
$sql = "SELECT * FROM teachers WHERE id = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // Output user details
        $row = $result->fetch_assoc();

        // Display the form for editing
        ?>
        <form method="post" action="" enctype="multipart/form-data">
            Full Name: <input type="text" name="fullname" value="<?php echo $row["fullname"]; ?>"><br><br>
            Phone Number: <input type="text" name="phno" value="<?php echo $row["phno"]; ?>"><br><br>
            Email: <input type="text" name="email" value="<?php echo $row["email"]; ?>"><br><br>
            Gender: 
            <input type="radio" name="gender" value="Male" <?php if($row["gender"]=="Male") echo "checked"; ?>> Male
            <input type="radio" name="gender" value="Female" <?php if($row["gender"]=="Female") echo "checked"; ?>> Female
            <input type="radio" name="gender" value="Other" <?php if($row["gender"]=="Other") echo "checked"; ?>> Other
            <br><br>
            Date of Birth: <input type="date" name="dob" value="<?php echo $row["dob"]; ?>"><br><br>
            Address: <input type="text" name="address" value="<?php echo $row["address"]; ?>"><br><br>
            
            <input type="submit" value="Update">
            <button  class="btn" onclick="window.location.href='teachprofile.php';">
            BACK</button>
        </form>
        <?php
    }
} else {
    echo "Error fetching teacher details: " . $stmt->error;
}

// Close the database connection
$stmt->close();
$connection->close();
?>
