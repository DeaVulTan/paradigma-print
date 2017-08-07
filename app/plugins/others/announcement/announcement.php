<?php
defined ( 'PF_VERSION' ) or header ( 'Location:404.html' );
class Announcement_Plugin extends Pf_Plugin {
    public $name = 'Announcement';
    public $version = '1.0';
    public $author = 'Vitubo Team';
    public $description = 'This is the Announcement description';
    public function activate() {
        $db = Pf::database();
        $sql = "DROP TABLE IF EXISTS `".DB_PREFIX."announcement`;";
        $db->query($sql);
        
        $sql = "CREATE TABLE `".DB_PREFIX."announcement` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `announcement_status` tinyint(4) NOT NULL,
                `announcement_pubdate` timestamp NULL DEFAULT NULL,
                `announcement_unpubdate` timestamp NULL DEFAULT NULL,
                `announcement_type` tinyint(4) NOT NULL,
                `announcement_content` varchar(255) NOT NULL,
                `announcement_author` int(11) NOT NULL,
                `announcement_to` varchar(255) NOT NULL,
                PRIMARY KEY (`id`)
               ) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8
                ";
        $db->query($sql);
    }
    public function deactivate() {
        $db = Pf::database();
        $sql = "DROP TABLE IF EXISTS `".DB_PREFIX."announcement`;";
        $db->query($sql);
    }
    public function admin_init() {
        if (is_admin())
        $this->admin_menu ( 'fa fa-tasks', __('Announcement', 'announcement'), 'announcement', 'announcement' );
    }
    public function public_init() {
        $shortcode = Pf::shortcode();
        $this->add_shortcode('announcement', 'display','display');
    }
    public function announcement(){
        $this->js('media/assets/jquery-loadmask-0.4/jquery.loadmask.min.js');
        $this->css('media/assets/jquery-loadmask-0.4/jquery.loadmask.css');
        
        $this->css('media/assets/magicsuggest/css/magicsuggest-min.css');
        $this->js('media/assets/magicsuggest/js/magicsuggest-min.js');
        
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
}