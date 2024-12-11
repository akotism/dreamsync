<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Polysomnogram Data</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        h1 {
            margin-top: 20px;
            text-align: center;
        }
        .table-container {
            margin: 20px auto;
            max-width: 90%;
        }
        .chart-container {
            margin: 40px auto;
            max-width: 80%;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        #chart {
            height: 400px;
        }
        footer {
            background-color: #343a40;
            color: white;
            text-align: center;
            padding: 10px 0;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <header class="bg-primary text-white text-center py-3">
        <h1>Polysomnogram Data for Doctor <span id="doctorName"></span></h1>
    </header>

    <!-- Chart Section -->
    <div class="chart-container">
        <h3 class="text-center">Polysomnogram Overview</h3>
        <canvas id="chart"></canvas>
    </div>

    <!-- Table Section -->
    <div class="table-container">
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>PDate</th>
                    <th>Patient Name</th>
                    <th>PID</th>
                    <th>N1Time</th>
                    <th>N2Time</th>
                    <th>N3Time</th>
                    <th>REMTime</th>
                    <th>Oxygen Levels</th>
                    <th>Times Woken Up</th>
                    <th>Heart Rate</th>
                </tr>
            </thead>
            <tbody id="polysomnogramTableBody">
                <!-- Rows will be populated dynamically -->
            </tbody>
        </table>
    </div>

    <nav class="text-center mb-3">
        <a href="doctorHome.php" class="btn btn-secondary">Return to Dashboard</a>
    </nav>

    <form action="logout.php" method="post" class="text-center mb-3">
        <button type="submit" id="logout" name="logout" class="btn btn-danger">Logout</button>
    </form>

    <footer class="bg-dark text-white text-center py-3">
        © 2024 DreamSync
    </footer>

    <script>
        async function loadPolysomnograms() {
            try {
                const response = await fetch('controllers/doctorPolysomnogramApi.php');
                if (!response.ok) {
                    throw new Error(`Failed to fetch data: ${response.status}`);
                }
                const polysomnograms = await response.json();

                if (polysomnograms.error) {
                    console.error(polysomnograms.error);
                    return;
                }

                const tableBody = document.getElementById('polysomnogramTableBody');
                tableBody.innerHTML = ''; // Clear existing rows

                // Data for Chart.js
                const labels = [];
                const n1Data = [];
                const n2Data = [];
                const n3Data = [];
                const remData = [];

                polysomnograms.forEach(data => {
                    labels.push(data.PatientName);
                    n1Data.push(data.N1Time);
                    n2Data.push(data.N2Time);
                    n3Data.push(data.N3Time);
                    remData.push(data.REMTime);

                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${data.PDate}</td>
                        <td>${data.PatientName}</td>
                        <td>${data.PID}</td>
                        <td>${data.N1Time}</td>
                        <td>${data.N2Time}</td>
                        <td>${data.N3Time}</td>
                        <td>${data.REMTime}</td>
                        <td>${data.OxygenLevels}</td>
                        <td>${data.TimesWokenUp}</td>
                        <td>${data.HeartRate}</td>
                    `;
                    tableBody.appendChild(row);
                });

                // Initialize Chart.js
                const ctx = document.getElementById('chart').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [
                            {
                                label: 'N1 Time',
                                data: n1Data,
                                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 1
                            },
                            {
                                label: 'N2 Time',
                                data: n2Data,
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 1
                            },
                            {
                                label: 'N3 Time',
                                data: n3Data,
                                backgroundColor: 'rgba(255, 206, 86, 0.2)',
                                borderColor: 'rgba(255, 206, 86, 1)',
                                borderWidth: 1
                            },
                            {
                                label: 'REM Time',
                                data: remData,
                                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                borderColor: 'rgba(255, 99, 132, 1)',
                                borderWidth: 1
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top'
                            },
                            title: {
                                display: true,
                                text: 'Polysomnogram Data'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            } catch (error) {
                console.error('Error loading polysomnograms:', error);
            }
        }

        // Load polysomnograms on page load
        loadPolysomnograms();
    </script>
</body>
</html>
