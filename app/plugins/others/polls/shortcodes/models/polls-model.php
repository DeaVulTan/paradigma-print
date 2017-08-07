<?php
defined ( 'PF_VERSION' ) or header ( 'Location:404.html' );
class Polls_Model extends Pf_Model{
     protected $format_date;
    public function __construct(){
        parent::__construct(DB_PREFIX.'polls_questions');
          $this->format_date = $this->get_format_date_sql(date("d-m-Y H:i:s"));
    }
    
    //this is function get answers
    public function get_polls($id){
        $params = array();
        $where = '';
        $operator = '';
        $where_value = array();
        $where = 'id=?';
        $where_value[] = $id; 
        $operator = ' AND ';
        $where .= $operator.' polls_status = 1 ';
        $operator = ' AND ';
        $where .= $operator.'(';
        $where .= ' `polls_pubdate` <= "'.$this->format_date.'"';
        $operator = ' OR ';
        $where .= $operator.' (`polls_pubdate`) = 0 ';
        $where .= ')';
        $operator = ' AND ';
        $where .= $operator.'(';
        $where .= ' `polls_unpubdate` > "'.$this->format_date.'"';
        $operator = ' OR ';
        $where .= $operator.' (`polls_unpubdate`) = 0 ';
        $where .= ')';
        $params['fields'] =  array('id','polls_question','polls_pubdate','polls_unpubdate','polls_status','polls_totalvote','polls_multiple');
        $params['where'] =  array($where,$where_value);
        $result = $this->fetch_one($params);
        return  $result;
    }
    public function get_format_date_sql($d){
        $date = date('d-m-Y H:i:s', strtotime($d));
        $date = new DateTime($date);
        $result = $date->format('Y-m-d H:i:s');
        return $result;
    }
}