<?php
// Start the session
session_start();

// Check if session variables are set
if (!isset($_SESSION["ID"]) || !isset($_SESSION["dname"])) {
    echo "Session data not set. Please log in again.";
    exit;
}

require_once("config.php");

$db = get_db();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate input
    $errors = [];
    $pid = $_POST["PID"];
    $weight = trim($_POST["Weight"]);
    $height = trim($_POST["Height"]);
    $age = trim($_POST["Age"]);
    $password = trim($_POST["Password"]);
    $did = trim($_POST["DID"]);

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

    // Remove the trailing comma and space
    $query = rtrim($query, ", ");
    $query .= " WHERE PID = ?";
    $params[] = $pid;

    if (empty($errors)) {
        $stmt = $db->prepare($query);
        if ($stmt->execute($params)) {
            $successMessage = "Patient information updated successfully.";
        } else {
            $errors[] = "Failed to update patient information.";
        }
    }
}

// Fetch patients for the dropdown
$patientsQuery = $db->prepare("SELECT PID, pname, Sex FROM Patient WHERE DID = ?");
$patientsQuery->bindParam(1, $_SESSION["ID"], PDO::PARAM_INT);
$patientsQuery->execute();
$patients = $patientsQuery->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Patient Information</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        h1 {
            color: #333;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-top: 10px;
        }
        input, select {
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        button:hover {
            background-color: #45a049;
        }
        .error {
            color: red;
            margin-top: 10px;
        }
        .success {
            color: green;
            margin-top: 10px;
        }
        #back {
            padding: 10px 20px;
            background-color: #008CBA;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        #back:hover {
            background-color: #007BB5;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Update Patient Information</h1>
            <a id="back" href="doctorHome.php">Back to Dashboard</a>
        </div>
        <?php if (!empty($errors)): ?>
            <div class="error">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        <?php if (!empty($successMessage)): ?>
            <div class="success">
                <?php echo htmlspecialchars($successMessage); ?>
            </div>
        <?php endif; ?>
        <form action="doctorUpdate.php" method="post">
            <label for="PID">Select Patient:</label>
            <select id="PID" name="PID" required>
                <?php foreach ($patients as $patient): ?>
                    <option value="<?php echo htmlspecialchars($patient['PID']); ?>">
                        <?php echo htmlspecialchars($patient['pname']); ?> (<?php echo htmlspecialchars($patient['Sex']); ?>)
                    </option>
                <?php endforeach; ?>
            </select>
            <label for="Weight">Weight:</label>
            <input type="text" id="Weight" name="Weight">
            <label for="Height">Height:</label>
            <input type="text" id="Height" name="Height">
            <label for="Age">Age:</label>
            <input type="number" id="Age" name="Age">
            <label for="Password">Password (leave blank if unchanged):</label>
            <input type="password" id="Password" name="Password">
            <label for="DID">Doctor ID:</label>
            <input type="number" id="DID" name="DID">
            <button type="submit">Update Patient</button>
        </form>
    </div>
</body>
</html>


