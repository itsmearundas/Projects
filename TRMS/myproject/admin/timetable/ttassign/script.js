
document.addEventListener("DOMContentLoaded", function () {
    const calendarBody = document.getElementById("calendarBody");
    const monthYear = document.getElementById("monthYear");
    const prevMonthButton = document.getElementById("prevMonth");
    const nextMonthButton = document.getElementById("nextMonth");
    const departmentInput = document.getElementById("department");
    const subjectInput = document.getElementById("subject");

    const saveDataButton = document.getElementById("saveData");
    const modal = document.getElementById("modal");
    const close = document.querySelector(".close");
    const popupDepartmentInput = document.getElementById("popupDepartment");
    const popupYearInput = document.getElementById("popupYear");
    const popupSubjectInput = document.getElementById("popupSubject");
    const popupWhichInput = document.getElementById("popupWhich");
    const popupSaveButton = document.getElementById("popupSave");
    const selectedDate = document.getElementById("selectedDate");
    const selectedDay = document.getElementById("selectedDay");
    const selectedTimeSlot = document.getElementById("selectedTimeSlot");

    // Initialize with the current date
    let currentDate = new Date();


       function generateCalendar() {
        const year = currentDate.getFullYear();
        const month = currentDate.getMonth();

        const firstDayOfMonth = new Date(year, month, 1);
        const lastDayOfMonth = new Date(year, month + 1, 0);

        monthYear.textContent = `${new Intl.DateTimeFormat('en-US', { month: 'long' }).format(currentDate)} ${year}`;

        let day = new Date(firstDayOfMonth);
    
        calendarBody.innerHTML = '';
    
        while (day <= lastDayOfMonth) {
            const row = document.createElement("tr");
    
            // Create a cell for date, day, year, and month
            const dateCell = document.createElement("td");
            const date = day.getDate();
            const dayName = new Intl.DateTimeFormat('en-US', { weekday: 'short' }).format(day);
            dateCell.innerHTML = `<div class="date">${date}</div><div class="day">${dayName}</div><div class="year">${year}</div><div class="month">${new Intl.DateTimeFormat('en-US', { month: 'long' }).format(currentDate)}</div>`;
    
            // Create a cell for option dots
            const dotsCell = document.createElement("td");
            const dots = document.createElement("div");
            dots.className = "dots";
    
            // Create 5 dots for time slots
            for (let i = 1; i <= 5; i++) {
                const dot = document.createElement("span");
                dot.className = "dot";
                dot.dataset.department = "";
                dot.dataset.subject = "";
                
                dot.dataset.timeSlot = `${i} hour`;
                dots.appendChild(dot);
    

                
                // Add labels under each dot
                const label = document.createElement("div");
                label.className = "time-label";
                label.textContent = `${i} hour`;
                dots.appendChild(label);
            }
            dotsCell.appendChild(dots);
    
            row.appendChild(dateCell);
            row.appendChild(dotsCell);
    
            calendarBody.appendChild(row);
            day.setDate(day.getDate() + 1);
        }
    }
    
    function showModal(dot) {
        modal.style.display = "block";
        const dotDepartment = dot.dataset.department || "";
        const dotSubject = dot.dataset.subject || "";
     
        const dotTimeSlot = dot.dataset.timeSlot || "";
        const dotDateCell = dot.closest("tr").querySelector(".date");
        const dotDay = dotDateCell.textContent;
        const dotMonth = dotDateCell.nextElementSibling.nextElementSibling.textContent;
        const dotYear = dotDateCell.nextElementSibling.nextElementSibling.nextElementSibling.textContent;
    
        popupDepartmentInput.value = dotDepartment;
        popupYearInput.value = ""; // Clear the year input field
        popupSubjectInput.value = dotSubject;
        popupWhichInput.value = "";
    
        selectedDate.textContent = `Selected Date: ${dotYear} ${dotMonth} ${dotDay}`;
        selectedDay.textContent = `Day: ${dotDateCell.nextElementSibling.textContent}`;
        selectedTimeSlot.textContent = `Time Slot: ${dotTimeSlot}`;
    
        sendDateToServer(dotDateCell.getAttribute("data-date"));
    }
    

    function closeModal() {
        modal.style.display = "none";
    }

    function sendDateToServer(selectedDate) {
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "ttainsertion.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    console.log("Selected date sent to the server: " + selectedDate);
                } else {
                    console.error("Error sending selected date to the server.");
                }
            }
        };
    
        const selectedYear = popupYearInput.value; // Get the selected year from user input
    
        // Format the date as "YYYY-MM-DD"
        const formattedDate = `${selectedYear}-${padZero(selectedMonth)}-${padZero(selectedDay)}`;
    
        const data = `selectedDate=${encodeURIComponent(formattedDate)}&selectedYear=${encodeURIComponent(selectedYear)}`;
        xhr.send(data);
    }
    
