<?php 
class Logging {
    private static $log_file_path = 'c\tmp\log\debug.log';

    public static function LogError($message) {
        error_log(self::getTime()."-ERROR: ".$message."\n", 3, self::$log_file_path);
    }

    private static function getTime(){
        date_default_timezone_set("Europe/Berlin");
        $timestamp = time();
        $date = date("d:m:Y", $timestamp);
        $time = date("H:i:s", $timestamp);
        return $date." ".$time;
    }
}
?>