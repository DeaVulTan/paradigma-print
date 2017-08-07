<?php
defined ( 'PF_VERSION' ) or header ( 'Location:404.html' );
class Category_Model extends Pf_Model{
    public function __construct(){
        parent::__construct(DB_PREFIX.'portfolio_categories');
    }
   //this is function list category
    public function list_cat(){
        $params = array();
        $where = 'category_status= 1';
        $where_value = array();
        $params['fields'] =  array('id','category_name');
        $params['where'] =  array($where,$where_value);
        $result = $this->fetch($params);
        return  $result;
    }
    // this is function convert categories name 
    public function convert_catname($name){
        return strtolower(str_replace(' ', '-', $name));
    }
    public function get_cate($cate_id){
        $params = array();
        $where = 'id=?';
        $where_value = array();
        $where_value[] = $cate_id;
        $params['fields'] =  array('id','category_name');
        $params['where'] =  array($where,$where_value);
        $result = $this->fetch_one($params);
        return  $result;
    }
}