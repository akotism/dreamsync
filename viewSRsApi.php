<?php
session_start();
header('Content-Type: application/json');

// Ensure the session ID is set
if (!isset($_SESSION["ID"])) {
    http_response_code(401); 
    echo json_encode(["error" => "Session data not set. Please log in again."]);
    exit;
}

require_once("../config.php");

try {
    $db = get_db();

    // Query sleep reports for the logged-in patient
    $query = $db->prepare("SELECT * FROM SleepReport WHERE PID = ?");
    $query->bindParam(1, $_SESSION["ID"], PDO::PARAM_INT);

    if ($query->execute()) {
        $data = $query->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($data); // Return JSON response
    } else {
        throw new Exception("Failed to execute query: " . implode(", ", $query->errorInfo()));
    }
} catch (Exception $e) {
    http_response_code(500); 
    echo json_encode(["error" => $e->getMessage()]);
}
