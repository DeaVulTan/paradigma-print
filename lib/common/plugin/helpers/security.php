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
function remove_invisible_characters($str, $url_encoded = TRUE)
{
    $non_displayables = array();
    if ($url_encoded) {
        $non_displayables[] = '/%0[0-8bcef]/';
        $non_displayables[] = '/%1[0-9a-f]/';
    }
    $non_displayables[] = '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S';

    do {
        $str = preg_replace($non_displayables, '', $str, -1, $count);
    } while ($count);

    return $str;
}
