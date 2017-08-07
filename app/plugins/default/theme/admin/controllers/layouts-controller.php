<?php
defined ( 'PF_VERSION' ) or header ( 'Location:404.html' );
class Layouts_Controller extends Pf_Controller {
    public function __construct() {
        parent::__construct ();
        $this->load_model ( 'layouts' );
        $this->view->menu_settings = get_option ( 'admin_menu_setting' );
    }
    public function index() {
        $params [] = array ();
        $where = '';
        $where_values = array ();
        $operator = '';
        $data = '';
        
        $layouts = $data = get_option ( 'layouts' );
        $theme = get_option ( 'active_theme' );
        $params ['limit'] = NUM_PER_PAGE;
        $params ['page_index'] = (isset ( $this->get->{$this->page} )) ? ( int ) $this->get->{$this->page} : 1;
        
        $operator = '';
        $url = admin_url ( 'admin-page=themes&sub_page=layouts', false );
        
        // search
        if (isset ( $this->get->search ["name"] )) {
            $name = $this->get->search ["name"];
            $data = $this->search_by_key ( $data, $name, 'layout_name' );
        }
        if (! empty ( $this->get->order_field ) && ! empty ( $this->get->order_type )) {
            if ($this->get->order_field == 'layout_name' && $this->get->order_type == 'asc') {
                asort ( $data );
            } else if ($this->get->order_field == 'layout_name' && $this->get->order_type == 'desc') {
                arsort ( $data );
            }
        }
        $this->view->page_index = $params ['page_index'];
        
        $this->view->records = $data;
        $this->view->total_records = count ( $data );
        $total_page = ceil ( $this->view->total_records / NUM_PER_PAGE );
        $this->view->pagination = new Pf_Paginator ( $this->view->total_records, NUM_PER_PAGE, $this->page );
        
        if (! empty ( $this->view->records ) && $total_page > 1) {
            $tesst = $this->pagination ( $data, $params ['page_index'] );
            $this->view->records = $tesst;
        } else {
            $this->view->records = $data;
        }
        
        $template = null;
        $template = Pf::event ()->trigger ( "filter", "layout-index-template", $template );
        $this->view->render ( $template );
    }
    
    public function get_pattern() {
        $this->view->render ( 'get-pattern' );
    }
    public function add() {
        $template = null;
        $template = Pf::event ()->trigger ( "filter", "layout-add-template", $template );
        $this->view->activate_widgets = $this->get_activated_widgets ();
        $this->view->list_widgets = $this->get_list_widgets ();
        $pattern = $data['pattern'] = $this->post->pattern;
        $this->post->datas ($data);
        $this->view->render ('add');
    }
    
    public function edit() {
        //debug(get_option ( 'layouts' ));
        $this->layouts_model->rules = Pf::event()->trigger("filter","layouts-editing-validation-rule",$this->layouts_model->rules);
        $template = null;
        $template = Pf::event()->trigger("filter","layouts-edit-template",$template);
        
        $var = array();
        
        if (isset($this->get->id) && isset($this->get->token)){
            if (isset($this->get->id)){
                $layouts = get_option ('layouts');
                $layout = array ();
                foreach ($layouts as $v) {
                    if ($this->get->id == $v['id']) {
                        $layout = $v;
                        break;
                    }
                }
                $data['setting_data'] = json_encode($layout['setting_data']);
                $_POST = $layout;
                $data['pattern'] = (! empty ($this->post->{"pattern"})) ? $this->post->{"pattern"} : 1;
                $data['activate_widgets'] = $this->get_activated_widgets ();
                $data['list_widgets'] = $this->get_list_widgets ();
                
                $data = Pf::event()->trigger("filter","layouts-database-data",$data);
                $data = Pf::event()->trigger("filter","layouts-editing-database-data",$data);
                $this->post->datas($data);
                $this->view->token = Pf_Plugin_CSRF::token($this->key.$this->get->id);
                $var['content'] = $this->view->fetch($template);
                $var['error'] = 0;
            }else{
                $var['error'] = 1;
                $var['url'] = admin_url($this->action.'=index&ajax=&id=&token=');
            }
        }else{
            $var['error'] = 1;
            $var['url'] = admin_url($this->action.'=index&ajax=&id=&token=');
        }
        
        echo json_encode($var);
    }
    
