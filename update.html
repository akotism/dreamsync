<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Patient Record</title>
    <link rel="stylesheet" href="./update.css">
</head>
<body>
    <h2>Update Patient Record</h2>
    <form id="updatePatientForm">
        <label for="patientID">Patient ID:</label>
        <input type="number" id="patientID" name="patientID" required><br>
        <label for="newName">New Name:</label>
        <input type="text" id="newName" name="newName" required><br>
        <label for="newAge">New Age:</label>
        <input type="number" id="newAge" name="newAge" required><br>
        <label for="newSex">New Sex:</label>
        <input type="text" id="newSex" name="newSex" required><br>
        <label for="newHeight">New Height:</label>
        <input type="text" id="newHeight" name="newHeight" required><br>
        <button type="submit">Update Record</button>
        <a id="back" href="patientHome.php">Back to Dashboard</a>
    </form>
    <p id="updateMessage"></p>

    <script>
        // Handle the form submission
        document.getElementById("updatePatientForm").addEventListener("submit", async function(event) {
            event.preventDefault();

            const formData = new FormData(event.target);
            const data = Object.fromEntries(formData);

            try {
                const response = await fetch('controllers/updateApi.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();
                const messageElement = document.getElementById("updateMessage");

                if (response.ok) {
                    messageElement.textContent = result.message;
                    messageElement.style.color = "green";
                } else {
                    messageElement.textContent = result.error || "An error occurred.";
                    messageElement.style.color = "red";
                }
            } catch (error) {
                console.error('Error:', error);
                const messageElement = document.getElementById("updateMessage");
                messageElement.textContent = "An unexpected error occurred.";
                messageElement.style.color = "red";
            }
        });
    </script>
</body>
</html>
