
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
                     <a href="#" >  <h3 id="adt4">HELP</h3></a><br>
                 </div>
                 <div class="n1">
                      <a href="../teachtimetable.php">  <h3 id="adt2">GO BACK</h3></a><br>      
                 </div>               
                
        </div>    
        <div class="attendance">
            <div class="details">
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

$teacher_id = $_GET['teacher_id'];

// Fetch the teacher's name based on their ID
$sqlTeacherName = "SELECT fullname FROM teachers WHERE id = $teacher_id";
$resultTeacherName = $conn->query($sqlTeacherName);

if ($resultTeacherName->num_rows > 0) {
    // Fetch the teacher's name
    $rowTeacherName = $resultTeacherName->fetch_assoc();
    $teacher_name = $rowTeacherName['fullname'];
}

// Fetch the selected departments and subjects for the teacher from the database
$sqlSelectedSubAndDept = "SELECT d.department_name, s.subject_name
                          FROM selectedsubanddept sd
                          INNER JOIN departments d ON sd.department_id = d.department_id
                          INNER JOIN subjects s ON sd.subject_id = s.subject_id
                          WHERE sd.teacher_id = $teacher_id";

$resultSelectedSubAndDept = $conn->query($sqlSelectedSubAndDept);

$departmentSubjects = [];

if ($resultSelectedSubAndDept->num_rows > 0) {
    while ($rowSelectedSubAndDept = $resultSelectedSubAndDept->fetch_assoc()) {
        $department = $rowSelectedSubAndDept['department_name'];
        $subject = $rowSelectedSubAndDept['subject_name'];
    
        $departmentSubjects[$department][] = $subject;
    }
    
    // Remove duplicate subjects for each department
    foreach ($departmentSubjects as $department => $subjects) {
        $departmentSubjects[$department] = array_unique($subjects);
    }
    
}
?>



             
                    <div class="userdetails">
    <!-- Teacher name -->
    <div class="tname">
        NAME: <?php echo $teacher_name; ?>
    </div>

    <!-- Loop through unique departments and display subjects -->
    <?php foreach ($departmentSubjects as $department => $subjects) : ?>
        <div class="department">
            <div class="tdept">
                DEPARTMENT: <?php echo $department; ?>
            </div>
            <div class="tsub">
                SUBJECTS: <?php echo implode(', ', $subjects); ?>
            </div>
        </div>
    <?php endforeach; ?>
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
        <?php foreach ($departmentSubjects as $department => $subjects) : ?>
            <option value="<?php echo htmlspecialchars($department); ?>"><?php echo htmlspecialchars($department); ?></option>
        <?php endforeach; ?>
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
            // Retrieve the teacher's departments and subjects from the PHP array
            echo 'var departmentSubjects = {';

            foreach ($departmentSubjects as $department => $subjects) {
                echo '"' . htmlspecialchars($department) . '": [';
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
