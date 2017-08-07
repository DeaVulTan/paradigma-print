<?php
defined ( 'PF_VERSION' ) or header ( 'Location:404.html' );
class Answers_Model extends Pf_Model{
    public function __construct(){
        parent::__construct(DB_PREFIX.'polls_answers');
    }
    
    //this is function getl list answers
    public function get_list_answers($id){
        $settings = Pf::setting();
        $answer_order = $settings->get_element_value('pf_polls', 'answer_order');
        $params = array();
        $where = 'pollsa_qid=?';
        $where_value = array();
        $where_value[] = $id;
        $params['fields'] =  array('pollsa_answers','pollsa_vote','id');
        $params['where'] =  array($where,$where_value);
        if( $answer_order == 1){
            $params["order"] = "`".Pf::database ()->escape('pollsa_answers')."` ".Pf::database ()->escape('ASC');
        }else if($answer_order == 2){
            $params["order"] = "`".Pf::database ()->escape('pollsa_answers')."` ".Pf::database ()->escape('DESC');
        }else if($answer_order == 3){
            $params["order"] = "`".Pf::database ()->escape('pollsa_vote')."` ".Pf::database ()->escape('ASC');
        }else{
            $params["order"] = "`".Pf::database ()->escape('pollsa_vote')."` ".Pf::database ()->escape('DESC');
        }
        $result = $this->fetch($params);
        return  $result;
    }
    
    //this is funtion get id answers
    
    public function get_id_answers($aid){
        $params = array();
        $where = 'id=?';
        $where_value = array();
        $where_value[] = $aid;
        $params['fields'] =  array('pollsa_answers','pollsa_vote','id');
        $params['where'] =  array($where,$where_value);
        $result = $this->fetch_one($params);
        return  $result;
    }
    
}