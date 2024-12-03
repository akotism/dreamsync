<?php
// Include your database configuration file
require_once("config.php");

// Initialize variables
$reportMsg = "";

// Check if form is submitted
if (isset($_POST["submit"])) {
    // Get form inputs
    $patientName = $_POST["patientName"];
    $rating = $_POST["rating"];
    $roomTemp = $_POST["roomTemp"];
    $hours = $_POST["hours"];
    $food = $_POST["food"];
    $mood = $_POST["mood"];
    $caffeine = $_POST["caffeine"];
    $stress = isset($_POST["stress"]) ? 1 : 0;
    $exercise = $_POST["exercise"];
    $notes = $_POST["notes"];

    try {
        // Create a new PDO connection
        $db = get_pdo_connection();

        // Prepare the call to the stored procedure
        $stmt = $db->prepare("CALL CreateSleepReport(:patientName, :rating, :roomTemp, :hours, :food, :mood, :caffeine, :stress, :exercise, :notes)");

        // Bind parameters
        $stmt->bindParam(':patientName', $patientName, PDO::PARAM_STR);
        $stmt->bindParam(':rating', $rating, PDO::PARAM_INT);
        $stmt->bindParam(':roomTemp', $roomTemp, PDO::PARAM_STR);
        $stmt->bindParam(':hours', $hours, PDO::PARAM_STR);
        $stmt->bindParam(':food', $food, PDO::PARAM_STR);
        $stmt->bindParam(':mood', $mood, PDO::PARAM_STR);
        $stmt->bindParam(':caffeine', $caffeine, PDO::PARAM_INT);
        $stmt->bindParam(':stress', $stress, PDO::PARAM_INT);
        $stmt->bindParam(':exercise', $exercise, PDO::PARAM_STR);
        $stmt->bindParam(':notes', $notes, PDO::PARAM_STR);

        // Execute the stored procedure
        $stmt->execute();

        // Fetch result
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Get the message from the result
        $reportMsg = $result['Result'];

        header("Location: patientHome.php");
        exit();
    } catch (PDOException $e) {
        $reportMsg = "Error: " . $e->getMessage(); // Return error message if any
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Sleep Report</title>
    <link rel="stylesheet" href="./sleepreport.css">
</head>
<body>
    <div class="container">
        <h2>Create Sleep Report</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="patientName">Patient Name:</label>
                <input type="text" id="patientName" name="patientName" required>
            </div>
            <div class="form-group">
                <label for="rating">Rating:</label>
                <input type="number" id="rating" name="rating" required>
            </div>
            <div class="form-group">
                <label for="roomTemp">Room Temperature:</label>
                <input type="text" id="roomTemp" name="roomTemp" required>
            </div>
            <div class="form-group">
                <label for="hours">Hours Slept:</label>
                <input type="text" id="hours" name="hours" required>
            </div>
            <div class="form-group">
                <label for="food">Food Eaten:</label>
                <input type="text" id="food" name="food" required>
            </div>
            <div class="form-group">
                <label for="mood">Mood:</label>
                <input type="text" id="mood" name="mood" required>
            </div>
            <div class="form-group">
                <label for="caffeine">Caffeine Intake(mg):</label>
                <input type="number" id="caffeine" name="caffeine" required>
            </div>
            <div class="form-group">
                <label for="stress">Stressful Day?</label>
                <input type="checkbox" id="stress" name="stress">
            </div>
            <div class="form-group">
                <label for="exercise">Exercise:</label>
                <input type="text" id="exercise" name="exercise" required>
            </div>
            <div class="form-group">
                <label for="notes">Notes:</label>
                <textarea id="notes" name="notes" required></textarea>
            </div>
            <button type="submit" name="submit">Create Report</button>
        </form>
        <?php if ($reportMsg): ?>
            <p class="message"><?php echo $reportMsg; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>

