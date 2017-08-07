<?php
defined ( 'PF_VERSION' ) or header ( 'Location:404.html' );
class Meta_Model extends Pf_Model{
    public function __construct(){
        parent::__construct(DB_PREFIX.'portfolio_meta');
    }
  //this is function get portfolio_meta
    public function get_meta($id){
        $params = array();
        $where = 'meta_portfolio=?';
        $where_value = array();
        $where_value[] = $id;
        $params['fields'] =  array('meta_name','meta_value');
        $params['where'] =  array($where,$where_value);
        $result = $this->fetch($params);
        return  $result;
    }
}