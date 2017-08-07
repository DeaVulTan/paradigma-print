<?php
defined ( 'PF_VERSION' ) or header ( 'Location:404.html' );
class Portfolio_Plugin extends Pf_Plugin {
    public $name = 'Portfolio';
    public $version = '1.0';
    public $author = 'Vitubo Team';
    public $description = 'This is the Portfolio description';
    public function activate() {
        $db = Pf::database();
        
        $sql = "DROP TABLE IF EXISTS `".DB_PREFIX."portfolios`,`".DB_PREFIX."portfolio_categories`,`".DB_PREFIX."portfolio_meta`;";
        $db->query($sql);
        
        $db->query("CREATE TABLE `".DB_PREFIX."portfolios` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        `portfolio_name` varchar(50) NOT NULL,
                        `portfolio_status` tinyint(4) NOT NULL,
                        `portfolio_category` mediumint(9) NOT NULL,
                        `portfolio_avatar` varchar(100) NOT NULL,
                        `portfolio_description` longtext NOT NULL,
                        `portfolio_items` text NULL,
                        PRIMARY KEY (`id`)
                       ) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8
                    ");
        $db->query("CREATE TABLE `".DB_PREFIX."portfolio_meta` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        `meta_portfolio` int(11) NOT NULL,
                        `meta_name` varchar(50) NOT NULL,
                        `meta_value` varchar(100) NOT NULL,
                        PRIMARY KEY (`id`)
                       ) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8"
        );
        $db->query("CREATE TABLE `".DB_PREFIX."portfolio_categories` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `category_name` varchar(50) NOT NULL,
                    `category_status` tinyint(4) NOT NULL,
                    `category_description` longtext NOT NULL,
                    PRIMARY KEY (`id`)
                   ) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8"
        );
    }
    public function deactivate() {
        $db = Pf::database();
        $db->query("DROP TABLE IF EXISTS `".DB_PREFIX."portfolios`,`".DB_PREFIX."portfolio_categories`,`".DB_PREFIX."portfolio_meta`;");
    }
    public function admin_init() {
        if (is_admin() or is_editor() or is_author()){
            $this->admin_menu ( 'fa fa-tasks', __('Portfolios', 'portfolio'), 'portfolio', 'portfolio' );
            $this->admin_children_menu('fa fa-angle-double-right', __('Portfolios', 'portfolio'),'portfolio', 'portfolio', 'portfolio');
            $this->admin_children_menu('fa fa-angle-double-right', __('Category', 'portfolio'),'category', 'category', 'portfolio');
        }
    }
    public function public_init() {
        $shortcode = Pf::shortcode();
        $this->add_shortcode('portfolio', 'display','display');
    }
    public function portfolio(){
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
        $this->css ('app/plugins/others/portfolio/assets/portfolio.css' );
        $this->js('media/assets/handlebars/js/handlebars.min.js');
    }
    
    public function category(){
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
}