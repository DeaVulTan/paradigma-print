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
if (!function_exists('plugin_check_acl')) {

    function plugin_check_acl($act = array())
    {
        if (!in_array(current_user('user-group'), $act)) {
            return false;
        }
        return true;
    }

}
