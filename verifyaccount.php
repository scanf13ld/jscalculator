<?php
require 'database.php';
session_start();
ini_set('display_errors', 1);
?>

<?php

        $_SESSION['username'] = (string) $_POST["username"];

        $password = (string) $_POST["password"];

        //$user = $_SESSION['username'];

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
            if ($_SESSION['username'] == $user){             //if a username is equal to the input
                $stmt->close();

                $stmt2 = $mysqli->prepare("select hash_pass from users where user_id = (?)");   //get hashed password
                if(!$stmt2){
                  echo json_encode("Password Query Prep Failed: %s\n", $mysqli->error);
                  exit;
                }

                $stmt2->bind_param('s', $_SESSION['username']);
                $stmt2->execute();

                $stmt2->bind_result($pass);
                $stmt2->fetch();
                if (password_verify($password,$pass)){      //check hashed pass against password inputted
                    $stmt2->close();
                    echo json_encode($_SESSION['username']); //user successfully verified
                    //header("Location: storyfeed.php");      //redirect to the storyfeed
                    exit;
                }
                $stmt2->close();
                //header("Location: index.php");
                echo json_encode("Password Incorrect!");
                exit;
            }
        }
        //header("Location: index.php");
        echo json_encode("Username not found!");
        exit;

    ?>
