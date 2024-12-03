<?php
session_start();
require_once("config.php");

$db = get_db();

//get doctor name by PID
$bindDoc = $db->prepare("SELECT dname FROM Doctor WHERE DID = ?");
$bindDoc->bindParam(1, $_SESSION["DID"], PDO::PARAM_INT);
$bindDoc->execute();

$docArr = $bindDoc->fetchAll(PDO::FETCH_ASSOC);
$docName = $docArr[0]['dname'];

//patient Medications
$M = $db->prepare("SELECT * from Medications WHERE PID = ?");
$M->bindParam(1, $_SESSION["ID"], PDO::PARAM_INT);
$M->execute();

$medArr = $M->fetchAll(PDO::FETCH_ASSOC);
//print_r($medArr);

//patient Health conditions
$H = $db->prepare("SELECT * from HealthConditions WHERE PID = ?");
$H->bindParam(1, $_SESSION["ID"], PDO::PARAM_INT);
$H->execute();

$HCArr = $H->fetchAll(PDO::FETCH_ASSOC);
//print_r($HCArr);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Patient Home</title>
    <link rel="stylesheet" type="text/css" href="./patient.css">
</head>
<body>
<header>
    <h1>Welcome Back, <?php echo htmlspecialchars($_SESSION["pname"]); ?>!</h1>
</header>
<section>
    <h2>Your Info:</h2>
    <table border="1">
        <tr>
            <th>Doctor</th>
            <td><?php echo htmlspecialchars($docName); ?></td>
        </tr>
        <tr>
            <th>Weight</th>
            <td><?php echo htmlspecialchars($_SESSION["Weight"]); ?></td>
        </tr>
        <tr>
            <th>Height</th>
            <td><?php echo htmlspecialchars($_SESSION["Height"]); ?></td>
        </tr>
        <tr>
            <th>Age</th>
            <td><?php echo htmlspecialchars($_SESSION["Age"]); ?></td>
        </tr>
        <tr>
            <th>Sex</th>
            <td><?php echo htmlspecialchars($_SESSION["Sex"]); ?></td>
        </tr>
    </table>

    <h2>Your Medications:</h2>
    <table border="1">
        <tr>
            <th>Medication</th>
        </tr>
            <tbody>
                <?php
                foreach ($medArr as $row) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($row["Medication"]) . '</td>';
                }
                ?>
            </tbody>
    </table>

    <h2>Your Health Conditions:</h2>
    <table border="1">
        <tr>
            <th>Condition</th>
        </tr>
            <tbody>
                <?php
                foreach ($HCArr as $row) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($row["Conditions"]) . '</td>';
                }
                ?>
            </tbody>
    </table>
    <p>If any of the information above is inaccurate, please contact our office at (535)-456-2319.</p>
</section>
<nav>
    <ul>
        <li><a href="./viewSRs.php">View Sleep Reports</a></li>
        <li><a href="./viewPolys.php">View Polysomnograms</a></li>
        <li><a href="./createSR.php">Create Sleep Report</a></li>
        <li><a href="./update.php">Update Your Information</a></li>
        <li><a href="./delete.php">Delete Your Account</a></li>
        
        
    </ul>
</nav>
<form action="logout.php" method="post">
    <button type="submit" id="logout" name="logout">Logout</button>
    </form>
</body>
</html>

