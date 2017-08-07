<?php
defined ( 'PF_VERSION' ) or header ( 'Location:404.html' );
class Menu_Controller extends Pf_Controller {
    public function __construct() {
        parent::__construct ();
        $this->load_model ('menu');
        $this->view->menu_settings = get_option ( 'admin_menu_setting' );
        $this->menu_model->rules = Pf::event ()->trigger ( "filter", "menu-validation-rule", $this->menu_model->rules );
    }
    public function index() {
        $params = array ();
        $where = '';
        $where_values = array ();
        $operator = '';
        $list_menu = get_option ('menu');
        if (!isset($this->get->{$this->page})){
            $page = 0;
        } else {
            $page = $this->get->{$this->page} - 1;
        }
        $params ['page_index'] = $page;
        $params ['limit'] = NUM_PER_PAGE;
        
        $operator = '';
        
        if (empty ( $this->get->search )) {
            $this->get->search = array ();
        }
        if (isset ( $this->get->search ["menu_title"] ) && trim ( $this->get->search ["menu_title"] ) != "") {
            $sub = strtolower($this->get->search ["menu_title"]);
            foreach ($list_menu as $k => $v) {
                $str = strtolower($v['name']) ;
                if( strpos($str, $sub) === false ){
                    unset($list_menu[$k]);
                }
            }
            
            $list_menu = array_values($list_menu);
        }
        
        $limit = $params ['page_index'] * $params ['limit'];
        $list = array ();
        if (! empty ( $list_menu ))
            $list_menu = array_values ( $list_menu );
        for($i = $limit; $i < $limit + $params ['limit']; $i ++) {
            if (! empty ( $list_menu [$i] ))
                $list [] = $list_menu [$i];
        }
        if (!empty ( $this->get->order_field ) && !empty ( $this->get->order_type )) {
            if($this->get->order_field == 'menu_title' && $this->get->order_type == 'asc'){
                asort($list);
            }else if($this->get->order_field == 'menu_title' && $this->get->order_type == 'desc'){
                arsort($list);
            }
        }
        
        $this->view->page_index = $params ['page_index'];
        $this->view->records = $list;
        $this->view->total_records = count ( $list_menu );
        $total_page = ceil ( $this->view->total_records / NUM_PER_PAGE );
        
        if (empty ( $this->view->records ) && $total_page > 0) {
            $this->get->{$this->page} = $params ['page_index'] = $total_page;
            $this->view->page_index = $params ['page_index'];
            $this->view->records = $list;
            $this->view->total_records = count ($list_menu);
        }
        $this->view->pagination = new Pf_Paginator ( $this->view->total_records, NUM_PER_PAGE, $this->page );
        
        $template = null;
        $template = Pf::event ()->trigger ( "filter", "menu-index-template", $template );
        $this->view->render ( $template );
    }
    
    public function add() {
        $template = null;
        $template = Pf::event ()->trigger ( "filter", "menu-add-template", $template );
        
        if ($this->request->is_post ()) {
            $menu_all = get_option('menu');
            $id = uniqid();
            $data_menu = json_decode($this->post->{"json-data"},true);
            $list_id=array();
            foreach($data_menu as $key){
                $list_id[$key['id']] = 1;
            }
            $list  = $this->menu_model->filter_data(json_decode($this->post->{"json-menu"}, true), $list_id);
            $data = array(
                'id' => $id,
                'name' => $this->post->{"title"},
                'data' => array(0 => $list, 1 => $data_menu)
            );
            $menu_all[] = $data;
            $data_menu = $data['data'];
            $this->post->datas ($data_menu);
            $data = Pf::event ()->trigger ( "filter", "menu-post-data", $data );
            $data = Pf::event ()->trigger ( "filter", "menu-adding-post-data", $data );
            
            $var = array ();
            if (empty($this->post->{"title"})) {
                $var ['error'] = 1;
                $var['warning'] = "Please enter Menu name";
            } else if(strlen($this->post->{"title"})>255){
                $var ['error'] = 2;
                $var['warning'] = "Menu name can not more than 255 characters";
            }else{
                update_option('menu', $menu_all);
                Pf::event ()->trigger ( "action", "menu-add-successfully", $this->menu_model->insert_id (), $data );
                $var ['error'] = 0;
                $data_menu = $data['data'];
                $this->post->datas ($data_menu);
                $var ['url'] = admin_url ( $this->action . '=index&ajax=&id=&token=' );
            }
            
            echo json_encode ($var);
        } else {
            $this->view->render ( $template );
        }
    }
    
