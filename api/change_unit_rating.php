<?php

$servername = $_ENV['servername'];
$username 	= $_ENV['username'];
$password 	= $_ENV['db_password'];

$unit_id  = $_POST['unit_id'];
$level 		= $_POST['level'];
$rating 	= $_POST['rating'];
$action 	= $_POST['action'];

$update_field = $level . "_" . $rating;

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if ( $action == 'add' ) {
	$sql = "UPDATE legion_data.units SET $update_field = $update_field + 1 where `id` = $unit_id;";
} else if ( $action == 'remove' ) {
	$sql = "UPDATE legion_data.units SET $update_field = $update_field - 1 where `id` = $unit_id;";
}

$result = $conn->query($sql);

$output = [];
// while($row = $result->fetch_assoc()) {
// 	$output[] = $row;
// }

echo "update successful";