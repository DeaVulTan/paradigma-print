<?php
defined ( 'PF_VERSION' ) or header ( 'Location:404.html' );
class Role_Model extends Pf_Model{
    
    public function __construct(){
        parent::__construct(DB_PREFIX.'role');
    }
    /*list role function*/
    public function list_role(){
        $params = array();
        $params['fields'] =  array('id','role_name');
        $list = $this->fetch($params);
        return $list;
    }
}