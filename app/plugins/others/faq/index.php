<?php defined('PF_VERSION') OR header('Location:404.html'); ?>
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
require_once ABSPATH . '/lib/common/plugin/load.php';
require_once abs_plugin_path(__FILE__) . '/faq/helper.php';
$bootstrap = new Pf_Plugin_Bootstrap();
$bootstrap->set_plugin_name('faq');
//$permission = array(
//    'main'=>'view',
//    'create'=>'create',
//    'edit'=>'edit',
//    'status'=>'status',
//    'delete'=>'delete'
//);
//$bootstrap->start('faq',$permission);
    $bootstrap->start();