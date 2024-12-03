<?php
session_start();
require_once("config.php");

// Establish the database connection
$db = get_db();

// Prepare and execute the SQL query to fetch polysomnogram data
$q = $db->prepare("SELECT * FROM Polysomnogram WHERE PID = ?");
$q->bindParam(1, $_SESSION["ID"], PDO::PARAM_INT);
$q->execute();

// Fetch all the results
$POLY = $q->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Polysomnogram</title>
    <link rel="stylesheet" href="polysomnogram.css">
</head>
<body>
    <h1>Your Polysomnogram</h1>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>PID</th>
                <th>DID</th>
                <th>N1Time</th>
                <th>N2Time</th>
                <th>N3Time</th>
                <th>REMTime</th>
                <th>Oxygen Level</th>
                <th>Times Woken Up</th>
                <th>Heart Rate</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($POLY as $row) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($row['PDate']) . '</td>';
                echo '<td>' . htmlspecialchars($row['PID']) . '</td>';
                echo '<td>' . htmlspecialchars($row['DID']) . '</td>';
                echo '<td>' . htmlspecialchars($row['N1Time']) . '</td>';
                echo '<td>' . htmlspecialchars($row['N2Time']) . '</td>';
                echo '<td>' . htmlspecialchars($row['N3Time']) . '</td>';
                echo '<td>' . htmlspecialchars($row['REMTime']) . '</td>';  // Corrected to include REMTime
                echo '<td>' . htmlspecialchars($row['OxygenLevels']) . '</td>';
                echo '<td>' . htmlspecialchars($row['TimesWokenUp']) . '</td>';
                echo '<td>' . htmlspecialchars($row['HeartRate']) . '</td>';  // Ensure HeartRate is displayed
                echo '</tr>';
            }
            ?>
        </tbody>
    </table>
<nav>
    <ul>
        <li><a href="./patientHome.php">Home</a></li>
    </ul>
    <form action="logout.php" method="post">
    <button type="submit" id="logout" name="logout">Logout</button>
    </form>

</body>
</html>

