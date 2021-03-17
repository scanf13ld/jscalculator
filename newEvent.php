<?php
require 'database.php';
ini_set("session.cookie_httponly", 1);
session_start();
ini_set('display_errors', 1);
?>

<?php

    if(!hash_equals($_SESSION['token'], $_POST['token'])){  //CSRF token validation
        die("Request forgery detected");
    }

    if ($_POST['time'] == null || $_POST['month'] == null ||$_POST['day'] == null || $_POST['year'] == null || $_POST['title'] == null || $_POST['tag'] == null)
    {
      echo json_encode(htmlentities("Please fill out each of the inputs!"));
    }

    else{
      $time = $mysqli->real_escape_string($_POST['time']);
      $m = $mysqli->real_escape_string($_POST['month']) - 1;
      $d = $mysqli->real_escape_string($_POST['day']);
      $y = $mysqli->real_escape_string($_POST['year']);
      $t = $mysqli->real_escape_string($_POST['title']);
      $tag = $mysqli->real_escape_string($_POST['tag']);
      $dur = $mysqli->real_escape_string($_POST['duration']);
      $num_repeats = $_POST['num_repeats'];                       //doesn't have to be injection resistant bc it's not going in database

      if($m < 0 || $m > 11){
          echo json_encode(htmlentities("Please enter a valid month."));
      }

      if($m == 1 && ($d > 28 || $d < 0)){
          echo json_encode(htmlentities("That date does not exist."));
      }

      if($m == 3 || $m == 5 || $m == 8 || $m == 10){              //months with 30 days
          if($d > 30 || $d < 0){
              echo json_encode(htmlentities("That date does not exist."));
          }
      }
      else{
          if ($d > 31 || $d < 0){
              echo json_encode(htmlentities("That date does not exist."));
          }
      }

      if($dur == "weekly"){
          for($i = 0; $i<$num_repeats; $i++){
              $stmt = $mysqli->prepare("insert into events (user_id, month, year, day, title, tag_id, dur, time_event) values (?, ?, ?, ?, ?, ?, ?, ?)");

              if(!$stmt){
                  echo json_encode(htmlentities("Query Prep Failed: %s\n", $mysqli->error));
                  exit;
              }
              $stmt->bind_param('siiisiss', $_SESSION['username'], $m, $y, $d, $t, $tag, $dur, $time);         //add event info into database

              $stmt->execute();

              $stmt->close();

              if($m == 3 || $m == 5 || $m == 8 || $m == 10){              //months with 30 days
                  if($d + 7 > 30){
                      $remainder = $d + 7 - 30;
                      $m = $m + 1;
                      $d = $remainder;
                  }
                  else{
                      $d = $d + 7;
                  }
              }
              else if ($m == 1){
                  if($d + 7 > 28){
                      $remainder = $d + 7 - 30;
                      $m = $m + 1;
                      $d = $remainder;
                  }
                  else{
                      $d = $d + 7;
                  }
              }
              else{
                  if($d + 7 > 31){
                      $remainder = $d + 7 - 30;
                      $m = $m + 1;
                      $d = $remainder;
                  }
                  else{
                      $d = $d + 7;
                  }
              }
          }
          echo json_encode(htmlentities("Weekly repeating event successfully added!"));
      }
      else if($dur == "monthly"){
          for($i = 0; $i<$num_repeats; $i++){
              $stmt = $mysqli->prepare("insert into events (user_id, month, year, day, title, tag_id, dur, time_event) values (?, ?, ?, ?, ?, ?, ?, ?)");

              if(!$stmt){
                  echo json_encode("Query Prep Failed: %s\n", $mysqli->error);
                  exit;
              }
              $stmt->bind_param('siiisiss', $_SESSION['username'], $m, $y, $d, $t, $tag, $dur, $time);         //add event info into database

              $stmt->execute();

              $stmt->close();

              $m = ($m + 1) % 12;
          }
          echo json_encode(htmlentities("Monthly repeating event successfully added!"));
      }
      else if($dur == "yearly"){
          for($i = 0; $i<$num_repeats; $i++){
              $stmt = $mysqli->prepare("insert into events (user_id, month, year, day, title, tag_id, dur, time_event) values (?, ?, ?, ?, ?, ?, ?, ?)");

              if(!$stmt){
                  echo json_encode("Query Prep Failed: %s\n", $mysqli->error);
                  exit;
              }
              $stmt->bind_param('siiisiss', $_SESSION['username'], $m, $y, $d, $t, $tag, $dur, $time);         //add event info into database

              $stmt->execute();

              $stmt->close();

              $y = $y + 1;
          }
          echo json_encode(htmlentities("Yearly repeating event successfully added!"));
      }
      else{                               //dur = once or null
          $stmt = $mysqli->prepare("insert into events (user_id, month, year, day, title, tag_id, dur, time_event) values (?, ?, ?, ?, ?, ?, ?, ?)");

          if(!$stmt){
              echo json_encode("Query Prep Failed: %s\n", $mysqli->error);
              exit;
          }
          $stmt->bind_param('siiisiss', $_SESSION['username'], $m, $y, $d, $t, $tag, $dur, $time);         //add event info into database

          $stmt->execute();

          $stmt->close();

          echo json_encode(htmlentities("Event successfully added!"));
      }
    }


?>
