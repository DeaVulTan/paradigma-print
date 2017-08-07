<?php
defined ( 'PF_VERSION' ) or header ( 'Location:404.html' );
class Comment_Controller extends Pf_Controller{
    public function __construct(){
        parent::__construct();
        $this->load_model('comment');
        $this->view->menu_settings = get_option('admin_menu_setting');
        $this->comment_model->rules = Pf::event()->trigger("filter","comment-validation-rule",$this->comment_model->rules);
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

        if (isset($this->get->search["comment_content"]) && trim($this->get->search["comment_content"]) != ""){
            $where .= $operator.' `comment_content` like ? ';
            $where_values[] = '%'.$this->get->search["comment_content"].'%';
            $operator = ' AND ';
        }

        if (isset($this->get->search["comment_author"]) && trim($this->get->search["comment_author"]) != ""){
            $where .= $operator.' `comment_author` like ? ';
            $where_values[] = '%'.$this->get->search["comment_author"].'%';
            $operator = ' AND ';
        }

        if (isset($this->get->search["comment_created_date"]) && trim($this->get->search["comment_created_date"]) != ""){
            $where .= $operator.' `comment_created_date` = ? ';
            $where_values[] = str_to_mysqldate($this->get->search["comment_created_date"],$this->comment_model->elements_value["comment_created_date"],"Y-m-d H:i:s");
            $operator = ' AND ';
        }

        if (isset($this->get->search["comment_status"]) && trim($this->get->search["comment_status"]) != ""){
            $where .= $operator.' `comment_status` like ? ';
            $where_values[] = '%'.$this->get->search["comment_status"].'%';
            $operator = ' AND ';
        }

        //view all status
        if (isset($this->post->action) && trim($this->post->action) != ""){
            $where .= $operator.' `comment_status` = ? ';
            $where_values[] = $this->post->id;
        }
        
        if (!empty($where_values)){
            $params['where'] = array($where,$where_values);
        }
        
        
        if (!empty($this->get->order_field) && !empty($this->get->order_type)){
            $params["order"] = "`".Pf::database ()->escape($this->get->order_field)."` ".Pf::database ()->escape($this->get->order_type);
        }

        $params = Pf::event()->trigger("filter","comment-index-params",$params);

        
        $this->view->page_index = $params['page_index'];
        $this->view->records = $this->comment_model->fetch($params,true);
        $this->view->total_records = $this->comment_model->found_rows();
        $total_page = ceil($this->view->total_records/NUM_PER_PAGE);
        if (empty($this->view->records) && $total_page > 0){
            $this->get->{$this->page} = $params['page_index'] = $total_page; 
            $this->view->page_index = $params['page_index'];
            $this->view->records = $this->comment_model->fetch($params,true);
            $this->view->total_records = $this->comment_model->found_rows();
        }
        $this->view->pagination = new Pf_Paginator($this->view->total_records, NUM_PER_PAGE, $this->page);
        
        $template = null;
        $template = Pf::event()->trigger("filter","comment-index-template",$template);
        
        $this->view->render($template);
    }
    
    public function edit(){
        $this->comment_model->rules = Pf::event()->trigger("filter","comment-editing-validation-rule",$this->comment_model->rules);
        $template = null;
        $template = Pf::event()->trigger("filter","comment-edit-template",$template);
        
        $var = array();
        
        if (isset($this->get->id) && isset($this->get->token) && Pf_Plugin_CSRF::is_valid($this->get->token, $this->key.$this->get->id)){
            if ($this->request->is_post()){
                $data = array();
                $data['id'] = $this->get->id;
                
                
                $data["comment_content"] = $this->post->{"comment_content"};
                
                $data = Pf::event()->trigger("filter","comment-post-data",$data);
                $data = Pf::event()->trigger("filter","comment-editing-post-data",$data);
                
                $this->view->token = Pf_Plugin_CSRF::token($this->key.$this->get->id);
                
                if (!$this->comment_model->save($data)){
                    $this->view->errors = $this->comment_model->errors;
                    $var['content'] = $this->view->fetch($template);
                    $var['error'] = 1;
                }else{
                    Pf::event()->trigger("action","comment-edit-successfully",$data);
                    $var['error'] = 0;
                    $var['content'] = $this->view->fetch($template);
                    $var['url'] = admin_url($this->action.'=index&ajax=&id=&token=');
                }
            }else{
                if (isset($this->get->id)){
                    $params = array();
                    $params['where'] = array('id=?',array((int)$this->get->id));
                    $data = $this->comment_model->fetch_one($params);
                    
                    
                    $data = Pf::event()->trigger("filter","comment-database-data",$data);
                    $data = Pf::event()->trigger("filter","comment-editing-database-data",$data);
                    
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
                            $this->comment_model->delete('id=?',array($id));
                        }
                    }
                break;
                case 'publish':
                
                    if (!empty($this->post->id) && is_array($this->post->id)){
                        foreach ($this->post->id as $id){
                            $params['where'] = array('id=?',array($id));
                            $data = $this->comment_model->fetch_one($params);
                            $data['comment_status'] = 1;
                            $this->comment_model->save($data);
                        }
                    }
                    $var['action'] = 'publish';
                break;
                case 'unpublish':
                    if (!empty($this->post->id) && is_array($this->post->id)){
                        foreach ($this->post->id as $id){
                            $params['where'] = array('id=?',array($id));
                            $data = $this->comment_model->fetch_one($params);
                            $data['comment_status'] = 2;
                            $this->comment_model->save($data);
                        }
                    }
                    $var['action'] = 'unpublish';
                break;
            }
            Pf::event()->trigger("action","comment-bulk-action-successfully",$this->get->type,$this->post->id);
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
            if ($this->comment_model->delete('id=?',array($this->get->id))){
                $var['error'] = 0;
                Pf::event()->trigger("action","comment-delete-successfully",$this->get->id);
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
        $data = $this->comment_model->fetch_one($params);
    
        switch ($status) {
            case 'publish':
                $data['comment_status'] = 1;
                $this->comment_model->save($data);
                break;
            case 'unpublish':
                $data['comment_status'] = 2;
                $this->comment_model->save($data);
                break;
        }
    }
    
    public function detail() {
        if (!is_ajax()) {
            return;
        }
        $id = $this->post->id;
        if ($id < 0) {
            return;
        }
        $data = array();
        $params = array();
        $params['where'] = array('id=?',array($this->post->id));
        $data = $this->comment_model->fetch_one($params);
        echo $data['comment_content'];
    }
}