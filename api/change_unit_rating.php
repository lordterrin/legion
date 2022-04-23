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
$created_at = date("Y-m-d H:i:s", time());

$user_id 		= $_SESSION["user_id"] ?? 0;
if ( $user_id == 0 ) {
	echo "no user is logged in";
	die();
}

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

/* create audit record */
if ( $action == 'add' ) {

	$sql = "
		INSERT INTO legion_data.units_audit
		(user_id, unit_id, audit_field, audit_value, updated_at)
		VALUES
		($user_id, $unit_id, '$update_field', 1, '$created_at')
		ON DUPLICATE KEY UPDATE audit_value = 1";

} else if ( $action == 'remove' ) {

	$sql = "
		INSERT INTO legion_data.units_audit
		(user_id, unit_id, audit_field, audit_value, updated_at)
		VALUES
		($user_id, $unit_id, '$update_field', 0, '$created_at')
		ON DUPLICATE KEY UPDATE audit_value = 0";

}

$result = $conn->query($sql);

$output = [];
// while($row = $result->fetch_assoc()) {
// 	$output[] = $row;
// }

echo "update successful";