    public function edit() {
        $template = null;
        $template = Pf::event ()->trigger ( "filter", "menu-edit-template", $template );
        
        $var = array ();
        
        if (isset ( $this->get->id ) && isset ( $this->get->token )){
            $menu['menu_all'] = $menu_all = get_option('menu');
            $menu_id = $this->get->id;
            $menu['k'] = $this->menu_model->choose_menu($menu_all, $menu_id);
            $menu['value'] = $menu_all[$menu['k']]['name'];
            $menu['data_menu'] = $menu_all[$menu['k']]['data'];
            if ($this->request->is_post ()) {
                $menu_all[$menu['k']]['name'] = $this->post->{"title"};
                $data = json_decode($this->post->{"json-data"},true);
                $list_id = array();
                foreach($data as $key){
                    $list_id[$key['id']] = 1;
                }
                $order = $this->menu_model->filter_data(json_decode($this->post->{"json-menu"},true),$list_id);
                $menu['menu_all_0'] = $menu_all[$menu['k']]['data'][0]    =   $order;
                $menu['menu_all_1'] = $menu_all[$menu['k']]['data'][1]    =   $data;
                
                
                $menu = Pf::event ()->trigger ( "filter", "menu-post-data", $menu );
                $menu = Pf::event ()->trigger ( "filter", "menu-editing-post-data", $menu );
                
                $this->view->token = Pf_Plugin_CSRF::token ( $this->key . $this->get->id );
                
                if (empty($this->post->{"title"})) {
                    $var ['error'] = 1;
                    $var['warning'] = "Please enter Menu name";
                } else if(strlen($this->post->{"title"})>255){
                    $var ['error'] = 2;
                    $var['warning'] = "Menu name can not more than 255 characters";
                }else{
                    update_option('menu', $menu_all);
                    Pf::event ()->trigger ( "action", "menu-edit-successfully", $menu );
                    $var ['error'] = 0;
                    $var ['url'] = admin_url ( $this->action . '=index&ajax=&id=&token=' );
                }
            } else {
                if (isset ( $this->get->id )) {
                    $menu = Pf::event ()->trigger ( "filter", "menu-database-data", $menu );
                    $menu = Pf::event ()->trigger ( "filter", "menu-editing-database-data", $menu );
                    
                    $this->post->datas ( $menu );
                    $this->view->token = Pf_Plugin_CSRF::token ( $this->key . $this->get->id );
                    $var ['content'] = $this->view->fetch ( $template );
                    $var ['error'] = 0;
                } else {
                    $var ['error'] = 1;
                    $var ['url'] = admin_url ( $this->action . '=index&ajax=&id=&token=' );
                }
            }
        } else {
            $var ['error'] = 1;
            $var ['url'] = admin_url ( $this->action . '=index&ajax=&id=&token=' );
        }
        
        echo json_encode ( $var );
    }
    
    public function bulk_action() {
        $var = array ();
        $data = array ();
        $params = array ();
        
        if (Pf_Plugin_CSRF::is_valid ( $this->post->token, $this->key )) {
            switch ($this->get->type) {
                case 'delete' :
                    if (! empty($this->post->id) && is_array($this->post->id)){
                        $list_menu = get_option('menu');
                        foreach ($this->post->id as $id) {
                            foreach ($list_menu as $k => $v) {
                                if ($id == $v['id']) {
                                    unset($list_menu[$k]);
                                    break;
                                }
                            }
                        }
                        if (empty($list_menu))
                        $list_menu = '';
                        update_option('menu', $list_menu);
                    }
                    $var['action'] = 'delete';
                break;
            }
            Pf::event ()->trigger ( "action", "menu-bulk-action-successfully", $this->get->type, $this->post->id );
            $var ['error'] = 0;
        } else {
            $var ['error'] = 1;
        }
        
        $var ['url'] = admin_url ( $this->action . '=index&ajax=&id=&token=&type=' );
        echo json_encode ( $var );
    }
    
    public function delete() {
        $var = array ($this->get->id);
        if (isset ( $this->get->id ) && isset ( $this->get->token ) && Pf_Plugin_CSRF::is_valid ( $this->get->token, $this->key . $this->get->id )) {
            $list_menu = get_option('menu');
            foreach ($list_menu as $k => $v) {
                if ($this->get->id == $v['id']) {
                    unset($list_menu[$k]);
                    break;
                }
            }
            if (empty($list_menu))
            $list_menu = '';
            if (update_option('menu', $list_menu)){
                $var ['error'] = 0;
                Pf::event ()->trigger ( "action", "menu-delete-successfully", $this->get->id );
            }else {
                $var ['error'] = 1;
            }
        }
        
        $var ['url'] = admin_url ( $this->action . '=index&ajax=&id=&token=' );
        echo json_encode($var);
    }
}