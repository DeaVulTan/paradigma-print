<?php
defined ( 'PF_VERSION' ) or header ( 'Location:404.html' );
class Answers_Model extends Pf_Model{
    
    public function __construct(){
        parent::__construct(DB_PREFIX.'polls_answers');
    }
    //this is function get answers
    public function get_answers($id){
        $params = array();
        $where = 'pollsa_qid=?';
        $where_value = array();
        $where_value[] = $id;
        $params['fields'] =  array('pollsa_answers','pollsa_vote','id');
        $params['where'] =  array($where,$where_value);
        $params["order"] = "`".Pf::database ()->escape('id')."` ".Pf::database ()->escape('ASC');
        $result = $this->fetch($params);
        return  $result;
    }
    // this is function get aid 
    public function get_aid($id){
        $params = array();
        $where = 'pollsa_qid=?';
        $where_value = array();
        $where_value[] = $id;
        $params['fields'] =  array('id');
        $params['where'] =  array($where,$where_value);
        $params["order"] = "`".Pf::database ()->escape('id')."` ".Pf::database ()->escape('ASC');
        $result = $this->fetch($params);
        return  $result;
    }
    //this is function update answers
    public function update_answers($aid,$field){
        $data = array();
        $where = 'id='.$aid.'';
        $data['pollsa_answers'] = $field;
        return  $this->update($data,$where);
    }
}