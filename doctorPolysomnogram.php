<?php
// Start the session
session_start();

// Check if session variables are set
if (!isset($_SESSION["ID"]) || !isset($_SESSION["dname"])) {
    echo "Session data not set. Please log in again.";
    exit;
}

require_once("config.php");

try {
    $db = get_db();

    // Query to fetch polysomnogram data for the doctor's patients ordered by PDate
    $polysomnogramQuery = $db->prepare("
        SELECT ps.PDate, p.pname AS PatientName, ps.PID, ps.N1Time, ps.N2Time, ps.N3Time, ps.REMTime, ps.OxygenLevels, ps.TimesWokenUp, ps.HeartRate
        FROM Polysomnogram ps
        JOIN Patient p ON ps.PID = p.PID
        WHERE p.DID = ?
        ORDER BY ps.PDate
    ");
    $polysomnogramQuery->bindParam(1, $_SESSION["ID"], PDO::PARAM_INT);

    if ($polysomnogramQuery->execute()) {
        $polysomnogramData = $polysomnogramQuery->fetchAll(PDO::FETCH_ASSOC);
    } else {
        throw new Exception("Failed to execute polysomnogram query: " . implode(", ", $polysomnogramQuery->errorInfo()));
    }

} catch (Exception $e) {
    echo "An error occurred: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Polysomnogram Data</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        h1 {
            color: #333;
        }
        h2 {
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            color: #333;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        #logout, #back, #home {
            padding: 10px 20px;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
        }
        #logout {
            background-color: #f44336;
        }
        #logout:hover {
            background-color: #d32f2f;
        }
        #back {
            background-color: #4CAF50;
        }
        #back:hover {
            background-color: #45a049;
        }
        #home {
            background-color: #008CBA;
        }
        #home:hover {
            background-color: #007BB5;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Polysomnogram Data for Doctor <?php echo htmlspecialchars($_SESSION["dname"]); ?></h1>
            <form action="logout.php" method="post">
                <button type="submit" id="logout" name="logout">Logout</button>
            </form>
        </div>
        <h2>Your Patients' Polysomnogram Data:</h2>
        <table>
            <thead>
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
            <tbody>
                <?php foreach ($polysomnogramData as $data): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($data['PDate']); ?></td>
                        <td><?php echo htmlspecialchars($data['PatientName']); ?></td>
                        <td><?php echo htmlspecialchars($data['PID']); ?></td>
                        <td><?php echo htmlspecialchars($data['N1Time']); ?></td>
                        <td><?php echo htmlspecialchars($data['N2Time']); ?></td>
                        <td><?php echo htmlspecialchars($data['N3Time']); ?></td>
                        <td><?php echo htmlspecialchars($data['REMTime']); ?></td>
                        <td><?php echo htmlspecialchars($data['OxygenLevels']); ?></td>
                        <td><?php echo htmlspecialchars($data['TimesWokenUp']); ?></td>
                        <td><?php echo htmlspecialchars($data['HeartRate']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a id="back" href="doctorHome.php">Back to Dashboard</a>
    </div>
</body>
</html>

