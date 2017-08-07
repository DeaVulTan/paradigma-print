<?php
defined ( 'PF_VERSION' ) or header ( 'Location:404.html' );
class Announcement_Model extends Pf_Model{
    public function __construct(){
        parent::__construct(DB_PREFIX.'announcement');
        $this->list_to    =   $this->get_list_to();
    }
    public function get_list_to() {
        $params = array();
        $params['fields'] =  array('id','announcement_to');
        $list = $this->fetch($params);
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
    public function get_icon($typenum, $typearr) {
        switch ($typenum) {
            case 1:
                $icon = 'ban';
                break;
            case 4:
                $icon = 'check';
                break;
            default:
                $icon = $typearr[$typenum];
                break;
        }
        return $icon;
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
            $params = array();
            $params['fields'] = array('announcement_pubdate','announcement_unpubdate','announcement_status','announcement_content','announcement_type');
            $params['where'] = array($clause,$array_clause);
            $_result  = $this->fetch($params);
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
}