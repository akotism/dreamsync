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

    // Query to fetch sleep reports for the doctor's patients ordered by SRDate
    $sleepReportQuery = $db->prepare("
        SELECT sr.SRDate, sr.PID, sr.Rating, sr.RoomTemperature, sr.Hours, sr.Food, sr.Mood, sr.Caffeine, sr.Stress, sr.Exercise, sr.Notes
        FROM SleepReport sr
        JOIN Patient p ON sr.PID = p.PID
        WHERE p.DID = ?
        ORDER BY sr.SRDate
    ");
    $sleepReportQuery->bindParam(1, $_SESSION["ID"], PDO::PARAM_INT);

    if ($sleepReportQuery->execute()) {
        $sleepReports = $sleepReportQuery->fetchAll(PDO::FETCH_ASSOC);
    } else {
        throw new Exception("Failed to execute sleep report query: " . implode(", ", $sleepReportQuery->errorInfo()));
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
    <title>Doctor Data</title>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Sleep Reports for Doctor <?php echo htmlspecialchars($_SESSION["dname"]); ?></h1>
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
            <tbody>
                <?php foreach ($sleepReports as $report): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($report['SRDate']); ?></td>
                        <td><?php echo htmlspecialchars($report['PID']); ?></td>
                        <td><?php echo htmlspecialchars($report['Rating']); ?></td>
                        <td><?php echo htmlspecialchars($report['RoomTemperature']); ?></td>
                        <td><?php echo htmlspecialchars($report['Hours']); ?></td>
                        <td><?php echo htmlspecialchars($report['Food']); ?></td>
                        <td><?php echo htmlspecialchars($report['Mood']); ?></td>
                        <td><?php echo htmlspecialchars($report['Caffeine']); ?></td>
                        <td><?php echo htmlspecialchars($report['Stress']); ?></td>
                        <td><?php echo htmlspecialchars($report['Exercise']); ?></td>
                        <td><?php echo htmlspecialchars($report['Notes']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a id="back" href="doctorHome.php">Back to Dashboard</a>
    </div>
</body>
</html>

