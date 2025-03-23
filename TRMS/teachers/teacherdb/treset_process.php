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

// Retrieve form data
$email = $_POST['t_mail'];
$newPassword = $_POST['new_pass'];
$confirmPassword = $_POST['confirm_pass'];

// Check if passwords match
if ($newPassword !== $confirmPassword) {
    echo "Passwords do not match";
    exit();
}

// Hash the new password
$hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

// Update the user's password in the database
$updateQuery = "UPDATE teachers SET t_pass = ? WHERE t_mail = ?";
$updateStatement = $conn->prepare($updateQuery);
$updateStatement->bind_param("ss", $hashedPassword, $email);

if ($updateStatement->execute()) {
    echo "Password reset successfully";
    header("Location: ../teacherlogin.html"); // Redirect to login page after password reset
} else {
    echo "Error updating password: " . $updateStatement->error;
}

$updateStatement->close();

// Close the connection
$conn->close();
?>
