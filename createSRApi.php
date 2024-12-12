<?php
session_start();
header('Content-Type: application/json');

require_once("../config.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Decode the JSON request body
    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data["patientName"], $data["rating"], $data["roomTemp"], $data["hours"], $data["food"], $data["mood"], $data["caffeine"], $data["exercise"], $data["notes"])) {
        http_response_code(400);
        echo json_encode(["error" => "Invalid input. All fields except stress are required."]);
        exit;
    }

    $patientName = $data["patientName"];
    $rating = $data["rating"];
    $roomTemp = $data["roomTemp"];
    $hours = $data["hours"];
    $food = $data["food"];
    $mood = $data["mood"];
    $caffeine = $data["caffeine"];
    $stress = isset($data["stress"]) ? 1 : 0;
    $exercise = $data["exercise"];
    $notes = $data["notes"];

    try {
        // Get the database connection
        $db = get_pdo_connection();

        $stmt = $db->prepare("CALL CreateSleepReport(:patientName, :rating, :roomTemp, :hours, :food, :mood, :caffeine, :stress, :exercise, :notes)");

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

        $stmt->execute();

        // Fetch the result
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        echo json_encode(["message" => $result['Result']]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["error" => $e->getMessage()]);
    }
} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(["error" => "Invalid request method."]);
}
