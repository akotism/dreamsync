<?php
session_start();
require_once("config.php");

if (!isset($_SESSION["ID"])) {
    header("Location: login.php");
    exit;
}

$db = get_db();

function updateSleepReport($db) {
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
        $q = $db->prepare("UPDATE SleepReport SET 
            Rating = :Rating, RoomTemperature = :RoomTemperature, Hours = :Hours, Food = :Food, 
            Mood = :Mood, Caffeine = :Caffeine, Stress = :Stress, Exercise = :Exercise, Notes = :Notes 
            WHERE SRDate = :SRDate AND PID = :PID");

        $q->bindParam(':Rating', $_POST['Rating']);
        $q->bindParam(':RoomTemperature', $_POST['RoomTemperature']);
        $q->bindParam(':Hours', $_POST['Hours']);
        $q->bindParam(':Food', $_POST['Food']);
        $q->bindParam(':Mood', $_POST['Mood']);
        $q->bindParam(':Caffeine', $_POST['Caffeine']);
        $q->bindParam(':Stress', $_POST['Stress']);
        $q->bindParam(':Exercise', $_POST['Exercise']);
        $q->bindParam(':Notes', $_POST['Notes']);
        $q->bindParam(':SRDate', $_POST['SRDate']);
        $q->bindParam(':PID', $_SESSION['ID']);

        if ($q->execute()) {
            echo "Sleep report updated successfully.";
        } else {
            echo "Error updating record: " . $q->errorInfo()[2];
        }
    }
}

updateSleepReport($db);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Sleep Report</title>
</head>
<body>
    <h1>Update Sleep Report</h1>
    <form action="editSR.php" method="post">

        <label for="Rating">Rating:</label>
        <input type="number" id="Rating" name="Rating" required><br>

        <label for="RoomTemperature">Room Temperature:</label>
        <input type="number" step="0.01" id="RoomTemperature" name="RoomTemperature" required><br>

        <label for="Hours">Hours of Sleep:</label>
        <input type="number" step="0.01" id="Hours" name="Hours" required><br>

        <label for="Food">Food Consumed:</label>
        <input type="text" id="Food" name="Food" required><br>

        <label for="Mood">Mood:</label>
        <input type="text" id="Mood" name="Mood" required><br>

        <label for="Caffeine">Caffeine Intake (mg):</label>
        <input type="number" id="Caffeine" name="Caffeine" required><br>

        <label for="Stress">Stress:</label>
        <input type="number" min="0" max="1" id="Stress" name="Stress" required><br>

        <label for="Exercise">Type of Exercise:</label>
        <input type="text" id="Exercise" name="Exercise" required><br>

        <label for="Notes">Notes:</label>
        <textarea id="Notes" name="Notes" required></textarea><br>

        <button type="submit" name="update">Update</button>
    </form>
</body>
</html>

