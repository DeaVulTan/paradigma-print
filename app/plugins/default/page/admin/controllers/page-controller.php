<?php
defined ( 'PF_VERSION' ) or header ( 'Location:404.html' );
class Page_Controller extends Pf_Controller{
    public function __construct(){
        parent::__construct();
        $this->load_model('page');
        $this->model = new Pf_Plugin_Model;
        $this->view->menu_settings = get_option('admin_menu_setting');
        $this->page_model->rules = Pf::event()->trigger("filter","page-validation-rule",$this->page_model->rules);
    }
    public function index(){
        $params = array();
        $where = '';
        $where_values = array();
        $operator = '';
        
        $params['limit'] = NUM_PER_PAGE;
        $params['page_index'] = (isset($this->get->{$this->page}))?(int)$this->get->{$this->page}:1;
        
        $operator = '';
        
        $where = $operator.' `page_system` = ?';
        $where_values[] = '0';
        $operator = ' AND ';

        if (empty($this->get->search)){
                $this->get->search = array();
        }

        if (isset($this->get->search["page_title"]) && trim($this->get->search["page_title"]) != ""){
            $where .= $operator.' `page_title` like ? ';
            $where_values[] = '%'.$this->get->search["page_title"].'%';
            $operator = ' AND ';
        }

        if (isset($this->get->search["page_created_date"]) && trim($this->get->search["page_created_date"]) != ""){
            $where .= $operator.' `page_created_date` = ? ';
            $where_values[] = str_to_mysqldate($this->get->search["page_created_date"],$this->page_model->elements_value["page_created_date"],"Y-m-d H:i:s");
            $operator = ' AND ';
        }

        //view all status
        if (isset($this->post->action) && trim($this->post->action) != ""){
            $where .= $operator.' `page_status` = ? ';
            $where_values[] = $this->post->id;
        }
        
        //Search Status
        if (isset($this->get->search["page_status"])){
            if (is_array($this->get->search["page_status"])){
                if (!empty($this->get->search["page_status"])){
                    $where .= $operator.' ( ';
                    $operator1 = '';
                    foreach($this->get->search["page_status"] as $v){
                        $where .= $operator1.'  `page_status` = ?  OR `page_status` like ?  OR `page_status` like ? OR `page_status` like ? ';
                        $where_values[] = $v;
                        $where_values[] = $v.',%';
                        $where_values[] = '%,'.$v.',%';
                        $where_values[] = '%,'.$v;
                        $operator1 = ' OR ';
                    }
                    $where .= ' ) ';
                    $operator = ' AND ';
                }
            }else{
                if (trim($this->get->search["page_status"]) != ""){
                    $where .= $operator.' (  `page_status` = ?  OR `page_status` like ?  OR `page_status` like ? OR `page_status` like ? )';
                    $where_values[] = $this->get->search["page_status"];
                    $where_values[] = $this->get->search["page_status"].',%';
                    $where_values[] = '%,'.$this->get->search["page_status"].',%';
                    $where_values[] = '%,'.$this->get->search["page_status"];
                    $operator = ' AND ';
                }
            }
        }
        
        
        if (!empty($where_values)){
            $params['where'] = array($where,$where_values);
        }
        
        
        if (!empty($this->get->order_field) && !empty($this->get->order_type)){
            $params["order"] = "`".Pf::database ()->escape($this->get->order_field)."` ".Pf::database ()->escape($this->get->order_type);
        }

        $params = Pf::event()->trigger("filter","page-index-params",$params);

        
        $this->view->page_index = $params['page_index'];
        $this->view->records = $this->page_model->fetch($params,true);
        $this->view->total_records = $this->page_model->found_rows();
        $total_page = ceil($this->view->total_records/NUM_PER_PAGE);
        if (empty($this->view->records) && $total_page > 0){
            $this->get->{$this->page} = $params['page_index'] = $total_page; 
            $this->view->page_index = $params['page_index'];
            $this->view->records = $this->page_model->fetch($params,true);
            $this->view->total_records = $this->page_model->found_rows();
        }
        $this->view->pagination = new Pf_Paginator($this->view->total_records, NUM_PER_PAGE, $this->page);
        
        $template = null;
        $template = Pf::event()->trigger("filter","page-index-template",$template);
        
        $this->view->render($template);
    }
    
