<?php

require_once  '../Classes/Unit.php';

/* pull in the posted variables */
$unit_id  = $_POST['unit_id'];
$level    = $_POST['level'];
$rating   = $_POST['rating'];
$action   = $_POST['action'];

echo Unit::changeUnitRating($unit_id, $level, $rating, $action);


die();




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