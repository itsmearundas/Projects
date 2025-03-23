<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "trms";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Retrieve and validate form data
    $name = validateName($_POST['fullname']);
    $phno = validatePhoneNumber($_POST['phno']);
    $email = validateEmail($_POST['email']);
    $gender = validateGender($_POST['gender']);
    $dob = validateDateOfBirth($_POST['dob']);
    $address = validateAddress($_POST['address']);
    $password = validatePassword($_POST['password']);
    $confirmpassword = validatePassword($_POST['confirmpassword']);

    // Check if email already exists
    $check_query = "SELECT * FROM teachers WHERE email = ?";
    $check_statement = $conn->prepare($check_query);
    $check_statement->bind_param("s", $email);
    $check_statement->execute();
    $check_result = $check_statement->get_result();
    
    if ($check_result->num_rows > 0) {
        echo "Email already exists in the database";
    } else {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // File upload logic (move this to a separate function)
        

        // Insert data into the database
        $insert_query = "INSERT INTO teachers (fullname, phno, email, gender, dob, address, password)
                         VALUES (?, ?, ?, ?, ?, ?,  ?)";
        $insert_statement = $conn->prepare($insert_query);
        $insert_statement->bind_param(
            "sssssss",
            $name, $phno, $email, $gender, $dob, $address, $hashedPassword
        );

        if ($insert_statement->execute()) {
            echo "REGISTRATION SUCCESSFULLY COMPLETED";
            header("Location: ../teachlsf/teacherlogin.html");
            exit;
        } else {
            echo "Error: " . $insert_statement->error;
        }
    }

    $conn->close();
}

function validateName($name) {
    // Validate name (only letters and spaces allowed)
    if (!preg_match("/^[a-zA-Z ]*$/", $name)) {
        die("Invalid name format");
    }
    return validateInput($name);
}

function validatePhoneNumber($phno) {
    // Validate phone number (exactly 10 digits allowed)
    if (!preg_match("/^\d{10}$/", $phno)) {
        die("Invalid phone number format. It must be exactly 10 digits.");
    }
    return validateInput($phno);
}


function validateEmail($email) {
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format");
    }
    return validateInput($email);
}

function validateGender($gender) {
    // Validate gender (only 'Male' or 'Female' allowed)
    $validGenders = ['male', 'female','other'];
    if (!in_array($gender, $validGenders)) {
        die("Invalid gender");
    }
    return validateInput($gender);
}

function validateDateOfBirth($dob) {
    // Validate date of birth (YYYY-MM-DD format)
    if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $dob)) {
        die("Invalid date of birth format");
    }
    return validateInput($dob);
}

function validateAddress($address) {
    // Validate address (allow letters, numbers, and spaces)
    if (!preg_match("/^[a-zA-Z0-9 ]*$/", $address)) {
        die("Invalid address format");
    }
    return validateInput($address);
}

function validatePassword($password) {
    // Validate password strength (at least 8 characters)
    if (strlen($password) < 8) {
        die("Password must be at least 8 characters long");
    }
    return validateInput($password);
}

function validateInput($data) {
    // Trim spaces, strip tags, and ensure safe input
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

?>
