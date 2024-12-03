<?php

require_once("config.php");

if (isset($_POST["login"])) {
    echo "Log in attempt made through form<br>";

    $id = $_POST["ID"];
    $password = $_POST["password"];

    $db = get_db();


    $isDoctor = isset($_POST["doctor"]);

    if ($isDoctor) {
        $verify = $db->prepare("SELECT Hash FROM Doctor WHERE DID = ?");
    } else{
        $verify = $db->prepare("SELECT Hash FROM Patient WHERE PID = ?");
    }

    $verify->bindParam(1, $id, PDO::PARAM_STR);

    if (!$verify->execute()) {
        print_r($verify->errorInfo());
    } 

    $verifyResults = $verify->fetchAll(PDO::FETCH_ASSOC);

    $loginError = false;

    // Part 1: we know that the employee SSN is valid. 
    if (count($verifyResults) == 1) {
    
        if (password_verify($password, $verifyResults[0]["Hash"])) {
            if ($isDoctor) {
                $q = $db->prepare("SELECT * FROM Doctor WHERE DID = ?");
            } else{
                $q = $db->prepare("SELECT * FROM Patient WHERE PID = ?");
            }            
            $q->bindParam(1, $id, PDO::PARAM_STR);
        
            if (!$q->execute()) {
                print_r($q->errorInfo());
            }
            else {
                echo "Query successful...<br>";
            }
        
            $rows = $q->fetchAll(PDO::FETCH_ASSOC);
        
            if (count($rows) == 1) {
                if ($isDoctor) {
                    $_SESSION["logged_in"] = true;
                    $_SESSION["ID"] = $id;
                    $_SESSION["dname"] = $rows[0]["dname"];
                    header("Location: doctorHome.php");
                } else{
                    $_SESSION["logged_in"] = true;
                    $_SESSION["ID"] = $id;
                    $_SESSION["DID"] = $rows[0]["DID"];
                    $_SESSION["pname"] = $rows[0]["pname"];
                    $_SESSION["Weight"] = $rows[0]["Weight"];
                    $_SESSION["Height"] = $rows[0]["Height"];
                    $_SESSION["Age"] = $rows[0]["Age"];
                    $_SESSION["Sex"] = $rows[0]["Sex"];
                    header("Location: patientHome.php");
                }
            }
            else {
                $loginError = false;
            }
        }
        else {
            $loginError = true;
        }
    }
    else {
        $loginError = true;
    }

    if ($loginError) {
        echo "Invalid credentials<br>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title>DreamSync Login</title>
    </head>
    <body>
        <h2>Login</h2>
        <form action="login.php" method="POST">
            <input type="text" name="ID" placeholder="ID #">
            <input type="password" name="password" placeholder="Password"><br>
            <input type="checkbox" id="doctor" name="doctor">
            <label for="doctor"> Doctor Login</label><br><br>
            <input type="submit" name="login" value="Log in">
        </form>
    </body>
</html>
