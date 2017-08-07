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
        
        $params['page_index'] = (isset($this->get->{$this->page}))?(int)$this->get->{$this->page}:1;
        
        if (empty($this->get->search)){
                $this->get->search = array();
        }

        if (isset($this->get->search["id"]) && trim($this->get->search["id"]) != ""){
            $where .= $operator.' `id` like ? ';
            $where_values[] = '%'.$this->get->search["id"].'%';
            $operator = ' AND ';
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

        if (isset($this->get->search["category_author"]) && trim($this->get->search["category_author"]) != ""){
            $where .= $operator.' `category_author` like ? ';
            $where_values[] = '%'.$this->get->search["category_author"].'%';
            $operator = ' AND ';
        }

        //view all status
        if (isset($this->post->action) && trim($this->post->action) != ""){
            $where .= $operator.' `category_status` = ? ';
            $where_values[] = $this->post->id;
        }
        
        //Search Status
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
        
        $params['fields'] = array(DB_PREFIX.'post_categories.id,category_name,category_type,category_parent,category_description,category_status,user_name');
        $params['join'] = array('0' => array('LEFT',''.DB_PREFIX.'users',''.DB_PREFIX.'post_categories.category_author = '.DB_PREFIX.'users.id'));
        
        if (!empty($where_values)){
            $params['where'] = array($where,$where_values);
        }
        
        if (!empty($this->get->order_field) && !empty($this->get->order_type)){
            $params["order"] = "`".Pf::database ()->escape($this->get->order_field)."` ".Pf::database ()->escape($this->get->order_type);
        }

        $params = Pf::event()->trigger("filter","category-index-params",$params);

        $data_records = $this->category_model->fetch($params,true);
        $this->view->records = get_level($data_records);
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
            
            
                $data["category_name"] = $this->post->{"category_name"};
                $data["category_status"] = $this->post->{"category_status"};
                $data["category_parent"] = $this->post->{"category_parent"};
                $data["category_description"] = $this->post->{"category_description"};
                $data["category_created_date"] = date("Y-m-d H:i:s");
                $data["category_author"] = current_user('user-id');
                $data["category_type"] = 1;
                
            
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
            $data['list_category'] = $this->get_category();
            $this->post->datas($data);
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
                $data["category_name"] = $this->post->{"category_name"};
                $data["category_status"] = $this->post->{"category_status"};
                $data["category_parent"] = $this->post->{"category_parent"};
                $data["category_description"] = $this->post->{"category_description"};
                $data["category_modified_date"] = date("Y-m-d H:i:s");
                $data["category_author"] = current_user('user-id');
                
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
                    
                    
                    $data = Pf::event()->trigger("filter","category-database-data",$data);
                    $data = Pf::event()->trigger("filter","category-editing-database-data",$data);
                    
                    $list_category = $this->get_category();
                    $list_all_category = array();
                    foreach($list_category as $key => $value){
                        if($value['id'] == $this->get->id){
                            $data['level'] = $value['level'];
                        }
                    }
                    foreach($list_category as $key => $value){
                        if($data['level'] == 0 && $value['level'] == 0 && $data['id'] != $value['id']){
                            $list_all_category[$key]['id'] = $value['id'];
                            $list_all_category[$key]['category_name'] = $value['category_name'];
                            $list_all_category[$key]['category_parent'] = $value['category_parent'];
                            $list_all_category[$key]['level'] = $value['level'];
                        }
                        if($data['level'] > $value['level'] && $data['id'] != $value['id']){
                            $list_all_category[$key]['id'] = $value['id'];
                            $list_all_category[$key]['category_name'] = $value['category_name'];
                            $list_all_category[$key]['category_parent'] = $value['category_parent'];
                            $list_all_category[$key]['level'] = $value['level'];
                        }
                    }
                    $data['list_category'] = $list_all_category;
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
                
                
                $data["category_name"] = $this->post->{"category_name"};
                $data["category_status"] = $this->post->{"category_status"};
                $data["category_parent"] = $this->post->{"category_parent"};
                $data["category_description"] = $this->post->{"category_description"};
                $data["category_created_date"] = date("Y-m-d H:i:s");
                $data["category_author"] = current_user('user-id');
                
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
                    
                    
                    $data = Pf::event()->trigger("filter","category-database-data",$data);
                    $data = Pf::event()->trigger("filter","category-copy-database-data",$data);
                    
                    $data['list_category'] = $this->get_category();
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
                    $params['fields'] = 'category_parent';
                    $data = $this->category_model->fetch($params);
                    foreach($data as $key => $parent){
                        $arr_parent[$key] = $parent['category_parent'];
                    }
                    //debug($arr_parent);
                    
                    if (!empty($this->post->id) && is_array($this->post->id)){
                        foreach ($this->post->id as $id){
                            if(in_array($id,$arr_parent)){
                                $var['error'] = 2;
                                Pf::event()->trigger("action","category-bulk-action-successfully",$this->get->type,$id);
                            }else{
                                $this->category_model->delete('id=?',array($id));
                                Pf::event()->trigger("action","category-bulk-action-successfully",$this->get->type,$this->post->id);
                                $var['error'] = 0;
                            }
                        }
                    }
                    $var['action'] = 'delete';
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
                    Pf::event()->trigger("action","category-bulk-action-successfully",$this->get->type,$this->post->id);
                    $var['error'] = 0;
                break;
                case 'unpublish':
                    if (!empty($this->post->id) && is_array($this->post->id)){
                        foreach ($this->post->id as $id){
                            $params['where'] = array('id=?',array($id));
                            $data = $this->category_model->fetch_one($params);
                            $data['category_status'] = 2;
                            $this->category_model->save($data);
                        }
                    }
                    $var['action'] = 'unpublish';
                    Pf::event()->trigger("action","category-bulk-action-successfully",$this->get->type,$this->post->id);
                    $var['error'] = 0;
                break;
            }
            
        }else{
            $var['error'] = 1;
        }
        
        $var['url'] = admin_url($this->action.'=index&ajax=&id=&token=&type=');
        
        echo json_encode($var);
    }
    
    public function delete(){
        $var = array();
        if (isset($this->get->id) && isset($this->get->token) && Pf_Plugin_CSRF::is_valid($this->get->token, $this->key.$this->get->id)){
            $params['fields'] = 'category_parent';
            $data = $this->category_model->fetch($params);
            foreach($data as $key => $parent){
                $arr_parent[$key] = $parent['category_parent'];
            }
            
            if(in_array($this->get->id,$arr_parent)){
                $var['error'] = 2;
            }else{
                if ($this->category_model->delete('id=?',array($this->get->id))){
                    $var['error'] = 0;
                    Pf::event()->trigger("action","category-delete-successfully",$this->get->id);
                }else{
                    $var['error'] = 1;
                }
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
        $data = $this->category_model->fetch_one($params);
    
        switch ($status) {
            case 'publish':
                $data['category_status'] = 1;
                $this->category_model->save($data);
                break;
            case 'unpublish':
                $data['category_status'] = 2;
                $this->category_model->save($data);
                break;
        }
    }
    
    private function get_category(){
        $params = array();
        $where = '';
        $where_values = array();
        $operator = '';
    
        $params['fields'] = array('id,category_name,category_parent');
    
        if (!empty($where_values)){
            $params['where'] = array($where,$where_values);
        }
    
        $records = $this->category_model->fetch($params,true);
        $data = get_level($records);
    
        return $data;
    }
}