    public function add(){
        $this->page_model->rules = Pf::event()->trigger("filter","page-adding-validation-rule",$this->page_model->rules);
        
        $template = null;
        $template = Pf::event()->trigger("filter","page-add-template",$template);
        
        if ($this->request->is_post()){
            $data = array();
            $data = array(
                "page_title" => e($this->post->{"page_title"}),
                "page_url" => $this->post->{"url"},
                "page_layout" => $this->post->{"page_layout"},
                "page_type" => $this->post->{"page_type"},
                "page_meta_title" => e($this->post->{"page_meta_title"}),
                "page_meta_keywords" => e($this->post->{"page_meta_keywords"}),
                "page_meta_description" => e($this->post->{"page_meta_description"}),
                "page_content" => str_replace("<br class=\"br\" />", "\n", $this->post->{"page_content"}),
                "page_status" => $this->post->{"page_status"},
                "page_created_date" => date("Y-m-d H:i:s")
            );
            
            $visible_users = e($this->post->{"visible_users_data"});
            $visible_groups = $this->post->{"visible_group"};
            $theme_options = $this->theme_option();
            
            $data['page_visible'] = json_encode(array(
                'users' => json_decode($visible_users, true),
                'groups' => $visible_groups
            ));
            $data['page_theme_options'] = $theme_options;
            
            $data = Pf::event()->trigger("filter","page-post-data",$data);
            $data = Pf::event()->trigger("filter","page-adding-post-data",$data);
            
            $var = array();
            
            //check url
            if($this->post->{"url"} != NULL){
                $param = array();
                if (isset($this->post->{"url"}) && $this->post->{"url"} != NULL) {
                    $conditions = "page_url = ? ";
                    $arr_param[] = $this->post->{"url"};
                }
                
                if (isset($this->post->{"page_id"}) && $this->post->{"page_id"} != NULL) {
                    $conditions .= " and id != ? ";
                    $arr_param[] = $this->post->{"page_id"};
                }
                
                $params['where'] = array($conditions,$arr_param);
                $count_url = $this->page_model->fetch($params,true);
                $total_records = $this->page_model->found_rows();
                $var['error_url'] = $total_records;
                $var['content'] = $this->view->fetch($template);
            }
            
            if ($var['error_url'] == 0 && !$this->page_model->insert($data)){
                $this->view->errors = $this->page_model->errors;
                $var['content'] = $this->view->fetch($template);
                $var['error'] = 1;
                
            }else{
                Pf::event()->trigger("action","page-add-successfully",$this->page_model->insert_id(),$data);
                $var['error'] = 0;
                $var['url'] = admin_url($this->action.'=index&ajax=&id=&token=');
            }
            
            echo json_encode($var);
        }else{
        
            $this->view->render($template);
        }
    }
    
