<?php
defined ( 'PF_VERSION' ) or header ( 'Location:404.html' );
class Comment_Model extends Pf_Model{
    
        public $rules = array(
            'comment_content'=>'required|max_len,1000|min_len,2',
		);
    
        public $elements_value = array(
                'comment_created_date' => 'DD-MM-YYYY',
            );

    
    public function __construct(){
        parent::__construct(''.DB_PREFIX.'comments');
    }
}