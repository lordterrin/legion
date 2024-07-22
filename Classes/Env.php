<?php

class Env
{

  public static $environment;
  public static $servername;
  private static $envLoaded = false;

  public static function load()
  {
    if (!self::$envLoaded) {
      require_once $_SERVER['DOCUMENT_ROOT'] .'/legion/vendor/autoload.php';
      $dotenv = Dotenv\Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT'] . '/legion');
      $dotenv->load();

      self::$environment = $_ENV['environment'];
      self::$servername = (self::$environment !== 'prod') ? $_ENV['local_servername'] : $_ENV['servername'];
      self::$envLoaded = true;
    }
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }
  }


  public static function getServername()
  {
    return self::$servername;
  }

}

Env::load();