<?php

require_once  __DIR__ . '/User.php';

class Unit {

  private static $unit_id;
  private static $level;
  private static $rating;
  private static $action;
  private static $UserIsLoggedIn = false;

  public static function output($status = 'fail', $operation = '', $message = '')
  {

    $output = [
      'status'    => $status,
      'operation' => $operation,
      'message'   => $message
    ];

    return json_encode($output);

  }

  public static function changeUnitRating($unit_id, $level, $rating, $action)
  {

      self::$unit_id  = $unit_id;
      self::$level    = $level;
      self::$rating   = $rating;
      self::$action   = $action;

      $update_field   = $level . "_" . $rating;
      $audit          = (self::$action == 'add' ? 1 : 0);
      $user_id        = User::getUserId();
      $created_at     = date("Y-m-d H:i:s", time());
      $output         = [];

      if ( !self::isUserLoggedIn() ) {
        return self::output(0, 'login', 'You must be logged in to do this.');
      }

      /* increase or decrease the level in question */
      if ( self::$action == 'add' ) {
        $update = "+ 1";
        $audit = 1;
      } elseif ( self::$action == 'remove' ) {
        $update = "- 1";
        $audit = 0;
      }

      $sql = "UPDATE legion_data.units SET $update_field = $update_field $update where `id` = $unit_id;";

      if ( $foo = Dba::ExecuteQuery($sql) ) {
        $execUpdate = [
          'status'    => 'success',
          'operation' => 'update',
          'message'   => '',
        ];
      } else {
        $execUpdate = [
          'status'    => 'fail',
          'operation' => 'update',
          'message'   => $foo,
        ];
      }

      array_push($output, $execUpdate);

      $sql = "
          INSERT INTO legion_data.units_audit
          (user_id, unit_id, audit_field, audit_value, updated_at)
          VALUES
          ($user_id, $unit_id, '$update_field', $audit, '$created_at')
          ON DUPLICATE KEY UPDATE audit_value = $audit";

      if ( $foo = Dba::ExecuteQuery($sql) ) {
        $execUpdate = [
          'status'    => 'success',
          'operation' => 'audit',
          'message'   => '',
        ];
      } else {
        $execUpdate = [
          'status'    => 'fail',
          'operation' => 'audit',
          'message'   => '',
        ];
      }

      array_push($output, $execUpdate);

      return json_encode($output);
  }

  public static function isUserLoggedIn() {

    if ( empty($_SESSION["user_id"]) ) {
      return false;
    } else {
      return true;
    }

  }

}