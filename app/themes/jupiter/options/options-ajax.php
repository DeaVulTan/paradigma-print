<?php
defined('PF_VERSION') OR header('Location:404.html');
if (count($_POST) > 0){
    update_option('theme_options',$_POST);
    echo json_encode(array('type' => 'success', 'message' => __('Theme option is updated successfully', 'jupiter-theme')));
    die;
}