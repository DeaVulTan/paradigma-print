<?php
defined ( 'PF_VERSION' ) or header ( 'Location:404.html' );
class Testimonials_Controller extends Pf_Controller{
    public function __construct(){
        parent::__construct();
        $this->load_model('testimonials');
        $this->view->menu_settings = get_option('admin_menu_setting');
        $this->testimonials_model->rules = Pf::event()->trigger("filter","testimonials-validation-rule",$this->testimonials_model->rules);
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

        if (isset($this->get->search["testimonial_avatar"]) && trim($this->get->search["testimonial_avatar"]) != ""){
            $where .= $operator.' `testimonial_avatar` like ? ';
            $where_values[] = '%'.$this->get->search["testimonial_avatar"].'%';
            $operator = ' AND ';
        }

        if (isset($this->get->search["testimonial_name"]) && trim($this->get->search["testimonial_name"]) != ""){
            $where .= $operator.' `testimonial_name` like ? ';
            $where_values[] = '%'.$this->get->search["testimonial_name"].'%';
            $operator = ' AND ';
        }

        if (isset($this->get->search["testimonial_content"]) && trim($this->get->search["testimonial_content"]) != ""){
            $where .= $operator.' `testimonial_content` like ? ';
            $where_values[] = '%'.$this->get->search["testimonial_content"].'%';
            $operator = ' AND ';
        }

        if (isset($this->get->search["testimonial_status"]) && trim($this->get->search["testimonial_status"]) != ""){
            $where .= $operator.' `testimonial_status` like ? ';
            $where_values[] = '%'.$this->get->search["testimonial_status"].'%';
            $operator = ' AND ';
        }
        
        //view all status
        if (isset($this->post->action) && trim($this->post->action) != ""){
            $where .= $operator.' `testimonial_status` = ? ';
            $where_values[] = $this->post->id;
        }

        
        if (!empty($where_values)){
            $params['where'] = array($where,$where_values);
        }
        
        
        if (!empty($this->get->order_field) && !empty($this->get->order_type)){
            $params["order"] = "`".Pf::database ()->escape($this->get->order_field)."` ".Pf::database ()->escape($this->get->order_type);
        }

        $params = Pf::event()->trigger("filter","testimonials-index-params",$params);
        
        $this->view->page_index = $params['page_index'];
        $this->view->records = $this->testimonials_model->fetch($params,true);
        $this->view->total_records = $this->testimonials_model->found_rows();
        $total_page = ceil($this->view->total_records/NUM_PER_PAGE);
        
        if (empty($this->view->records) && $total_page > 0){
            $this->get->{$this->page} = $params['page_index'] = $total_page; 
            $this->view->page_index = $params['page_index'];
            $this->view->records = $this->testimonials_model->fetch($params,true);
            $this->view->total_records = $this->testimonials_model->found_rows();
        }
        $this->view->pagination = new Pf_Paginator($this->view->total_records, NUM_PER_PAGE, $this->page);
        
        $template = null;
        $template = Pf::event()->trigger("filter","testimonials-index-template",$template);
        $this->view->render($template);
    }
    
    public function add(){
        $this->testimonials_model->rules = Pf::event()->trigger("filter","testimonials-adding-validation-rule",$this->testimonials_model->rules);
        
        $template = null;
        $template = Pf::event()->trigger("filter","testimonials-add-template",$template);
        
        if ($this->request->is_post()){
            $data = array();
            
            
                $data["testimonial_name"] = e($this->post->{"testimonial_name"});
                $data["testimonial_content"] = e($this->post->{"testimonial_content"});
                $data["testimonial_info"] = e($this->post->{"testimonial_info"});
                $data["testimonial_avatar"] = e($this->post->{"testimonial_avatar"});
                $data["testimonial_status"] = 1;
            
            $data = Pf::event()->trigger("filter","testimonials-post-data",$data);
            $data = Pf::event()->trigger("filter","testimonials-adding-post-data",$data);
            
            $var = array();
            if (!$this->testimonials_model->insert($data)){
                $this->view->errors = $this->testimonials_model->errors;
                $var['content'] = $this->view->fetch($template);
                $var['error'] = 1;
            }else{
                Pf::event()->trigger("action","testimonials-add-successfully",$this->testimonials_model->insert_id(),$data);
                $var['error'] = 0;
                $var['url'] = admin_url($this->action.'=index&ajax=&id=&token=');
            }
            
            echo json_encode($var);
        }else{
        
            $this->view->render($template);
        }
    }
    
