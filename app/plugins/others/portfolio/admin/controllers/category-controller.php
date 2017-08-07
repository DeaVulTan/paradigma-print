<?php
defined ( 'PF_VERSION' ) or header ( 'Location:404.html' );
class Category_Controller extends Pf_Controller{
    public function __construct(){
        parent::__construct();
        $this->load_model('category');
        $this->view->menu_settings = get_option('admin_menu_setting');
        $this->category_model->rules = Pf::event()->trigger("filter","category-validation-rule",$this->category_model->rules);
    }
    public function index(){
        $params = array();
        $where = '';
        $where_values = array();
        $operator = '';
        
        $params['limit'] = NUM_PER_PAGE;
        $params['page_index'] = (isset($this->get->{$this->page}))?(int)$this->get->{$this->page}:1;
        
        
        $operator = '';
        
        if (empty($this->get->search)){
                $this->get->search = array();
        }

        if (isset($this->get->search["category_name"]) && trim($this->get->search["category_name"]) != ""){
            $where .= $operator.' `category_name` like ? ';
            $where_values[] = '%'.$this->get->search["category_name"].'%';
            $operator = ' AND ';
        }

        if (isset($this->get->search["category_description"]) && trim($this->get->search["category_description"]) != ""){
            $where .= $operator.' `category_description` like ? ';
            $where_values[] = '%'.$this->get->search["category_description"].'%';
            $operator = ' AND ';
        }
        if (isset($this->post->action) && trim($this->post->action) != ""){
            $where .= $operator.' `category_status` = ? ';
            $where_values[] = $this->post->id;
        }
        if (isset($this->get->search["category_status"])){
            if (is_array($this->get->search["category_status"])){
                if (!empty($this->get->search["category_status"])){
                    $where .= $operator.' ( ';
                    $operator1 = '';
                    foreach($this->get->search["category_status"] as $v){
                        $where .= $operator1.'  `category_status` = ?  OR `category_status` like ?  OR `category_status` like ? OR `category_status` like ? ';
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
                if (trim($this->get->search["category_status"]) != ""){
                    $where .= $operator.' (  `category_status` = ?  OR `category_status` like ?  OR `category_status` like ? OR `category_status` like ? )';
                    $where_values[] = $this->get->search["category_status"];
                    $where_values[] = $this->get->search["category_status"].',%';
                    $where_values[] = '%,'.$this->get->search["category_status"].',%';
                    $where_values[] = '%,'.$this->get->search["category_status"];
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

        $params = Pf::event()->trigger("filter","category-index-params",$params);

        
        $this->view->page_index = $params['page_index'];
        $this->view->records = $this->category_model->fetch($params,true);
        $this->view->total_records = $this->category_model->found_rows();
        $total_page = ceil($this->view->total_records/NUM_PER_PAGE);
        if (empty($this->view->records) && $total_page > 0){
            $this->get->{$this->page} = $params['page_index'] = $total_page; 
            $this->view->page_index = $params['page_index'];
            $this->view->records = $this->category_model->fetch($params,true);
            $this->view->total_records = $this->category_model->found_rows();
        }
        $this->view->pagination = new Pf_Paginator($this->view->total_records, NUM_PER_PAGE, $this->page);
        
        $template = null;
        $template = Pf::event()->trigger("filter","category-index-template",$template);
        
        $this->view->render($template);
    }
    
    public function add(){
        $this->category_model->rules = Pf::event()->trigger("filter","category-adding-validation-rule",$this->category_model->rules);
        
        $template = null;
        $template = Pf::event()->trigger("filter","category-add-template",$template);
        
        if ($this->request->is_post()){
            $data = array();
            
            
                $data["category_name"] = e($this->post->{"category_name"});
                $data["category_description"] = e($this->post->{"category_description"});
                if (is_array($this->post->{"category_status"})){
                    $data["category_status"] = implode(",",$this->post->{"category_status"});
                }else{
                    $data["category_status"] = $this->post->{"category_status"};
                }
            
            $data = Pf::event()->trigger("filter","category-post-data",$data);
            $data = Pf::event()->trigger("filter","category-adding-post-data",$data);
            
            $var = array();
            if (!$this->category_model->insert($data)){
                $this->view->errors = $this->category_model->errors;
                $var['content'] = $this->view->fetch($template);
                $var['error'] = 1;
            }else{
                Pf::event()->trigger("action","category-add-successfully",$this->category_model->insert_id(),$data);
                $var['error'] = 0;
                $var['url'] = admin_url($this->action.'=index&ajax=&id=&token=');
            }
            
            echo json_encode($var);
        }else{
        
            $this->view->render($template);
        }
    }
    
    public function edit(){
        $this->category_model->rules = Pf::event()->trigger("filter","category-editing-validation-rule",$this->category_model->rules);
        $template = null;
        $template = Pf::event()->trigger("filter","category-edit-template",$template);
        
        $var = array();
        
        if (isset($this->get->id) && isset($this->get->token) && Pf_Plugin_CSRF::is_valid($this->get->token, $this->key.$this->get->id)){
            if ($this->request->is_post()){
                $data = array();
                $data['id'] = $this->get->id;
                
                
                $data["category_name"] = e($this->post->{"category_name"});
                $data["category_description"] = e($this->post->{"category_description"});
                if (is_array($this->post->{"category_status"})){
                    $data["category_status"] = implode(",",$this->post->{"category_status"});
                }else{
                    $data["category_status"] = $this->post->{"category_status"};
                }
                
                $data = Pf::event()->trigger("filter","category-post-data",$data);
                $data = Pf::event()->trigger("filter","category-editing-post-data",$data);
                
                $this->view->token = Pf_Plugin_CSRF::token($this->key.$this->get->id);
                
                if (!$this->category_model->save($data)){
                    $this->view->errors = $this->category_model->errors;
                    $var['content'] = $this->view->fetch($template);
                    $var['error'] = 1;
                }else{
                    Pf::event()->trigger("action","category-edit-successfully",$data);
                    $var['error'] = 0;
                    $var['content'] = $this->view->fetch($template);
                    $var['url'] = admin_url($this->action.'=index&ajax=&id=&token=');
                }
            }else{
                if (isset($this->get->id)){
                    $params = array();
                    $params['where'] = array('id=?',array((int)$this->get->id));
                    $data = $this->category_model->fetch_one($params);
                    
                $data["category_status"] = explode(",",$data["category_status"]);
                                
                    
                    $data = Pf::event()->trigger("filter","category-database-data",$data);
                    $data = Pf::event()->trigger("filter","category-editing-database-data",$data);
                    
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
        $this->category_model->rules = Pf::event()->trigger("filter","category-copy-validation-rule",$this->category_model->rules);
    
        $template = null;
        $template = Pf::event()->trigger("filter","category-edit-template",$template);
        
        $var = array();
        
        if (isset($this->get->id) && isset($this->get->token) && Pf_Plugin_CSRF::is_valid($this->get->token, $this->key.$this->get->id)){
            if ($this->request->is_post()){
                $data = array();
                
                
                $data["category_name"] = e($this->post->{"category_name"});
                $data["category_description"] = e($this->post->{"category_description"});
                if (is_array($this->post->{"category_status"})){
                    $data["category_status"] = implode(",",$this->post->{"category_status"});
                }else{
                    $data["category_status"] = $this->post->{"category_status"};
                }
                
                $data = Pf::event()->trigger("filter","category-post-data",$data);
                $data = Pf::event()->trigger("filter","category-copy-post-data",$data);
                
                $this->view->token = Pf_Plugin_CSRF::token($this->key.$this->get->id);
                
                if (!$this->category_model->insert($data)){
                    $this->view->errors = $this->category_model->errors;
                    $var['content'] = $this->view->fetch($template);
                    $var['error'] = 1;
                }else{
                    Pf::event()->trigger("action","category-copy-successfully",$data);
                    $var['error'] = 0;
                    $var['url'] = admin_url($this->action.'=index&ajax=&id=&token=');
                }
                
            }else{
                if (isset($this->get->id)){
                    $params = array();
                    $params['where'] = array('id=?',array((int)$this->get->id));
                    $data = $this->category_model->fetch_one($params);
                    
                $data["category_status"] = explode(",",$data["category_status"]);
                                
                    
                    $data = Pf::event()->trigger("filter","category-database-data",$data);
                    $data = Pf::event()->trigger("filter","category-copy-database-data",$data);
                    
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
                            $this->category_model->delete('id=?',array($id));
                        }
                    }
                    break;
                    case 'publish':
                        if (!empty($this->post->id) && is_array($this->post->id)){
                            foreach ($this->post->id as $id){
                                $params['where'] = array('id=?',array($id));
                                $data = $this->category_model->fetch_one($params);
                                $data['category_status'] = 1;
                                $this->category_model->save($data);
                            }
                        }
                    
                        $var['action'] = 'publish';
                        break;
                    case 'unpublish':
                        if (!empty($this->post->id) && is_array($this->post->id)){
                            foreach ($this->post->id as $id){
                                $params['where'] = array('id=?',array($id));
                                $data = $this->category_model->fetch_one($params);
                                $data['category_status'] = 0;
                                $this->category_model->save($data);
                            }
                        }
                        $var['action'] = 'unpublish';
                        break;
            }
            Pf::event()->trigger("action","category-bulk-action-successfully",$this->get->type,$this->post->id);
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
            if ($this->category_model->delete('id=?',array($this->get->id))){
                $var['error'] = 0;
                Pf::event()->trigger("action","category-delete-successfully",$this->get->id);
            }else{
                $var['error'] = 1;
            }
        }
        
        $var['url'] = admin_url($this->action.'=index&ajax=&id=&token=');
        
        echo json_encode($var);
    }
    public function change_status() {
        $data = array();
        $status = $this->post->{"status"};
        $params = array();
        $params['where'] = array('id=?',array((int)$this->post->id));
        $data = $this->category_model->fetch_one($params);
    
        switch ($status) {
            case 'publish':
                $data['category_status'] = 1;
                $this->category_model->save($data);
                break;
            case 'unpublish':
                $data['category_status'] = 0;
                $this->category_model->save($data);
                break;
        }
    }
}