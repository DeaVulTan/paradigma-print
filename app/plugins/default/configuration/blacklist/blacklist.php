<?php defined('PF_VERSION') OR header('Location:404.html');?>
<?php
$action = (!empty($_GET['action']))?$_GET['action']:'index';
switch ($action){
	case 'save':
	        $rs = $_POST['list_ip'];
	        $rule = "/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/";
	        $arr = explode("\n",$rs);
	        $flag = true;
	        foreach($arr as $items){
	            if(!preg_match($rule,$items)){
	                $flag = false;
	                break;
	            }
	        }
	        if($flag == true || $rs == NULL){
	            update_option('ip_blacklist', $rs);
	            echo "true";
	        }else{
	            echo "false";
	        }
	    break;
	default:
	    $rs = get_option('ip_blacklist');
	    $data = $rs;
	    require abs_plugin_path(__FILE__).'/configuration/blacklist/templates/index.php';
	    break;
}