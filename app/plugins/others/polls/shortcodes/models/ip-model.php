<?php
defined ( 'PF_VERSION' ) or header ( 'Location:404.html' );
class Ip_Model extends Pf_Model{
    public function __construct(){
        parent::__construct(DB_PREFIX.'polls_ip');
    }
    
    public function check_ipquestion($ip,$qid){
        $params = array();
        $where_value = array();
        $operator = '';
        $where = $operator.' pollsip_ip = ? ';
        $where_value[] =  $ip;
        $operator = ' AND ';
        $where .= $operator.'pollsip_qid = ?';
        $where_value[] =  $qid;
        $params['where'] =  array($where,$where_value);
        $result = $this->fetch($params);
        if(count($result) == 0){
            return TRUE;
        }else{
            return FALSE;
        }
    }
}