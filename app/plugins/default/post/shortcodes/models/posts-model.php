<?php
defined ( 'PF_VERSION' ) or header ( 'Location:404.html' );
class Posts_Model extends Pf_Model{
    public function __construct(){
        parent::__construct(DB_PREFIX.'posts');
    }
}