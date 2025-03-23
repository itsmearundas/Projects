<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "trms";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Retrieve form data and perform basic validation
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        die("Both email and password are required");
    }

    // Query to fetch hashed password for the given email
    $login_query = "SELECT id, fullname, password FROM teachers WHERE email = ?";
    $login_statement = $conn->prepare($login_query);
    $login_statement->bind_param("s", $email);
    $login_statement->execute();
    $login_statement->bind_result($user_id, $user_name, $hashedPassword);
    $login_statement->fetch();

    if (password_verify($password, $hashedPassword)) {
        // Successful login
        // You can perform further actions here, such as redirecting to a dashboard page
        $_SESSION["user_id"] = $user_id;
        $_SESSION["user_name"] = $user_name;

        // Redirect to the user details page
        header("Location: ../teachdash/teacherdashboard.php");
        exit();
    } else {
        echo "Login failed. Incorrect email or password.";
    }

    // Close the database connection
    $conn->close();
}
?>
