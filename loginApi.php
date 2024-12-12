<?php
session_start();
header('Content-Type: application/json');

require_once("../config.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data["ID"], $data["password"])) {
        http_response_code(400);
        echo json_encode(["error" => "ID and password are required."]);
        exit;
    }

    $id = $data["ID"];
    $password = $data["password"];
    $isDoctor = isset($data["doctor"]) ? $data["doctor"] : false;

    try {
        $db = get_db();

        if ($isDoctor) {
            $verify = $db->prepare("SELECT Hash FROM Doctor WHERE DID = ?");
        } else {
            $verify = $db->prepare("SELECT Hash FROM Patient WHERE PID = ?");
        }

        $verify->bindParam(1, $id, PDO::PARAM_STR);

        if (!$verify->execute()) {
            throw new Exception("Database query failed: " . implode(", ", $verify->errorInfo()));
        }

        $verifyResults = $verify->fetchAll(PDO::FETCH_ASSOC);

        if (count($verifyResults) === 1 && password_verify($password, $verifyResults[0]["Hash"])) {
            if ($isDoctor) {
                $q = $db->prepare("SELECT * FROM Doctor WHERE DID = ?");
            } else {
                $q = $db->prepare("SELECT * FROM Patient WHERE PID = ?");
            }

            $q->bindParam(1, $id, PDO::PARAM_STR);
            if (!$q->execute()) {
                throw new Exception("Database query failed: " . implode(", ", $q->errorInfo()));
            }

            $rows = $q->fetchAll(PDO::FETCH_ASSOC);

            if (count($rows) === 1) {
                $_SESSION["logged_in"] = true;
                $_SESSION["ID"] = $id;

                if ($isDoctor) {
                    $_SESSION["dname"] = $rows[0]["dname"];
                    echo json_encode(["redirect" => "doctorHome.php"]);
                } else {
                    $_SESSION["DID"] = $rows[0]["DID"];
                    $_SESSION["pname"] = $rows[0]["pname"];
                    $_SESSION["Weight"] = $rows[0]["Weight"];
                    $_SESSION["Height"] = $rows[0]["Height"];
                    $_SESSION["Age"] = $rows[0]["Age"];
                    $_SESSION["Sex"] = $rows[0]["Sex"];
                    echo json_encode(["redirect" => "patientHome.php"]);
                }
                exit;
            }
        }

        http_response_code(401);
        echo json_encode(["error" => "Invalid credentials."]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["error" => $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(["error" => "Invalid request method."]);
}


