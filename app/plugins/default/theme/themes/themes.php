<?php
defined('PF_VERSION') OR header('Location:404.html');
$action = (! empty ( $_GET ['action'] )) ? $_GET ['action'] : 'index';

switch ($action) {
    case 'index' :
        $themes = get_themes ( ABSPATH . '/app/themes' );
        $active_theme = get_option ( 'active_theme' );
        $theme_info = get_theme_info ( ABSPATH . '/app/themes/' . $active_theme . '/' . $active_theme . '.php' );
        eval ( $theme_info );
        
        require abs_plugin_path(__FILE__) . '/theme/themes/templates/index.php';
        break;
    case 'activate':
        if (!empty($_GET['theme'])){
            update_option('active_theme', $_GET['theme']);
        }
        header("Location: ".admin_url('action=index&theme='));
        break;
}