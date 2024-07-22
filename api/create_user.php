<?php

sleep(1);


require_once '../Classes/User.php'; // user contains Dba.php and Env.php

$username 	= trim($_POST['user_username']);
$password 	= $_POST['user_password'];
$version 		= $_POST['version'];
$email 			= '';


$new_user = User::insertUser($username, $password, $version);

echo $new_user;

?>
