<?php

//require_once '../Classes/Env.php';
// require_once '../Classes/Dba.php';
require_once '../Classes/User.php';


//echo Env::$environment;



//$foo = New User('yobbo', 'ohyeah', 'asdf');

$fake_username = "yobbo";
$fake_password = "ohyeah";

echo User::insertUser($fake_username, $fake_password);

//echo $foo->getUsername();





?>