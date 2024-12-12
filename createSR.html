<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Sleep Report</title>
    <link rel="stylesheet" href="./sleepreport.css">
</head>
<body>
    <div class="container">
        <h2>Create Sleep Report</h2>
        <form id="createSleepReportForm">
            <div class="form-group">
                <label for="patientName">Patient Name:</label>
                <input type="text" id="patientName" name="patientName" required>
            </div>
            <div class="form-group">
                <label for="rating">Rating:</label>
                <input type="number" id="rating" name="rating" required>
            </div>
            <div class="form-group">
                <label for="roomTemp">Room Temperature:</label>
                <input type="text" id="roomTemp" name="roomTemp" required>
            </div>
            <div class="form-group">
                <label for="hours">Hours Slept:</label>
                <input type="text" id="hours" name="hours" required>
            </div>
            <div class="form-group">
                <label for="food">Food Eaten:</label>
                <input type="text" id="food" name="food" required>
            </div>
            <div class="form-group">
                <label for="mood">Mood:</label>
                <input type="text" id="mood" name="mood" required>
            </div>
            <div class="form-group">
                <label for="caffeine">Caffeine Intake (mg):</label>
                <input type="number" id="caffeine" name="caffeine" required>
            </div>
            <div class="form-group">
                <label for="stress">Stressful Day?</label>
                <input type="checkbox" id="stress" name="stress">
            </div>
            <div class="form-group">
                <label for="exercise">Exercise:</label>
                <input type="text" id="exercise" name="exercise" required>
            </div>
            <div class="form-group">
                <label for="notes">Notes:</label>
                <textarea id="notes" name="notes" required></textarea>
            </div>
            <button type="submit">Create Report</button>
            <a id="back" href="patientHome.php">Back to Dashboard</a>
        </form>
        <p id="reportMessage"></p>
    </div>

    <script>
        document.getElementById("createSleepReportForm").addEventListener("submit", async function(event) {
            event.preventDefault();

            const formData = new FormData(event.target);
            const data = Object.fromEntries(formData);

            data.stress = document.getElementById("stress").checked;

            try {
                const response = await fetch('controllers/createSRApi.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();
                const messageElement = document.getElementById("reportMessage");

                if (response.ok) {
                    messageElement.textContent = result.message;
                    messageElement.style.color = "green";
                } else {
                    messageElement.textContent = result.error || "An error occurred.";
                    messageElement.style.color = "red";
                }
            } catch (error) {
                console.error('Error:', error);
                const messageElement = document.getElementById("reportMessage");
                messageElement.textContent = "An unexpected error occurred.";
                messageElement.style.color = "red";
            }
        });
    </script>
</body>
</html>

