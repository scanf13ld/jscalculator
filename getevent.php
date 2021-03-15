<?php
require 'database.php'; //returns $mysqli which can be used in mysqli_query commands
session_start();
if (empty($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
}
ini_set('display_errors', 1);


$user_id = 1;

$output_arr = array();

if ($_POST){
  $day = $_POST['day_ajax'];
  $month = $_POST['month_ajax'];
  $year = $_POST['year_ajax'];

  $sql = "SELECT event_id, title, tag_id FROM events WHERE day='". $day ."' AND month='". $month ."' AND year='". $year ."' AND user_id='shane'";
  $result = mysqli_query($mysqli, $sql);
  while($row = mysqli_fetch_assoc($result)) {

    $output_arr[] = array("event_id" => $row['event_id'],
                  "title" => $row['title'],
                  "tag_id" => $row['tag_id']);
  }
}

else{ //populating icons
  $sql = "SELECT event_id, user_id, month, year, day, title, tag_id FROM events WHERE user_id='shane'";
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
echo json_encode($output_arr);


?>
