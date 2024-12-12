<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SERVER['REQUEST_METHOD'])) {
    echo json_encode(["error" => "REQUEST_METHOD not set. This script must be accessed via an HTTP request."]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(["error" => "Method not allowed. Please use POST."]);
    exit;
}

if (!isset($_SESSION["ID"])) {
    http_response_code(401); // Unauthorized
    echo json_encode(["error" => "Session data not set. Please log in again."]);
    exit;
}

require_once("../config.php");

$db = get_db();
$data = json_decode(file_get_contents("php://input"), true);

$errors = [];
$response = [];

// Validate input
$pid = $data["PID"] ?? null;
$weight = trim($data["Weight"] ?? "");
$height = trim($data["Height"] ?? "");
$age = trim($data["Age"] ?? "");
$password = trim($data["Password"] ?? "");
$did = trim($data["DID"] ?? "");

if (!$pid) {
    $errors[] = "Patient ID is required.";
}

$params = [];
$query = "UPDATE Patient SET ";

if (!empty($weight)) {
    $query .= "Weight = ?, ";
    $params[] = $weight;
}
if (!empty($height)) {
    $query .= "Height = ?, ";
    $params[] = $height;
}
if (!empty($age) && is_numeric($age)) {
    $query .= "Age = ?, ";
    $params[] = $age;
}
if (!empty($did) && is_numeric($did)) {
    $query .= "DID = ?, ";
    $params[] = $did;
}
if (!empty($password)) {
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    $query .= "Password = ?, Hash = ?, ";
    $params[] = $password;
    $params[] = $hashedPassword;
}

$query = rtrim($query, ", ");
$query .= " WHERE PID = ?";
$params[] = $pid;

if (empty($errors)) {
    try {
        $stmt = $db->prepare($query);
        if ($stmt->execute($params)) {
            $response["message"] = "Patient information updated successfully.";
        } else {
            $errors[] = "Failed to update patient information.";
        }
    } catch (Exception $e) {
        $errors[] = "Database error: " . $e->getMessage();
    }
}

if (!empty($errors)) {
    http_response_code(400); // Bad Request
    $response["errors"] = $errors;
}

echo json_encode($response);
?>
