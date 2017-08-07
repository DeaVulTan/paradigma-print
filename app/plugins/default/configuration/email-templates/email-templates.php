<?php defined('PF_VERSION') OR header('Location:404.html');?>
<?php
$email_template = Pf::email_template();
$action = (!empty($_GET['action']))?$_GET['action']:'index';
$type = '';

if (!empty($_GET['type'])){
    $type = strtolower($_GET['type']);
}else{
    if (!empty($email_template->properties)){
        $ary_keys= array_keys($email_template->properties);
        $type = strtolower($ary_keys[0]);
    }
}

switch ($action){
	case 'save':
	    if (is_ajax() && $type != ''){
    	    $rs = get_option('email-templates');
    	    if (empty($rs) || !is_array($rs)){
    	        $rs = array();
    	    }
    	    $rs[$type] =  $_POST;
    	    
    	    update_option('email-templates', $rs);
    	    echo '{}';
	    }
	    break;
	default:
	    $rs = get_option('email-templates');
	    $data = (!empty($rs) && !empty($rs[$type]) && is_array($rs[$type]))?json_encode($rs[$type]):'{}';
	    require abs_plugin_path(__FILE__).'/configuration/email-templates/templates/index.php';
	    break;
}