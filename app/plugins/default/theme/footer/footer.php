<?php
defined('PF_VERSION') OR header('Location:404.html');
$action = (!empty($_GET ['action']) && in_array($_GET ['action'], array(
            'index',
            'save',
            'setting-form'
        ))) ? $_GET ['action'] : 'index';
switch ($action) {
    case 'save':
        $data = array(
            'json_data' => $_POST['json_data'],
            'setting_data' => json_decode($_POST ['setting_data'], true)
        );
        update_option('footer', $data);
        $_SESSION['success']    =  __('Footer is updated successfully','theme');
        header('Location: ' . admin_url('action=index'));
        break;
    case 'setting-form':
        if (is_ajax()){
            $widget = $_POST['widget'];
            if (!empty($_POST['data'])){
                $_POST = $_POST['data'];
            }
            if (!empty($widget['id'])){
                $widget_id = str_replace('widget_', '', strtolower($widget['id']));
                $widget_class = ucfirst($widget_id).'_Widget';
                
                $widgets = array();
                $plugins = get_all_actived_plugins();
                foreach ($plugins['default'] as $plugin){
                    $plugin_ary = explode('/', $plugin);
                    $widgets = array_merge($widgets, get_widgtes(PLUGIN_PATH.'/'.$plugin_ary[0].'/widgets'));
                }
                
                foreach ($plugins['admin'] as $plugin){
                    $plugin_ary = explode('/', $plugin);
                    $widgets = array_merge($widgets, get_widgtes(DEFAULT_PLUGIN_PATH.'/'.$plugin_ary[0].'/widgets'));
                }
                
                $path_theme_widget = isset($widgets[$widget_id])?$widgets[$widget_id]['file']:'';
            
                if (!class_exists($widget_class)){
                    if (is_file($path_theme_widget)){
                        require $path_theme_widget;
                    }else{
                        // Error: Widget class is not found.
                    }
                }
            
                if (class_exists($widget_class) && get_parent_class($widget_class) == 'Pf_Widget'){
                    $widget_object = new $widget_class($widget,array());
                    if (method_exists($widget_object, 'setting')){
                        $widget_object->custom_template = 'setting';
                        echo '<form role="form">';
                        $widget_object->setting();
                        require dirname(__FILE__).'/templates/setting-form.php';
                        echo '</form>';
                        
                        //$widget_object->setting();
                    }else{
                        // Error: Widget main method is not found.
                    }
                }else{
                    // Error: Widget class must extend Pf_Widget .
                }
            }else{
                // Error: Widget don't have the id.
            }
        }
        break;
    case 'index':
        if(!empty($_SESSION['success'])){
            echo "<script>notif('".$_SESSION['success']."')</script>";
            unset($_SESSION['success']);
        }
        $error = array();
        
        $widgets = array();
        
        $plugins = get_all_actived_plugins();
        foreach ($plugins['default'] as $plugin){
            $plugin_ary = explode('/', $plugin);
            $widgets = array_merge($widgets, get_widgtes(PLUGIN_PATH.'/'.$plugin_ary[0].'/widgets'));
        }
        
        foreach ($plugins['admin'] as $plugin){
            $plugin_ary = explode('/', $plugin);
            $widgets = array_merge($widgets, get_widgtes(DEFAULT_PLUGIN_PATH.'/'.$plugin_ary[0].'/widgets'));
        }
        
        $active_widgets = get_option('active_widgets');
        if (!is_array($widgets)) {
            $widgets = array();
        }
        if (!is_array($active_widgets)) {
            $active_widgets = array();
        }
        $footer = get_option('footer');
        $layout['setting_data'] = json_encode($footer['setting_data']);
        $layout['json_data'] = $footer['json_data'];
        $_POST = $layout;
        require abs_plugin_path(__FILE__) . '/theme/footer/templates/index.php';
        break;
}