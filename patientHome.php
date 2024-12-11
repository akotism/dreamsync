<?php
session_start();
require_once("config.php");

$db = get_db();

// Get doctor name by PID
$bindDoc = $db->prepare("SELECT dname FROM Doctor WHERE DID = ?");
$bindDoc->bindParam(1, $_SESSION["DID"], PDO::PARAM_INT);
$bindDoc->execute();

$docArr = $bindDoc->fetchAll(PDO::FETCH_ASSOC);
$docName = $docArr[0]['dname'];

// Patient Medications
$M = $db->prepare("SELECT * from Medications WHERE PID = ?");
$M->bindParam(1, $_SESSION["ID"], PDO::PARAM_INT);
$M->execute();

$medArr = $M->fetchAll(PDO::FETCH_ASSOC);

// Patient Health Conditions
$H = $db->prepare("SELECT * from HealthConditions WHERE PID = ?");
$H->bindParam(1, $_SESSION["ID"], PDO::PARAM_INT);
$H->execute();

$HCArr = $H->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Patient Home</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<header class="bg-primary text-white text-center py-4">
    <h1>Welcome Back, <?php echo htmlspecialchars($_SESSION["pname"]); ?>!</h1>
</header>
<div class="container my-5">
    <section class="mb-5">
        <h2 class="mb-3">Your Info</h2>
        <table class="table table-bordered table-hover">
            <tbody>
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
            </tbody>
        </table>
    </section>

    <section class="mb-5">
        <h2 class="mb-3">Your Medications</h2>
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Medication</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($medArr as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row["Medication"]); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <section class="mb-5">
        <h2 class="mb-3">Your Health Conditions</h2>
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Condition</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($HCArr as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row["Conditions"]); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <p class="text-muted">If any of the information above is inaccurate, please contact our office at (535)-456-2319.</p>
    </section>

    <nav class="mb-5">
        <ul class="list-group">
            <li class="list-group-item"><a href="./viewSRs.html" class="text-decoration-none">View Sleep Reports</a></li>
            <li class="list-group-item"><a href="./viewPolys.html" class="text-decoration-none">View Polysomnograms</a></li>
            <li class="list-group-item"><a href="./createSR.php" class="text-decoration-none">Create Sleep Report</a></li>
            <li class="list-group-item"><a href="./update.php" class="text-decoration-none">Update Your Information</a></li>
            <li class="list-group-item"><a href="./delete.php" class="text-decoration-none">Delete Your Account</a></li>
        </ul>
    </nav>

    <form action="logout.php" method="post" class="text-center">
        <button type="submit" id="logout" name="logout" class="btn btn-danger">Logout</button>
    </form>
</div>
<footer class="bg-dark text-white text-center py-3">
    © 2024 DreamSync
</footer>
</body>
</html>



