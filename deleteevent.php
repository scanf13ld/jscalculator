<?php
require 'database.php';
ini_set("session.cookie_httponly", 1);
session_start();
ini_set('display_errors', 1);
?>


<?php
if ($_POST){

  if(!hash_equals($_SESSION['token'], $_POST['token'])){  //CSRF token validation
      die("Request forgery detected");
  }

  $event_id = $_POST['event_id'];

  $stmt = $mysqli->prepare("DELETE FROM events WHERE events.event_id=?");

  if(!$stmt){
      echo json_encode("Query Prep Failed: %s\n", $mysqli->error);
      exit;
  }

  $stmt->bind_param('i', $event_id);

  $stmt->execute();

  $stmt->close();

  echo json_encode("Event successfully deleted.");
}

 ?>
