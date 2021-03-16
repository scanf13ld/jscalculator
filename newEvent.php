<?php
require 'database.php';
session_start();
ini_set('display_errors', 1);
?>

<?php    
    $time = $mysqli->real_escape_string($_POST['time']);
    $m = $mysqli->real_escape_string($_POST['month']) - 1;
    $d = $mysqli->real_escape_string($_POST['day']) - 1;
    $y = $mysqli->real_escape_string($_POST['year']);
    $t = $mysqli->real_escape_string($_POST['title']);
    $tag = $mysqli->real_escape_string($_POST['tag']);
    $dur = $mysqli->real_escape_string($_POST['duration']);
    
    $thirty = array(3, 5, 8, 10);     //thirty days has september (8) april (3) june (5) and november (10)
    $isThirty = False;

    if($m < 0 || $m > 11){
        echo json_encode("Please enter a valid month.");
    }
    
    if($m == 1 && ($d > 28 || $d < 0)){
        echo json_encode("That date does not exist.");
    }

    if($m == 3 || $m == 5 || $m == 8 || $m == 10){              //months with 30 days
        if($d > 30 || $d < 0){
            echo json_encode("That date does not exist.");
        }
    }
    else{
        if ($d > 31 || $d < 0){
            echo json_encode("That date does not exist.");
        }
    }
    
    $stmt = $mysqli->prepare("insert into events (user_id, month, year, day, title, tag_id, dur, time) values (?, ?, ?, ?, ?, ?, ?, ?)");
    
    if(!$stmt){
        echo json_encode("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $stmt->bind_param('siiissss', $_SESSION['username'], $m, $y, $d, $t, $tag, $dur, $time);         //add event info into database

    $stmt->execute();

    $stmt->close();

    echo json_encode("Event successfully added!");    
?>