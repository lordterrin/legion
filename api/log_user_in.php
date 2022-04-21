<?php

sleep(2);

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

// Validate password
if(empty(trim($_POST["user_password"]))){
    $password_err = "Please enter a password.";
} elseif(strlen(trim($_POST["user_password"])) < 3){
    $password_err = "Password must have atleast 3 characters.";
} else{
    $password = trim($_POST["user_password"]);
}

// Validate username
if(empty( $username )) {
    $username_err = "Please enter a username.";
} elseif(!preg_match('/^[a-zA-Z0-9_]+$/', $username )) {
    $username_err = "Username can only contain letters, numbers, and underscores.";
} else {

    $sql = "SELECT id, username, password FROM legion_data.users WHERE username = ?";

    if($stmt = mysqli_prepare($conn, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $param_username);

        // Set parameters
        $param_username = $username;

        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            // Store result
            mysqli_stmt_store_result($stmt);

            // Check if username exists, if yes then verify password
            if(mysqli_stmt_num_rows($stmt) == 1){
                // Bind result variables
                mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                if(mysqli_stmt_fetch($stmt)){
                    if(password_verify($password, $hashed_password)){
                        // Password is correct, so start a new session
                        session_start();

                        // Store data in session variables
                        $_SESSION["loggedin"] = true;
                        $_SESSION["user_id"]  = $id;
                        $_SESSION["username"] = $username;

                        echo "Success!";

                    } else{
                        // Password is not valid, display a generic error message
                        echo "Invalid username or password.";
                    }
                }
            } else{
                // Username doesn't exist, display a generic error message
                echo "Invalid username or password.";
            }
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

}


// Close connection
mysqli_close($conn);

?>
