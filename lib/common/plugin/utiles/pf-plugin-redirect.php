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
class Pf_Plugin_Redirect {

    public static function to($url, $admin = true) {
        if ($admin) {
            header('Location: ' . admin_url($url));
            exit();
        } else {
            header('Location: ' . RELATIVE_PATH . '/' . $url);
            exit();
        }
    }

}
