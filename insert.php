<?php
require_once("config.php");
$insertMsg = "";
// Handle any inserts/updates/deletes before outputting any HTML
// INSERT 
if (isset($_POST["insert"]) 
    && !empty($_POST["Weight"]) 
    && !empty($_POST["Height"]) 
    && !empty($_POST["Password"]) 
    && !empty($_POST["Age"]) 
    && !empty($_POST["Sex"])) {
    echo "Inserting New Patient";

    $db = get_pdo_connection();
    $query = $db->prepare("INSERT INTO Patient (PID, DID, pname, Weight, Height, Password, Age, Sex) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    $DID = htmlspecialchars($_POST["DID"]);
    $pname = htmlspecialchars($_POST["pname"]);
    $Weight = htmlspecialchars($_POST["Weight"])." lbs";
    $Height = htmlspecialchars($_POST["Height"]);
    $Password = htmlspecialchars($_POST["Password"]);
    $Age = htmlspecialchars($_POST["Age"]);
    $Sex = htmlspecialchars($_POST["Sex"]);

    $query->bindParam(1, $PID, PDO::PARAM_INT);
    $query->bindParam(2, $DID, PDO::PARAM_INT);
    $query->bindParam(3, $pname, PDO::PARAM_STR);
    $query->bindParam(4, $Weight, PDO::PARAM_STR);
    $query->bindParam(5, $Height, PDO::PARAM_STR);
    $query->bindParam(6, $Password, PDO::PARAM_STR);
    $query->bindParam(7, $Age, PDO::PARAM_INT);
    $query->bindParam(8, $Sex, PDO::PARAM_STR);
 
    if (!$query->execute()) {    
        $insertMsg =  "Error executing insert query:<br>" . print_r($query->errorInfo(), true);
    }
    else {
        header("Location: doctorHome.php");
        exit();
    }
}
?>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title> New Patient </title>
    <link rel="stylesheet" href="./insert.css">
</head>
<body>
    <h1><?= $PROJECT_NAME ?></h1>
    <h2>INSERT NEW PATIENT</h2>
<?php
if (!empty($insertMsg)) {
    echo "$insertMsg<br>";
    $insertMsg = "";
}
$insert_form = new PhpFormBuilder();
$insert_form->set_att("method", "POST");
$insert_form->add_input("Doctor ID:", array("type" => "number",), "DID");
$insert_form->add_input("Patient Name:", array("type" => "text",), "pname");
$insert_form->add_input("Weight", array("type" => "text",), "Weight");
$insert_form->add_input("Height", array("type" => "text",), "Height");
$insert_form->add_input("Password", array("type" => "text",), "Password");
$insert_form->add_input("Age", array("type" => "number",), "Age");
$insert_form->add_input("Sex", array("type" => "text",), "Sex");
$insert_form->add_input("Insert", array("type" => "submit","value" => "Insert"), "insert");
$insert_form->build_form();
?>
</body>
</html>
