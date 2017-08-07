<?php 
defined('PF_VERSION') OR header('Location:404.html');

$action = (!empty($_GET['action']))?strtolower($_GET['action']):'index';

switch ($action){
	case 'load':
	    if (is_ajax() && !empty($_GET['template'])){
	        if ($_GET['template'] == 'sidebar-menu'){
	            require ABSPATH . '/'.ADMIN_FOLDER.'/themes/default/sidebar-menu.php';
	        }else{
    	        if (is_file(DEFAULT_PLUGIN_PATH.'/configuration/admin-menu/templates/'.$_GET['template'].'-template.php')){
    	            require DEFAULT_PLUGIN_PATH.'/configuration/admin-menu/templates/'.$_GET['template'].'-template.php';
    	        }
	        }
	    }
	    break;
	case 'save':
	    if (is_ajax() && !empty($_POST['admin_menu_setting'])){
	        update_option('admin_menu_setting', $_POST['admin_menu_setting']);
	    }
	    break;
	default:
	    $settings = get_option('admin_menu_setting');
	    if (!is_array($settings)){
	        $settings = array();
	    }
	    require abs_plugin_path(__FILE__).'/configuration/admin-menu/templates/index.php';
	    break;
}