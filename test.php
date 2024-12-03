<?php
// Start the session
session_start();

// Include external PHP files
require_once("config.php");

// Get database connection
$db = get_db();

// Prepare statement to fetch the doctor's name
$docQuery = $db->prepare("SELECT dname FROM Doctor WHERE DID = ?");
$docQuery->bindParam(1, $_SESSION["DID"], PDO::PARAM_INT);
if ($docQuery->execute()) {
    $docResult = $docQuery->fetch(PDO::FETCH_ASSOC);
    if ($docResult) {
        $docName = $docResult['dname'];
    } else {
        $docName = "No doctor found";
    }
} else {
    print_r($docQuery->errorInfo());
}

// Check if user is logged in and is a Doctor
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || !isset($_SESSION['DID'])) {
    // If not -> redirect to login page
    header('Location: login.php');
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
    <!-- Maybe add doctor name in this -->
    <h1>Welcome to your Dashboard, Doctor <?php echo htmlspecialchars($docName); ?></h1>
</body>
</html>

