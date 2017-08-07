<?php
defined ( 'PF_VERSION' ) or header ( 'Location:404.html' );
class Popup_Model extends Pf_Model{
     protected $format_date;
    public function __construct(){
        parent::__construct(DB_PREFIX.'popup');
    }
}