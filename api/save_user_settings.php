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

$version 				= $_POST['version'];
$database = 'legion_data';


// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * from legion_data.units_audit where user_id = $user_id;";
$result = $conn->query($sql);

$output = [];
while($row = $result->fetch_assoc()) {
	$output[] = $row;
}


echo json_encode($output);