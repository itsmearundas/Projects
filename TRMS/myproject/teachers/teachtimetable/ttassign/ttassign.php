<!DOCTYPE html>
<html>
<head>
    <title>TIMETABLE ASSIGNMENT</title>
    <meta charset="UTF-8"/>
    <link rel="stylesheet" href="ttassign.css" />
    <script src="script.js" defer></script>
</head>
<body class="teachbackground" style="background-image: url('../teachimg/1teacherback.jpg');">

    <div class="wholesessionassign">
        <div class="navigatorsassign">
                <div class="l1">
                     <a href="../../../index.html"> <img src="../../../logoss/trmslogo.jpg" alt="TRMS" height="150" width="150" /></a>
                 </div>           
                 <div class="n3">
                     <a href="searchtimetable.php" >  <h3 id="adt4">SEARCH</h3></a><br>
                 </div>
                 <div class="n3">
                     <a href="#" >  <h3 id="adt4">HELP</h3></a><br>
                 </div>
                 <div class="n1">
                      <a href="../teachtimetable.php">  <h3 id="adt2">GO BACK</h3></a><br>      
                 </div>               
                
        </div>    
        <div class="attendance">
            <div class="details">
            <?php
// Start the session (if not already started)
session_start();

// Check if the user is logged in and the session variable is set
if (isset($_SESSION["user_id"])) {
    // Fetch user-related data from the session
    $user_id = $_SESSION["user_id"];

    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "trms";

    // Create a new database connection
    $conn = new mysqli($servername, $username, $password, $database);

    // Check if the database connection was successful
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Query to fetch teacher's name
    $teacherNameQuery = "SELECT fullname FROM teachers WHERE id = $user_id";
    $result = $conn->query($teacherNameQuery);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $teacher_name = $row['fullname'];
    }

    // Initialize arrays
    $teacher_departments = [];
    $teacher_subjects = [];

    // Query to fetch teacher's departments and subjects
    $departmentsAndSubjectsQuery = "SELECT d.department_name, s.subject_name 
                                    FROM selectedsubanddept sd
                                    JOIN departments d ON sd.department_id = d.department_id
                                    JOIN subjects s ON sd.subject_id = s.subject_id
                                    WHERE sd.teacher_id = $user_id";

    $departmentsAndSubjectsResult = $conn->query($departmentsAndSubjectsQuery);

    if ($departmentsAndSubjectsResult && $departmentsAndSubjectsResult->num_rows > 0) {
        while ($row = $departmentsAndSubjectsResult->fetch_assoc()) {
            $teacher_department = $row['department_name'];
            $teacher_subject = $row['subject_name'];

            // Add department to the list if it hasn't been added yet
            if (!in_array($teacher_department, $teacher_departments)) {
                $teacher_departments[] = $teacher_department;
            }

            // Add subject to the corresponding department's list
            if (!isset($teacher_subjects[$teacher_department])) {
                $teacher_subjects[$teacher_department] = [];
            }
            $teacher_subjects[$teacher_department][] = $teacher_subject;
        }
    }

    // Close the database connection
    $conn->close();
} else {
    // Handle the case where the user is not logged in
    // You can redirect them to the login page or show an error message
}
?>




                <div class="userdetails">
                    <!-- Teacher name -->
                    <div class="tname">
                        NAME: <?php echo $teacher_name; ?>
                    </div>
                    <!-- Loop through departments and display subjects -->
                    <!-- Inside your HTML code where you display departments and subjects -->
                            <!-- Loop through departments and display subjects -->
                            <?php for ($i = 0; $i < count($teacher_departments); $i++) : ?>
                                <div class="department">
                                    <div class="tdept">
                                        DEPARTMENT: <?php echo $teacher_departments[$i]; ?>
                                    </div>
                                    <!-- Check if the department key exists in $teacher_subjects before accessing it -->
                                    <?php if (isset($teacher_subjects[$teacher_departments[$i]])) : ?>
                                        <div class="tsub">
                                            SUBJECTS: <?php echo implode(', ', $teacher_subjects[$teacher_departments[$i]]); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endfor; ?>

                </div>
                <!-- Month-wise calendar -->
                <div class="month">
                    <div class="calendar">
                        <div class="header">
                            <button id="prevMonth" >Previous Month</button>
                            <h2 id="monthYear">September 2023</h2>
                            <button id="nextMonth">Next Month</button>
                        </div>
                        <table>
                            <tbody id="calendarBody">
                                <!-- Calendar rows will be generated here -->
                            </tbody>
                        </table>
                    </div>

                    <!-- Pop-up dialog -->
                    <div id="modal" class="modal">
                        <div class="modal-content">
                            <div class="dotdetails">
                                <h3 id="selectedDate">DATE</h3>
                                
                                <p id="selectedDay">DAY</p>
                                <p id="selectedTimeSlot">TIME SLOT</p>
                            </div>
                            <form action="ttainsertion.php" method="POST">
                            <input type="hidden" id="teacherNameInput" name="teacher_name" value="<?php echo $teacher_name; ?>" />
                            <input type="hidden" id="selectedYearInput" name="selected_year" value="" />
                            <input type="hidden" id="selectedMonthInput" name="selected_month" value="" />
                            <input type="hidden" id="popupYear" name="year" value="" />

                           


                            
