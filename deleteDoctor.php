<?php
require_once("config.php");


$deleteMsg = "";

if (isset($_POST["delete"])) {
    $doctorID = $_POST["doctorID"];

    try {
        $db = get_pdo_connection();

        if ($doctorID == 1) {
            $deleteMsg = "Deletion of Doctor 1 is prohibited.";
        } else {
            $stmt = $db->prepare("DELETE FROM Doctor WHERE DID = :doctorID");

            $stmt->bindParam(':doctorID', $doctorID, PDO::PARAM_INT);
            $stmt->execute();

            $deleteMsg = "Doctor deleted successfully.";
        }
    } catch (PDOException $e) {
        $deleteMsg = "Error: " . $e->getMessage(); 
    }

    header("Location: login.php");
    exit(); 
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Account</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow p-4">
            <h2 class="text-danger text-center">Delete Account</h2>
            <p class="text-muted text-center">Please confirm your Doctor ID to delete your account.</p>
            <div class="form-container">
                <form method="POST" action="" class="text-center">
                    <div class="mb-3">
                        <label for="doctorID" class="form-label">Doctor ID:</label>
                        <input type="number" id="doctorID" name="doctorID" class="form-control w-50 mx-auto" required>
                    </div>
                    <button type="submit" name="delete" class="btn btn-danger w-50 mb-3">Delete Account</button>
                </form>
                <div class="text-center">
                    <a href="doctorHome.php" class="btn btn-secondary w-50">Return to Dashboard</a>
                </div>
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


