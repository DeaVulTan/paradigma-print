<?php

/**
 * 
 * @package		Vitubo
 * @author		Vitubo Team 
 * @copyright   Vitubo Team
 * @link		http://www.vitubo.com
 * @since		Version 1.0
 * @filesource
 *
 */

define('ABSPATH', preg_replace('/\\\/', '/', dirname(dirname(dirname(__FILE__)))));
require ABSPATH . '/app/configs/config-tinymce.php';

/** Administration folder */
define('ADMIN_FOLDER',"admin");

$relative_path = (strpos($_SERVER["SCRIPT_NAME"], '/'.ADMIN_FOLDER.'/') !== false) ? dirname(substr($_SERVER['SCRIPT_NAME'], 0, strrpos($_SERVER['SCRIPT_NAME'], '/'))) : substr($_SERVER['SCRIPT_NAME'], 0, strrpos($_SERVER['SCRIPT_NAME'], '/'));
define('RELATIVE_PATH', (strlen($relative_path) > 1) ? $relative_path : '');

define('PLUGIN_PATH', ABSPATH . '/app/plugins/others');
define('DEFAULT_PLUGIN_PATH', ABSPATH . '/app/plugins/default');

define('RELATIVE_PLUGIN_PATH', RELATIVE_PATH . '/app/plugins/others');
define('RELATIVE_DEFAULT_PLUGIN_PATH', RELATIVE_PATH . '/app/plugins/default');

// A random string used in security hashing methods.
define('__SECURITY_SALT__', '');

// A random numeric string (digits only) used to encrypt/decrypt strings.
define('__SECURITY_CIPHER_SEED__', '');


/** Debug */
define('DEBUG', false);

if (DEBUG == true) {
    error_reporting(-1);
}

/** Table Prefix */
define('DB_PREFIX', '');

/** The name of the database for Vitubo */
define('DB_NAME', '');

/** MySQL database username */
define('DB_USER', '');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', '');


define('DB_PORT', '');

define('DB_SOCKET', '');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. */
define('DB_COLLATE', 'utf8_general_ci');

if (get_magic_quotes_gpc()) {

    function stripslashes_array($array)
    {
        return is_array($array) ? array_map('stripslashes_array', $array) : stripslashes($array);
    }

    $_GET = stripslashes_array($_GET);
    $_POST = stripslashes_array($_POST);
    $_COOKIE = stripslashes_array($_COOKIE);
}
global $html_charset_list;
$html_charset_list = array(
    'utf-8'=>'UTF-8',
    'gb2312'=>'Chinese Simplified (GB2312)',
    'iso-8859-1'=>'Western Alphabet',
    'iso-8859-2'=>'Central European Alphabet (ISO)',
    'shift-jis'=>'Japanese (Shift-JIS)',
);

/** Vitubo Version */
define('PF_VERSION','2.3');

/** MOD_REWRITE */
define('MOD_REWRITE',true);