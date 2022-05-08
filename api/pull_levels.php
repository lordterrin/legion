<?php

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

$version 				= $_POST['version'];
if ( $version == 'matt' ) {
	$database = 'legion_data';
} else if ( $version == 'oze' ) {
	$database = 'legion_data_oze';
}

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM $database.levels;";
$result = $conn->query($sql);

$output = [];
while($row = $result->fetch_assoc()) {
	$output[] = $row;
}


echo json_encode($output);