<div class="idept">
    DEPARTMENT:
    <select id="popupDepartment" name="department">
        <?php
        // Loop through the teacher's departments and create options
        foreach ($teacher_departments as $department) {
            echo "<option value='" . htmlspecialchars($department) . "'>" . htmlspecialchars($department) . "</option>";
        }
        ?>
    </select>
</div><br>
<div class="isub">
    SUBJECT:
    <select id="popupSubject" name="subject">
        <!-- Subject options will be dynamically populated based on the selected department -->
    </select>
</div><br>
<div class="iyear">
    WHICH YEAR:
    <select id="popupWhich" name="which">
        <?php
        // Generate options for years from 1 to 10
        for ($i = 1; $i <= 10; $i++) {
            echo "<option value='$i'>$i</option>";
        }
        ?>
    </select>
</div><br>

                                <div class="isave">
                                     <button type="submit" id="popupSave" >Save</button>
                                </div><br>
                                <div class="iclose">
                                     <span class="close" >&times;</span>
                                </div><br>
                            </form>
             

                            <script>
    document.addEventListener("DOMContentLoaded", function () {
        const popupForm = document.querySelector("form");
        const popupDepartmentInput = document.getElementById("popupDepartment");
        const popupYearInput = document.getElementById("popupYear");
        const popupSubjectInput = document.getElementById("popupSubject");
        const popupWhichInput = document.getElementById("popupWhich");
        const popupSaveButton = document.getElementById("popupSave");

        // Function to generate the departmentSubjects object
        function generateDepartmentSubjects() {
            <?php
            // Retrieve the teacher's departments and subjects from the $_GET parameter

            // Convert PHP arrays to JavaScript objects for department-subject mappings
            echo 'var departmentSubjects = {';

            for ($i = 0; $i < count($teacher_departments); $i++) {
                echo '"' . htmlspecialchars($teacher_departments[$i]) . '": [';
                $subjects = $teacher_subjects[$teacher_departments[$i]];
                foreach ($subjects as $subject) {
                    echo '"' . htmlspecialchars($subject) . '", ';
                }
                echo '], ';
            }

            echo '};';
            ?>

            return departmentSubjects;
        }

        const departmentSubjects = generateDepartmentSubjects();
        console.log(departmentSubjects);

        // Function to populate subject options based on the selected department in the popup
        function populateSubjectOptions() {
            console.log("populateSubjectOptions called");
            const selectedDepartment = popupDepartmentInput.value;
            const subjects = departmentSubjects[selectedDepartment];

            // Clear existing options
            popupSubjectInput.innerHTML = '';

            // Populate subject options
            if (subjects) {
                for (const subject of subjects) {
                    const option = document.createElement('option');
                    option.value = subject;
                    option.text = subject;
                    popupSubjectInput.appendChild(option);
                }
            }
        }

        // Event listener to update subject options when the department selection changes
        popupDepartmentInput.addEventListener("change", populateSubjectOptions);

        // Event listener to save data in the popup
        popupSaveButton.addEventListener("click", function (event) {
            event.preventDefault(); // Prevent the form from submitting

            const department = popupDepartmentInput.value;
            const year = popupYearInput.value;
            const subject = popupSubjectInput.value;
            const which = popupWhichInput.value;

            // Add your code here to save the selected data
            // You can use JavaScript to interact with the parent window
            // Example: window.opener.saveData(department, year, subject);

            // Close the popup
            window.close();
        });

        // Initial population of subject options
        populateSubjectOptions();
    });
</script>

             
        </div>
    </div>
                       </div> <!--montwise-->

                                                                



                        <div class="marking">

                       </div>                      <!--marking-->



                 </div><!--attendance-->
           
         </div><!--wholesection-->
</body>
</html>
