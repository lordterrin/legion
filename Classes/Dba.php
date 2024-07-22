<?php

require_once  __DIR__ . '/Env.php';

class Dba
{

  private static $servername;
  private static $db_db      = 'legion_data';
  private static $dbusername;
  private static $dbpassword;
  private static $db_set = false;
  private static $conn;

  public static function loaddba() {
    if ( !self::$db_set ) {
      self::$servername = Env::getServername();
      self::$dbusername = $_ENV['username'];
      self::$dbpassword = $_ENV['db_password'];
      self::$db_set = true;
    }
  }

  public static function getDbdb() {
    return self::$db_db;
  }

  public static function ExecuteQuery($query)
  {

    $conn = self::connect();

    $result = $conn->query($query);
    return $result;

  }

  public static function connect() {

    try {
      $conn = new mysqli(self::$servername, self::$dbusername, self::$dbpassword, self::$db_db);
      return $conn;
    } catch (Exception $e) {
      die("Connection failed : " . $conn->connect_error );
    }
  }

}

Dba::loaddba();
