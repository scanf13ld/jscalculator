<?php
// Content of database.php
$mysqli = new mysqli('localhost', 'cal', 'cse330!', 'calendar');

if($mysqli->connect_errno) {
	printf("Connection Failed: %s\n", $mysqli->connect_error);
	exit;
}
?>
