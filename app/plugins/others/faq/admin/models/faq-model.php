<?php
defined ( 'PF_VERSION' ) or header ( 'Location:404.html' );
class Faq_Model extends Pf_Model{
    
    public $rules = array(
        'title' => 'required|max_len,255',
        'meta_keywords' => 'max_len,1000',
    );

    public $elements_value = array(
        'status' =>
        array(
            '1' => 'Published',
            '2' => 'Unpublished'
        ),
    );
    public function __construct(){
        parent::__construct(''.DB_PREFIX.'options');
    }
    public function get_status_faq() {
    	return array('1' => __('Published', 'faq'), '2' => __('Unpublished', 'faq'));
    }
}