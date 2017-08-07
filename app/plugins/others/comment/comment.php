<?php
defined ( 'PF_VERSION' ) or header ( 'Location:404.html' );
class Comment_Plugin extends Pf_Plugin {
    public $name = 'Comment';
    public $version = '1.0';
    public $author = 'Vitubo Team';
    public $description = 'This is the Comment description';
    public function activate() {
        $db = Pf::database();
        $db->query("CREATE TABLE `".DB_PREFIX."comments` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `comment_author` varchar(128) NOT NULL,
            `comment_email` varchar(128) NOT NULL,
            `comment_url` varchar(255) NOT NULL,
            `comment_content` varchar(500) NOT NULL,
            `comment_created_date` datetime NOT NULL,
            `comment_modified_date` datetime NOT NULL,
            `comment_parent` int(11) NOT NULL,
            `comment_user_id` int(11) NOT NULL,
            `comment_key` varchar(50) NOT NULL,
            `comment_status` tinyint(1) NOT NULL,
            PRIMARY KEY (`id`)
           ) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8");
    }
    public function deactivate() {
        $db = Pf::database();
        $db->query("DROP TABLE IF EXISTS ".DB_PREFIX."comments");
    }
    public function admin_init() {
    	if (is_admin() or is_editor() or is_author()){
            require_once ABSPATH . '/lib/common/plugin/utiles/pf-plugin-singleton.php';
            require_once abs_plugin_path(__FILE__) . '/comment/comment-config.php';
            require ABSPATH . '/lib/common/plugin/helpers/permission.php';
            $this->admin_menu ( 'fa fa-tasks', __('Comments', 'comment'), 'comment', 'comment' );
    	}
    }
    
    public function comment(){
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
    
    //Public
    public function public_init()
    {
        $this->add_shortcode('comment', 'display');
        $this->add_shortcode('comment', 'comment_post');
        $this->add_shortcode('comment', 'comment_load_comment');
        $this->add_shortcode('comment', 'comment_edit');
        $this->add_shortcode('comment', 'comment_delete');
        
        require abs_plugin_path(__FILE__) . '/comment/shortcodes/index.php';

    }
}