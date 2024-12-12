<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Patient Information</title>
    <link rel="stylesheet" href="./doctorUpdate.css">
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Update Patient Information</h1>
            <a id="back" href="doctorHome.php">Return to Dashboard</a>
        </div>
        <div id="messages"></div>
        <form id="updateForm">
            <label for="PID">Select Patient:</label>
            <select id="PID" name="PID" required>
                <?php
                session_start();
                require_once("config.php");

                if (!isset($_SESSION["ID"])) {
                    echo "<option value=''>Session not set. Please log in again.</option>";
                } else {
                    $db = get_db();
                    $patientsQuery = $db->prepare("SELECT PID, pname, Sex FROM Patient WHERE DID = ?");
                    $patientsQuery->bindParam(1, $_SESSION["ID"], PDO::PARAM_INT);
                    $patientsQuery->execute();
                    $patients = $patientsQuery->fetchAll(PDO::FETCH_ASSOC);

                    if (empty($patients)) {
                        echo "<option value=''>No patients available</option>";
                    } else {
                        foreach ($patients as $patient) {
                            echo "<option value='" . htmlspecialchars($patient['PID']) . "'>" .
                                htmlspecialchars($patient['pname']) . " (" . htmlspecialchars($patient['Sex']) . ")" .
                                "</option>";
                        }
                    }
                }
                ?>
            </select>
            <label for="Weight">Weight:</label>
            <input type="text" id="Weight" name="Weight">
            <label for="Height">Height:</label>
            <input type="text" id="Height" name="Height">
            <label for="Age">Age:</label>
            <input type="number" id="Age" name="Age">
            <label for="Password">Password (leave blank if unchanged):</label>
            <input type="password" id="Password" name="Password">
            <label for="DID">Doctor ID:</label>
            <input type="number" id="DID" name="DID">
            <button type="submit">Update Patient</button>
        </form>
    </div>
    <footer>© 2024 dreamsync</footer>

    <script>
        document.getElementById("updateForm").addEventListener("submit", async function (event) {
            event.preventDefault();

            const formData = new FormData(event.target);
            const data = Object.fromEntries(formData);

            try {
                const response = await fetch("controllers/docUpdateApi.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();
                const messages = document.getElementById("messages");
                messages.innerHTML = "";

                if (response.ok) {
                    messages.innerHTML = `<div class="success">${result.message}</div>`;
                } else {
                    messages.innerHTML = `<div class="error"><ul>${result.errors.map(err => `<li>${err}</li>`).join("")}</ul></div>`;
                }
            } catch (error) {
                console.error("Error:", error);
                document.getElementById("messages").innerHTML = `<div class="error">An error occurred. Please try again.</div>`;
            }
        });
    </script>
</body>

</html>


