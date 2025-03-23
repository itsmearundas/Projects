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
$name = validateInput($_POST['name']);
$email = validateEmail($_POST['email']);
$pass = validatePassword($_POST['password']);
$confirmpassword = validatePassword($_POST['cpassword']);
$confirmid = validateConfirmID($_POST['confirmid']);

// Check if passwords match
if ($pass !== $confirmpassword) {
    die("Passwords do not match");
}

// Check if confirm ID is correct
if ($confirmid !== "2002") {
    die("Incorrect confirm ID");
}

// Hash the password
$hashedPassword = password_hash($pass, PASSWORD_DEFAULT);

// Check if email already exists
$check_query = "SELECT * FROM admin WHERE email = ?";
$check_statement = $conn->prepare($check_query);
$check_statement->bind_param("s", $email);
$check_statement->execute();
$check_result = $check_statement->get_result();

if ($check_result->num_rows > 0) {
    echo "Email already exists in the database";
} else {
    // Insert data into the database
    $insert_query = "INSERT INTO admin (name, email, password) VALUES (?, ?, ?)";
    $insert_statement = $conn->prepare($insert_query);
    $insert_statement->bind_param("sss", $name, $email, $hashedPassword);
    
    if ($insert_statement->execute()) {
        echo "Inserted successfully";
        // Redirect to the login page
        header("Location: ../adminlogin.html");
        exit(); // Make sure to exit after the redirection
    } else {
        echo "Error: " . $insert_statement->error;
        header("Location: ../adminsignup.html");
    }
}

// Close the connection
$conn->close();

// Validation functions
function validateInput($data) {
    // Trim spaces, strip tags, and ensure safe input
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function validateEmail($email) {
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format");
    }
    return validateInput($email);
}

function validatePassword($password) {
    // Validate password strength (at least 8 characters)
    if (strlen($password) < 8) {
        die("Password must be at least 8 characters long");
    }
    return validateInput($password);
}

function validateConfirmID($confirmid) {
    // Validate confirm ID (exact match)
    if ($confirmid !== "2002") {
        die("Incorrect confirm ID");
    }
    return validateInput($confirmid);
}
?>
