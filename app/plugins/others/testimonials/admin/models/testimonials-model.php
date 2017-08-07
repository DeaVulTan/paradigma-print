<?php
defined ( 'PF_VERSION' ) or header ( 'Location:404.html' );
class Testimonials_Model extends Pf_Model{
    
        public $rules = array(
			'testimonial_name'=>'required|max_len,255',
			'testimonial_content'=>'required|max_len,1000|min_len,2',
			'testimonial_info'=>'max_len,255',
		);
    
        public $elements_value = array(
            );

    
    public function __construct(){
        parent::__construct(''.DB_PREFIX.'testimonials');
    }
}