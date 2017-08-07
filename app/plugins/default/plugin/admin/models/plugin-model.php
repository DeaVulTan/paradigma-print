<?php
defined ( 'PF_VERSION' ) or header ( 'Location:404.html' );
class Plugin_Model extends Pf_Model{
    
    public $rules = array(
        'title' => 'required|max_len,255',
        'meta_keywords' => 'max_len,1000',
    );

    public $elements_value = array(
        'action' =>
        array(
            'Activate' => 'Activate',
            'Deactivate' => 'Deactivate'
        ),
    );
    public function __construct(){
        parent::__construct(''.DB_PREFIX.'options');
    }
}