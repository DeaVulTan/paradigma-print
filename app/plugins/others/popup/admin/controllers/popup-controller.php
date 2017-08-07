<?php
defined ( 'PF_VERSION' ) or header ( 'Location:404.html' );
class Popup_Controller extends Pf_Controller{
    public function __construct(){
        parent::__construct();
        $this->load_model('popup');
        $this->view->menu_settings = get_option('admin_menu_setting');
        $this->popup_model->rules = Pf::event()->trigger("filter","popup-validation-rule",$this->popup_model->rules);
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

        if (isset($this->get->search["popup_title"]) && trim($this->get->search["popup_title"]) != ""){
            $where .= $operator.' `popup_title` like ? ';
            $where_values[] = '%'.$this->get->search["popup_title"].'%';
            $operator = ' AND ';
        }
        
        if (isset($this->get->search["popup_url"]) && trim($this->get->search["popup_url"]) != ""){
            $where .= $operator.' `popup_url` like ? ';
            $where_values[] = '%'.$this->get->search["popup_url"].'%';
            $operator = ' AND ';
        }

        //Search Status
        if (isset($this->get->search["popup_status"])){
            if (is_array($this->get->search["popup_status"])){
                if (!empty($this->get->search["popup_status"])){
                    $where .= $operator.' ( ';
                    $operator1 = '';
                    foreach($this->get->search["popup_status"] as $v){
                        $where .= $operator1.'  `popup_status` = ?  OR `popup_status` like ?  OR `popup_status` like ? OR `popup_status` like ? ';
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
                if (trim($this->get->search["popup_status"]) != ""){
                    $where .= $operator.' (  `popup_status` = ?  OR `popup_status` like ?  OR `popup_status` like ? OR `popup_status` like ? )';
                    $where_values[] = $this->get->search["popup_status"];
                    $where_values[] = $this->get->search["popup_status"].',%';
                    $where_values[] = '%,'.$this->get->search["popup_status"].',%';
                    $where_values[] = '%,'.$this->get->search["popup_status"];
                    $operator = ' AND ';
                }
            }
        }

        //view all status
        if (isset($this->post->action) && trim($this->post->action) != ""){
            $where .= $operator.' `popup_status` = ? ';
            $where_values[] = $this->post->id;
        }
        
        if (!empty($where_values)){
            $params['where'] = array($where,$where_values);
        }
        
        
        if (!empty($this->get->order_field) && !empty($this->get->order_type)){
            $params["order"] = "`".Pf::database ()->escape($this->get->order_field)."` ".Pf::database ()->escape($this->get->order_type);
        }

        $params = Pf::event()->trigger("filter","popup-index-params",$params);

        
        $this->view->page_index = $params['page_index'];
        $this->view->records = $this->popup_model->fetch($params,true);
        $this->view->total_records = $this->popup_model->found_rows();
        $total_page = ceil($this->view->total_records/NUM_PER_PAGE);
        if (empty($this->view->records) && $total_page > 0){
            $this->get->{$this->page} = $params['page_index'] = $total_page; 
            $this->view->page_index = $params['page_index'];
            $this->view->records = $this->popup_model->fetch($params,true);
            $this->view->total_records = $this->popup_model->found_rows();
        }
        $this->view->pagination = new Pf_Paginator($this->view->total_records, NUM_PER_PAGE, $this->page);
        
        $template = null;
        $template = Pf::event()->trigger("filter","popup-index-template",$template);
        
        $this->view->render($template);
    }
    
    public function add(){
        $this->popup_model->rules = Pf::event()->trigger("filter","popup-adding-validation-rule",$this->popup_model->rules);
        
        $template = null;
        $template = Pf::event()->trigger("filter","popup-add-template",$template);
        
        if ($this->request->is_post()){
            $data = array();
            $data["popup_title"] = $this->post->{"popup_title"};
            $data["popup_url"] = $this->post->{"popup_url"};
            $data["popup_type"] = $this->post->{"popup_type"};
            $data["popup_created_date"] = date("Y-m-d H:i:s");
            $data["popup_status"] = 1;
            if(empty($this->post->{"popup_width"})){
                $data["popup_width"] = 600;
            }else{
                $data["popup_width"] = $this->post->{"popup_width"};
            }
            if(empty($this->post->{"popup_height"})){
                $data["popup_height"] = 450;
            }else{
                $data["popup_height"] = $this->post->{"popup_height"};
            }
            $data["popup_published_date"] = str_to_mysqldate($this->post->{"popup_published_date"},$this->popup_model->elements_value["popup_published_date"],"Y-m-d H:i:s");
            $data["popup_unpublished_date"] = str_to_mysqldate($this->post->{"popup_unpublished_date"},$this->popup_model->elements_value["popup_unpublished_date"],"Y-m-d H:i:s");
            $data["popup_description"] = $this->post->{"popup_description"};
            
            $data = Pf::event()->trigger("filter","popup-post-data",$data);
            $data = Pf::event()->trigger("filter","popup-adding-post-data",$data);
            
            $var = array();
            if (!$this->popup_model->insert($data)){
                $this->view->errors = $this->popup_model->errors;
                $var['content'] = $this->view->fetch($template);
                $var['error'] = 1;
            }else{
                Pf::event()->trigger("action","popup-add-successfully",$this->popup_model->insert_id(),$data);
                $var['error'] = 0;
                $var['url'] = admin_url($this->action.'=index&ajax=&id=&token=');
            }
            
            echo json_encode($var);
        }else{
        
            $this->view->render($template);
        }
    }
    
    public function edit(){
        $this->popup_model->rules = Pf::event()->trigger("filter","popup-editing-validation-rule",$this->popup_model->rules);
        $template = null;
        $template = Pf::event()->trigger("filter","popup-edit-template",$template);
        
        $var = array();
        
        if (isset($this->get->id) && isset($this->get->token) && Pf_Plugin_CSRF::is_valid($this->get->token, $this->key.$this->get->id)){
            if ($this->request->is_post()){
                $data = array();
                $data['id'] = $this->get->id;
                $data["popup_title"] = $this->post->{"popup_title"};
                $data["popup_url"] = $this->post->{"popup_url"};
                $data["popup_type"] = $this->post->{"popup_type"};
                $data["popup_width"] = $this->post->{"popup_width"};
                $data["popup_height"] = $this->post->{"popup_height"};
                $data["popup_modified_date"] = date("Y-m-d H:i:s");
                $data["popup_published_date"] = str_to_mysqldate($this->post->{"popup_published_date"},$this->popup_model->elements_value["popup_published_date"],"Y-m-d H:i:s");
                $data["popup_unpublished_date"] = str_to_mysqldate($this->post->{"popup_unpublished_date"},$this->popup_model->elements_value["popup_unpublished_date"],"Y-m-d H:i:s");
                $data["popup_description"] = $this->post->{"popup_description"};
                
                $data = Pf::event()->trigger("filter","popup-post-data",$data);
                $data = Pf::event()->trigger("filter","popup-editing-post-data",$data);
                
                $this->view->token = Pf_Plugin_CSRF::token($this->key.$this->get->id);
                
                if (!$this->popup_model->save($data)){
                    $this->view->errors = $this->popup_model->errors;
                    $var['content'] = $this->view->fetch($template);
                    $var['error'] = 1;
                }else{
                    Pf::event()->trigger("action","popup-edit-successfully",$data);
                    $var['error'] = 0;
                    $var['content'] = $this->view->fetch($template);
                    $var['url'] = admin_url($this->action.'=index&ajax=&id=&token=');
                }
            }else{
                if (isset($this->get->id)){
                    $params = array();
                    $params['where'] = array('id=?',array((int)$this->get->id));
                    $data = $this->popup_model->fetch_one($params);
                    
                $data["popup_published_date"] =  str_to_mysqldate($data["popup_published_date"],"Y-m-d",$this->popup_model->elements_value["popup_published_date"]." g:i A");
                $data["popup_unpublished_date"] =  str_to_mysqldate($data["popup_unpublished_date"],"Y-m-d",$this->popup_model->elements_value["popup_unpublished_date"]." g:i A");
                    
                    $data = Pf::event()->trigger("filter","popup-database-data",$data);
                    $data = Pf::event()->trigger("filter","popup-editing-database-data",$data);
                    
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
        $this->popup_model->rules = Pf::event()->trigger("filter","popup-copy-validation-rule",$this->popup_model->rules);
    
        $template = null;
        $template = Pf::event()->trigger("filter","popup-edit-template",$template);
        
        $var = array();
        
        if (isset($this->get->id) && isset($this->get->token) && Pf_Plugin_CSRF::is_valid($this->get->token, $this->key.$this->get->id)){
            if ($this->request->is_post()){
                $data = array();
                $data["popup_title"] = $this->post->{"popup_title"};
                $data["popup_url"] = $this->post->{"popup_url"};
                $data["popup_type"] = $this->post->{"popup_type"};
                $data["popup_width"] = $this->post->{"popup_width"};
                $data["popup_height"] = $this->post->{"popup_height"};
                $data["popup_created_date"] = date("Y-m-d H:i:s");
                $data["popup_status"] = 1;
                $data["popup_published_date"] = str_to_mysqldate($this->post->{"popup_published_date"},$this->popup_model->elements_value["popup_published_date"],"Y-m-d H:i:s");
                $data["popup_unpublished_date"] = str_to_mysqldate($this->post->{"popup_unpublished_date"},$this->popup_model->elements_value["popup_unpublished_date"],"Y-m-d H:i:s");
                $data["popup_description"] = $this->post->{"popup_description"};
                
                $data = Pf::event()->trigger("filter","popup-post-data",$data);
                $data = Pf::event()->trigger("filter","popup-copy-post-data",$data);
                
                $this->view->token = Pf_Plugin_CSRF::token($this->key.$this->get->id);
                
                if (!$this->popup_model->insert($data)){
                    $this->view->errors = $this->popup_model->errors;
                    $var['content'] = $this->view->fetch($template);
                    $var['error'] = 1;
                }else{
                    Pf::event()->trigger("action","popup-copy-successfully",$data);
                    $var['error'] = 0;
                    $var['url'] = admin_url($this->action.'=index&ajax=&id=&token=');
                }
                
            }else{
                if (isset($this->get->id)){
                    $params = array();
                    $params['where'] = array('id=?',array((int)$this->get->id));
                    $data = $this->popup_model->fetch_one($params);
                    
                $data["popup_published_date"] =  str_to_mysqldate($data["popup_published_date"],"Y-m-d",$this->popup_model->elements_value["popup_published_date"]." g:i A");
                $data["popup_unpublished_date"] =  str_to_mysqldate($data["popup_unpublished_date"],"Y-m-d",$this->popup_model->elements_value["popup_unpublished_date"]." g:i A");
                    
                    $data = Pf::event()->trigger("filter","popup-database-data",$data);
                    $data = Pf::event()->trigger("filter","popup-copy-database-data",$data);
                    
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
                            $this->popup_model->delete('id=?',array($id));
                        }
                    }
                    $var['action'] = 'delete';
                break;
                case 'publish':
                    if (!empty($this->post->id) && is_array($this->post->id)){
                        foreach ($this->post->id as $id){
                            $params['where'] = array('id=?',array($id));
                            $data = $this->popup_model->fetch_one($params);
                            $data['popup_status'] = 1;
                            $this->popup_model->save($data);
                        }
                    }
                    $var['action'] = 'publish';
                break;
                case 'unpublish':
                    if (!empty($this->post->id) && is_array($this->post->id)){
                        foreach ($this->post->id as $id){
                            $params['where'] = array('id=?',array($id));
                            $data = $this->popup_model->fetch_one($params);
                            $data['popup_status'] = 2;
                            $this->popup_model->save($data);
                        }
                    }
                    $var['action'] = 'unpublish';
                break;
            }
            Pf::event()->trigger("action","popup-bulk-action-successfully",$this->get->type,$this->post->id);
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
            if ($this->popup_model->delete('id=?',array($this->get->id))){
                $var['error'] = 0;
                Pf::event()->trigger("action","popup-delete-successfully",$this->get->id);
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
        $data = $this->popup_model->fetch_one($params);
        switch ($status) {
            case 'publish':
                $data['popup_status'] = 1;
                $this->popup_model->save($data);
                break;
            case 'unpublish':
                $data['popup_status'] = 2;
                $this->popup_model->save($data);
                break;
        }
    }
}