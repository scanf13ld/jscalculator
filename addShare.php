<?php
require 'database.php';
ini_set("session.cookie_httponly", 1);
session_start();
ini_set('display_errors', 1);
?>

<?php

    $shared_to = $mysqli->real_escape_string($_POST['shared_to']);

    $realUser = false;

    $stmt = $mysqli->prepare("select user_id from users");        //go through the database list of usernames
    if(!$stmt){
        echo json_encode("Username Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $stmt->execute();

    //$result = $stmt->get_result();

    $stmt->bind_result($user);

    //echo "<ul>\n";
    while($stmt->fetch()){               //go through each line of the usernames output
        if ($shared_to == $user){             //if a username is equal to the input
            $realUser = true;                   //then there is a real user with that username and the share can proceed
        }
    }

    if($realUser){

        $stmt = $mysqli->prepare("insert into share (from_id, to_id) values (?, ?)");

        if(!$stmt){
            echo json_encode("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt->bind_param('ss', $_SESSION['username'], $shared_to);

        $stmt->execute();

        $stmt->close();

        echo json_encode("Successfully shared.");
    }
    else{
        echo json_encode("!! This user does not exist !!");
    }

?>
