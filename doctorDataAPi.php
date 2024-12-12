<?php
session_start();
header('Content-Type: application/json');

// Check if session variables are set
if (!isset($_SESSION["ID"])) {
    http_response_code(401); 
    echo json_encode(["error" => "Session not set. Please log in again."]);
    exit;
}

require_once("../config.php");

try {
    $db = get_db();

    // Query to fetch sleep reports
    $query = $db->prepare("
        SELECT sr.SRDate, sr.PID, sr.Rating, sr.RoomTemperature, sr.Hours, sr.Food, sr.Mood, sr.Caffeine, sr.Stress, sr.Exercise, sr.Notes
        FROM SleepReport sr
        JOIN Patient p ON sr.PID = p.PID
        WHERE p.DID = ?
        ORDER BY sr.SRDate
    ");
    $query->bindParam(1, $_SESSION["ID"], PDO::PARAM_INT);

    if ($query->execute()) {
        $data = $query->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($data);
    } else {
        throw new Exception("Query execution failed: " . implode(", ", $query->errorInfo()));
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}


