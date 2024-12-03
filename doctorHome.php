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

    // Prepare statement to fetch the doctor's name
    $bindDoc = $db->prepare("SELECT dname FROM Doctor WHERE DID = ?");
    $bindDoc->bindParam(1, $_SESSION["ID"], PDO::PARAM_INT);

    if (!$bindDoc->execute()) {
        throw new Exception("Failed to execute doctor query: " . implode(", ", $bindDoc->errorInfo()));
    }

    $docArr = $bindDoc->fetchAll(PDO::FETCH_ASSOC);
    if (count($docArr) > 0) {
        $_SESSION["dname"] = $docArr[0]['dname'];
    } else {
        echo "Doctor not found.";
        exit;
    }

    // Query to fetch the doctor's patients ordered by name
    $patientQuery = $db->prepare("
        SELECT pname AS Name, Age, Sex, Height, Weight 
        FROM Patient 
        WHERE DID = ? 
        ORDER BY pname
    ");
    $patientQuery->bindParam(1, $_SESSION["ID"], PDO::PARAM_INT);

    if ($patientQuery->execute()) {
        $patients = $patientQuery->fetchAll(PDO::FETCH_ASSOC);
    } else {
        throw new Exception("Failed to execute patient query: " . implode(", ", $patientQuery->errorInfo()));
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
    <title>Doctor Home</title>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Welcome to your Dashboard, Doctor <?php echo htmlspecialchars($_SESSION["dname"]); ?></h1>
            <form action="logout.php" method="post">
                <button type="submit" id="logout" name="logout">Logout</button>
            </form>
        </div>
        <h2>Your Patients:</h2>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Age</th>
                    <th>Sex</th>
                    <th>Height</th>
                    <th>Weight</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($patients as $patient): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($patient['Name']); ?></td>
                        <td><?php echo htmlspecialchars($patient['Age']); ?></td>
                        <td><?php echo htmlspecialchars($patient['Sex']); ?></td>
                        <td><?php echo htmlspecialchars($patient['Height']); ?></td>
                        <td><?php echo htmlspecialchars($patient['Weight']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a id="view-report" href="doctorData.php">View Sleep Report</a>
        <a id="view-polysomnogram" href="doctorPolysomnogram.php">View Polysomnogram Data</a>
        <a id="insert-patient" href="doctorUpdate.php">Update Patient Info</a>
        <a id="create-polysomnogram" href="createPS.php">Create Polysonmogram</a>
        <a id="view-patient-health" href="viewPMC.php">View Patient Meds and Conditions</a>
        <a id="delete-doctor" href="deleteDoctor.php">Delete Account</a>
        
        
    </div>
</body>
</html>