    public function edit(){
        $this->testimonials_model->rules = Pf::event()->trigger("filter","testimonials-editing-validation-rule",$this->testimonials_model->rules);
        $template = null;
        $template = Pf::event()->trigger("filter","testimonials-edit-template",$template);
        
        $var = array();
        
        if (isset($this->get->id) && isset($this->get->token) && Pf_Plugin_CSRF::is_valid($this->get->token, $this->key.$this->get->id)){
            if ($this->request->is_post()){
                $data = array();
                $data['id'] = $this->get->id;
                
                
                $data["testimonial_name"] = e($this->post->{"testimonial_name"});
                $data["testimonial_content"] = e($this->post->{"testimonial_content"});
                $data["testimonial_info"] = e($this->post->{"testimonial_info"});
                $data["testimonial_avatar"] = e($this->post->{"testimonial_avatar"});
                
                
                $data = Pf::event()->trigger("filter","testimonials-post-data",$data);
                $data = Pf::event()->trigger("filter","testimonials-editing-post-data",$data);
                
                $this->view->token = Pf_Plugin_CSRF::token($this->key.$this->get->id);
                
                if (!$this->testimonials_model->save($data)){
                    $this->view->errors = $this->testimonials_model->errors;
                    $var['content'] = $this->view->fetch($template);
                    $var['error'] = 1;
                }else{
                    Pf::event()->trigger("action","testimonials-edit-successfully",$data);
                    $var['error'] = 0;
                    $var['content'] = $this->view->fetch($template);
                    $var['url'] = admin_url($this->action.'=index&ajax=&id=&token=');
                }
            }else{
                if (isset($this->get->id)){
                    $params = array();
                    $params['where'] = array('id=?',array((int)$this->get->id));
                    $data = $this->testimonials_model->fetch_one($params);
                    
                    
                    $data = Pf::event()->trigger("filter","testimonials-database-data",$data);
                    $data = Pf::event()->trigger("filter","testimonials-editing-database-data",$data);
                    
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
        $this->testimonials_model->rules = Pf::event()->trigger("filter","testimonials-copy-validation-rule",$this->testimonials_model->rules);
    
        $template = null;
        $template = Pf::event()->trigger("filter","testimonials-edit-template",$template);
        
        $var = array();
        
        if (isset($this->get->id) && isset($this->get->token) && Pf_Plugin_CSRF::is_valid($this->get->token, $this->key.$this->get->id)){
            if ($this->request->is_post()){
                $data = array();
                
                
                $data["testimonial_name"] = e($this->post->{"testimonial_name"});
                $data["testimonial_content"] = e($this->post->{"testimonial_content"});
                $data["testimonial_info"] = e($this->post->{"testimonial_info"});
                $data["testimonial_avatar"] = e($this->post->{"testimonial_avatar"});
                $data["testimonial_status"] = 1;
                
                $data = Pf::event()->trigger("filter","testimonials-post-data",$data);
                $data = Pf::event()->trigger("filter","testimonials-copy-post-data",$data);
                
                $this->view->token = Pf_Plugin_CSRF::token($this->key.$this->get->id);
                
                if (!$this->testimonials_model->insert($data)){
                    $this->view->errors = $this->testimonials_model->errors;
                    $var['content'] = $this->view->fetch($template);
                    $var['error'] = 1;
                }else{
                    Pf::event()->trigger("action","testimonials-copy-successfully",$data);
                    $var['error'] = 0;
                    $var['url'] = admin_url($this->action.'=index&ajax=&id=&token=');
                }
                
            }else{
                if (isset($this->get->id)){
                    $params = array();
                    $params['where'] = array('id=?',array((int)$this->get->id));
                    $data = $this->testimonials_model->fetch_one($params);
                    
                    
                    $data = Pf::event()->trigger("filter","testimonials-database-data",$data);
                    $data = Pf::event()->trigger("filter","testimonials-copy-database-data",$data);
                    
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
        $data = array();
        $params = array();
        
        if (Pf_Plugin_CSRF::is_valid($this->post->token,$this->key)){
            switch ($this->get->type){
                case 'delete':
                    if (!empty($this->post->id) && is_array($this->post->id)){
                        foreach ($this->post->id as $id){
                            $this->testimonials_model->delete('id=?',array($id));
                        }
                    }
                    $var['action'] = 'delete';
                break;
                case 'publish':
                    
                    if (!empty($this->post->id) && is_array($this->post->id)){
                        foreach ($this->post->id as $id){
                            $params['where'] = array('id=?',array($id));
                            $data = $this->testimonials_model->fetch_one($params);
                            $data['testimonial_status'] = 1;
                            $this->testimonials_model->save($data);
                        }
                    }
                    $var['action'] = 'publish';
                break;
                case 'unpublish':
                    if (!empty($this->post->id) && is_array($this->post->id)){
                        foreach ($this->post->id as $id){
                            $params['where'] = array('id=?',array($id));
                            $data = $this->testimonials_model->fetch_one($params);
                            $data['testimonial_status'] = 2;
                            $this->testimonials_model->save($data);
                        }
                    }
                    $var['action'] = 'unpublish';
                break;
            }
            Pf::event()->trigger("action","testimonials-bulk-action-successfully",$this->get->type,$this->post->id);
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
            if ($this->testimonials_model->delete('id=?',array($this->get->id))){
                $var['error'] = 0;
                Pf::event()->trigger("action","testimonials-delete-successfully",$this->get->id);
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
        $data = $this->testimonials_model->fetch_one($params);
        
        switch ($status) {
            case 'publish':
                $data['testimonial_status'] = 1;
                $this->testimonials_model->save($data);
                break;
            case 'unpublish':
                $data['testimonial_status'] = 2;
                $this->testimonials_model->save($data);
                break;
        }
    }

}