<?php
defined ( 'PF_VERSION' ) or header ( 'Location:404.html' );
class Page_Plugin extends Pf_Plugin {
    public $name = 'Page';
    public $version = '1.0';
    public $author = 'Vitubo Team';
    public $description = 'This is the Page description';
    public function activate() {
        $db = Pf::database();
        $sql = "DROP TABLE IF EXISTS `".DB_PREFIX."pages`;";
        $db->query($sql);
        $db->query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX."pages` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `page_title` varchar(255) NOT NULL,
            `page_content` text NOT NULL,
            `page_url` varchar(255) NOT NULL,
            `page_layout` varchar(50) NOT NULL,
            `page_type` int(11) NOT NULL,
            `page_author` int(11) NOT NULL,
            `page_meta_title` varchar(70) NOT NULL,
            `page_meta_keywords` varchar(255) NOT NULL,
            `page_meta_description` varchar(156) NOT NULL,
            `page_created_date` datetime NOT NULL,
            `page_modified_date` datetime NOT NULL,
            `page_system` tinyint(1) NOT NULL,
            `page_status` tinyint(1) NOT NULL,
            PRIMARY KEY (`id`)
          ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=28;"
        );
    }
    public function deactivate() {
        $db = Pf::database();
        $db->query("DROP TABLE ".DB_PREFIX."pages");
    }
    public function admin_init() {
        require ABSPATH . '/lib/common/plugin/helpers/permission.php';
        if (plugin_check_acl(array(1, 2))) {
            $this->admin_menu('fa fa-tasks', __('Pages', 'page'), 'page', 'page');
        }
    }
    
    public function page(){
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
        
        $this->css('page/admin/assets/page.css', __FILE__);
        $this->css('media/assets/magicsuggest/css/magicsuggest-min.css');
        $this->css('media/assets/colorpicker/css/colorpicker.css');
        $this->js('media/assets/colorpicker/js/colorpicker.js');
        
        $this->js('media/assets/bootbox/js/bootbox.min.js');
        $this->js('media/assets/magicsuggest/js/magicsuggest-min.js');
        require_once abs_plugin_path(__FILE__) . '/page/index.php';
    }
}