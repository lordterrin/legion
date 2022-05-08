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
$created_at  = date("Y-m-d H:i:s", time());

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

$sql = "SELECT basic_strategy from $database.units WHERE id = $unit_id;";
$result = $conn->query($sql);

while($row = $result->fetch_assoc()) {
	$output[] = $row;
}

try {
	$previous_basic_strategy = $output[0]['basic_strategy'] ?? '';
} catch(Exception $e) {
	$previous_basic_strategy = '';
}

/* update the units table */
$stmt = $conn->prepare("UPDATE $database.units SET basic_strategy = ? , updated_by = ? where id = $unit_id");
$stmt->bind_param("si", $new_unit_text, $user_id);
$stmt->execute();


/* create audit record */
$sql = "
	INSERT INTO $database.description_audit
	(user_id, unit_id, previous_description, new_description, updated_at)
	VALUES
	($user_id, $unit_id, '$previous_basic_strategy', '$new_unit_text', '$created_at');";

$result = $conn->query($sql);


echo "update successful";