<?php
defined ( 'PF_VERSION' ) or header ( 'Location:404.html' );
class Portfolio_Model extends Pf_Model{
    public function __construct(){
        parent::__construct(DB_PREFIX.'portfolios');
    }
    //this is function list category
    public function list_port(){
        $params = array();
        $where_value = array();
        $where = '';
        $operator = '';
        $where .= 'portfolio_status= 1';
        $operator = ' AND ';
        $where .= $operator.'category_status= 1';
        $params['fields'] =  array(DB_PREFIX.'portfolios.id','portfolio_avatar','portfolio_name','portfolio_items','portfolio_category','portfolio_description,portfolio_status,category_status,category_name');
        $params['join'] = array('0' => array('LEFT',''.DB_PREFIX.'portfolio_categories',''.DB_PREFIX.'portfolios.portfolio_category = '.DB_PREFIX.'portfolio_categories.id'));
        $params['where'] =  array($where,$where_value);
        $result = $this->fetch($params);
        return  $result;
    }
    //this is function the excerpt 225 description
    public function the_excerpt($text, $strLen = 255) {
        $sanitized = strip_tags($text);
        if (strlen($sanitized) > $strLen) {
            $cutString = substr($sanitized, 0, $strLen);
            if (strrpos($cutString, ' ')) {
                return substr($sanitized, 0, strrpos($cutString, ' ')) . '...';
            }
            return $cutString . '...';
        }
        return $sanitized;
    }
    //this is function get portfolio
    public function get_port($id){
        $params = array();
        $where = 'id=?';
        $where_value = array();
        $where_value[] = $id;
        $params['fields'] =  array('portfolio_avatar','portfolio_name','portfolio_category','portfolio_description','portfolio_items');
        $params['where'] =  array($where,$where_value);
        $result = $this->fetch_one($params);
        return  $result;
    }
}