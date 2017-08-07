<?php
defined ( 'PF_VERSION' ) or header ( 'Location:404.html' );
class Category_Model extends Pf_Model {
    
    public $rules = array (
        'category_name' => 'required|min_len,2|max_len,255',
        'category_description' => 'max_len,255' 
    );
    
    public $elements_value = array (
        'category_parent' => array (),
        'category_status' => array (
            '1' => 'Published',
            '2' => 'Unpublished' 
        ) 
    );
    
    public function __construct() {
        parent::__construct (DB_PREFIX.'post_categories' );
    }
    
    public function get_status_category() {
        return array('1' => __('Published', 'category'), '2' => __('Unpublished', 'category'));
    }
}