// Helper function to pad a single-digit number with a leading zero
function padZero(number) {
    return number.toString().padStart(2, '0');
}

    
    
prevMonthButton.addEventListener("click", function () {
    currentDate.setMonth(currentDate.getMonth() - 1);
    generateCalendar();
});

nextMonthButton.addEventListener("click", function () {
    currentDate.setMonth(currentDate.getMonth() + 1);
    generateCalendar();
});

    calendarBody.addEventListener("click", function (event) {
        if (event.target.classList.contains("dot")) {
            showModal(event.target);
            const selectedMonth = monthYear.textContent;
            document.getElementById("selectedMonthInput").value = selectedMonth;
        }
    });

    close.addEventListener("click", closeModal);

   // ...
popupSaveButton.addEventListener("click", function (event) {
    event.preventDefault();

    const department = popupDepartmentInput.value;
    const year = popupYearInput.value;
    const subject = popupSubjectInput.value;
    const which = popupWhichInput.value;
    const timeSlot = selectedTimeSlot.textContent;
    const dateText = selectedDate.textContent;
    const dateParts = dateText.split(" ");
    const day = dateParts[dateParts.length - 1]; // Extract the last part as the day
    const monthYearText = monthYear.textContent;
    const monthYearParts = monthYearText.split(" ");
    const month = monthYearParts[0];
    const yearValue = monthYearParts[1];

    const teacherName = document.getElementById("teacherNameInput").value;
    const selectedMonth = document.getElementById("selectedMonthInput").value;

    const xhr = new XMLHttpRequest();
    xhr.open("POST", "ttainsertion.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                alert("Data inserted successfully.");
                closeModal();
            } else {
                alert("Error: " + xhr.responseText);
            }
        }
    };

    // Format the date as "YYYY-MM-DD"
   const formattedDate = `${yearValue} ${month} ${day}`;

   const data = `teacher_name=${teacherName}&selected_month=${selectedMonth}&department=${department}&year=${yearValue}&subject=${subject}&which=${which}&timeSlot=${timeSlot}&selectedDate=${formattedDate}`;

   
xhr.send(data);
});
// ...

// ...


    generateCalendar();
});


