<?php defined('PF_VERSION') OR header('Location:404.html');?>
<?php

define('PLUGIN_CONFIGURATION', __('Configuration','configuration'));

class Configuration_Plugin extends Pf_Plugin{
    public $name = PLUGIN_CONFIGURATION;
    public $version = '1.0';
    public $author = 'Vitubo Team';
    public $description = 'This is Configuration system';

    public function admin_init(){
        if(is_admin()){
            $this->admin_menu ( 'fa fa-wrench', __('Configuration','configuration'), 'configuration', 'setting_manager_main');
            $this->admin_children_menu ( 'fa fa-angle-double-right', __('Settings','configuration'), 'settings', 'setting_manager_main', 'Configuration' );
            $this->admin_children_menu ( 'fa fa-angle-double-right', __('Email templates','configuration'), 'email-templates', 'email_template_manager_main', 'Configuration' );
            $this->admin_children_menu ( 'fa fa-angle-double-right', __('Admin menu','configuration'), 'admin-menu', 'admin_menu_manager', 'Configuration' );
            $this->admin_children_menu ( 'fa fa-angle-double-right', __('Backup','configuration'), 'backup', 'backup_restore', 'Configuration' );
            $this->admin_children_menu ( 'fa fa-angle-double-right', __('System information','configuration'), 'sysinfo', 'system_info', 'Configuration' );
            $this->admin_children_menu ( 'fa fa-angle-double-right', __('Error log','configuration'), 'error-log', 'error_log', 'Configuration' );
            $this->admin_children_menu ( 'fa fa-angle-double-right', __('IP Blacklist','configuration'), 'Blacklist', 'Blacklist', 'Configuration' );
        }
    }
    
    public function setting_manager_main() {
        $this->css ('media/assets/bootstrap-notification/css/animate.min.css' );
        $this->css ( 'configuration/css/style.css',__FILE__ );
        $this->js ('media/assets/bootstrap-notification/js/jquery/jquery.easing.1.3.js' );
        $this->js ('media/assets/bootstrap-notification/js/bootstrap.notification.js' );
        $this->js ('media/assets/jquery-serialize/js/jquery.serializeObject.js' );

        require abs_plugin_path(__FILE__) . '/configuration/settings/settings.php';
    }
    public function email_template_manager_main() {
        $this->css ( 'configuration/css/style.css',__FILE__ );
        $this->css ('media/assets/bootstrap-notification/css/animate.min.css' );
        $this->js ('media/assets/bootstrap-notification/js/jquery/jquery.easing.1.3.js' );
        $this->js ('media/assets/bootstrap-notification/js/bootstrap.notification.js' );
        
        require abs_plugin_path(__FILE__) . '/configuration/email-templates/email-templates.php';
    }
    
    public function admin_menu_manager(){
        $this->css ('configuration/admin-menu/media/css/admin-menu.css',__FILE__);
        $this->js('configuration/admin-menu/media/js/admin-menu.js',__FILE__);
        
        $this->css ('media/assets/bootstrap-notification/css/animate.min.css' );
        $this->js ('media/assets/bootstrap-notification/js/jquery/jquery.easing.1.3.js' );
        $this->js ('media/assets/bootstrap-notification/js/bootstrap.notification.js' );
        
        $this->css ('media/assets/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css');
        $this->js ('media/assets/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js');
        
        require abs_plugin_path(__FILE__) . '/configuration/admin-menu/admin-menu.php';
    }
    
    public function backup_restore(){
        $this->css ('media/assets/bootstrap-modal/css/animate.min.css' );
        $this->js ('media/assets/bootstrap-modal/js/jquery/jquery.easing.1.3.js' );
        $this->js ('media/assets/bootstrap-modal/js/bootstrap.modal.js' );
        $this->js('configuration/backup/ajax/jquery.form.js',__FILE__);
        $this->js ( 'configuration/backup/ajax/ajax.js',__FILE__ );
        
        require_once abs_plugin_path(__FILE__) . "/configuration/backup/pclzip.lib.php";
        require_once abs_plugin_path(__FILE__) . "/configuration/backup/backup-class.php";
        require_once abs_plugin_path(__FILE__) . "/configuration/backup/action/main.php";
    }
    
    public function system_info(){
        $this->css ( 'configuration/css/style.css',__FILE__ );
        require_once abs_plugin_path(__FILE__) . "/configuration/system-info/system.php";
    }
    
    public function error_log(){
       $this->css ( 'configuration/css/style.css',__FILE__ );
       $this->js ( 'configuration/error-log/scripts/scripts.js',__FILE__ );
       $this->js ( 'media/assets/bootstrap-notification/js/bootstrap.notification.js' );
       $this->js ( 'media/assets/bootstrap-notification/js/jquery/jquery.easing.1.3.js' );
       $this->css ( 'media/assets/bootstrap-notification/css/animate.min.css' );
       
       $this->js ( 'media/assets/bootstrap-modal/js/bootstrap.modal.js' );
       require_once abs_plugin_path(__FILE__) . "/configuration/error-log/error-log.php";
    }
    
    public function blacklist(){
        $this->css ('media/assets/bootstrap-notification/css/animate.min.css' );
        $this->js ('media/assets/bootstrap-notification/js/bootstrap.notification.js' );
        require_once abs_plugin_path(__FILE__) . "/configuration/blacklist/blacklist.php";
    }
    
}
