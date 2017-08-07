<?php
defined('PF_VERSION') OR header('Location:404.html');
$ref=array('admin-page'=>'menu');
    if(!empty($_GET['page'])) $ref['page']  =   $_GET['page'];
    if(!empty($_GET['menu-name'])) $ref['menu-name']    =   $_GET['menu-name'];
$menu = new Pf_Menu();
$menu_name = array(
    "name" => "title",
    "required" => "yes",
    "id"    =>  "menu_title"
);
$item_name = array(
    "name" => "item-name",
    "class" => "form-control item-name"
);
$page_list = array();
$menu->db->select('id, page_title', ''.DB_PREFIX.'pages', 'page_system=? & page_status=?', array(0,1), 'id desc');

while ($pages = $menu->db->fetch_assoc()) {

    $page_id = $pages['id'];

    $page_name = $pages['page_title'];

    $page_list[$page_id] = htmlspecialchars($page_name);
}
$checked = '';
$menu_all = get_option('menu');
if (isset($_GET['menu-id'])) {
    if(isset($_SESSION['success'])){
        echo "<script>notif('".__('Menu is created successfully','menu')."','success');</script>";
        unset ($_SESSION['success']);
    }
    $menu_id = $_GET['menu-id'];
    $k = $menu->choose_menu($menu_all, $menu_id);
    $menu_name['value'] = $menu_all[$k]['name'];
    $data_menu = $menu_all[$k]['data'];
    if(!empty($_POST['json-menu'])){
        if(strlen($_POST['title'])>255){
            echo "<script>notif('".__('Menu name can not more than 255 characters','menu')."','warning');</script>";
        }
        else{
        $menu_all[$k]['name']=$_POST['title'];
        $data=json_decode($_POST['json-data'],true);
        $list_id=array();
        foreach($data as $key){
            $list_id[$key['id']]    =   1;
        }
        $order=$menu->filter_data(json_decode($_POST['json-menu'],true),$list_id);
        $menu_all[$k]['data'][0]    =   $order;
        $menu_all[$k]['data'][1]    =   $data;
        update_option('menu', $menu_all);
        $data_menu  =   array($order, $data);
        $note= __('Menu is updated successfully','menu');
        }
    }
} else {
    if (!empty($_POST['title'])) {
        if(strlen($_POST['title'])>255){
            echo "<script>notif('".__('Menu name can not more than 255 characters','menu')."','warning');</script>";
        }
        else{
        $id = uniqid();
        $data_menu = json_decode($_POST['json-data'],true);
        $list_id=array();
        foreach($data_menu as $key){
            $list_id[$key['id']]    =   1;
        }
        $list  =   $menu->filter_data(json_decode($_POST['json-menu'], true), $list_id);
        $data = array(
            'id' => $id,
            'name' => $_POST['title'],
            'data' => array(0 => $list, 1 => $data_menu)
        );
        $menu_all[] = $data;
        update_option('menu', $menu_all);
        $_SESSION['success']    =   'success';
        header('location: ?admin-page=menu&act=once&menu-id=' . $id);
        $data_menu = $data['data'];
        }
    }
}
require_once abs_plugin_path(__FILE__) . "/menu/views/views-once.php";
