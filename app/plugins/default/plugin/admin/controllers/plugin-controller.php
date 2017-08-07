<?php
defined ( 'PF_VERSION' ) or header ( 'Location:404.html' );
class Plugin_Controller extends Pf_Controller {
    public function __construct() {
    parent::__construct ();
    $this->load_model ( 'plugin' );
    $this->view->menu_settings = get_option ( 'admin_menu_setting' );
    $this->plugin_model->rules = Pf::event ()->trigger ( "filter", "plugin-validation-rule", $this->plugin_model->rules );
    }
    public function index() {
    $params [] = array ();
    $where = '';
    $where_values = array ();
    $operator = '';
    $data = '';
    
    $params ['plugin'] = get_option ( 'active_plugins' );
    $plugins = get_plugins ( PLUGIN_PATH );
    $this->view->actived_plugins = $params['plugin'];
    $data = $plugins;
    $params ['limit'] = NUM_PER_PAGE;
    $params ['page_index'] = (isset ( $this->get->{$this->page} )) ? ( int ) $this->get->{$this->page} : 1;
    
    $operator = '';
    $url = admin_url('admin-page=plugin', false);
    // view all action 
    if (isset ( $this->post->action ) && trim ( $this->post->action ) != "") {
        $data_plugin = get_option ( 'active_plugins' );
        $plugin = get_plugins ( PLUGIN_PATH );
        if($this->post->status == 'activate'){
            foreach ($plugin as $id => $value){
                if (! in_array ( $id, $data_plugin )) {
                    $data1[$id] = $plugin[$id];
                }
            }
            $data = $data1;
        }else if($this->post->status = 'deactivate'){
            foreach ($plugin as $id => $value){
                foreach ($data_plugin as $id2 => $value2){
                    if($id == $value2){
                        $data1[$id] = $plugin[$id];
                    }
                }
            }
            $data = $data1;
        }
    }
    //search
    if(isset($this->get->search["name"])){
        $name = $this->get->search["name"];
        $data = $this->search_by_key($data, $name, 'name');
    }
    if(isset($this->get->search["action"])){
        $action = $this->get->search["action"];
        if(isset($action[1]) == 'Deactivate'){
            $data_plugin = get_option ( 'active_plugins' );
            $plugin = get_plugins ( PLUGIN_PATH );
            $data = $plugin;
            $name = $this->get->search["name"];
            $data = $this->search_by_key($data, $name, 'name');
        }else{
        if($action[0] == 'Activate'){
            $data_plugin = get_option ( 'active_plugins' );
            $plugin = get_plugins ( PLUGIN_PATH );
            foreach ($plugin as $id => $value){
                if (! in_array ( $id, $data_plugin )) {
                    $data1[$id] = $plugin[$id];
                }
            }
            $data = $data1;
            $name = $this->get->search["name"];
            $data = $this->search_by_key($data, $name, 'name');
        }else if($action[0] == 'Deactivate'){
            $data_plugin = get_option ( 'active_plugins' );
            $plugin = get_plugins ( PLUGIN_PATH );
            foreach ($plugin as $id => $value){
                foreach ($data_plugin as $id2 => $value2){
                    if($id == $value2){
                        $data1[$id] = $plugin[$id];
                    }
                }
            }
            $data = $data1;
            $name = $this->get->search["name"];
            $data = $this->search_by_key($data, $name, 'name');
        }
        }
    }
    if (!empty ( $this->get->order_field ) && !empty ( $this->get->order_type )) {
        if($this->get->order_field == 'name' && $this->get->order_type == 'asc'){
            asort($data);
        }else if($this->get->order_field == 'name' && $this->get->order_type == 'desc'){
            arsort($data);
        }
    }
        $this->view->page_index = $params ['page_index'];
        
        $this->view->records = $data;
        $this->view->total_records = count ( $data );
        $total_page = ceil ( $this->view->total_records / NUM_PER_PAGE );
        $this->view->pagination = new Pf_Paginator ( $this->view->total_records, NUM_PER_PAGE, $this->page );
        
        if (!empty($this->view->records) && $total_page > 1){
            $tesst = $this->pagination($data,$params['page_index']);
            $this->view->records = $tesst;
        }else{
            $this->view->records = $data;
        }
        
        $template = null;
        $template = Pf::event ()->trigger ( "filter", "plugin-index-template", $template );
        $this->view->render ( $template );
}
    
