<?php
defined ( 'PF_VERSION' ) or header ( 'Location:404.html' );
class Page_Model extends Pf_Model{
    
        public $rules = array(
			'page_title'=>'required|max_len,255',
			'page_content'=>'required,max_len,1000000',
			'page_layout'=>'required',
			'page_meta_title'=>'max_len,255',
			'page_meta_keywords'=>'max_len,255',
			'page_meta_description'=>'max_len,255',
		);
    
        public $elements_value = array(
            'page_status' =>
                array(
                    '1' => 'Published',
                    '2' => 'Unpublished',
                ),
            'page_created_date' => 'DD-MM-YYYY',
        );

    
    public function __construct(){
        parent::__construct(''.DB_PREFIX.'pages');
    }
    
    public function get_status_pages() {
        return array('1' => __('Published', 'page'), '0' => __('Unpublished', 'page'));
    }
    
    public function get_layout_pages() {
        $layouts = array('' => __('Choose layout', 'page'));
        $rs = get_option('layouts');
        foreach ($rs as $v) {
            $layouts[$v['id']] = $v['layout_name'];
        }
        return $layouts;
    }
    
    public function get_page_type() {
        return array('0' => __('Default', 'page'), '1' => __('Landing page', 'page'));
    }
    
    public function get_list_role() {
        $this->db->select('id, role_name', ''.DB_PREFIX.'role');
        return $this->db->fetch_assoc_all();
    }
    
    public function get_list_user() {
        $this->db->select('user_name',''.DB_PREFIX.'users','user_delete_flag =0');
        $list   =   $this->db->fetch_assoc_all();
        $value  =   '';
        foreach($list as $user){
            if(empty($value))
                $value  =   "'".$user['user_name']."'";
            else
                $value  .=  ","."'".$user['user_name']."'";
        }
        return $value;
    }
}