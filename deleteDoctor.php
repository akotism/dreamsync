<?php
// Include your database configuration file
require_once("config.php");

// Initialize variables
$deleteMsg = "";

// Check if form is submitted
if (isset($_POST["delete"])) {
    // Get the doctor ID from the form
    $doctorID = $_POST["doctorID"];

    try {
        // Create a new PDO connection
        $db = get_pdo_connection();

        // Check if the doctor being deleted is Doctor 1
        if ($doctorID == 1) {
            $deleteMsg = "Deletion of Doctor 1 is prohibited.";
        } else {
            // Prepare the delete query
            $stmt = $db->prepare("DELETE FROM Doctor WHERE DID = :doctorID");

            // Bind the parameter
            $stmt->bindParam(':doctorID', $doctorID, PDO::PARAM_INT);

            // Execute the delete query
            $stmt->execute();

            $deleteMsg = "Doctor deleted successfully.";
        }
    } catch (PDOException $e) {
        $deleteMsg = "Error: " . $e->getMessage(); // Return error message if any
    }
    
    // Redirect to login.php after deleting the doctor
    header("Location: login.php");
    exit(); // Stop further execution
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Account</title>
    <link rel="stylesheet" href="./deleteDoctor.css">
</head>
<body>
    <div class="container">
        <h2>Delete Account</h2>
        <div class="form-container">
            <form method="POST" action="">
                <label for="doctorID">Doctor ID:</label>
                <input type="number" id="doctorID" name="doctorID" required>
                <button type="submit" name="delete">Delete Account</button>
            </form>
        </div>
        <?php if ($deleteMsg): ?>
            <p class="message"><?php echo $deleteMsg; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>