    public function copy(){
        $this->layouts_model->rules = Pf::event()->trigger("filter","layouts-copy-validation-rule",$this->layouts_model->rules);
    
        $template = null;
        $template = Pf::event()->trigger("filter","layouts-edit-template",$template);
    
        $var = array();
    
        if (isset($this->get->id) && isset($this->get->token)){
            if ($this->request->is_post()){
                $data = array();
    
                $layouts = array (
                    'layout_name' => $this->post->{"layout_name"},
                    'layout_type' => $this->post->{"layout_type"},
                    'pattern' => $this->post->{"pattern"},
                    'json_data' => $this->post->{"json_data"},
                    'setting_data' => json_decode($this->post->{"setting_data"},true)
                );
                
                $data = get_option ( 'layouts' );
                if ($data == null) {
                    $data = array ();
                }
                $layouts['id'] = uniqid ();
                $data[] = $layouts;
    
                $data = Pf::event()->trigger("filter","layouts-post-data",$data);
                $data = Pf::event()->trigger("filter","layouts-copy-post-data",$data);
    
                $this->view->token = Pf_Plugin_CSRF::token($this->key.$this->get->id);
    
                if (! update_option ( 'layouts', $data )) {
                    $this->view->errors = $this->layout_model->errors;
                    $var ['content'] = $this->view->fetch ( $template );
                    $var ['error'] = 1;
                } else {
                    Pf::event ()->trigger ( "action", "layout-add-successfully", $this->layouts_model->insert_id (), $data );
                    $var ['error'] = 0;
                    $var ['url'] = admin_url ( $this->action . '=index&ajax=&id=&token=' );
                }
    
            }else{
                if (isset($this->get->id)){
                    $layouts = get_option ('layouts');
                    $layout = array ();
                    foreach ($layouts as $v) {
                        if ($this->get->id == $v['id']) {
                            $layout = $v;
                            break;
                        }
                    }
                    $data['setting_data'] = json_encode($layout['setting_data']);
                    $_POST = $layout;
                    $data['pattern'] = (! empty ($this->post->{"pattern"})) ? $this->post->{"pattern"} : 1;
                    $data['activate_widgets'] = $this->get_activated_widgets ();
                    $data['list_widgets'] = $this->get_list_widgets ();
    
    
                    $data = Pf::event()->trigger("filter","layouts-database-data",$data);
                    $data = Pf::event()->trigger("filter","layouts-copy-database-data",$data);
    
                    $this->post->datas($data);
                    $this->view->token = Pf_Plugin_CSRF::token($this->key.$this->get->id);
                    $var['content'] = $this->view->fetch($template);
                    $var['error'] = 0;
                }else{
                    $var['error'] = 1;
                    $var['url'] = admin_url($this->action.'=index&ajax=&id=&token=');
                }
            }
        }else{
            $var['error'] = 1;
            $var['url'] = admin_url($this->action.'=index&ajax=&id=&token=');
        }
    
        echo json_encode($var);
    }
    
    public function save() {
        $template = null;
        $template = Pf::event ()->trigger ( "filter", "layout-add-template", $template );
        if ($this->request->is_post ()) {
            $layouts = array (
                'layout_name' => $this->post->{"layout_name"},
                'layout_type' => $this->post->{"layout_type"},
                'pattern' => $this->post->{"pattern"},
                'json_data' => $this->post->{"json_data"},
                'setting_data' => json_decode($this->post->{"setting_data"},true)
            );
            
            $data = get_option ( 'layouts' );
            if ($data == null) {
                $data = array ();
            }
            if (empty ($this->post->{"id"})){
                $layouts['id'] = uniqid ();
                $data[] = $layouts;
            } else {
                $layouts['id'] = $this->post->{"id"};
                if (! empty ( $data )) {
                    foreach ( $data as $k => $v ) {
                        if ($layouts ['id'] == $v['id']) {
                            $data [$k] = $layouts;
                            break;
                        }
                    }
                }
            }
            
            $data = Pf::event ()->trigger ( "filter", "layout-post-data", $data );
            $data = Pf::event ()->trigger ( "filter", "layout-adding-post-data", $data );
            
            $var = array ();
            if (! update_option ( 'layouts', $data )) {
                $this->view->errors = $this->layout_model->errors;
                $var ['content'] = $this->view->fetch ( $template );
                $var ['error'] = 1;
            } else {
                Pf::event ()->trigger ( "action", "layout-add-successfully", $this->layouts_model->insert_id (), $data );
                $var ['error'] = 0;
                $var ['url'] = admin_url ( $this->action . '=index&ajax=&id=&token=' );
            }
            
            echo json_encode ( $var );
        } else {
            ;
            $this->post->datas ( $data );
            $this->view->render ( $template );
        }
    }
    public function delete() {
        $var = array ();
        if (isset ( $this->get->id ) && isset ( $this->get->token ) && Pf_Plugin_CSRF::is_valid ( $this->get->token, $this->key . $this->get->id )) {
            $data_layout = get_option ( 'layouts' );
            foreach ( $data_layout as $key => $value ) {
                if ($key == $this->get->id) {
                    unset ( $data_layout [$key] );
                    $var ['error'] = 0;
                    Pf::event ()->trigger ( "action", "layout-delete-successfully", $this->get->id );
                    update_option ( 'layouts', $data_layout );
                    break;
                } else {
                    $var ['error'] = 1;
                }
            }
        
        }
        $var ['url'] = admin_url ( $this->action . '=index&ajax=&id=&token=' );
        echo json_encode ( $var );
    }
    
