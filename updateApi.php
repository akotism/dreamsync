<?php
session_start();
header('Content-Type: application/json');

require_once("../config.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data["patientID"], $data["newName"], $data["newAge"], $data["newSex"], $data["newHeight"])) {
        http_response_code(400);
        echo json_encode(["error" => "Invalid input. All fields are required."]);
        exit;
    }

    $patientID = $data["patientID"];
    $newName = $data["newName"];
    $newAge = $data["newAge"];
    $newSex = $data["newSex"];
    $newHeight = $data["newHeight"];

    try {
        $db = get_pdo_connection();

        $stmt = $db->prepare("CALL UpdatePatientRecord(:patientID, :newName, :newAge, :newSex, :newHeight)");

        $stmt->bindParam(':patientID', $patientID, PDO::PARAM_INT);
        $stmt->bindParam(':newName', $newName, PDO::PARAM_STR);
        $stmt->bindParam(':newAge', $newAge, PDO::PARAM_INT);
        $stmt->bindParam(':newSex', $newSex, PDO::PARAM_STR);
        $stmt->bindParam(':newHeight', $newHeight, PDO::PARAM_STR);

        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        echo json_encode(["message" => $result['Message']]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["error" => $e->getMessage()]);
    }
} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(["error" => "Invalid request method."]);
}


