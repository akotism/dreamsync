<?php
// Include your database configuration file
require_once("config.php");

// Initialize variables
$updateMsg = "";

// Check if form is submitted
if (isset($_POST["submit"])) {
    // Get the form inputs
    $patientID = $_POST["patientID"];
    $newName = $_POST["newName"];
    $newAge = $_POST["newAge"];
    $newSex = $_POST["newSex"];
    $newHeight = $_POST["newHeight"];

    try {
        // Create a new PDO connection
        $db = get_pdo_connection();

        // Prepare the call to the stored procedure
        $stmt = $db->prepare("CALL UpdatePatientRecord(:patientID, :newName, :newAge, :newSex, :newHeight)");

        // Bind the parameters
        $stmt->bindParam(':patientID', $patientID, PDO::PARAM_INT);
        $stmt->bindParam(':newName', $newName, PDO::PARAM_STR);
        $stmt->bindParam(':newAge', $newAge, PDO::PARAM_INT);
        $stmt->bindParam(':newSex', $newSex, PDO::PARAM_STR);
        $stmt->bindParam(':newHeight', $newHeight, PDO::PARAM_STR);

        // Execute the stored procedure
        $stmt->execute();

        // Fetch result
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Get the message from the result
        $updateMsg = $result['Message'];
    } catch (PDOException $e) {
        $updateMsg = "Error: " . $e->getMessage(); // Return error message if any
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Patient Record</title>
    <link rel="stylesheet" href="./update.css">
</head>
<body>
    <h2>Update Patient Record</h2>
    <form method="POST" action="">
        <label for="patientID">Patient ID:</label>
        <input type="number" id="patientID" name="patientID" required><br>
        <label for="newName">New Name:</label>
        <input type="text" id="newName" name="newName" required><br>
        <label for="newAge">New Age:</label>
        <input type="number" id="newAge" name="newAge" required><br>
        <label for="newSex">New Sex:</label>
        <input type="text" id="newSex" name="newSex" required><br>
        <label for="newHeight">New Height:</label>
        <input type="text" id="newHeight" name="newHeight" required><br>
        <button type="submit" name="submit">Update Record</button>
    </form>
    <?php if ($updateMsg): ?>
        <p><?php echo $updateMsg; ?></p>
    <?php endif; ?>
</body>
</html>

