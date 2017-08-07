<?php 
class Customfields_Controller extends Pf_Controller{
    public function __construct(){
        parent::__construct();
        $this->load_model('user');
        $this->view->menu_settings = get_option('admin_menu_setting');
        $this->user_model->rules = Pf::event()->trigger("filter","user-validation-rule",$this->user_model->rules);
    }
    public function index(){
        $template = null;
        $template = Pf::event()->trigger("filter","customfields-index-template",$template);
        if(isset($this->post->action)){
        	$updateopt  =   json_decode($this->post->{"data"},true);
        	update_option('user_custom_fields', $updateopt);
        	header("Location:".admin_url());
        }else{
        	$this->view->render($template);
        }
    }
}