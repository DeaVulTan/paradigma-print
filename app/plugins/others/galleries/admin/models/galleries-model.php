<?php
defined ( 'PF_VERSION' ) or header ( 'Location:404.html' );
class Galleries_Model extends Pf_Model{
    
        public $rules = array(
			'gallery_name'=>'max_len,255',
			'gallery_description'=>'max_len,500',
		);
    
        public $elements_value = array(
        		'gallery_status' =>
        				array(
        					'1' => 'Published',
        					'2' => 'Unpublished'
        				),
            );
    public function __construct(){
        parent::__construct(''.DB_PREFIX.'galleries');
    }
    
    public function get_status_gallery() {
    	return array('1' => __('Published', 'galleries'), '2' => __('Unpublished', 'galleries'));
    }
   
}