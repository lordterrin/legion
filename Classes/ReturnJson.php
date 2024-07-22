<?php

class ReturnJson {

  public static function ReturnJson($input = []) {

    $output = [
      'status'         => $status ?? 'fail',
      'message'        => $message ?? '',
      'error_line'     => $error_line ?? '',
      'error_file'     => $error_file ?? '',
      'execution_time' => round(microtime(true) - $start_time, 3) . " seconds",
      'queue_rows'     => $queue_rows ?? [],
      'final_status'   => $final_status ?? '',
      'final_message'  => $final_message ?? '',
      'debug'          => self::$debug ?? [],
    ];

    return json_encode($output);

  }

}