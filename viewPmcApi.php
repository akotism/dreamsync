<?php
require_once("../config.php");

header("Content-Type: application/json");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $response = ["success" => false, "data" => [], "message" => "Invalid Request"];
    $action = $_POST["action"] ?? null;

    if (!isset($_SESSION["ID"])) {
        $response["message"] = "Session ID is not set. Please log in.";
        echo json_encode($response);
        exit;
    }

    $db = get_db();
    if (!$db) {
        $response["message"] = "Failed to connect to the database.";
        echo json_encode($response);
        exit;
    }

    if ($action === "getPatients") {
        try {
            $query = $db->prepare("SELECT PID, pname FROM Patient WHERE DID = ?");
            $query->bindParam(1, $_SESSION["ID"], PDO::PARAM_INT);
            $query->execute();
            $patients = $query->fetchAll(PDO::FETCH_ASSOC);

            $response = [
                "success" => true,
                "data" => $patients,
                "message" => "Patients retrieved successfully."
            ];
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            $response["message"] = "A database error occurred.";
        }
    } elseif ($action === "getPatientRecords") {
        $pid = $_POST["PID"] ?? null;

        if (!$pid) {
            $response["message"] = "Patient ID (PID) is required.";
        } else {
            try {
                $medicationsQuery = $db->prepare("SELECT Medication FROM Medications WHERE PID = ?");
                $medicationsQuery->bindParam(1, $pid, PDO::PARAM_INT);
                $medicationsQuery->execute();
                $medArr = $medicationsQuery->fetchAll(PDO::FETCH_ASSOC);

                $conditionsQuery = $db->prepare("SELECT Conditions AS hname FROM HealthConditions WHERE PID = ?");
                $conditionsQuery->bindParam(1, $pid, PDO::PARAM_INT);
                $conditionsQuery->execute();
                $HCArr = $conditionsQuery->fetchAll(PDO::FETCH_ASSOC);


                $response = [
                    "success" => true,
                    "data" => [
                        "medications" => $medArr,
                        "healthConditions" => $HCArr
                    ],
                    "message" => "Patient records retrieved successfully."
                ];
            } catch (PDOException $e) {
                error_log("Database error: " . $e->getMessage());
                $response["message"] = "A database error occurred.";
            }
        }
    } else {
        $response["message"] = "Invalid action specified.";
    }

    echo json_encode($response);
    exit;
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method. Use POST."]);
    exit;
}

