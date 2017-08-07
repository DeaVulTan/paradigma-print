<?php
defined ( 'PF_VERSION' ) or header ( 'Location:404.html' );
class Post_Plugin extends Pf_Plugin {
    public $name = 'post';
    public $version = '1.0';
    public $author = 'Vitubo Team';
    public $description = 'This is the post description';
    public function activate() {
        $db = Pf::database();
        $sql = "DROP TABLE IF EXISTS `".DB_PREFIX."post_categories`,`".DB_PREFIX."posts`, `".DB_PREFIX."tags`, `".DB_PREFIX."post_tags`;";
        $db->query($sql);
        $db->query("CREATE TABLE `".DB_PREFIX."post_categories` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `category_name` varchar(255) DEFAULT NULL,
            `category_type` int(11) NOT NULL,
            `category_parent` int(11) NOT NULL,
            `category_author` int(11) NOT NULL,
            `category_description` varchar(255) DEFAULT NULL,
            `category_created_date` datetime NOT NULL,
            `category_modified_date` datetime NOT NULL,
            `category_status` tinyint(1) NOT NULL,
            PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;");
        
        $db->query("CREATE TABLE `".DB_PREFIX."posts` (
            `id` bigint(20) NOT NULL AUTO_INCREMENT,
            `post_title` varchar(255) NOT NULL,
            `post_author` int(11) NOT NULL,
            `post_category` int(11) NOT NULL,
            `post_thumbnail` varchar(500) DEFAULT NULL,
            `post_created_date` datetime NOT NULL,
            `post_modified_date` datetime NOT NULL,
            `post_content` longtext NOT NULL,
            `post_published_date` datetime DEFAULT NULL,
            `post_unpublished_date` datetime DEFAULT NULL,
            `post_views` int(11) DEFAULT '1',
            `post_status` tinyint(1) NOT NULL,
            PRIMARY KEY (`id`)
           ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 ;");
        
        $db->query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX."tags` (
            `id` bigint(20) NOT NULL AUTO_INCREMENT,
            `tag_name` varchar(255) NOT NULL,
            PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
        
        $db->query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX."post_tags` (
            `id` bigint(20) NOT NULL AUTO_INCREMENT,
            `post_tag_post_id` bigint(20) NOT NULL,
            `post_tag_tag_id` bigint(20) NOT NULL,
            PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
    }
    public function deactivate() {
        $db = Pf::database();
        $sql = "DROP TABLE IF EXISTS `".DB_PREFIX."post_categories`,`".DB_PREFIX."posts`, `".DB_PREFIX."tags`, `".DB_PREFIX."post_tags`;";
        $db->query($sql);
    }
    public function admin_init() {
        require_once ABSPATH . '/lib/common/plugin/utiles/pf-plugin-singleton.php';
        require_once abs_plugin_path(__FILE__) . '/post/post-config.php';
        require_once abs_plugin_path(__FILE__) . '/post/helper.php';
        
        if (is_admin () or is_editor () or is_author () or is_contributor ()) {
            $this->admin_menu ( 'fa fa-tasks', __('Posts', 'post'), 'post', 'post' );
            $this->admin_children_menu('fa fa-angle-double-right', __('Posts', 'post'),'post', 'post', 'post');
        }
        
        if (is_admin () or is_editor ()) {
            $this->admin_children_menu('fa fa-angle-double-right', __('Category', 'post'),'category', 'category', 'post');
        }
    }
    
    public function post() {
        $this->js ( 'media/assets/jquery-loadmask-0.4/jquery.loadmask.min.js' );
        $this->css ( 'media/assets/jquery-loadmask-0.4/jquery.loadmask.css' );
    
        $this->js ( 'media/assets/bootstrap-notification/js/bootstrap.notification.js' );
        $this->js ( 'media/assets/bootstrap-notification/js/jquery/jquery.easing.1.3.js' );
        $this->css ( 'media/assets/bootstrap-notification/css/animate.min.css' );
    
        $this->js ( 'media/assets/bootstrap-modal/js/bootstrap.modal.js' );
        $this->js ( ADMIN_FOLDER . '/themes/default/assets/tinymce/js/tinymce/tinymce.min.js' );
        
        $this->js ( 'media/assets/moment/js/moment.js' );
        $this->js ( 'media/assets/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js' );
        $this->css ( 'media/assets/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css' );
    
        $this->js ( 'media/assets/fancybox/jquery.fancybox-1.3.6.pack.js' );
        $this->css ( 'media/assets/fancybox/jquery.fancybox-1.3.6.css' );
    }
    
    public function category() {
        $this->js ( 'media/assets/jquery-loadmask-0.4/jquery.loadmask.min.js' );
        $this->css ( 'media/assets/jquery-loadmask-0.4/jquery.loadmask.css' );
        
        $this->js ( 'media/assets/bootstrap-notification/js/bootstrap.notification.js' );
        $this->js ( 'media/assets/bootstrap-notification/js/jquery/jquery.easing.1.3.js' );
        $this->css ( 'media/assets/bootstrap-notification/css/animate.min.css' );
        
        $this->js ( 'media/assets/bootstrap-modal/js/bootstrap.modal.js' );
        $this->js ( ADMIN_FOLDER . '/themes/default/assets/tinymce/js/tinymce/tinymce.min.js' );
        
        $this->js ( 'media/assets/moment/js/moment.js' );
        $this->js ( 'media/assets/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js' );
        $this->css ( 'media/assets/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css' );
        
        $this->js ( 'media/assets/fancybox/jquery.fancybox-1.3.6.pack.js' );
        $this->css ( 'media/assets/fancybox/jquery.fancybox-1.3.6.css' );
    }
    
    function public_init(){
        $this->add_shortcode('posts','display','display');
        $this->add_shortcode('posts','load_page');
        $this->add_shortcode('post','display','display');
        $this->add_shortcode('widgets','display','display');
        
        require_once abs_plugin_path(__FILE__) . '/post/helper.php';
        
    }
}