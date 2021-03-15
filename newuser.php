<?php
require 'database.php';
session_start();
ini_set('display_errors', 1);
?>

<?php
        $_SESSION['username'] = (string) $_POST["username"];
        $hpass = password_hash((string) $_POST["password"], PASSWORD_DEFAULT);
        if($_SESSION['username'] == "" || $hpass == ""){
            echo json_encode("You must input a username and password to create new user!");
          //  header("Location: index.php");
            exit;
        }

        $safe_username = $mysqli->real_escape_string($_POST['username']);       //safe against sql injection

        //
        $stmt = $mysqli->prepare("select user_id from users");      //get list of usernames
        if(!$stmt){
            echo json_encode("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }

        $stmt->execute();

        $result = $stmt->get_result();

        //echo "<ul>\n";
        while($row = $result->fetch_assoc()){                       //make sure someone else doesn't already have that username
            if ($row == $_SESSION['username']){
                echo json_encode("This user already exists!");
                //header("Location: index.php");
                exit;
            }
        }
        //echo "</ul>\n";

        $stmt->close();
        //
        //if(!hash_equals($_SESSION['token'], $_POST['token'])){          //CSRF
        //  die("Request forgery detected");
        //}
        $stmt = $mysqli->prepare("insert into users (user_id, hash_pass) values (?, ?)");
        if(!$stmt){
            echo json_encode("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }

        $stmt->bind_param('ss', $safe_username, $hpass);         //add username and hashed pass into database

        $stmt->execute();

        $stmt->close();

        //header("Location: storyfeed.php");      //redirect to the storyfeed
        echo json_encode("User added!");

?>
