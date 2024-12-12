<?php
session_start();
header('Content-Type: application/json');

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check session
if (!isset($_SESSION["ID"])) {
    http_response_code(401);
    echo json_encode(["error" => "Session data not set. Please log in again."]);
    exit;
}

// Database connection
require_once("../config.php");

try {
    $db = get_db();
    if (!$db) {
        throw new Exception("Database connection failed.");
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $stmt = $db->query("SELECT PID, pname FROM Patient");
        $patients = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($patients);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["error" => "Failed to fetch patients: " . $e->getMessage()]);
    }
} 

elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    if (!$data) {
        http_response_code(400);
        echo json_encode(["error" => "Invalid input."]);
        exit;
    }

    $pdate = $data["pdate"] ?? null;
    $pid = $data["PID"] ?? null;
    $did = $_SESSION["ID"];
    $n1time = (float) ($data["n1time"] ?? 0);
    $n2time = (float) ($data["n2time"] ?? 0);
    $n3time = (float) ($data["n3time"] ?? 0);
    $remtime = (float) ($data["remtime"] ?? 0);
    $oxygenlevels = (float) ($data["oxygenlevels"] ?? 0);
    $timeswokenup = (int) ($data["timeswokenup"] ?? 0);
    $heartrate = (int) ($data["heartrate"] ?? 0);

    if (!$pdate || !$pid) {
        http_response_code(400);
        echo json_encode(["error" => "Date and Patient ID are required."]);
        exit;
    }

    try {
        $query = "
            INSERT INTO Polysomnogram 
            (PDate, PID, DID, N1Time, N2Time, N3Time, REMTime, OxygenLevels, TimesWokenUp, HeartRate) 
            VALUES (:pdate, :pid, :did, :n1time, :n2time, :n3time, :remtime, :oxygenlevels, :timeswokenup, :heartrate)
        ";

        $stmt = $db->prepare($query);

        $stmt->execute([
            ':pdate' => $pdate,
            ':pid' => $pid,
            ':did' => $did,
            ':n1time' => $n1time,
            ':n2time' => $n2time,
            ':n3time' => $n3time,
            ':remtime' => $remtime,
            ':oxygenlevels' => $oxygenlevels,
            ':timeswokenup' => $timeswokenup,
            ':heartrate' => $heartrate
        ]);

        echo json_encode(["message" => "Polysomnogram created successfully."]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["error" => "Failed to create polysomnogram: " . $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(["error" => "Method not allowed."]);
}
?>
