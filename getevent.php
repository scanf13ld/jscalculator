<?php
require 'database.php'; //returns $mysqli which can be used in mysqli_query commands
session_start();
if (empty($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
}
ini_set('display_errors', 1);


$user_id = $_SESSION["username"];

$output_arr = array();

$from_arr = array();

$sql = "SELECT from_id FROM share WHERE to_id='". $user_id ."'";

$result = mysqli_query($mysqli, $sql);

while($row = mysqli_fetch_assoc($result)) {
  $from_arr[] = array("from_id" => $row['from_id']);
}

if ($_POST){
  $day = $_POST['day_ajax'];
  $month = $_POST['month_ajax'];
  $year = $_POST['year_ajax'];

  $sql = "SELECT event_id, title, tag_id, user_id, time, dur FROM events WHERE day='". $day ."' AND month='". $month ."' AND year='". $year ."' AND user_id='". $user_id ."'";
  $result = mysqli_query($mysqli, $sql);
  while($row = mysqli_fetch_assoc($result)) {

    $output_arr[] = array("event_id" => $row['event_id'],
    "user_id" => $row['user_id'],
    "title" => $row['title'],
    "tag_id" => $row['tag_id'],
    "dur" => $row['dur'],
    "time" => $row['time']);
  }
  for($i=0; $i<count($from_arr); $i++){
    $sql = "SELECT event_id, title, tag_id, user_id, time, dur FROM events WHERE day='". $day ."' AND month='". $month ."' AND year='". $year ."' AND user_id='". $from_arr[$i] ."'";
    $result = mysqli_query($mysqli, $sql);
    while($row = mysqli_fetch_assoc($result)) {

      $output_arr[] = array("event_id" => $row['event_id'],
      "user_id" => $row['user_id'],
      "title" => $row['title'],
      "tag_id" => $row['tag_id'],
      "dur" => $row['dur'],
      "time" => $row['time']);
    }
  }
}

else{ //populating icons
  $sql = "SELECT event_id, user_id, month, year, day, title, tag_id FROM events WHERE user_id='". $user_id ."'";
  $result = mysqli_query($mysqli, $sql);

  while($row = mysqli_fetch_assoc($result)) {

    $output_arr[] = array("event_id" => $row['event_id'],
    "user_id" => $row['user_id'],
    "year" => $row['year'],
    "month" => $row['month'],
    "day" => $row['day'],
    "title" => $row['title'],
    "tag_id" => $row['tag_id']);
  }
  for($i=0; $i<count($from_arr); $i++){
    echo($from_arr[$i]);
    $sql = "SELECT event_id, user_id, month, year, day, title, tag_id FROM events WHERE user_id='". $from_arr[$i] ."'";
    $result = mysqli_query($mysqli, $sql);
    echo(mysqli_fetch_assoc($result));
    
    while($row = mysqli_fetch_assoc($result)) {
      echo json_encode("inside while");
      $output_arr[] = array("event_id" => $row['event_id'],
      "user_id" => $row['user_id'],
      "year" => $row['year'],
      "month" => $row['month'],
      "day" => $row['day'],
      "title" => $row['title'],
      "tag_id" => $row['tag_id']);
    }

    echo("after while");
  }

}
echo json_encode($output_arr);


?>
