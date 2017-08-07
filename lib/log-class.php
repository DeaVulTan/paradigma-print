<?php
class Pf_Log {
    
    const INFO = 1;
    const NOTICE = 2;
    const DEBUG = 3;
    const WARNING = 4;
    const ALERT = 5;
    const ERROR = 6;
    const CRITICAL = 7;
    const EMERGENCY = 8;
    
    private $level = array(
        1 => 'INFO',
        2 => 'NOTICE',
        3 => 'DEBUG',
        4 => 'WARNING',
        5 => 'ALERT',
        6 => 'ERROR',
        7 => 'CRITICAL',
        8 => 'EMERGENCY',
    );
    
    private $date_format = 'Y-m-d H:i:s';
    private $log_directory;
    private $log_file_path;
    
    
    public function __construct($log_directory){
        $this->log_directory = $log_directory;
        $oldumask = umask(0);
        if (!file_exists($log_directory)){
            mkdir($log_directory,0777,true);
        }
        umask($oldumask);
        
        $this->log_file_path = $log_directory.'/log_'.date('Y-m-d').'.txt';
    }
    
    public function set_date_format($date_format){
        return $this->date_format = $date_format;
    }
    
    public function log($level,$message,$context = array()){
        $message = $this->format_message($level, $message, $context);
        $oldumask = umask(0);
        error_log($message,3,$this->log_file_path);
        umask($oldumask);
    }
    
    private function format_message($level,$message,$context){
        $level = (isset($this->level[$level]))?$this->level[$level]:$level;
        if (! empty($context)) {
            $message .= PHP_EOL.print_r($context,true);
        }
        return "[{$this->get_timestamp()}] [{$level}] {$message}".PHP_EOL;
    }
    
    private function get_timestamp(){
        $original_time = microtime(true);
        $micro = sprintf("%06d", ($original_time - floor($original_time)) * 1000000);
        $date = new DateTime(date('Y-m-d H:i:s.'.$micro, $original_time));
    
        return $date->format($this->date_format);
    }
}