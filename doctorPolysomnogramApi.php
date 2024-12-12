<?php
session_start();
header('Content-Type: application/json');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    // Check session
    if (!isset($_SESSION["ID"])) {
        throw new Exception("Session not set. Please log in.");
    }

    require_once("../config.php");
    $db = get_db();

    // Fetch data
    $query = $db->prepare("
        SELECT ps.PDate, p.pname AS PatientName, ps.PID, ps.N1Time, ps.N2Time, ps.N3Time, ps.REMTime, ps.OxygenLevels, ps.TimesWokenUp, ps.HeartRate
        FROM Polysomnogram ps
        JOIN Patient p ON ps.PID = p.PID
        WHERE p.DID = ?
        ORDER BY ps.PDate
    ");
    $query->bindParam(1, $_SESSION["ID"], PDO::PARAM_INT);

    if ($query->execute()) {
        $data = $query->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($data);
    } else {
        throw new Exception("Failed to execute query: " . implode(", ", $query->errorInfo()));
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
?>



