<?php
defined('PF_VERSION') OR header('Location:404.html');
class Pf_Error_Handler {
    private $error_file = 'error.log';
    private $error_folder_path = '';
    public function __construct() {
        $this->error_file = 'error_'.date('Y-m-d').'.log';
        $this->error_folder_path = ABSPATH . '/tmp/logs';
        $oldumask = umask ( 0 );
        if (! is_dir ( $this->error_folder_path ) && ! file_exists ( $this->error_folder_path ))
            mkdir ( $this->error_folder_path, 0777, true );
        umask ( $oldumask );
        
        register_shutdown_function ( array (
                $this,
                "handle_fatal_error" 
        ) );
        set_error_handler ( array (
                $this,
                'handle_error' 
        ), E_ALL | E_STRICT );
        set_exception_handler ( array (
                $this,
                'handle_exception' 
        ) );
    }
    function handle_fatal_error() {
        $error = error_get_last ();
        
        if (! empty ( $error )) {
            $errno = $error ["type"];
            $errfile = $error ["file"];
            $errline = $error ["line"];
            $errstr = $error ["message"];
            if (in_array ( $errno, array (1,4,16,64) )) {
                $this->handle_error ( $errno, $errstr, $errfile, $errline );
                $_SESSION['PF_ERROR'] = 1;
                header ( "Location: " . site_url () . RELATIVE_PATH . '/error.php' );
            }
        }
    }
    public function handle_error($errno, $errstr, $errfile, $errline) {
        if (! (error_reporting () & $errno)) {
            // error do not match current error reporting level
            return true;
        }
        
        switch ($errno) {
            case E_ERROR :
                $errno_str = 'E_ERROR';
                break;
            case E_WARNING :
                $errno_str = 'E_WARNING';
                break;
            case E_PARSE :
                $errno_str = 'E_PARSE';
                break;
            case E_NOTICE :
                $errno_str = 'E_NOTICE';
                break;
            case E_CORE_ERROR :
                $errno_str = 'E_CORE_ERROR';
                break;
            case E_CORE_WARNING :
                $errno_str = 'E_CORE_WARNING';
                break;
            case E_COMPILE_ERROR :
                $errno_str = 'E_COMPILE_ERROR';
                break;
            case E_COMPILE_WARNING :
                $errno_str = 'E_COMPILE_WARNING ';
                break;
            case E_USER_ERROR :
                $errno_str = 'E_USER_ERROR';
                break;
            case E_USER_WARNING :
                $errno_str = 'E_USER_WARNING';
                break;
            case E_USER_NOTICE :
                $errno_str = 'E_USER_NOTICE';
                break;
            case E_STRICT :
                $errno_str = 'E_STRICT';
                break;
            case E_DEPRECATED :
                $errno_str = 'E_DEPRECATED';
                break;
            case E_USER_DEPRECATED :
                $errno_str = 'E_USER_DEPRECATED';
                break;
            default :
                $errno_str = 'UNKNOWN';
        }
        
        $message = '[' . date ( 'Y-m-d H:i:s' ) . '] [' . $errno_str . '] [' . $errno . '] ' . $errstr . " in {$errfile}:{$errline}" . PHP_EOL;
        $this->log_file ( $message, $this->error_folder_path . '/' . $this->error_file );
    }
    public function handle_exception(Exception $Exception) {
        $message = '[' . date ( 'Y-m-d H:i:s' ) . '] [EXCEPTION] [class:' . get_class ( $Exception ) . '] ' . $Exception->getMessage () . " in {$Exception->getFile()}:{$Exception->getLine()}";
        $message .= PHP_EOL . $Exception->getTraceAsString () . PHP_EOL;
        $this->log_file ( $message, $this->error_folder_path . '/' . $this->error_file );
        $_SESSION['PF_ERROR'] = 1;
        header ( "Location: " . site_url () . RELATIVE_PATH . '/error.php' );
        exit ();
    }
    private function log_file($message, $file) {
        error_log ( $message, 3, $file );
    }
}

if (! function_exists ( 'error_get_last' )) {
    function error_get_last() {
        return array ();
    }
}
