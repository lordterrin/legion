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

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$user_id 		= $_SESSION["user_id"] ?? 0;
if ( $user_id == 0 ) {
	echo "no user is logged in";
	die();
} else {

	$version 				= $_POST['version'];
	$database 			= '';

	if ( $version == 'matt' ) {
		$database = 'legion_data';
	} else if ( $version == 'oze' ) {
		$database = 'legion_data_oze';
	}

	echo "version = " . $version . "<br>";
	echo "database = " . $database . "<br>";

	if ( !empty($database) ) {

		/* see if this user has a cached version */
		$sql = "SELECT username from $database.users where user_id = $user_id;";
		$result = $conn->query($sql);

		$output = [];
		while($row = $result->fetch_assoc()) {
			$output[] = $row;
		}

		echo "<pre>";
		print_r($output);
		echo "</pre>";
		die();

	} else {
		echo "asdf";
		die();
	}

}




// $version 				= $_POST['version'];
// if ( $version == 'matt' ) {
// 	$database = 'legion_data';
// } else if ( $version == 'oze' ) {
// 	$database = 'legion_data_oze';
// }




// $sql = "SELECT * from $database.units_audit where user_id = $user_id;";
// $result = $conn->query($sql);

// $output = [];
// while($row = $result->fetch_assoc()) {
// 	$output[] = $row;
// }


// echo json_encode($output);