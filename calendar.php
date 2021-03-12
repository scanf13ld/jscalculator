<?php
require 'database.php'; //returns $mysqli which can be used in mysqli_query commands
session_start();
if (empty($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
}
ini_set('display_errors', 1);
?>

<!DOCTYPE html>
<html lang=en-US>
<head>
<meta charset="UTF-8">
<link rel="stylesheet"
href= "./assets/CSS/calendar.css">
<title>Calendar</title>
</head>
<body>

    <div class="calendar-event-editor" style="display:none;"> <!-- Pop-Up For New Event -->
        Title:<input type="text" name = "title"/><br>
        Tag:<br>
        <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>"/>
        <input type="radio" name="tag" value="work" id="tech" /><label for="work">Work</label><br>
        <input type="radio" name="tag" value="school" id="sports" /><label for="school">School</label><br>
        <input type="radio" name="tag" value="family" id="science" /><label for="family">Family</label><br>
        <input type="radio" name="tag" value="birthday" id="politics" /><label for="birthday">Birthday</label><br>
        <input type="radio" name="tag" value="misc" id="misc" /><label for="misc">Misc</label><br>
        <input type="submit" class = "button" value ="Post"/>
        <input type="submit" class = "button" value ="Cancel" formaction="calendar.php"/><br>
        Duration:
        <input type="radio" name="duration" value="once" id="once" /><label for="once">Just this once</label><br>
        <input type="radio" name="duration" value="weekly" id="weekly" /><label for="weekly">Weekly</label><br>
        <input type="radio" name="duration" value="biweekly" id="biweekly" /><label for="biweekly">Bi-Weekly</label><br>
        <input type="radio" name="duration" value="monthly" id="monthly" /><label for="monthly">Monthly</label><br>
        <input type="radio" name="duration" value="yearly" id="yearly" /><label for="yearly">Yearly</label><br>
        <?php
            if( $_SERVER['REQUEST_METHOD'] === "POST"){
                if(!hash_equals($_SESSION['token'], $_POST['token'])){  //CSRF token validation
                    die("Request forgery detected");
                }

                if($_POST['title'] == ""){
                    echo "Your story must have a title.";
                }
                $safe_title = $mysqli->real_escape_string($_POST['title']); //SQL injection resistant


                $stmt = $mysqli->prepare("insert into events (user_id, dt, title, tag_id, duration) values (?, ?, ?, ?, ?)");
                if(!$stmt){
                    printf("Post Query Prep Failed: %s\n", $mysqli->error);
                    exit;
                }

                $stmt->bind_param('sssss', $_SESSION['username'], $_SESSION['dt'],$safe_title, $_POST['tag_id'], $_POST['duration']);

                $stmt->execute();

                $stmt->close();

                echo
                exit;
            }
        ?>
      </div>


    </form>



<footer>
	  <p>Made by SCanfield and LBucchieri </p>
</footer>
</body>
</html>
