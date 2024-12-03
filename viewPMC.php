<?php
require_once("config.php");

$db = get_db();

$patientsQuery = $db->prepare("SELECT PID, pname FROM Patient WHERE DID = ?");
$patientsQuery->bindParam(1, $_SESSION["ID"], PDO::PARAM_INT);
$patientsQuery->execute();
$patients = $patientsQuery->fetchAll(PDO::FETCH_ASSOC);

$medArr = [];
$HCArr = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pid = $_POST["PID"];
    //patient Medications
    $M = $db->prepare("SELECT * from Medications WHERE PID = ?");
    $M->bindParam(1, $pid, PDO::PARAM_INT);
    $M->execute();

    $medArr = $M->fetchAll(PDO::FETCH_ASSOC);
    //print_r($medArr);

    //patient Health conditions
    $H = $db->prepare("SELECT * from HealthConditions WHERE PID = ?");
    $H->bindParam(1, $pid, PDO::PARAM_INT);
    $H->execute();

    $HCArr = $H->fetchAll(PDO::FETCH_ASSOC);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Patient Health Records</title>
</head>
<body>
<header>
    <h1>View Patient Health Records</h1>
</header>
<form action="viewPMC.php" method="post">
    <label for="PID">Select Patient:</label>
    <select id="PID" name="PID" required>
        <?php foreach ($patients as $patient): ?>
            <option value="<?php echo htmlspecialchars($patient['PID']); ?>">
                <?php echo htmlspecialchars($patient['pname']); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <button type="submit">View Records</button>
</form>

<h2>Medications:</h2>
<table border="1">
    <tr>
        <th>Medication</th>
    </tr>
    <tbody>
        <?php if (empty($medArr)): ?>
        <tr>
            <td>None</td>
        </tr>
        <?php else: ?>
        <?php foreach ($medArr as $row): ?>
        <tr>
            <td><?php echo htmlspecialchars($row["Medication"]); ?></td>
        </tr>
        <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<h2>Health Conditions:</h2>
<table border="1">
    <tr>
        <th>Condition</th>
    </tr>
    <tbody>
        <?php if (empty($HCArr)): ?>
        <tr>
            <td>None</td>
        </tr>
        <?php else: ?>
        <?php foreach ($HCArr as $row): ?>
        <tr>
            <td><?php echo htmlspecialchars($row["Conditions"]); ?></td>
        </tr>
        <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>
</body>
</html>
