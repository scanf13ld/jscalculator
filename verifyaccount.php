<?php
require 'database.php';
ini_set("session.cookie_httponly", 1);
session_start();
ini_set('display_errors', 1);
?>

<?php

        $_SESSION['username'] = htmlentities($_POST["username"]);

        $password = htmlentities($_POST["password"]);

        $stmt = $mysqli->prepare("select user_id from users");        //go through the database list of usernames
        if(!$stmt){
          echo json_encode("Username Query Prep Failed: %s\n", $mysqli->error);
          exit;
        }
        $stmt->execute();

        $stmt->bind_result($user);

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
                    $_SESSION['token'] = bin2hex(random_bytes(32)); //generate CSRF token
                    echo json_encode($_SESSION['username']); //user successfully verified
                    exit;
                }
                $stmt2->close();
                echo json_encode("Password Incorrect!");
                exit;
            }
        }
        echo json_encode("Username not found!");
        exit;

    ?>
