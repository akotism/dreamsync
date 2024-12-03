<?php
require_once("config.php");
?>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= $PROJECT_NAME ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1><?= $PROJECT_NAME ?></h1>
    <?php include("nav.php"); ?>
    <h2>SQL SELECT -> HTML Table using <a href="https://www.php.net/manual/en/book.pdo.php">PDO</a></h2>
    <?php

$db = get_pdo_connection();
$query = $db->prepare("SELECT * FROM Patient");
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);

?>


<h2>SQL SELECT using input from form</h2>
<?php
$select_form = new PhpFormBuilder();
$select_form->set_att("method", "POST");
$select_form->add_input("Patient id to search for", array(
    "type" => "number"
), "patient_id");
$select_form->add_input("Patient Data to search for", array(
    "type" => "text"
), "column_data");
$select_form->add_input("Submit", array(
    "type" => "submit",
    "value" => "Search"
), "search");
$select_form->build_form();

if (isset($_POST["search"])) {
    echo "searching...<br>";

    $db = get_pdo_connection();
    $query = false;

    if (!empty($_POST["search_id"])) {
        echo "searching by patient id...";
        $query = $db->prepare("select * from patient where pid = :id");
        $query->bindParam(":pid", $_POST["pid"], PDO::PARAM_INT);
    }
    else if (!empty($_POST["search_data"])) {
        echo "searching by patient data...";
        $query = $db->prepare("select * from Patient where column_data like :data");
        $query->bindValue(":data", "%" . $_POST["search_data"] . "%", PDO::PARAM_STR);
    }
    if ($query) {
        if ($query->execute()) {
            $rows = $query->fetchAll(PDO::FETCH_ASSOC);
            echo makeTable($rows);
        }
        else {
            echo "Error executing select query:<br>";
            print_r($query->errorInfo());
        }
        
    }
    else{
        echo "Error executing select query: no id or data specified<br>";
    }
}
?>
</body>
</html>