    public function edit(){
        $this->page_model->rules = Pf::event()->trigger("filter","page-editing-validation-rule",$this->page_model->rules);
        $template = null;
        $template = Pf::event()->trigger("filter","page-edit-template",$template);
        
        $var = array();
        
        if (isset($this->get->id) && isset($this->get->token) && Pf_Plugin_CSRF::is_valid($this->get->token, $this->key.$this->get->id)){
            if ($this->request->is_post()){
                $data = array();
                $data = array(
                    "id" => $this->get->id,
                    "page_title" => e($this->post->{"page_title"}),
	                "page_url" => $this->post->{"url"},
	                "page_layout" => $this->post->{"page_layout"},
	                "page_type" => $this->post->{"page_type"},
	                "page_meta_title" => e($this->post->{"page_meta_title"}),
	                "page_meta_keywords" => e($this->post->{"page_meta_keywords"}),
	                "page_meta_description" => e($this->post->{"page_meta_description"}),
                    "page_content" => str_replace("<br class=\"br\" />", "\n", $this->post->{"page_content"}),
                    "page_status" => $this->post->{"page_status"},
                    "page_modified_date" => date("Y-m-d H:i:s")
                );
                
                $visible_users = e($this->post->{"visible_users_data"});
                $visible_groups = $this->post->{"visible_group"};
                $theme_options = $this->theme_option();
                
                $data['page_visible'] = json_encode(array(
                    'users' => json_decode($visible_users, true),
                    'groups' => $visible_groups
                ));
                $data['page_theme_options'] = $theme_options;
               
                $data = Pf::event()->trigger("filter","page-post-data",$data);
                $data = Pf::event()->trigger("filter","page-editing-post-data",$data);
                
                $this->view->token = Pf_Plugin_CSRF::token($this->key.$this->get->id);
                
                //check url
                if($this->post->{"url"} != NULL){
                    $param = array();
                    if (isset($this->post->{"url"}) && $this->post->{"url"} != NULL) {
                        $conditions = "page_url = ? ";
                        $arr_param[] = $this->post->{"url"};
                    }
                
                    if (isset($this->post->{"page_id"}) && $this->post->{"page_id"} != NULL) {
                        $conditions .= " and id != ? ";
                        $arr_param[] = $this->post->{"page_id"};
                    }
                
                    $params['where'] = array($conditions,$arr_param);
                    $count_url = $this->page_model->fetch($params,true);
                    $total_records = $this->page_model->found_rows();
                    $var['error_url'] = $total_records;
                    $var['content'] = $this->view->fetch($template);
                }
                
                if ($var['error_url'] == 0 && !$this->page_model->save($data)){
                    $this->view->errors = $this->page_model->errors;
                    $var['content'] = $this->view->fetch($template);
                    $var['error'] = 1;
                }else{
                    $params = array();
                    $params['where'] = array('id=?',array((int)$this->get->id));
                    $data = $this->page_model->fetch_one($params);
                    
                    Pf::event()->trigger("action","page-edit-successfully",$data);
                    $var['error'] = 0;
                    $this->post->datas($data);
                    $var['content'] = $this->view->fetch($template);
                    $var['url'] = admin_url($this->action.'=index&ajax=&id=&token=');
                }
            }else{
                if (isset($this->get->id)){
                    $params = array();
                    $params['where'] = array('id=?',array((int)$this->get->id));
                    $data = $this->page_model->fetch_one($params);
                    
                    
                    $data = Pf::event()->trigger("filter","page-database-data",$data);
                    $data = Pf::event()->trigger("filter","page-editing-database-data",$data);
                    
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
    
    public function copy(){
        $this->page_model->rules = Pf::event()->trigger("filter","page-copy-validation-rule",$this->page_model->rules);
    
        $template = null;
        $template = Pf::event()->trigger("filter","page-edit-template",$template);
        
        $var = array();
        
        if (isset($this->get->id) && isset($this->get->token) && Pf_Plugin_CSRF::is_valid($this->get->token, $this->key.$this->get->id)){
            if ($this->request->is_post()){
                $data = array();
                
                $data = array(
                    "page_title" => e($this->post->{"page_title"}),
	                "page_url" => $this->post->{"url"},
	                "page_layout" => $this->post->{"page_layout"},
	                "page_type" => $this->post->{"page_type"},
	                "page_meta_title" => e($this->post->{"page_meta_title"}),
	                "page_meta_keywords" => e($this->post->{"page_meta_keywords"}),
	                "page_meta_description" => e($this->post->{"page_meta_description"}),
                    "page_content" => str_replace("<br class=\"br\" />", "\n", $this->post->{"page_content"}),
                    "page_status" => $this->post->{"page_status"},
                    "page_created_date" => date("Y-m-d H:i:s")
                );

                $visible_users = e($this->post->{"visible_users_data"});
                $visible_groups = $this->post->{"visible_group"};
                $theme_options = $this->theme_option();
                
                $data['page_visible'] = json_encode(array(
                        'users' => json_decode($visible_users, true),
                        'groups' => $visible_groups
                ));
                $data['page_theme_options'] = $theme_options;
                
                $data = Pf::event()->trigger("filter","page-post-data",$data);
                $data = Pf::event()->trigger("filter","page-copy-post-data",$data);
                
                $this->view->token = Pf_Plugin_CSRF::token($this->key.$this->get->id);
                
                //check url
                if($this->post->{"url"} != NULL){
                    $param = array();
                    if (isset($this->post->{"url"}) && $this->post->{"url"} != NULL) {
                        $conditions = "page_url = ? ";
                        $arr_param[] = $this->post->{"url"};
                    }
                
                    if (isset($this->post->{"page_id"}) && $this->post->{"page_id"} != NULL) {
                        $conditions .= " and id != ? ";
                        $arr_param[] = $this->post->{"page_id"};
                    }
                
                    $params['where'] = array($conditions,$arr_param);
                    $count_url = $this->page_model->fetch($params,true);
                    $total_records = $this->page_model->found_rows();
                    $var['error_url'] = $total_records;
                    $var['content'] = $this->view->fetch($template);
                }
                
                if ($var['error_url'] == 0 && !$this->page_model->insert($data)){
                    $this->view->errors = $this->page_model->errors;
                    $var['content'] = $this->view->fetch($template);
                    $var['error'] = 1;
                }else{
                    $params = array();
                    $params['where'] = array('id=?',array((int)$this->get->id));
                    $data = $this->page_model->fetch_one($params);
                    
                    Pf::event()->trigger("action","page-copy-successfully",$data);
                    $var['error'] = 0;
                    $this->post->datas($data);
                    $var['content'] = $this->view->fetch($template);
                    $var['url'] = admin_url($this->action.'=index&ajax=&id=&token=');
                }
                
            }else{
                if (isset($this->get->id)){
                    $params = array();
                    $params['where'] = array('id=?',array((int)$this->get->id));
                    $data = $this->page_model->fetch_one($params);
                    
                    
                    $data = Pf::event()->trigger("filter","page-database-data",$data);
                    $data = Pf::event()->trigger("filter","page-copy-database-data",$data);
                    
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
    
    public function bulk_action(){
        $var = array();
        if (Pf_Plugin_CSRF::is_valid($this->post->token,$this->key)){
            switch ($this->get->type){
                case 'delete':
                    if (!empty($this->post->id) && is_array($this->post->id)){
                        foreach ($this->post->id as $id){
                            $this->page_model->delete('id=?',array($id));
                        }
                    }
                break;
                case 'publish':
                
                    if (!empty($this->post->id) && is_array($this->post->id)){
                        foreach ($this->post->id as $id){
                            $params['where'] = array('id=?',array($id));
                            $data = $this->page_model->fetch_one($params);
                            $data['page_status'] = 1;
                            $this->page_model->save($data);
                        }
                    }
                    $var['action'] = 'publish';
                break;
                case 'unpublish':
                    if (!empty($this->post->id) && is_array($this->post->id)){
                        foreach ($this->post->id as $id){
                            $params['where'] = array('id=?',array($id));
                            $data = $this->page_model->fetch_one($params);
                            $data['page_status'] = 2;
                            $this->page_model->save($data);
                        }
                    }
                    $var['action'] = 'unpublish';
                break;
            }
            Pf::event()->trigger("action","page-bulk-action-successfully",$this->get->type,$this->post->id);
            $var['error'] = 0;
        }else{
            $var['error'] = 1;
        }
        
        $var['url'] = admin_url($this->action.'=index&ajax=&id=&token=&type=');
        
        echo json_encode($var);
    }
    
    public function delete(){
        $var = array();
        if (isset($this->get->id) && isset($this->get->token) && Pf_Plugin_CSRF::is_valid($this->get->token, $this->key.$this->get->id)){
            if ($this->page_model->delete('id=?',array($this->get->id))){
                $var['error'] = 0;
                Pf::event()->trigger("action","page-delete-successfully",$this->get->id);
            }else{
                $var['error'] = 1;
            }
        }
        
        $var['url'] = admin_url($this->action.'=index&ajax=&id=&token=');
        
        echo json_encode($var);
    }
    
    public function change_status() {
        $data = array();
        $status = $this->post->status;
        $params = array();
        $params['where'] = array('id=?',array((int)$this->post->id));
        $data = $this->page_model->fetch_one($params);
    
        switch ($status) {
            case 'publish':
                $data['page_status'] = 1;
                $this->page_model->save($data);
                break;
            case 'unpublish':
                $data['page_status'] = 2;
                $this->page_model->save($data);
                break;
        }
    }
    
    
    private function theme_option() {
        $theme_options = array();
        $val = array(
            "type" => $this->post->{"background"},
            "color" => e($this->post->{"background-color"}),
            "image" => e($this->post->{"background-image"}),
            "position" => e($this->post->{"background-position"}),
            "repeat" => $this->post->{"background-repeat"},
            "attachment" => $this->post->{"background-attachment"},
        );
        $background = get_theme_background($val['type'], $val['color'], $val['image'], $val['position'], $val['repeat']);
        $theme_options = array(
            "data" => $val,
            "background" => $background,
            "wrapper_background" => e($this->post->{"wrapper-background-color"})
        );
        return json_encode($theme_options);
    }
}