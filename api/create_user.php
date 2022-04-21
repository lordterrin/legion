<?php

sleep(1);

require $_SERVER['DOCUMENT_ROOT'] .'/legion/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT'] . '/legion');
$dotenv->load();

$environment = $_ENV['environment'];

if ( $environment !== 'prod') {
	$servername = $_ENV['local_servername'];
} else {
	$servername = $_ENV['servername'];
}

$db_username 	= $_ENV['username'];
$db_password 	= $_ENV['db_password'];

// Create connection
$conn = new mysqli($servername, $db_username, $db_password);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$username 	= trim($_POST['user_username']);
$password 	= $_POST['user_password'];
$email 			= '';
$created_at = date("Y-m-d H:i:s", time());



    // Validate username
    if(empty( $username )) {
        echo "Please enter a username.";
        die();
    } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', $username )) {
        echo "Username can only contain letters, numbers, and underscores.";
        die();
    } else {
        // Prepare a select statement
        $sql = "SELECT id FROM legion_data.users WHERE username = ?";

        if($stmt = mysqli_prepare($conn, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            // Set parameters
            $param_username = trim($_POST["user_username"]);

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);

                if(mysqli_stmt_num_rows($stmt) == 1){
                    echo "This username is already taken.";
                    die();
                } else{
                    $username = trim($_POST["user_username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        } else {
        	echo "error";
        }
    }

    // Validate password
    if(empty(trim($_POST["user_password"]))){
        echo "Please enter a password.";
        die();
    } elseif(strlen(trim($_POST["user_password"])) < 3){
        echo "Password must have atleast 3 characters.";
        die();
    } else{
        $password = trim($_POST["user_password"]);
    }


    // Check input errors before inserting in database


    // Prepare an insert statement
    $sql = "INSERT INTO legion_data.users (username, password) VALUES (?, ?)";

    if($stmt = mysqli_prepare($conn, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);

        // Set parameters
        $param_username = $username;
        $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash

        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            echo "Success!";
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }

        // Close statement
        mysqli_stmt_close($stmt);
    } else {
    	echo "error";
    }

    // Close connection
    mysqli_close($conn);

?>
