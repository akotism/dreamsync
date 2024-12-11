<?php
require_once("config.php");

$deleteMsg = "";

if (isset($_POST["submit"])) {
    $patientID = $_POST["patientID"];

    try {
        $db = get_pdo_connection();

        $stmt = $db->prepare("CALL DeletePatientRecord(:patientID)");

        $stmt->bindParam(':patientID', $patientID, PDO::PARAM_INT);

        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $deleteMsg = $result['Message'];

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
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow p-4">
            <h2 class="text-danger text-center">Delete Patient Record</h2>
            <p class="text-muted text-center">Please confirm your Patient ID to delete your account.</p>
            <form method="POST" action="" class="text-center">
                <div class="mb-3">
                    <label for="patientID" class="form-label">Patient ID:</label>
                    <input type="number" id="patientID" name="patientID" class="form-control w-50 mx-auto" required>
                </div>
                <button type="submit" name="submit" class="btn btn-danger w-50 mb-3">Delete Record</button>
            </form>
            <div class="text-center">
                <a href="patientHome.php" class="btn btn-secondary w-50">Return to Dashboard</a>
            </div>
            <?php if ($deleteMsg): ?>
                <div class="alert mt-4 <?php echo strpos($deleteMsg, 'successfully') !== false ? 'alert-success' : 'alert-danger'; ?>">
                    <?php echo $deleteMsg; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>



