<?php
defined ( 'PF_VERSION' ) or header ( 'Location:404.html' );
class Testimonials_Plugin extends Pf_Plugin {
    public $name = 'Testimonials';
    public $version = '1.0';
    public $author = 'Vitubo Team';
    public $description = 'This is the Testimonials description';
    public function activate() {
        $db = Pf::database();
        $db->query("CREATE TABLE `".DB_PREFIX."testimonials` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `testimonial_name` varchar(50) NOT NULL,
            `testimonial_content` varchar(1000) NOT NULL,
            `testimonial_info` varchar(200) NOT NULL,
            `testimonial_status` int(11) NOT NULL,
            `testimonial_avatar` varchar(200) NOT NULL,
            PRIMARY KEY (`id`)
           ) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8
        ");
    }
    public function deactivate() {
        $db = Pf::database();
        $sql = "DROP TABLE IF EXISTS `".DB_PREFIX."testimonials`;";
        
        $db->query($sql);
    }
    public function admin_init() {
        if (is_admin() or is_editor() or is_author()){
            $this->admin_menu ( 'fa fa-tasks', __('Testimonials','testimonials'), 'testimonials', 'testimonials' );
        }
    }
    
    public function public_init(){
        $this->add_shortcode('testimonials', 'display','display');
    }
    
    public function testimonials(){
        $this->js('media/assets/jquery-loadmask-0.4/jquery.loadmask.min.js');
        $this->css('media/assets/jquery-loadmask-0.4/jquery.loadmask.css');
        
        $this->js('media/assets/bootstrap-notification/js/bootstrap.notification.js');
        $this->js ('media/assets/bootstrap-notification/js/jquery/jquery.easing.1.3.js' );
        $this->css('media/assets/bootstrap-notification/css/animate.min.css');
        
        $this->js  ('media/assets/bootstrap-modal/js/bootstrap.modal.js' );
        $this->css('testimonials/admin/assets/testimonials.css',__FILE__);
        $this->js  ( ADMIN_FOLDER.'/themes/default/assets/tinymce/js/tinymce/tinymce.min.js' );

        $this->js('media/assets/moment/js/moment.js');        
        $this->js('media/assets/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js');
        $this->css('media/assets/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css');
        
        $this->js ('media/assets/fancybox/jquery.fancybox-1.3.6.pack.js' );
        $this->css ('media/assets/fancybox/jquery.fancybox-1.3.6.css' );
        $this->js('testimonials/admin/assets/testimonials.js',__FILE__);
    }
}