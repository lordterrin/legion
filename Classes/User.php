<?php

class User
{

  //default properties of the User class
  private $username;
  private $password;
  private $version;
  private $email;
  private $created_at;
  private $database;

  /* constructor */
  public function __construct($username, $password, $version, $email = '') {

    $this->username   = $username;
    $this->password   = $password;
    $this->version    = $version;
    $this->email      = $email;
    $this->created_at = date("Y-m-d H:i:s", time());
    $this->database   = 'legion_data';
  }

  public function getUsername()
  {
    return $this->username;
  }
  public function setUsername($username)
  {
    $this->username = $username;
  }
  public function setPassword($password)
  {
    $this->password = $password;
  }
  public function setVersion($version)
  {
    $this->version = $version;
  }
  public function setEmail($email)
  {
    $this->email = $email;
  }

  private function validateUser()
  {
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
  }


}
