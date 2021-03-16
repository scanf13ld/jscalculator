<?php
require 'database.php';
session_start();
ini_set('display_errors', 1);
?>

<?php

    $time = $mysqli->real_escape_string($_POST['time']);
    $m = $mysqli->real_escape_string($_POST['month']);
    $d = $mysqli->real_escape_string($_POST['day']);
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
    
    for ($i=0; $i<count($thirty); $i++){
        if($m == $thirty[i]){                                   //check months with 30 days to see if a number greater than 30 is entered
            $isThirty = True;
            if($d > 30 || $d < 0){
                echo json_encode("That date does not exist.");
            }
        }
    }
    if(!$isThirty &&( $d > 31 || $d < 0)){              //check the rest to see if a number greater than 31 is entered
        echo json_encode("That date does not exist.");  //technically includes Feb which would be caught earlier anyway
    }
    else{echo json_encode("yeah baby");}

    $stmt = $mysqli->prepare("insert into events (user_id, month, year, day, title, tag_id) values (?, ?, ?, ?, ?)");
    if(!$stmt){
        echo json_encode("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }

    $stmt->bind_param('siiiss', $_SESSION['username'], $m, $y, $d, $t, $tag);         //add event info into database

    $stmt->execute();

    $stmt->close();

    echo json_encode("Event successfully added!")
    
?>