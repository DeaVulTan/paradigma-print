<?php
class Menu_Shortcode_Model extends Pf_Model{
    public $db;
    protected $all;
    protected $pageurl;
    function __construct() {
        $this->db   =   Pf::database();
        $this->db->select('id,page_url',''.DB_PREFIX.'pages');
        $this->pageurl    =   $this->db->fetch_assoc_all();
    }
    
    
    public function navigation(){
        return $this->get_menu(get_configuration('main_menu'));
    }
    
    public function get_menu($id){
        $menus    =   get_option('menu');
        $navigation = array();
        
        foreach ($menus as $menu){
            if ($menu['id'] == $id){
                $navigation = $menu['data'];
                break;
            }
        }
        
        if (empty($navigation[0]) || !is_array($navigation[0]) || empty($navigation[1]) || !is_array($navigation[1])){
            $navigation = array();
        }
        
        return $navigation;
    }
    
    public function all_page_links(){
        $old_table = $this->table();
        $this->table(DB_PREFIX . 'pages');
        
        $params = array();
        $params['fields'] = array('id','page_url');
        $params['where'] = array('page_status = ?', array(1));
        $rs = $this->fetch($params);
        
        $page_links = array();
        if (!empty($rs) && is_array($rs)){
            foreach ($rs as $k => $v){
                $page_links[$v['id']] = $v['page_url']; 
            }
        }
        
        $this->table($old_table);
        
        return $page_links;
    }
    
    public function menu_detail($menu_id,& $details){
        $detail = array();
        foreach ($details as $k => $v){
            if ($menu_id == $v['id']){
                $detail = $details[$k];
                break;
            }
        }
        
        return $detail;
    }
    
    public function get_url($type, $call, $page_links){
        $url = '';
        switch ($type) {
            case 'page':
                if(!empty($page_links[$call])){
                    $url = public_url($page_links[$call],false);
                }
                break;
            case 'url':
            default :
                $url   =   $call;
        }
        
        return $url;
    }
    
    
    
    //public
    
