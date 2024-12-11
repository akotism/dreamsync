<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Data</title>
    <link rel="stylesheet" href="./doctorData.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Sleep Reports for Doctor <span id="doctorName"></span></h1>
            <form action="logout.php" method="post">
                <button type="submit" id="logout" name="logout">Logout</button>
            </form>
        </div>
        <h2>Your Patients' Sleep Reports:</h2>
        <table>
            <thead>
                <tr>
                    <th>SRDate</th>
                    <th>PID</th>
                    <th>Rating</th>
                    <th>Room Temperature</th>
                    <th>Hours</th>
                    <th>Food</th>
                    <th>Mood</th>
                    <th>Caffeine</th>
                    <th>Stress</th>
                    <th>Exercise</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody id="sleepReportsTableBody">
                <!-- Rows will be populated dynamically -->
            </tbody>
        </table>
        <a id="back" href="doctorHome.php">Return to Dashboard</a>
    </div>

    <script>
        // Populate the table with sleep reports
        async function loadSleepReports() {
            try {
                const response = await fetch('controllers/doctorDataAPi.php');
                if (!response.ok) {
                    throw new Error(`Failed to fetch data: ${response.status}`);
                }
                const sleepReports = await response.json();

                // Check for errors in the response
                if (sleepReports.error) {
                    console.error("API Error:", sleepReports.error);
                    return;
                }

                const tableBody = document.getElementById('sleepReportsTableBody');
                tableBody.innerHTML = ''; // Clear existing rows

                // Populate rows
                sleepReports.forEach(report => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${report.SRDate}</td>
                        <td>${report.PID}</td>
                        <td>${report.Rating}</td>
                        <td>${report.RoomTemperature}</td>
                        <td>${report.Hours}</td>
                        <td>${report.Food}</td>
                        <td>${report.Mood}</td>
                        <td>${report.Caffeine}</td>
                        <td>${report.Stress}</td>
                        <td>${report.Exercise}</td>
                        <td>${report.Notes}</td>
                    `;
                    tableBody.appendChild(row);
                });

                // Set the doctor's name
                document.getElementById('doctorName').textContent = sessionStorage.getItem('doctorName') || 'Doctor';
            } catch (error) {
                console.error('Error loading sleep reports:', error);
            }
        }

        // Load the data on page load
        loadSleepReports();
    </script>
</body>
</html>


