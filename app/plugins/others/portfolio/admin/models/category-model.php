<?php
defined ( 'PF_VERSION' ) or header ( 'Location:404.html' );
class Category_Model extends Pf_Model{
    
        public $rules = array(
			'category_name'=>'required',
		);
    
        public $elements_value = array(
                'category_status' => 
                    array(
                        '1' => 'Publish',
                        '0' => 'Unpublish',
                    ),
            );

    
    public function __construct(){
        parent::__construct(DB_PREFIX.'portfolio_categories');
    }
    
    /* Categories functions */
    public function list_cate(){
        $params = array();
        $params['fields'] =  array('id','category_name');
        $list = $this->fetch($params);
       return $list;
    }
    //this is function cut string description
    public function cut( $str, $limit, $more=" ..."){
        if ($str=="" || $str == NULL || is_array($str) || strlen($str)==0)
            return $str;
        $str = trim($str);
         
        if (strlen($str) <= $limit) return $str;
        $str = substr($str,0,$limit);
    
        if (!substr_count($str," ")){
            if ($more) $str .= " ...";
            return $str;
        }
        while(strlen($str) && ($str[strlen($str)-1] != " ")){
            $str = substr($str,0,-1);
        }
        $str = substr($str,0,-1);
        if ($more) $str .= " ...";
        return $str;
    }
}