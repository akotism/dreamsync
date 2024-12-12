<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Polysomnogram</title>
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"> -->
    <link rel="stylesheet" href="createPS.css">
    

</head>
<body>
    <div class="container">
        <h2>Create Polysomnogram</h2>
        <form id="createPSForm">
            <div class="form-group">
                <label for="pdate">Date:</label>
                <input type="date" id="pdate" name="pdate" required>
            </div>
            <div class="form-group">
                <label for="PID">Select Patient:</label>
                <select id="PID" name="PID" required>
                    <!-- This dropdown will be populated dynamically -->
                </select>
            </div>
            <div class="form-group">
                <label for="n1time">N1 Time:</label>
                <input type="number" step="0.01" id="n1time" name="n1time" required>
            </div>
            <div class="form-group">
                <label for="n2time">N2 Time:</label>
                <input type="number" step="0.01" id="n2time" name="n2time" required>
            </div>
            <div class="form-group">
                <label for="n3time">N3 Time:</label>
                <input type="number" step="0.01" id="n3time" name="n3time" required>
            </div>
            <div class="form-group">
                <label for="remtime">REM Time:</label>
                <input type="number" step="0.01" id="remtime" name="remtime" required>
            </div>
            <div class="form-group">
                <label for="oxygenlevels">Oxygen Levels:</label>
                <input type="number" step="0.01" id="oxygenlevels" name="oxygenlevels" required>
            </div>
            <div class="form-group">
                <label for="timeswokenup">Times Woken Up:</label>
                <input type="number" id="timeswokenup" name="timeswokenup" required>
            </div>
            <div class="form-group">
                <label for="heartrate">Average Heart Rate:</label>
                <input type="number" id="heartrate" name="heartrate">
            </div>
            <button type="submit">Create</button>
        </form>
        <p id="message" class="message"></p>
        <button onclick="location.href='./doctorHome.php'">Return to Dashboard</button>
    </div>

    <script>
        // Populate the patients dropdown
    async function loadPatients() {
        try {
        const response = await fetch("controllers/createPSApi.php");
        const rawText = await response.text(); // Get raw response for debugging

        try {
            const patients = JSON.parse(rawText); // Attempt to parse JSON
            console.log("Patients fetched:", patients);

            const patientDropdown = document.getElementById("PID");
            patientDropdown.innerHTML = patients
                .map((patient) => `<option value="${patient.PID}">${patient.pname}</option>`)
                .join("");
        } catch (jsonError) {
            console.error("Invalid JSON response:", rawText);
        }
    } catch (error) {
        console.error("Error loading patients:", error);
    }
}

loadPatients();


        document.getElementById("createPSForm").addEventListener("submit", async function(event) {
            event.preventDefault();

            const formData = new FormData(event.target);
            const data = Object.fromEntries(formData);

            try {
                const response = await fetch("api/createPSApi.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();
                const messageElement = document.getElementById("message");

                if (response.ok) {
                    messageElement.textContent = result.message;
                    messageElement.style.color = "green";
                } else {
                    messageElement.textContent = result.error || "An error occurred.";
                    messageElement.style.color = "red";
                }
            } catch (error) {
                console.error("Error:", error);
                const messageElement = document.getElementById("message");
                messageElement.textContent = "An unexpected error occurred.";
                messageElement.style.color = "red";
            }
        });

        // Load patients on page load
        loadPatients();
    </script>
</body>
</html>
