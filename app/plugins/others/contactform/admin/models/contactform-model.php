<?php
defined ( 'PF_VERSION' ) or header ( 'Location:404.html' );
class Contactform_Model extends Pf_Model{
    public $rules = array(
        'title' => 'required|max_len,255',
        'form' => 'required',
        'from' => 'required|valid_email|max_len,255',
        'name' => 'max_len,255',
        'to' => 'required|valid_email|max_len,255',
        'message' => 'required',
    );
    public $elements_value = array(
        'status' =>
        array(
                '1' => 'Published',
                '0' => 'Unpublished'
        ),
    );
    
    public function __construct(){
        parent::__construct(''.DB_PREFIX.'options');
    }
}