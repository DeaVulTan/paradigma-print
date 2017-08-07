<?php defined('PF_VERSION') OR header('Location:404.html');?>
<?php
$setting = Pf::setting();
$action = (!empty($_GET['action']))?$_GET['action']:'index';

switch ($action){
	case 'save':
	    if (is_ajax()){
	        update_option('settings', $_POST);
                upate_widget_category();
	        echo "{}";
	    }
	    break;
	case 'admin_menu_order':
	    if (!empty($_POST['menus'])){
	       update_option('admin_menu_order', $_POST['menus']);
	    }
	    break;
	default:
	    $db = Pf::database();
	    $rs = get_option('settings');
	    $data = (!empty($rs) && is_array($rs))?json_encode($rs):'{}';
	    $db->select(
	            'id,page_url, page_title',
	            ''.DB_PREFIX.'pages',
	            'page_system = 0 and page_status = ?',
	            array(1), 'id desc'
	    );
        global $html_charset_list;
	    $pages = $db->fetch_assoc_all();
	    require abs_plugin_path(__FILE__).'/configuration/settings/templates/index.php';
	    break;
}