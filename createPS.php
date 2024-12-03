<?php
// Start the session
session_start();

// Check if session variables are set
if (!isset($_SESSION["ID"])) {
    echo "Session data not set. Please log in again.";
    exit;
}

require_once("config.php");

$reportMsg = "";

$db = get_db();

$patientsQuery = $db->prepare("SELECT PID, pname FROM Patient WHERE DID = ?");
$patientsQuery->bindParam(1, $_SESSION["ID"], PDO::PARAM_INT);
$patientsQuery->execute();
$patients = $patientsQuery->fetchAll(PDO::FETCH_ASSOC);

// Check if form is submitted
if (isset($_POST["submit"])) {
    $pdate = $_POST["pdate"];
    $pid = $_POST["PID"];
    $did = $_SESSION["ID"];  // Get doctor ID from session
    $n1time = $_POST["n1time"];
    $n2time = $_POST["n2time"];
    $n3time = $_POST["n3time"];
    $remtime = $_POST["remtime"];
    $oxygenlevels = $_POST["oxygenlevels"];
    $timeswokenup = $_POST["timeswokenup"];
    $heartrate = $_POST["heartrate"];

    try {
        // Prepare the SQL query
        $stmt = $db->prepare("INSERT INTO Polysomnogram (PDate, PID, DID, N1Time, N2Time, N3Time, REMTime, OxygenLevels, TimesWokenUp, HeartRate) VALUES (:pdate, :pid, :did, :n1time, :n2time, :n3time, :remtime, :oxygenlevels, :timeswokenup, :heartrate)");

        // Bind parameters
        $stmt->bindParam(':pdate', $pdate, PDO::PARAM_STR);
        $stmt->bindParam(':pid', $pid, PDO::PARAM_INT);
        $stmt->bindParam(':did', $did, PDO::PARAM_INT);
        $stmt->bindParam(':n1time', $n1time, PDO::PARAM_STR);
        $stmt->bindParam(':n2time', $n2time, PDO::PARAM_STR);
        $stmt->bindParam(':n3time', $n3time, PDO::PARAM_STR);
        $stmt->bindParam(':remtime', $remtime, PDO::PARAM_STR);
        $stmt->bindParam(':oxygenlevels', $oxygenlevels, PDO::PARAM_STR);
        $stmt->bindParam(':timeswokenup', $timeswokenup, PDO::PARAM_INT);
        $stmt->bindParam(':heartrate', $heartrate, PDO::PARAM_INT);

        // Execute the query
        $stmt->execute();

        $reportMsg = "Polysomnogram created successfully.";

        // Redirect to the same page to avoid form resubmission
        header("Location: createPS.php");
        exit;
    } catch (PDOException $e) {
        $reportMsg = "Error: " . $e->getMessage(); // Return error message if any
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Polysomnogram</title>
    <style>
        .container {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input, select {
            width: 100%;
            padding: 8px;
            margin-bottom: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        button:hover {
            background-color: #45a049;
        }
        .message {
            margin-top: 20px;
            padding: 10px;
            background-color: #f4f4f4;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Create Polysomnogram</h2>
        <form method="POST" action="createPS.php">
            <div class="form-group">
                <label for="pdate">Date:</label>
                <input type="date" id="pdate" name="pdate" required>
            </div>
            <div class="form-group">
                <label for="PID">Select Patient:</label>
                <select id="PID" name="PID" required>
                    <?php foreach ($patients as $patient): ?>
                        <option value="<?php echo htmlspecialchars($patient['PID']); ?>">
                            <?php echo htmlspecialchars($patient['pname']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="n1time">N1 Time:</label>
                <input type="number" step="0.01" id="n1time" name="n1time" required>
            </div>
            <div class="form-group">
                <label for="n2time">N2 Time:</label>
                <input type="number" step="0.01" id="n2time" name="n2time" required>
            </div>
            <div class="form-group">
                <label for="n3time">N3 Time:</label>
                <input type="number" step="0.01" id="n3time" name="n3time" required>
            </div>
            <div class="form-group">
                <label for="remtime">REM Time:</label>
                <input type="number" step="0.01" id="remtime" name="remtime" required>
            </div>
            <div class="form-group">
                <label for="oxygenlevels">Oxygen Levels:</label>
                <input type="number" step="0.01" id="oxygenlevels" name="oxygenlevels" required>
            </div>
            <div class="form-group">
                <label for="timeswokenup">Times Woken Up:</label>
                <input type="number" id="timeswokenup" name="timeswokenup" required>
            </div>
            <div class="form-group">
                <label for="heartrate">Average Heart Rate:</label>
                <input type="number" id="heartrate" name="heartrate">
            </div>
            <button type="submit" name="submit">Create</button>
        </form>
        <?php if ($reportMsg): ?>
            <p class="message"><?php echo $reportMsg; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>