    public function bulk_action() {
    $var = array ();
    $data = array ();
    $params = array ();
    
    if (Pf_Plugin_CSRF::is_valid ( $this->post->token, $this->key )) {
        switch ($this->get->type) {
            case 'activate' :
                if (! empty ( $this->post->id ) && is_array ( $this->post->id )) {
                    $actived_plugins = get_option ('active_plugins');
                    $id = $this->post->id;
                    foreach ($id as $key2 => $value2) {
                        if (! empty ( $actived_plugins ) && is_array ( $actived_plugins )) {
                            if (! in_array ( $value2, $actived_plugins )) {
                                $actived_plugins [] = $value2;
                                update_option ( 'active_plugins', $actived_plugins );
                            }
                        } else {
                            $actived_plugins = array ();
                            $actived_plugins [] = $$value2;
                            update_option ( 'active_plugins', $actived_plugins );
                        }
                    }
                }
                $var ['action'] = 'activate';
                break;
            case 'deactivate' :
                if (! empty ( $this->post->id ) && is_array ( $this->post->id )) {
                    $actived_plugins = get_option ('active_plugins');
                    $id = $this->post->id;
                    foreach ($id as $key => $value){
                        if (! empty ( $actived_plugins ) && is_array ( $actived_plugins )) {
                            if (in_array ( $value, $actived_plugins )) {
                                if (($key = array_search ( $value, $actived_plugins )) !== false) {
                                    unset ( $actived_plugins [$key] );
                                    update_option ( 'active_plugins', $actived_plugins );
                                }
                            }
                        }
                    }
                }
                $var ['action'] = 'deactivate';
                break;
        }
        Pf::event ()->trigger ( "action", "plugin-bulk-action-successfully", $this->get->type, $this->post->id );
        $var ['error'] = 0;
    } else {
        $var ['error'] = 1;
    }
    $var ['url'] = admin_url ( $this->action . '=index&ajax=&id=&token=&type=' );
    echo json_encode ( $var );
    }
    
    
    public function change_actions(){
        $data = array();
        $id = $this->post->id;
        $status = $this->post->status;
        if($status == 'deactivate'){
            $actived_plugins = get_option ( 'active_plugins' );
            if (! empty ( $actived_plugins ) && is_array ( $actived_plugins )) {
                if (in_array ( $id, $actived_plugins )) {
                    if (($key = array_search ( $id, $actived_plugins )) !== false) {
                        unset ( $actived_plugins [$key] );
                        update_option ( 'active_plugins', $actived_plugins );
                    }
                }
            }
            $plugin_file = PLUGIN_PATH . '/' . $id;
            $plugin = explode ( '/', trim ( $id ) );
            if (is_array ( $plugin ) && count ( $plugin ) == 2) {
                $plugin_names = explode('-', $plugin [0]);
                $plugin_names = array_map('strtolower', $plugin_names);
                $plugin_names = array_map('ucfirst', $plugin_names);
                $class = implode('_', $plugin_names) . '_Plugin';
                if (! class_exists ( $class )) {
                    require $plugin_file;
                }
                if (class_exists ( $class )) {
                    $object = new $class ();
                    if (method_exists ( $object, 'deactivate' )) {
                        $object->deactivate ();
                    }
                }
            }
        }else if($status == 'activate'){;
            $plugins = get_plugins ( PLUGIN_PATH );
            if (array_key_exists ( $id, $plugins )) {
                $actived_plugins = get_option ( 'active_plugins' );
                if (! empty ( $actived_plugins ) && is_array ( $actived_plugins )) {
                    if (! in_array ( $id, $actived_plugins )) {
                        $actived_plugins [] = $id;
                        update_option ( 'active_plugins', $actived_plugins );
                    }
                } else {
                    $actived_plugins = array ();
                    $actived_plugins [] = $id;
                    update_option ( 'active_plugins', $actived_plugins );
                }
            }
            
            
            $plugin_file = PLUGIN_PATH . '/' . $id;
            $plugin = explode ( '/', trim ( $id ) );
            if (is_array ( $plugin ) && count ( $plugin ) == 2) {
                $plugin_names = explode('-', $plugin [0]);
                $plugin_names = array_map('strtolower', $plugin_names);
                $plugin_names = array_map('ucfirst', $plugin_names);
                $class = implode('_', $plugin_names) . '_Plugin';
                if (! class_exists ( $class )) {
                    require $plugin_file;
                }
                if (class_exists ( $class )) {
                    $object = new $class ();
                    if (method_exists ( $object, 'activate' )) {
                        $object->activate ();
                    }
                }
            }
            
        }
    }
    private function search_title($data, $condition) {
        $input = preg_quote(strtolower($condition), '~');
        $result = preg_grep('~' . $input . '~', $data);
        return $result;
    }
    
    private function search_status($data, $condtion) {
        $status = array();
        foreach ($data as $key => $value) {
            foreach ($condtion as $key1 => $value1)
            if ($value == $value1) {
                $status[$key] = $condtion[$key1];
            }
        }
        return $status;
    }
    
    private function search_by_key($data, $condition, $key_search) {
        foreach ($data as $key => $value){
            $lowercase_data = strtolower($value['name']);
            $data_get[$key] = $lowercase_data;
        }
        $result_search = is_string($condition) ? $this->search_title($data_get, $condition) : $this->search_status($data_get, $condition);
        $data_search = array();
        if (count($result_search)) {
            $data_search = array_intersect_key($data, $result_search);
        }
    
        return $data_search;
    }
    
    private function pagination($data, $curent_page) {
        if (is_array($data)) {
            $total = count($data);
            $total_page = ceil($total / NUM_PER_PAGE);
            if ($curent_page > $total_page) {
                $curent_page = $total_page;
            }
    
            $start = ($curent_page - 1) * NUM_PER_PAGE;
            return array_slice($data, $start, NUM_PER_PAGE);
        }
    }
    
    public function load(){
        if (is_ajax()){
            require ABSPATH . '/'.ADMIN_FOLDER.'/themes/default/sidebar-menu.php';
        }
    }
}
