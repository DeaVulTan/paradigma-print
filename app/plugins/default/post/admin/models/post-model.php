<?php
defined ( 'PF_VERSION' ) or header ( 'Location:404.html' );
class Post_Model extends Pf_Model {
    
    public $rules = array (
        'post_title' => 'required|min_len,2|max_len,255',
        'post_category' => 'required|required_number',
        'post_thumbnail' => 'max_len,500',
        'post_content' => 'required|min_len,2' 
    );
    
    public $elements_value = array (
        //'post_category' => array ('1' => 'menu1','2' => 'menu2','3' => 'menu3'),
        'post_created_date' => 'YYYY-MM-DD H:m:s',
        'post_published_date' => 'YYYY-MM-DD H:m:s',
        'post_unpublished_date' => 'YYYY-MM-DD H:m:s',
        'post_status' => array ('1' => 'Published','2' => 'Unpublished' ),
        'post_allow_rating' => array (),
        'post_allow_comment' => array () 
    );
    
    public function __construct() {
        parent::__construct ( DB_PREFIX . 'posts' );
    }
    
    public function get_status_post() {
        return array('1' => __('Published', 'post'), '2' => __('Unpublished', 'post'));
    }
    
    public function get_allow_comment() {
        return array('1' => __('Yes', 'post'), '0' => __('No', 'post'));
    }
    
    public function get_allow_rating() {
        return array('1' => __('Yes', 'post'), '0' => __('No', 'post'));
    }
}