<?php
defined('PF_VERSION') OR header('Location:404.html');
define('PLUGIN_THEME', __('Themes', 'theme'));

class Theme_Plugin extends Pf_Plugin{
    public $name = PLUGIN_THEME;
    public $version = '2.0';
    public $author = 'Vitubo Team';
    public $description = 'Theme Management';

    public function admin_init(){
        if(is_admin()){
            $this->admin_menu ( 'fa fa-eye', __('Themes', 'theme'), 'themes', 'theme_manager_main' );
            $this->admin_children_menu ( 'fa fa-angle-double-right', __('Themes', 'theme'), 'themes', 'theme_manager_main', 'Themes' );
            if(file_exists(ABSPATH . '/app/themes/'.get_option('active_theme').'/options/options.php')){
                $this->admin_children_menu ( 'fa fa-angle-double-right', __('Theme Options', 'theme'), 'options', 'options_manager_main', 'Themes' );
            }
            $this->admin_children_menu ( 'fa fa-angle-double-right', __('Layouts', 'theme'), 'layouts', 'layouts','Themes');
            
            $this->admin_children_menu ( 'fa fa-angle-double-right',__('Widgets','theme'),'widgets','widgets','Themes');
            
            $this->admin_children_menu ( 'fa fa-angle-double-right', __('Footer', 'theme'), 'footer', 'footer_manager_main', 'Themes' );
        }
    }
    function theme_manager_main() {
        $this->css('media/assets/bootstrap-modal/css/animate.min.css' );
        $this->css ( 'theme/admin/assets/theme-layouts.css',__FILE__ );
        
        $this->js ('media/assets/bootstrap-modal/js/jquery/jquery.easing.1.3.js' );
        $this->js ('media/assets/bootstrap-modal/js/bootstrap.modal.js' );
        $this->js ('media/assets/json2/json2.js' );
        $this->js( 'theme/admin/assets/theme-layouts.js',__FILE__ );
    
        require abs_plugin_path(__FILE__) . '/theme/themes/includes/functions.php';
        require abs_plugin_path(__FILE__) . '/theme/themes/themes.php';
    }
    function options_manager_main(){
        $this->css ('media/assets/bootstrap-modal/css/animate.min.css' );
        $this->js ('media/assets/bootstrap-modal/js/jquery/jquery.easing.1.3.js' );
        $this->js ('media/assets/bootstrap-notification/js/bootstrap.notification.js' );
        
        $this->css ('media/assets/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css' );
        $this->js ('media/assets/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js' );
        
        $this->js ('media/assets/fancybox/jquery.fancybox-1.3.6.pack.js' );
        $this->css ('media/assets/fancybox/jquery.fancybox-1.3.6.css' );
        
        if(is_ajax() && file_exists(ABSPATH . '/app/themes/'.get_option('active_theme').'/options/options-ajax.php')){
            require ABSPATH . '/app/themes/'.get_option('active_theme').'/options/options-ajax.php';
        }else{
            require abs_plugin_path(__FILE__) . '/theme/options/options.php';
        }
    }
    
    function layouts(){
        $this->js('media/assets/jquery-loadmask-0.4/jquery.loadmask.min.js');
        $this->css('media/assets/jquery-loadmask-0.4/jquery.loadmask.css');
        
        $this->js('media/assets/bootstrap-notification/js/bootstrap.notification.js');
        $this->js ('media/assets/bootstrap-notification/js/jquery/jquery.easing.1.3.js' );
        $this->css('media/assets/bootstrap-notification/css/animate.min.css');
        
        $this->js  ('media/assets/bootstrap-modal/js/bootstrap.modal.js' );
        $this->js  ( ADMIN_FOLDER.'/themes/default/assets/tinymce/js/tinymce/tinymce.min.js' );
        
        $this->js('media/assets/moment/js/moment.js');
        $this->js('media/assets/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js');
        $this->css('media/assets/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css');
        
        $this->js ('media/assets/fancybox/jquery.fancybox-1.3.6.pack.js' );
        $this->css ('media/assets/fancybox/jquery.fancybox-1.3.6.css' );
        
        $this->js ('media/assets/bootbox/js/bootbox.min.js');
        $this->js ('lib/common/plugin/assets/base.js');
        $this->js ('admin/themes/default/assets/admin-lte/js/AdminLTE/app.js');
        
        $this->css ( 'theme/admin/assets/theme-layouts.css',__FILE__ );
    }
    public function footer_manager_main(){
        $this->css ('media/assets/bootstrap-modal/css/animate.min.css' );
        $this->js ('media/assets/bootstrap-modal/js/jquery/jquery.easing.1.3.js' );
        $this->js ('media/assets/bootstrap-modal/js/bootstrap.modal.js' );
        $this->js ('media/assets/json2/json2.js' );
        $this->js ('media/assets/bootstrap-notification/js/bootstrap.notification.js' );
        $this->js ( ADMIN_FOLDER.'/themes/default/assets/js/notification.js' );
        $this->css ( 'theme/admin/assets/theme-layouts.css',__FILE__ );
        $this->js ( 'theme/admin/assets/theme-layouts.js',__FILE__ );
        
        require abs_plugin_path(__FILE__) . '/theme/footer/footer.php';
    }
    
    function widgets(){
        $this->js('media/assets/jquery-loadmask-0.4/jquery.loadmask.min.js');
        $this->css('media/assets/jquery-loadmask-0.4/jquery.loadmask.css');
        
        $this->js('media/assets/bootstrap-notification/js/bootstrap.notification.js');
        $this->js ('media/assets/bootstrap-notification/js/jquery/jquery.easing.1.3.js' );
        $this->css('media/assets/bootstrap-notification/css/animate.min.css');
        
        $this->js  ('media/assets/bootstrap-modal/js/bootstrap.modal.js' );
        $this->js  ( ADMIN_FOLDER.'/themes/default/assets/tinymce/js/tinymce/tinymce.min.js' );
        
        $this->js('media/assets/moment/js/moment.js');
        $this->js('media/assets/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js');
        $this->css('media/assets/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css');
        
        $this->js ('media/assets/fancybox/jquery.fancybox-1.3.6.pack.js' );
        $this->css ('media/assets/fancybox/jquery.fancybox-1.3.6.css' );
        
        $this->js ('media/assets/bootbox/js/bootbox.min.js');
        $this->js ('lib/common/plugin/assets/base.js');
        $this->js ('admin/themes/default/assets/admin-lte/js/AdminLTE/app.js');
    }
}