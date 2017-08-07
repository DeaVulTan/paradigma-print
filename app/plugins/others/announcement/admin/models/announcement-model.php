<?php
defined ( 'PF_VERSION' ) or header ( 'Location:404.html' );
class Announcement_Model extends Pf_Model{
    
        public $rules = array(
                'announcement_content'=>'required|max_len,255',
        );
    
        public $elements_value = array(
                'announcement_status' => 
                    array(
                        '1' => 'Published',
                        '2' => 'Unpublished',
                    ),
                'announcement_pubdate' => 'YYYY-MM-DD H:m:s',
                'announcement_unpubdate' => 'YYYY-MM-DD H:m:s',
                'announcement_type' => 
                    array(
                        '1' => 'Danger',
                        '2' => 'Info',
                        '3' => 'Warning',
                        '4' => 'Success',
                    ),
            );

    
    public function __construct(){
        parent::__construct(DB_PREFIX.'announcement');
    }
    public function list_role() {
        Pf::database()->select('id, role_name',''.DB_PREFIX.'role');
        return   Pf::database()->fetch_assoc_all();
    }
    public function get_list_user() {
         Pf::database()->select('user_name',''.DB_PREFIX.'users','user_delete_flag =0');
        $list   =   Pf::database()->fetch_assoc_all();
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