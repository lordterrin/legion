<?php

session_start();

if( isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true && ($_SESSION["user_id"] > 0 ) ){

} else {
	echo "Please login first";
	die();
	exit();
}


require $_SERVER['DOCUMENT_ROOT'] .'/legion/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT'] . '/legion');
$dotenv->load();

$environment = $_ENV['environment'];

if ( $environment !== 'prod') {
	$servername = $_ENV['local_servername'];
} else {
	$servername = $_ENV['servername'];
}

$username 			= $_ENV['username'];
$password 			= $_ENV['db_password'];
$unit_id 	  		= $_POST['unit_id'];
$new_unit_text 	= $_POST['new_unit_text'];
$user_id 				= $_SESSION["user_id"];



// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("UPDATE legion_data.units SET basic_strategy = ? , updated_by = ? where id = $unit_id");
$stmt->bind_param("si", $new_unit_text, $user_id);
$stmt->execute();

//$result = $conn->query($sql);

$output = [];
// while($row = $result->fetch_assoc()) {
// 	$output[] = $row;
// }

echo "update successful";