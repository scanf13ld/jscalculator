<?php
require 'database.php';
ini_set("session.cookie_httponly", 1);
session_start();
ini_set('display_errors', 1);
?>

<?php

    $shared_to = $mysqli->real_escape_string($_POST['shared_to']);

    $stmt = $mysqli->prepare("insert into share (from_id, to_id) values (?, ?)");

    if(!$stmt){
        echo json_encode("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $stmt->bind_param('ss', $_SESSION['username'], $shared_to);

    $stmt->execute();

    $stmt->close();

    echo json_encode("Successfully shared.")

?>
