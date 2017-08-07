<?php
defined ( 'PF_VERSION' ) or header ( 'Location:404.html' );
class Galleries_Plugin extends Pf_Plugin {
    public $name = 'Galleries';
    public $version = '1.0';
    public $author = 'Vitubo Team';
    public $description = 'This is the Gallery description';
    public function activate() {
    	$db = Pf::database();
    	
    	$sql = "DROP TABLE IF EXISTS `".DB_PREFIX."galleries`;";
    	$db->query($sql);
    	
    	$sql = "CREATE TABLE `".DB_PREFIX."galleries` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `gallery_name` varchar(255) NOT NULL,
                `gallery_description` varchar(500) NOT NULL,
                `gallery_status` tinyint(4) NOT NULL,
                `gallery_data` longtext NOT NULL,
                `gallery_views` int(100) DEFAULT '0',
                `gallery_cover` varchar(200) NOT NULL,
                PRIMARY KEY (`id`)
               ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
    	
    	$db->query($sql);
    }
    public function deactivate() {
    	$db = Pf::database();
    	$sql = "DROP TABLE IF EXISTS `".DB_PREFIX."galleries`;";
    	
    	$db->query($sql);
    }
    public function admin_init() {
    	if (is_admin() or is_editor() or is_author()){
    		$this->admin_menu ( 'fa fa-tasks', __('Galleries', 'galleries'), 'galleries', 'galleries' );
    	}
    }
    
    public function public_init(){
    	$shortcode = Pf::shortcode();
    	//$shortcode->add( 'gallery', array($this,'public_gallery' ));
    	$this->add_shortcode('galleries','display','display');
    	$this->add_shortcode('galleries','data');
    }
    
    public function galleries(){
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
        
        $this->js('galleries/admin/assets/galleries.js',__FILE__);
        $this->css('galleries/admin/assets/galleries.css',__FILE__);       
    }
}