    public function setting_form(){
        if (is_ajax()){
            $widget = $this->post->{"widget"};
            if (!empty($this->post->{"data"})){
                $_POST = $this->post->{"data"};
            }
            if (!empty($widget['id'])){
                $widget_id = str_replace('widget_', '', strtolower($widget['id']));
        
                $widget_id = strtolower($widget_id);
                $widget_ary = explode('-', $widget_id);
                $widget_class = '';
                foreach ($widget_ary as $v){
                    $widget_class .= ucfirst($v).'_';
                }
                $widget_class .= 'Widget';
        
                $widgets = array();
                $plugins = get_all_actived_plugins();
                foreach ($plugins['default'] as $plugin){
                    $plugin_ary = explode('/', $plugin);
                    $widgets = array_merge($widgets, get_widgtes(PLUGIN_PATH.'/'.$plugin_ary[0].'/widgets'));
                }
        
                foreach ($plugins['admin'] as $plugin){
                    $plugin_ary = explode('/', $plugin);
                    $widgets = array_merge($widgets, get_widgtes(DEFAULT_PLUGIN_PATH.'/'.$plugin_ary[0].'/widgets'));
                }
        
                $path_theme_widget = isset($widgets[$widget_id])?$widgets[$widget_id]['file']:'';
        
                if (!class_exists($widget_class)){
                    if (is_file($path_theme_widget)){
                        require $path_theme_widget;
                    }else{
                        // Error: Widget class is not found.
                    }
                }
                if (class_exists($widget_class) && get_parent_class($widget_class) == 'Pf_Widget'){
                    $widget_object = new $widget_class($widget,array());
                    if (method_exists($widget_object, 'setting')){
                        $widget_object->custom_template = 'setting';
                        echo '<form role="form">';
                        $widget_object->setting();
                        require dirname(dirname(__FILE__)).'/views/layouts/setting-form.php';
                        echo '</form>';
                    }else{
                        // Error: Widget main method is not found.
                    }
                }else{
                    // Error: Widget class must extend Pf_Widget .
                }
            }else{
                // Error: Widget don't have the id.
            }
        }
    }
    private function search_title($data, $condition) {
        $input = preg_quote ( strtolower ( $condition ), '~' );
        $result = preg_grep ( '~' . $input . '~', $data );
        return $result;
    }
    
    private function search_status($data, $condtion) {
        $status = array ();
        foreach ( $data as $key => $value ) {
            foreach ( $condtion as $key1 => $value1 )
                if ($value == $value1) {
                    $status [$key] = $condtion [$key1];
                }
        }
        return $status;
    }
    
    private function search_by_key($data, $condition, $key_search) {
        foreach ( $data as $key => $value ) {
            $lowercase_data = strtolower ( $value ['layout_name'] );
            $data_get [$key] = $lowercase_data;
        }
        $result_search = is_string ( $condition ) ? $this->search_title ( $data_get, $condition ) : $this->search_status ( $data_get, $condition );
        $data_search = array ();
        if (count ( $result_search )) {
            $data_search = array_intersect_key ( $data, $result_search );
        }
        
        return $data_search;
    }
    
    private function pagination($data, $curent_page) {
        if (is_array ( $data )) {
            $total = count ( $data );
            $total_page = ceil ( $total / NUM_PER_PAGE );
            if ($curent_page > $total_page) {
                $curent_page = $total_page;
            }
            
            $start = ($curent_page - 1) * NUM_PER_PAGE;
            return array_slice ( $data, $start, NUM_PER_PAGE );
        }
    }
    public function load() {
        if (is_ajax ()) {
            require ABSPATH . '/' . ADMIN_FOLDER . '/themes/default/sidebar-menu.php';
        }
    }
    private function get_activated_widgets() {
        $active_widgets = get_option ( 'active_widgets' );
        if (! is_array ( $active_widgets )) {
            $active_widgets = array ();
        }
        if (! is_array ( $active_widgets )) {
            $active_widgets = array ();
        }
        return $active_widgets;
    }
    private function get_list_widgets() {
        $widgets = array ();
        
        $plugins = get_all_actived_plugins ();
        foreach ( $plugins ['default'] as $plugin ) {
            $plugin_ary = explode ( '/', $plugin );
            $widgets = array_merge ( $widgets, get_widgtes ( PLUGIN_PATH . '/' . $plugin_ary [0] . '/widgets' ) );
        }
        
        foreach ( $plugins ['admin'] as $plugin ) {
            $plugin_ary = explode ( '/', $plugin );
            $widgets = array_merge ( $widgets, get_widgtes ( DEFAULT_PLUGIN_PATH . '/' . $plugin_ary [0] . '/widgets' ) );
        }
        return $widgets;
    }

}
