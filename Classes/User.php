<?php

require_once  __DIR__ . '/Dba.php';

class User
{

  //default properties of the User class
  private static $username;
  private static $password;
  private static $version;
  private static $email;
  private static $created_at; // = date("Y-m-d H:i:s", time());
  private static $database;
  private static $userError;
  private static $passwordError;
  private static $conn;

  public static function getUserId()
  {
    return $_SESSION['user_id'];
  }
  public static function getUsername()
  {
    return self::$username;
  }
  public function setUsername($username)
  {
    self::$username = $username;
  }
  public function setPassword($password)
  {
    self::$password = $password;
  }
  public function setVersion($version)
  {
    self::$version = $version;
  }
  public function setEmail($email)
  {
    self::$email = $email;
  }

  private static function validateUser()
  {
    if(empty( self::$username )) {
      self::$userError = "Please enter a username";
      return true;
    } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', self::$username )) {
      self::$userError = "Username can only contain letters, numbers, and underscores";
      return true;
    } else {
      try {

        self::$conn = Dba::connect();

        $sql = "SELECT id FROM legion_data.users WHERE username = ?";

        if($stmt = mysqli_prepare(self::$conn, $sql)) {

          mysqli_stmt_bind_param($stmt, "s", $param_username);
          $param_username = self::$username;


          if(mysqli_stmt_execute($stmt)){

            /* store result */
            mysqli_stmt_store_result($stmt);

            if(mysqli_stmt_num_rows($stmt) == 1){
              self::$userError = "Username is unavilable";
              return true;
          } else {
            mysqli_stmt_close($stmt);
            self::$userError = "";
            return false;
            }
          }
        }
      } catch (Exception $e) {
        self::$userError = "db error : " . $e->getMessage();
        return true;
      }
    }
  }

  private static function validatePassword()
  {
    if(empty(trim(self::$password))){
        self::$passwordError = "Please enter a password";
        return true;
    } elseif( strlen(trim(self::$password)) < 3 ){
        self::$passwordError = "Please create a longer password";
        return true;
    } else{
        return false;
    }
  }

  public static function insertUser($username, $password, $version = 'oze') {

    self::$username = $username;
    self::$password = $password;

    // Prepare a select statement
    if ( self::validateUser() ) {
      return self::$userError;
    }

    if ( self::validatePassword() ) {
      return self::$passwordError;
    }

    self::$version = 'oze';

    $sql = "INSERT INTO legion_data.users (username, password) VALUES (?, ?)";

    if($stmt = mysqli_prepare(self::$conn, $sql)){

      // Bind variables to the prepared statement as parameters
      mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);

      // Set parameters
      $param_username = self::$username;
      $param_password = password_hash(self::$password, PASSWORD_DEFAULT); // Creates a password hash

      // Attempt to execute the prepared statement
      if(mysqli_stmt_execute($stmt)){
          return "success";
      }
      // Close statement
      mysqli_stmt_close($stmt);
      mysqli_close(self::$conn);
    }
  }

}
