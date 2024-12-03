<?php
session_start();
require_once("config.php");

$db = get_db();

$q = $db->prepare("SELECT * FROM SleepReport WHERE PID = ?");
$q->bindParam(1, $_SESSION["ID"], PDO::PARAM_INT);
$q->execute();

$SR = $q->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sleep Reports</title>
    <link rel="stylesheet" href="./viewSR.css">
</head>
<body>
    <h1>Sleep Reports</h1>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Rating</th>
                <th>Room Temperature</th>
                <th>Sleep Hours</th>
                <th>Food</th>
                <th>Mood</th>
                <th>Caffeine (mg)</th>
                <th>Stress Level</th>
                <th>Exercise</th>
                <th>Notes</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($SR as $row) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($row['SRDate']) . '</td>';
                echo '<td>' . htmlspecialchars($row['Rating']) . '</td>';
                echo '<td>' . htmlspecialchars($row['RoomTemperature']) . '</td>';
                echo '<td>' . htmlspecialchars($row['Hours']) . '</td>';
                echo '<td>' . htmlspecialchars($row['Food']) . '</td>';
                echo '<td>' . htmlspecialchars($row['Mood']) . '</td>';
                echo '<td>' . htmlspecialchars($row['Caffeine']) . '</td>';
                echo '<td>' . htmlspecialchars($row['Stress']) . '</td>';
                echo '<td>' . htmlspecialchars($row['Exercise']) . '</td>';
                echo '<td>' . htmlspecialchars($row['Notes']) . '</td>';
                echo '<td>';
                echo '<form action="editSR.php" method="POST">';
                echo '<input type="hidden" name="SRDate" value="' . htmlspecialchars($row['SRDate']) . '">';
                echo '<button type="submit">Update</button>';
                echo '</form>';
                echo '</td>';
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
</body>
</html>

