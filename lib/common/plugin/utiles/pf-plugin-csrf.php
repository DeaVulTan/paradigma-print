<?php

defined('PF_VERSION') OR header('Location:404.html');

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
class Pf_Plugin_CSRF
{

    public static function token($key = '', $expiration = 600)
    {
        $time = time() + $expiration;
        $token = md5(uniqid());
        $csrf = isset($_SESSION['csrf']) ? $_SESSION['csrf'] : array();
        $csrf["_token{$key}"] = $token . '||' . $time;
        $_SESSION['csrf'] = $csrf;
        return $token;
    }

    private static function check_token($token, $key = '',$remove = true)
    {
        $csrf = isset($_SESSION['csrf']) ? $_SESSION['csrf'] : array();
        if (!isset($csrf["_token{$key}"])) {
            return false;
        }
        $tmp = explode("||", $csrf["_token{$key}"]);
        if (!isset($tmp[0]) || $tmp[0] != $token) {
            return false;
        }
        // Removed check token time
        //if (!isset($tmp[1]) || $tmp[1] < time()) {
        //    return false;
        //}
        if($remove === true){
            $csrf["_token{$key}"] = null;
            $_SESSION['csrf'] = array_filter($csrf);
        }
        return true;
        
    }

    public static function is_valid($token, $key = '',$remove = true)
    {

        if (is_array($token)) {
            $result = true;
            foreach ($token as $k => $value) {
                if (!self::check_token($value, $key.$k,$remove)) {
                    $result = false;
                    break;
                }
            }
            return $result;
        } else {
            return self::check_token($token, $key,$remove);
        }
    }

}
