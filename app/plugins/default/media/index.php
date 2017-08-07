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
require_once ABSPATH . '/lib/common/plugin/load.php';
$bootstrap = new Pf_Plugin_Bootstrap(true);
$bootstrap->set_plugin_name('media');
$bootstrap->start();