    private function pick_menu($id){
        $all    =   get_option('menu');
        $data   =   '';
        foreach($all as $k=>$v){
            if($v['id']==$id){
                $data   =   $all[$k]['data'];
            }
        }
        return $data;
    }
    public function filter_menu($list,$data) {
        foreach($list as $k=>$v){
            if($this->check_child($list[$k])==true){
                $list[$k]['children']=$this->filter_menu($list[$k]['children'], $data);
            }
            foreach ($data as $item){
                if($item['id']==$v['id'] && isset($item['status']) && $item['status']!=1){
                    unset($list[$k]);
                }
            }
        }
        return $list;
    }
    private function check_child($array) {
        if(!empty($array['children']) && is_array($array['children']))
            return true;
        else
            return false;
    }
    private function choose_item($array, $id) {
        for($i=0; $i<count($array);$i++){
            if($array[$i]['id']==$id)
                return $i;
        }
    }
    private function get_page_link($pageid) {
        $url    =   $this->pageurl;
        foreach($url as $item){
            $list[$item['id']]= $item['page_url'];
        }
        if(count($url)>0 && !empty($list[$pageid]))
            return public_url($list[$pageid],false);
        else
            return '';
    }
    private function url_builder($type, $call) {
        switch ($type) {
            case 'url':
                $link   =   $call;
                break;
            case 'page':
                $link   =   $this->get_page_link($call);
                break;
            default :
                $link   =   $call;
        }
        return $link;
    }
    private function builder_v($data,$detail) {
        if (!empty($data)) {
            $html = '';
            foreach ($data as $item) {
                $key = $this->choose_item($detail, $item['id']);
                $itemlink = $this->url_builder($detail[$key]['type'], $detail[$key]['call']);
                if ($this->check_child($item))
                    $li_class = "class='dropdown-submenu'";
                else
                    $li_class = '';
                $html .= "<li " . $li_class . ">";
                $html .= "<a href='" . $itemlink . "'>" . $detail[$key]['name'] . "</a>";
                if ($this->check_child($item)) {
                    $html .= "<ul class='dropdown-menu'>";
                    $html .= $this->builder_v($item['children'], $detail);
                    $html .= "</ul>";
                }
                $html .= "</li>";
            }
            return $html;
        }
    }
    private function builder_accordion($data,$detail) {
        if (!empty($data)) {
            $html = '';
            foreach ($data as $item) {
                $key = $this->choose_item($detail, $item['id']);
                $itemlink = $this->url_builder($detail[$key]['type'], $detail[$key]['call']);
                if ($this->check_child($item)){
                    $li_class = "class='treeview'";
                    $i        = "<i class='fa fa-angle-left'></i>";
                }
                else{
                    $li_class = '';
                    $i          =   '';
                }
                $html .= "<li " . $li_class . ">";
                $html .= "<a href='$itemlink' title='{$detail[$key]['name']}'>{$detail[$key]['name']}". $i ."</a>";
                    if ($this->check_child($item)) {
                        $html .= "<ul class='treeview-menu'>";
                        $html .= $this->builder_accordion($item['children'], $detail);
                        $html .= "</ul>";
                    }
                    $html .= "</li>";
            }
            return $html;
        }
    }
    private function builder($data,$detail,$level=1) {
        $html = '';
        foreach ($data as $item){
            $key    =   $this->choose_item($detail, $item['id']);
            if($level){
                if($this->check_child($item)){
                    $a_class    =   "class='dropdown-toggle hover-dropdown' data-toggle='dropdown'";
                    $li_class   =   "dropdown";
                    $i          =   "<i class='fa fa-angle-down'></i>";
                }
                else {
                    $a_class    =   "";
                    $li_class   =   "";
                    $i          =   "";
                }
            }else{
                $a_class    =   "";
                $li_class   =   "btn-link";
                $i          =   "";
            }
            $curentlink     =   RELATIVE_PATH."/".$_GET['pf_page_url'];
            $itemlink       =   $this->url_builder($detail[$key]['type'], $detail[$key]['call']);
            if(isset($curentlink) && $curentlink==$itemlink){
                $li_class.=" active";
            }
            $html .= "<li class='".$li_class."'>";
            $html .= "<a href='".$itemlink."' ".$a_class.">".$detail[$key]['name'].$i."</a>";
            if($this->check_child($item) && $level){
                $html .= "<ul class='dropdown-menu'>";
                $html .= $this->builder($item['children'], $detail);
                $html .= "</ul>";
            }
            $html .= "</li>";
        }
        return $html;
    }
    public function builder_top($data,$detail) {
        $html = '';
        if (!empty($data)) {
            foreach ($data as $item) {
                $key = $this->choose_item($detail, $item['id']);
                $itemlink = $this->url_builder($detail[$key]['type'], $detail[$key]['call']);
                if(!empty($detail[$key]['options']))
                    $itemlink.=$detail[$key]['options'];
                    $active = "";
                    $post_page     = get_configuration('page_lists', 'pf_post');
                    if( public_url('',true)==$itemlink.'/'){
                        $active = "active-color";
                    }else if(public_url($post_page,false)==$itemlink){
                        $page_detail     = get_configuration('page_detail', 'pf_post');
                        if($page_detail == $_GET['pf_page_url']){
                            $active = "active-color";
                        }
                    }
                    if(!empty($detail[$key]['icon']) && $detail[$key]['icon']!=''){
                        switch ($detail[$key]['icontype']) {
                            case 'css':
                                $name   =   "<i class='".$detail[$key]['icon']."'></i> ".$detail[$key]['name'];
                                break;
                            case 'img':
                                $name   =   is_file(urldecode($detail[$key]['icon']))?"<img src='".public_base_url().$detail[$key]['icon']."'/> ".$detail[$key]['name']:"<i class='fa fa-bars'></i> ".$detail[$key]['name'];
                        }
                    }
                    else
                        $name   =   $detail[$key]['name'];
                    if(!empty($detail[$key]['desc']) && $detail[$key]['desc']!='')
                        $desc   =   "<p class='menu-desc'>".$detail[$key]['desc']."</p>";
                    else
                        $desc   =   "";
                    if ($this->check_child($item)){
        
                        $li_class = "class='dropdown $active'";
                        $a_class  = "class='dropdown-toggle hover-dropdown' data-toggle='dropdown' data-close-others='false'";
                        $itemlink ="#";
                        $i        = "<i class='caret'></i>";
                    }
                    else{
                        $li_class = "class='$active'";
                        $a_class    = '';
                        $i          =   '';
                    }
                    $html .= "<li " . $li_class . ">";
                    $html .= "<a href='$itemlink' $a_class >".$name ."  ". $i ." $desc</a>";
                    if ($this->check_child($item)) {
                        $html .= "<ul class='dropdown-menu sub'>";
                                $html .= $this->builder_top($item['children'], $detail);
                                $html .= "</ul>";
                    }
                    $html .= "</li>";
                }
                return $html;
                }
            }
            private function builder_footer($data,$detail) {
                if (!empty($data)) {
                    $html = '';
                    foreach ($data as $item) {
                        $key = $this->choose_item($detail, $item['id']);
                        $itemlink = $this->url_builder($detail[$key]['type'], $detail[$key]['call']);
                        $html .= "<li>";
                        $html .= "<a href='" . $itemlink . "'>" . $detail[$key]['name'] . "</a>";
                        if ($this->check_child($item)) {
                        $html .= "<ul class='widget_submenu'>";
                            $html .= $this->builder_footer($item['children'], $detail);
                            $html .= "</ul>";
                        }
                        $html .= "</li>";
                    }
                    return $html;
                }
            }
            public function list_menu() {
            $all    =   get_option('menu');
            foreach ($all as $menu){
            $list[$menu['id']]  =   $menu['name'];
            }
            return $list;
            }
    
            public function show_menu($id, $type = '',$level=1) {
            $data = $this->pick_menu($id);
            if (!empty($data[0]))
                $data[0] = $this->filter_menu($data[0], $data[1]);
                if (!empty($data)) {
                    if ($type == 'v')
                        return $this->builder_v($data[0], $data[1]);
                    elseif ($type == 'top')
                        return $this->builder_top($data[0], $data[1]);
                    elseif ($type == 'footer') {
                        return $this->builder_footer($data[0], $data[1]);
                    } elseif($type == 'accordion'){
                        return $this->builder_accordion($data[0],$data[1]);
                    }else{
                        return $this->builder($data[0], $data[1],$level);
                    }
                }
        }
}