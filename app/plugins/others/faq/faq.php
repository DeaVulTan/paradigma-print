<?php
defined ( 'PF_VERSION' ) or header ( 'Location:404.html' );
class Faq_Plugin extends Pf_Plugin {
    public $name = 'Faq';
    public $version = '2.0';
    public $author = 'Vitubo Team';
    public $description = 'This is the Faq description';
	public function activate()
    	{
        	$db = Pf::database();
        	$count = $db->dcount('*', ''.DB_PREFIX.'options', "`option_name`='faq'");
        	if ($count == 0) {
            	$sql = "INSERT INTO `".DB_PREFIX."options` ( `option_name`, `option_value`) VALUES ('faq', '')";
            	$db->query($sql);
        	}
    	}

    public function deactivate()
    {
    }
    
    public function admin_init() {
        if (is_admin() or is_editor() or is_author()){
            $this->admin_menu ( 'fa fa-tasks', __('FAQs', 'faq'), 'faq', 'faq' );
        }
    }
    
    public function public_init(){
        $this->add_shortcode('faq', 'display');
        $this->add_shortcode('faq', 'get_faq');
        $this->add_shortcode('faq', 'faq_load_list_faqs');
        $this->add_shortcode('faq', 'list_question_faq');
        $this->add_shortcode('faq', 'list_answer_faq');
        $this->add_shortcode('faq', 'load_pagination');
        $this->add_shortcode('faq', 'load_question_pagination');
        $this->add_shortcode('faq', 'load_list_question_search');
        $this->add_shortcode('faq', 'pagination_search');
    }
    
    public function faq(){
        $this->js('media/assets/jquery-loadmask-0.4/jquery.loadmask.min.js');
        $this->css('media/assets/jquery-loadmask-0.4/jquery.loadmask.css');
        
        $this->js('media/assets/bootstrap-notification/js/bootstrap.notification.js');
        $this->js ('media/assets/bootstrap-notification/js/jquery/jquery.easing.1.3.js' );
        $this->css('media/assets/bootstrap-notification/css/animate.min.css');
        
        $this->js  ('media/assets/bootstrap-modal/js/bootstrap.modal.js' );
        $this->css('faq/admin/assets/faq.css',__FILE__);
        $this->js  ( ADMIN_FOLDER.'/themes/default/assets/tinymce/js/tinymce/tinymce.min.js' );

        $this->js('media/assets/moment/js/moment.js');        
        $this->js('media/assets/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js');
        $this->css('media/assets/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css');
        
        $this->js ('media/assets/fancybox/jquery.fancybox-1.3.6.pack.js' );
        $this->css ('media/assets/fancybox/jquery.fancybox-1.3.6.css' );
        $this->js('faq/admin/assets/faq.js',__FILE__);
        
              
   
        $this->js ('media/assets/bootbox/js/bootbox.min.js');
        $this->js ('lib/common/plugin/assets/base.js');
    }
    
}