document.addEventListener("DOMContentLoaded", function () {
    const calendarBody = document.getElementById("calendarBody");
    const monthYear = document.getElementById("monthYear");
    const prevMonthButton = document.getElementById("prevMonth");
    const nextMonthButton = document.getElementById("nextMonth");
    const departmentInput = document.getElementById("department");
    const subjectInput = document.getElementById("subject");

    const saveDataButton = document.getElementById("saveData");
    const modal = document.getElementById("modal");
    const close = document.querySelector(".close");
    const popupDepartmentInput = document.getElementById("popupDepartment");
    const popupYearInput = document.getElementById("popupYear");
    const popupSubjectInput = document.getElementById("popupSubject");
    const popupWhichInput = document.getElementById("popupWhich");
    const popupSaveButton = document.getElementById("popupSave");
    const selectedDate = document.getElementById("selectedDate");
    const selectedDay = document.getElementById("selectedDay");
    const selectedTimeSlot = document.getElementById("selectedTimeSlot");

    // Initialize with the current date
    let currentDate = new Date();


       function generateCalendar() {
        const year = currentDate.getFullYear();
        const month = currentDate.getMonth();

        const firstDayOfMonth = new Date(year, month, 1);
        const lastDayOfMonth = new Date(year, month + 1, 0);

        monthYear.textContent = `${new Intl.DateTimeFormat('en-US', { month: 'long' }).format(currentDate)} ${year}`;

        let day = new Date(firstDayOfMonth);
    
        calendarBody.innerHTML = '';
    
        while (day <= lastDayOfMonth) {
            const row = document.createElement("tr");
    
            // Create a cell for date, day, year, and month
            const dateCell = document.createElement("td");
            const date = day.getDate();
            const dayName = new Intl.DateTimeFormat('en-US', { weekday: 'short' }).format(day);
            dateCell.innerHTML = `<div class="date">${date}</div><div class="day">${dayName}</div><div class="year">${year}</div><div class="month">${new Intl.DateTimeFormat('en-US', { month: 'long' }).format(currentDate)}</div>`;
    
            // Create a cell for option dots
            const dotsCell = document.createElement("td");
            const dots = document.createElement("div");
            dots.className = "dots";
    
            // Create 5 dots for time slots
            for (let i = 1; i <= 5; i++) {
                const dot = document.createElement("span");
                dot.className = "dot";
                dot.dataset.department = "";
                dot.dataset.subject = "";
                
                dot.dataset.timeSlot = `${i} hour`;
                dots.appendChild(dot);
    

                
                // Add labels under each dot
                const label = document.createElement("div");
                label.className = "time-label";
                label.textContent = `${i} hour`;
                dots.appendChild(label);
            }
            dotsCell.appendChild(dots);
    
            row.appendChild(dateCell);
            row.appendChild(dotsCell);
    
            calendarBody.appendChild(row);
            day.setDate(day.getDate() + 1);
        }
    }
    
    function showModal(dot) {
        modal.style.display = "block";
        const dotDepartment = dot.dataset.department || "";
        const dotSubject = dot.dataset.subject || "";
     
        const dotTimeSlot = dot.dataset.timeSlot || "";
        const dotDateCell = dot.closest("tr").querySelector(".date");
        const dotDay = dotDateCell.textContent;
        const dotMonth = dotDateCell.nextElementSibling.nextElementSibling.textContent;
        const dotYear = dotDateCell.nextElementSibling.nextElementSibling.nextElementSibling.textContent;
    
        popupDepartmentInput.value = dotDepartment;
        popupYearInput.value = ""; // Clear the year input field
        popupSubjectInput.value = dotSubject;
        popupWhichInput.value = "";
    
        selectedDate.textContent = `Selected Date: ${dotYear} ${dotMonth} ${dotDay}`;
        selectedDay.textContent = `Day: ${dotDateCell.nextElementSibling.textContent}`;
        selectedTimeSlot.textContent = `Time Slot: ${dotTimeSlot}`;
    
        sendDateToServer(dotDateCell.getAttribute("data-date"));
    }
    

    function closeModal() {
        modal.style.display = "none";
    }

    function sendDateToServer(selectedDate) {
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "ttainsertion.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    console.log("Selected date sent to the server: " + selectedDate);
                } else {
                    console.error("Error sending selected date to the server.");
                }
            }
        };
    
        const selectedYear = popupYearInput.value; // Get the selected year from user input
    
        // Format the date as "YYYY-MM-DD"
        const formattedDate = `${selectedYear}-${padZero(selectedMonth)}-${padZero(selectedDay)}`;
    
        const data = `selectedDate=${encodeURIComponent(formattedDate)}&selectedYear=${encodeURIComponent(selectedYear)}`;
        xhr.send(data);
    }
    
// Helper function to pad a single-digit number with a leading zero
function padZero(number) {
    return number.toString().padStart(2, '0');
}

    
    
prevMonthButton.addEventListener("click", function () {
    currentDate.setMonth(currentDate.getMonth() - 1);
    generateCalendar();
});

nextMonthButton.addEventListener("click", function () {
    currentDate.setMonth(currentDate.getMonth() + 1);
    generateCalendar();
});

    calendarBody.addEventListener("click", function (event) {
        if (event.target.classList.contains("dot")) {
            showModal(event.target);
            const selectedMonth = monthYear.textContent;
            document.getElementById("selectedMonthInput").value = selectedMonth;
        }
    });

    close.addEventListener("click", closeModal);

   // ...
popupSaveButton.addEventListener("click", function (event) {
    event.preventDefault();

    const department = popupDepartmentInput.value;
    const year = popupYearInput.value;
    const subject = popupSubjectInput.value;
    const which = popupWhichInput.value;
    const timeSlot = selectedTimeSlot.textContent;
    const dateText = selectedDate.textContent;
    const dateParts = dateText.split(" ");
    const day = dateParts[dateParts.length - 1]; // Extract the last part as the day
    const monthYearText = monthYear.textContent;
    const monthYearParts = monthYearText.split(" ");
    const month = monthYearParts[0];
    const yearValue = monthYearParts[1];

    const teacherName = document.getElementById("teacherNameInput").value;
    const selectedMonth = document.getElementById("selectedMonthInput").value;

    const xhr = new XMLHttpRequest();
    xhr.open("POST", "ttainsertion.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                alert("Data inserted successfully.");
                closeModal();
            } else {
                alert("Error: " + xhr.responseText);
            }
        }
    };

    // Format the date as "YYYY-MM-DD"
   const formattedDate = `${yearValue} ${month} ${day}`;

   const data = `teacher_name=${teacherName}&selected_month=${selectedMonth}&department=${department}&year=${yearValue}&subject=${subject}&which=${which}&timeSlot=${timeSlot}&selectedDate=${formattedDate}`;

   
xhr.send(data);
});
// ...

// ...


    generateCalendar();
});

