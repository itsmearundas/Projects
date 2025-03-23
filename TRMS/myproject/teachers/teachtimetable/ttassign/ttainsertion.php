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

    // Get the data from the form
    $teacherName = $_POST['teacher_name'];
    $selectedMonth = $_POST['selected_month'];
    $department = $_POST['department'];
    $year = $_POST['year'];
    $subject = $_POST['subject'];
    $which = $_POST['which'];
    $timeSlot = $_POST['timeSlot'];

    // Extract date components from the selected date
    $selectedDateParts = explode(" ", $_POST['selectedDate']);
    $selectedYear = $selectedDateParts[2];
    $selectedMonth = $selectedDateParts[1];
    $selectedDay = $selectedDateParts[0];

    // Format the date as "YYYY-MM-DD"
    $formattedDate = "$selectedYear-$selectedMonth-$selectedDay";

    // Calculate the day of the week
    $dayOfWeek = date('D', strtotime($formattedDate));

    // Fetch teacher_id based on teacher_name (assuming teacher_name is unique)
    $teacherQuery = "SELECT id FROM teachers WHERE fullname = ?";
    $teacherStmt = $conn->prepare($teacherQuery);
    $teacherStmt->bind_param("s", $teacherName);
    $teacherStmt->execute();
    $teacherResult = $teacherStmt->get_result();

    if ($teacherResult->num_rows > 0) {
        $teacherRow = $teacherResult->fetch_assoc();
        $teacherId = $teacherRow['id'];
    } else {
        // Handle the case where the teacher doesn't exist
        die("Teacher not found in the database.");
    }

    // Fetch selection_id based on teacher_id (assuming unique combination)
    $selectionQuery = "SELECT selection_id FROM selectedsubanddept WHERE teacher_id = ?";
    $selectionStmt = $conn->prepare($selectionQuery);
    $selectionStmt->bind_param("s", $teacherId);
    $selectionStmt->execute();
    $selectionResult = $selectionStmt->get_result();

    if ($selectionResult->num_rows > 0) {
        $selectionRow = $selectionResult->fetch_assoc();
        $selectionId = $selectionRow['selection_id'];
    } else {
        // Handle the case where the selection doesn't exist
        die("Selection not found in the database.");
    }

    // Check if a record with the same time_slot and date exists
    $checkSql = "SELECT * FROM timetable_data WHERE time_slot = ? AND date = ? AND which = ?";
    $checkStmt = $conn->prepare($checkSql);
    
    if (!$checkStmt) {
        die("Error preparing statement: " . $conn->error);
    }
    
    $checkStmt->bind_param("sss", $timeSlot, $formattedDate, $which);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows > 0) {
        // Update the existing record
        $updateSql = "UPDATE timetable_data SET selection_id = ?, teacher_id = ?, teacher_name = ?, department = ?, subject = ?, which = ?, time_slot = ?, date = ?, selected_month = ?, day_of_week = ?, year = ? WHERE time_slot = ? AND date = ?";
        $updateStmt = $conn->prepare($updateSql);

        if (!$updateStmt) {
            die("Error preparing update statement: " . $conn->error);
        }

        $updateStmt->bind_param("ssssssssssss", $selectionId, $teacherId, $teacherName, $department, $subject, $which, $timeSlot, $formattedDate, $selectedMonth, $dayOfWeek, $year, $timeSlot, $formattedDate);

        if ($updateStmt->execute()) {
            echo "Data updated successfully.";
        } else {
            echo "Error updating data: " . $updateStmt->error;
        }
    } else {
        // Insert a new record
        $insertSql = "INSERT INTO timetable_data (selection_id, teacher_id, teacher_name, department, subject, which, time_slot, date, selected_month, day_of_week, year) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $insertStmt = $conn->prepare($insertSql);

        if (!$insertStmt) {
            die("Error preparing insert statement: " . $conn->error);
        }

        $insertStmt->bind_param("ssssssssssi", $selectionId, $teacherId, $teacherName, $department, $subject, $which, $timeSlot, $formattedDate, $selectedMonth, $dayOfWeek, $year);

        if ($insertStmt->execute()) {
            echo "Data inserted successfully.";
        } else {
            echo "Error inserting data: " . $insertStmt->error;
        }
    }

    // Close the database connection
    $conn->close();
} else {
    // Handle the case where the form was not submitted properly
    echo "Form submission error.";
}
?>
