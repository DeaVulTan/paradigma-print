<?php
defined ( 'PF_VERSION' ) or header ( 'Location:404.html' );
class Popup_Plugin extends Pf_Plugin {
    public $name = 'Popup';
    public $version = '1.0';
    public $author = 'Vitubo Team';
    public $description = 'This is the Polls description';
    public function activate() {
        $db = Pf::database();
        $sql = "DROP TABLE IF EXISTS `".DB_PREFIX."popup`;";
        $db->query($sql);
        
        $db->query("CREATE TABLE `".DB_PREFIX."popup` (
           `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
           `popup_title` varchar(200) NOT NULL,
           `popup_url` varchar(255) NOT NULL,
           `popup_width` int(10) NOT NULL,
           `popup_height` int(10) NOT NULL,
           `popup_created_date` datetime NOT NULL,
           `popup_modified_date` datetime NOT NULL,
           `popup_published_date` datetime NOT NULL,
           `popup_unpublished_date` datetime NOT NULL,
           `popup_status` tinyint(1) NOT NULL,
           `popup_type` tinyint(1) NOT NULL,
           `popup_description` text CHARACTER SET utf8 NOT NULL,
           PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
        ");
    }
    public function deactivate() {
        $db = Pf::database();
        $sql = "DROP TABLE IF EXISTS `".DB_PREFIX."popup`;";
        $db->query($sql);
    }
    public function admin_init() {
        $this->admin_menu ( 'fa fa-bomb', __('Popup','popup'), 'popup', 'popup' );
    }
    
    public function popup(){
        $this->js('media/assets/jquery-loadmask-0.4/jquery.loadmask.min.js');
        $this->css('media/assets/jquery-loadmask-0.4/jquery.loadmask.css');
        
        $this->js('media/assets/bootstrap-notification/js/bootstrap.notification.js');
        $this->js ('media/assets/bootstrap-notification/js/jquery/jquery.easing.1.3.js' );
        $this->css('media/assets/bootstrap-notification/css/animate.min.css');
        
        $this->js  ( 'media/assets/bootstrap-modal/js/bootstrap.modal.js' );
        $this->js  ( ADMIN_FOLDER.'/themes/default/assets/tinymce/js/tinymce/tinymce.min.js' );

        $this->js('media/assets/moment/js/moment.js');        
        $this->js('media/assets/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js');
        $this->css('media/assets/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css');
        
        $this->js ( 'media/assets/fancybox/jquery.fancybox-1.3.6.pack.js' );
        $this->css ('media/assets/fancybox/jquery.fancybox-1.3.6.css' );
    }
    public function public_init() {
        $this->add_shortcode('popup', 'display','display');
    }
}