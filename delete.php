<?php
// Include your database configuration file
require_once("config.php");

// Initialize variables
$deleteMsg = "";

// Check if form is submitted
if (isset($_POST["submit"])) {
    // Get the patient ID from the form
    $patientID = $_POST["patientID"];

    try {
        // Create a new PDO connection
        $db = get_pdo_connection();

        // Prepare the call to the stored procedure
        $stmt = $db->prepare("CALL DeletePatientRecord(:patientID)");

        // Bind the parameter
        $stmt->bindParam(':patientID', $patientID, PDO::PARAM_INT);

        // Execute the stored procedure
        $stmt->execute();

        // Fetch result
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Get the message from the result
        $deleteMsg = $result['Message'];

        // Redirect to login.php after deleting the record
        header("Location: login.php");
        exit(); // Stop further execution
    } catch (PDOException $e) {
        $deleteMsg = "Error: " . $e->getMessage(); // Return error message if any
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Patient Record</title>
    <link rel="stylesheet" href="./delete.css">
</head>
<body>
    <div class="container">
        <h2>Delete Patient Record</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="patientID">Patient ID:</label>
                <input type="number" id="patientID" name="patientID" required>
            </div>
            <button type="submit" name="submit">Delete Record</button>
        </form>
        <?php if ($deleteMsg): ?>
            <p class="message"><?php echo $deleteMsg; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>

