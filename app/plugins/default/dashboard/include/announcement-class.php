<?php defined('PF_VERSION') OR header('Location:404.html');?>
<?php

/**
 * 
 * @package     Vitubo
 * @author      Vitubo Team 
 * @copyright   Vitubo Team
 * @link        http://www.vitubo.com
 * @since       Version 1.0
 * @filesource
 *
 */
/**
 * Description of announcement-class
 *
 * @author eithree
 */
class Pf_Announcement {
    protected $db;
     function __construct() {
        $this->db   =   Pf::database();
        $this->list_to    =   $this->get_list_to();
    }
    public function list_role() {
        $this->db->select('id, role_name',''.DB_PREFIX.'role');
        return  $this->db->fetch_assoc_all();
    }
    public function get_announcements($clause, $array_clause, $col  =   '*') {
        $this->db->select($col,''.DB_PREFIX.'announcement',$clause, $array_clause);
        $list   =   $this->db->fetch_assoc_all();
        return $list;
    }
    public function get_list_to() {
        $this->db->select('id,announcement_to', ''.DB_PREFIX.'announcement');
        $list = $this->db->fetch_assoc_all();
        $i = 0;
        $list_new=array();
        if (!empty($list)) {
            foreach ($list as $item) {
                $list_new[$i]['id'] = $item['id'];
                $list_new[$i]['to'] = unserialize($item['announcement_to']);
                $i++;
            }
        }
        return $list_new;
    }

    public function check_role($role) {
        $list = $this->list_to;
        $result = array();
        if (!empty($list)) {
            foreach ($list as $item) {
                if (!empty($item['to']['togroup'])) {
                    foreach ($item['to']['togroup'] as $to) {
                        if ($to == $role or $to==100)
                            $result[] = $item['id'];
                    }
                }
            }
        }
        return $result;
    }

    public function check_user($username) {
        $list = $this->list_to;
        $result = array();
        if (!empty($list)) {
            foreach ($list as $item) {
                if(!empty($item['to']['touser'])){
                    foreach ($item['to']['touser'] as $to) {
                        if ($to == $username)
                            $result[] = $item['id'];
                    }
                }
            }
        }
        return $result;
    }

    public function public_announcement() {
        $togroup    =   $this->check_role(current_user('user-group'));
        $touser     =   $this->check_user(current_user('user-name'));
        if(count($touser)>count($togroup)){
            foreach($togroup as $group){
                $touser[]   =   $group;
            }
            return array_unique($touser);
        }
        else{
            foreach($touser as $user){
                $togroup[]   =   $user;
            }
            return array_unique($togroup);
        }
    }
    public function show() {
        $list   =   $this->public_announcement();
        if(!empty($list)){
        $or='';
        $clause='';
        foreach($list as $item){
            $clause .=   $or." `id`=?";
            $array_clause[]   =   $item;
            $or     =   ' or';
        }
        $this->db->select('announcement_pubdate,announcement_unpubdate,announcement_status,announcement_content,announcement_type',''.DB_PREFIX.'announcement',$clause, $array_clause);
        $_result    =   $this->db->fetch_assoc_all();
        $result     =   array();
        foreach ($_result as $item) {
            $pubtime    = strtotime($item['announcement_pubdate']);
            $unpubtime  = strtotime($item['announcement_unpubdate']);
            if(!empty($pubtime) || !empty($unpubtime)){
                if(($pubtime<time() || empty($pubtime)) && ($unpubtime>time() || empty($unpubtime)) && $item['announcement_status']==1){
                    $result[] = array('content'=>$item['announcement_content'],'type'=>$item['announcement_type']);
                }
            }
            else{
            if ($item['announcement_status'] == 1) {
                 $result[] = array('content'=>$item['announcement_content'],'type'=>$item['announcement_type']);
            }
            }
        }
        }
        else
            $result=NULL;
        return $result;
    }
    public function set_announcement($array) {
        if($this->db->insert(''.DB_PREFIX.'announcement',$array))
            return true;
    }
    public function update_announcement($array, $id) {
        if($this->db->update(''.DB_PREFIX.'announcement',$array, "`id`=?", array($id)))
            return true;
    }
    public function get_list_user() {
        $this->db->select('user_name',''.DB_PREFIX.'users','user_delete_flag =0');
        $list   =   $this->db->fetch_assoc_all();
        $value  =   '';
        foreach($list as $user){
            if(empty($value))
                $value  =   $user['user_name'];
            else
                $value  .=  ",".$user['user_name'];  
        }
        return $value;
    }
    public function announcement_action($action, $id) {
        switch ($action) {
            case 'del':
                $this->db->delete(''.DB_PREFIX.'announcement', "`id`=?", array($id));
                break;
            case 'publish':
                $this->db->update(''.DB_PREFIX.'announcement', array('announcement_status' => '1'), "`id`=?", array($id));
                break;
            case 'unpublish':
                $this->db->update(''.DB_PREFIX.'announcement', array('announcement_status' => '2'), "`id`=?", array($id));
                break;
        }
    }
}
