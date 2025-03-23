<?php
$servername = "localhost";
$username = "root";  // Replace with your MySQL username
$password = "";      // Replace with your MySQL password
$dbname = "trms";    // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve form data and perform basic validation
$email = $_POST['email'];
$pass = $_POST['password'];

// Check if any field is empty
if (empty($email) || empty($pass)) {
    die("Both email and password are required");
}

// Query to fetch hashed password for the given email
$query = "SELECT password FROM admin WHERE email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($hashedPassword);
$stmt->fetch();
$stmt->close();

if (password_verify($pass, $hashedPassword)) {
    // Password matches, user is authenticated
    header("Location: ../admindashboard.php"); // Change to your desired page
    exit();
} else {
    echo "Invalid login credentials";
}

// Close the connection
$conn->close();
?>
