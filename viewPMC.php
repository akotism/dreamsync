<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Patient Health Records</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
        h1 {
            margin-top: 20px;
            text-align: center;
        }
        .container {
            margin-top: 20px;
        }
        .table {
            margin-top: 20px;
        }
        footer {
            background-color: #343a40;
            color: white;
            text-align: center;
            padding: 10px 0;
            margin-top: 30px;
        }
    </style>
    <script>
        async function fetchPatients() {
            try {
                const response = await fetch("controllers/viewPmcApi.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: new URLSearchParams({ action: "getPatients" })
                });

                const data = await response.json();
                if (data.success) {
                    const select = document.getElementById("PID");
                    select.innerHTML = ""; // Clear any existing options
                    data.data.forEach(patient => {
                        const option = document.createElement("option");
                        option.value = patient.PID;
                        option.textContent = patient.pname;
                        select.appendChild(option);
                    });
                } else {
                    alert(data.message); // Display error message
                }
            } catch (error) {
                console.error("Error fetching patients:", error);
            }
        }

        async function fetchPatientRecords(event) {
            event.preventDefault();
            const patientID = document.getElementById("PID").value;

            try {
                const response = await fetch("controllers/viewPmcApi.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: new URLSearchParams({ action: "getPatientRecords", PID: patientID })
                });

                const data = await response.json();
                console.log("Fetched Patient Records:", data);

                if (data.success) {
                    // Populate Medications Table
                    const medsTable = document.getElementById("medicationsTable").querySelector("tbody");
                    medsTable.innerHTML = "";
                    if (data.data.medications.length > 0) {
                        data.data.medications.forEach(med => {
                            const tr = document.createElement("tr");
                            const td = document.createElement("td");
                            td.textContent = med.Medication; // Match API key
                            tr.appendChild(td);
                            medsTable.appendChild(tr);
                        });
                    } else {
                        const tr = document.createElement("tr");
                        const td = document.createElement("td");
                        td.textContent = "None";
                        tr.appendChild(td);
                        medsTable.appendChild(tr);
                    }

                    // Populate Health Conditions Table
                    const condTable = document.getElementById("conditionsTable").querySelector("tbody");
                    condTable.innerHTML = "";
                    if (data.data.healthConditions.length > 0) {
                        data.data.healthConditions.forEach(cond => {
                            const tr = document.createElement("tr");
                            const td = document.createElement("td");
                            td.textContent = cond.hname; // Match API key
                            tr.appendChild(td);
                            condTable.appendChild(tr);
                        });
                    } else {
                        const tr = document.createElement("tr");
                        const td = document.createElement("td");
                        td.textContent = "None";
                        tr.appendChild(td);
                        condTable.appendChild(tr);
                    }
                } else {
                    alert(data.message); // Display error message
                }
            } catch (error) {
                console.error("Error fetching patient records:", error);
            }
        }

        document.addEventListener("DOMContentLoaded", fetchPatients);
    </script>
</head>
<body>
    <header class="bg-primary text-white text-center py-3">
        <h1>View Patient Health Records</h1>
    </header>

    <div class="container">
        <!-- Button to redirect to doctorHome.php -->
        <div class="mb-3 text-center">
            <button onclick="window.location.href='doctorHome.php'" class="btn btn-secondary">Return to Dashboard</button>
        </div>

        <!-- Form to select patient -->
        <form onsubmit="fetchPatientRecords(event)" class="text-center mb-4">
            <label for="PID" class="form-label">Select Patient:</label>
            <select id="PID" name="PID" class="form-select w-50 mx-auto" required>
                <option value="" disabled selected>Select a patient</option>
            </select>
            <button type="submit" class="btn btn-primary mt-3">View Records</button>
        </form>

        <!-- Medications Table -->
        <h2>Medications:</h2>
        <table id="medicationsTable" class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Medication</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>None</td>
                </tr>
            </tbody>
        </table>

        <!-- Health Conditions Table -->
        <h2>Health Conditions:</h2>
        <table id="conditionsTable" class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Condition</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>None</td>
                </tr>
            </tbody>
        </table>
    </div>

    <footer class="bg-dark text-white text-center py-3">
        © 2024 DreamSync
    </footer>
</body>
</html>
