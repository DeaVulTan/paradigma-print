<?php
defined('PF_VERSION') OR header('Location:404.html');

$list_menu = get_option('menu');
$c_menu = new Pf_Menu();
if (isset($_GET['menu-name'])) {
    $sub = strtolower($_GET['menu-name']);
    foreach ($list_menu as $k => $v) {
        $str = strtolower($v['name']) ;
        if( strpos($str, $sub) === false ){
            unset($list_menu[$k]);
        }
    }
    
    $list_menu= array_values($list_menu);
}
if (isset($_GET['action'])) {
    if ($_GET['action'] == 'delete') {
        foreach ($list_menu as $k => $v) {
            if ($_GET['id'] == $v['id']) {
                unset($list_menu[$k]);
                break;
            }
        }
        if (empty($list_menu))
            $list_menu = '';
        update_option('menu', $list_menu);
        header("Location: " .admin_url('admin-page=menu',false));
    }
    elseif ($_GET['action'] == 'unpublish') {
        
    }
}
if (!empty($_POST['cid'])) {
    foreach ($_POST['cid'] as $cid) {
        foreach ($list_menu as $k => $v) {
            if (trim($v['id']) == trim($cid)) {
                unset($list_menu[$k]);
            }
        }
    }
    update_option('menu', $list_menu);
}
$id_new = uniqid();
if (isset($_POST['add'])) {
    $c_menu->add_menu($list_menu, $id_new);
}
if (!isset($_GET['page']))
    $page = 0;
else
    $page = $_GET['page']-1;
$org_url = "?admin-page=menu";
$ppage = NUM_PER_PAGE;
$limit = $page * $ppage;
$total = count($list_menu);
$list   =   array();
if(!empty($list_menu))
$list_menu  = array_values($list_menu);
for($i=$limit; $i<$limit+$ppage; $i++){
    if(!empty($list_menu[$i]))
        $list[]   =   $list_menu[$i];
}
$pages = new Pf_Paginator($total, NUM_PER_PAGE, 'page');
require_once abs_plugin_path(__FILE__) . "/menu/views/views-list.php";
