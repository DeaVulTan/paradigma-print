<?php
defined('PF_VERSION') OR header('Location:404.html');
define('PLUGIN_MENU', __('Menu', 'menu'));

class Menu_Plugin extends Pf_Plugin {

    public $name = PLUGIN_MENU;
    public $version = '1.0';
    public $author = 'Vitubo Team';
    public $description = 'This is the Menu description';

    public function activate() {
        $db = Pf::database();
        $count = $db->dcount('id', ''.DB_PREFIX.'options', "`option_name`='menu'");
        if ($count == 0) {
            $sql = "INSERT INTO `".DB_PREFIX."options` ( `option_name`, `option_value`) VALUES ('menu', '')";
            $db->query($sql);
        }
    }
    public function deactivate() {

    }
    public function admin_init() {
        if (is_admin())
        //$this->admin_menu('fa fa-tasks', __('Menus', 'menu'), 'menu', 'plugin_menu');
        $this->admin_menu ( 'fa fa-tasks', __('Menus', 'menu'), 'menu', 'menu' );
    }
    public function public_init(){
        $shortcode = Pf::shortcode();
        //$shortcode->add( 'menu', array($this,'public_menu' ));
        
        $this->add_shortcode('menu', 'navigation');
        $this->add_shortcode('menu', 'footer');
        $this->add_shortcode('menu', 'display');
        
    }
    
    function public_menu( $atts, $content = null, $code = '' ){
       $menuid  =   !empty($atts['id'])?$atts['id']:'';
       $type    =   !empty($atts['type'])?$atts['type']:'';
        ob_start();
        require abs_plugin_path(__FILE__) . "/menu/public/menu-public.php";
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }
    function plugin_menu() {
        if (is_admin()) {
            $action = isset($_GET ['act']) ? $_GET ['act'] : 'main';
            $a_act = array(
                'once',
                'edit',
                'main'
            );
            $this->js('media/assets/fancybox/jquery.fancybox-1.3.6.pack.js');
            $this->css('media/assets/fancybox/jquery.fancybox-1.3.6.css');
            $this->css('media/assets/bootstrap-modal/css/animate.min.css');
            $this->js('media/assets/bootstrap-modal/js/jquery/jquery.easing.1.3.js');
            $this->js('media/assets/bootstrap-modal/js/bootstrap.modal.js');
            $this->js ('media/assets/bootstrap-notification/js/bootstrap.notification.js' );
            $this->css('menu/assets/style.css', __FILE__);
            admin_js('app/plugins/default/menu/assets/js.js');
            $this->css('media/assets/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css');
            $this->js ('media/assets/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js' );
            $this->css('media/assets/bootstrap/css/bootstrap.min.css');
            require_once abs_plugin_path(__FILE__) . "/menu/class/menu-class.php";
            require_once abs_plugin_path(__FILE__) . "/menu/action/" . $action . ".php";
        }
        else
            echo __('Access Denied!','menu');
    }
    
    public function menu(){
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
        
        $this->css('menu/admin/assets/style.css', __FILE__);
        $this->css('media/assets/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css');
        $this->js ('media/assets/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js' );
        //admin_js('app/plugins/default/menu/admin/assets/js.js');
    }

}
