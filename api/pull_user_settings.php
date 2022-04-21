<?php

session_start();

require $_SERVER['DOCUMENT_ROOT'] .'/legion/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT'] . '/legion');
$dotenv->load();

$environment = $_ENV['environment'];

if ( $environment !== 'prod') {
	$servername = $_ENV['local_servername'];
} else {
	$servername = $_ENV['servername'];
}

$username 	= $_ENV['username'];
$password 	= $_ENV['db_password'];

$user_id 		= $_SESSION["user_id"] ?? 0;
if ( $user_id == 0 ) {
	echo "no user is logged in";
	die();
}


// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

/* $sql = "SELECT
id, 1_good, 1_bad, 2_good, 2_bad, 3_good, 3_bad, 4_good, 4_bad, 5_good, 5_bad, 6_good, 6_bad, 7_good, 7_bad, 8_good, 8_bad, 9_good, 9_bad, 10_good, 10_bad, 11_good, 11_bad, 12_good, 12_bad, 13_good, 13_bad, 14_good, 14_bad, 15_good, 15_bad, 16_good, 16_bad, 17_good, 17_bad, 18_good, 18_bad, 19_good, 19_bad, 20_good, 20_bad
  FROM `legion_data`.users where id = $user_id;";
*/

$sql = "SELECT * from legion_data.units_audit where user_id = $user_id;";
$result = $conn->query($sql);

$output = [];
while($row = $result->fetch_assoc()) {
	$output[] = $row;
}


echo json_encode($output);