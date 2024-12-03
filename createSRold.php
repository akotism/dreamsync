<?php
session_start();
require_once("config.php");

$db = get_db();

// yesterday's date (semantic constraint)
function getYesterdayDate() {
    return date('Y-m-d', strtotime('-1 day'));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $q = $db->prepare("INSERT INTO SleepReport (SRDate, PID, Rating, RoomTemperature, Hours, Food, Mood, Caffeine, Stress, Exercise, Notes) 
                          VALUES (:SRDate, :PID, :Rating, :RoomTemperature, :Hours, :Food, :Mood, :Caffeine, :Stress, :Exercise, :Notes)");

    $q->bindParam(':SRDate', $_POST['SRDate']);
    $q->bindParam(':PID', $_POST['PID']);
    $q->bindParam(':Rating', $_POST['Rating']);
    $q->bindParam(':RoomTemperature', $_POST['RoomTemperature']);
    $q->bindParam(':Hours', $_POST['Hours']);
    $q->bindParam(':Food', $_POST['Food']);
    $q->bindParam(':Mood', $_POST['Mood']);
    $q->bindParam(':Caffeine', $_POST['Caffeine']);
    $q->bindParam(':Stress', $_POST['Stress']);
    $q->bindParam(':Exercise', $_POST['Exercise']);
    $q->bindParam(':Notes', $_POST['Notes']);


if ($q->execute()) {
        // Redirect to patientHome.php after successful submission
        header("Location: patientHome.php");
        exit();
    } else {
        echo "Error: " . $q->errorInfo()[2];
    }
}


// Get the default PID from session
$defaultPID = $_SESSION["ID"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Sleep Report</title>
    <link rel="stylesheet" href="./sleepreport.css">
</head>
<body>
    <h1>Add New Sleep Report</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="SRDate">Date:</label>
        <input type="date" id="SRDate" name="SRDate" value="<?php echo getYesterdayDate(); ?>" readonly><br>

        <label for="PID">Patient ID:</label>
        <input type="number" id="PID" name="PID" value="<?php echo htmlspecialchars($defaultPID); ?>" readonly><br>

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
        <textarea id="Notes" name="Notes"></textarea><br>

        <button type="submit">Submit</button>
    </form>
</body>
</html>

