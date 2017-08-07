<?php
defined ( 'PF_VERSION' ) or header ( 'Location:404.html' );
class Popup_Model extends Pf_Model{
    
        public $rules = array(
			'popup_title'=>'required|min_len,2|max_len,255',
			'popup_url'=>'required|min_len,5|max_len,255',
		);
    
        public $elements_value = array(
                'popup_created_date' => 'YYYY-MM-DD H:m:s',
                'popup_modified_date' => 'YYYY-MM-DD H:m:s',
                'popup_published_date' => 'YYYY-MM-DD H:m:s',
                'popup_unpublished_date' => 'YYYY-MM-DD H:m:s',
                'popup_type' => array('1' => 'Link','2' => 'Button',),
                'popup_status' => array ('1' => 'Published','2' => 'Unpublished' ),
            );
    
    public function __construct(){
        parent::__construct(DB_PREFIX.'popup');
    }
}