<?php
require 'database.php'; //returns $mysqli which can be used in mysqli_query commands
ini_set("session.cookie_httponly", 1);
session_start();
ini_set('display_errors', 1);

if (isset($_SESSION['username'])){
  $user_id = $_SESSION['username'];
} else{ //$user_id == "guest"
  $user_id = "guest";
}

if ($user_id == "guest"){
  echo json_encode("Please sign in to make a new event!");

}

else{
  $output_arr = array();

  if ($_POST){
    $day = $_POST['day_ajax'];
    $month = $_POST['month_ajax'];
    $year = $_POST['year_ajax'];

    $sql = "SELECT event_id, title, tag_id, user_id, time_event, dur FROM events WHERE day='". $day ."' AND month='". $month ."' AND year='". $year ."' AND user_id='". $user_id ."'";
    $result = mysqli_query($mysqli, $sql);
    while($row = mysqli_fetch_assoc($result)) {

      $output_arr[] = array("event_id" => $row['event_id'],
      "user_id" => $row['user_id'],
      "title" => $row['title'],
      "tag_id" => $row['tag_id'],
      "dur" => $row['dur'],
      "time" => $row['time_event']);
    }

    $sql = "SELECT from_id FROM share WHERE to_id='". $user_id ."'";
    $result = mysqli_query($mysqli, $sql);
    while($row = mysqli_fetch_assoc($result)) {
      $from_id = $row['from_id'];
      $sql = "SELECT event_id, title, tag_id, user_id, time_event, dur FROM events WHERE day='". $day ."' AND month='". $month ."' AND year='". $year ."' AND user_id='". $from_id ."'";
      $result = mysqli_query($mysqli, $sql);
      while($row = mysqli_fetch_assoc($result)) {
        $output_arr[] = array("event_id" => $row['event_id'],
        "user_id" => $row['user_id'],
        "title" => $row['title'],
        "tag_id" => $row['tag_id'],
        "dur" => $row['dur'],
        "time" => $row['time_event']);
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

    $sql = "SELECT from_id FROM share WHERE to_id='". $user_id ."'";

    $result = mysqli_query($mysqli, $sql);

    while($row = mysqli_fetch_assoc($result)) {
      $from_id = $row['from_id'];
      $sql = "SELECT event_id, user_id, month, year, day, title, tag_id FROM events WHERE user_id='". $from_id ."'";

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
    }

  }
  echo json_encode($output_arr);

}



?>
