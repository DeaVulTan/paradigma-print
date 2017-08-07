<?php
defined ( 'PF_VERSION' ) or header ( 'Location:404.html' );
class Announcement_Controller extends Pf_Controller{
    public function __construct(){
        parent::__construct();
        $this->load_model('announcement');
        $this->view->menu_settings = get_option('admin_menu_setting');
        $this->announcement_model->rules = Pf::event()->trigger("filter","announcement-validation-rule",$this->announcement_model->rules);
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

        if (isset($this->get->search["announcement_content"]) && trim($this->get->search["announcement_content"]) != ""){
            $where .= $operator.' `announcement_content` like ? ';
            $where_values[] = '%'.$this->get->search["announcement_content"].'%';
            $operator = ' AND ';
        }

        if (isset($this->get->search["announcement_pubdate"]) && trim($this->get->search["announcement_pubdate"]) != ""){
            $where .= $operator.' `announcement_pubdate` = ? ';
            $where_values[] = str_to_mysqldate($this->get->search["announcement_pubdate"],$this->announcement_model->elements_value["announcement_pubdate"],"Y-m-d");
            $operator = ' AND ';
        }

        if (isset($this->get->search["announcement_unpubdate"]) && trim($this->get->search["announcement_unpubdate"]) != ""){
            $where .= $operator.' `announcement_unpubdate` = ? ';
            $where_values[] = str_to_mysqldate($this->get->search["announcement_unpubdate"],$this->announcement_model->elements_value["announcement_unpubdate"],"Y-m-d");
            $operator = ' AND ';
        }
        if (isset($this->post->action) && trim($this->post->action) != ""){
            $where .= $operator.' `announcement_status` = ? ';
            $where_values[] = $this->post->id;
        }
        if (isset($this->get->search["announcement_status"])){
            if (is_array($this->get->search["announcement_status"])){
                if (!empty($this->get->search["announcement_status"])){
                    $where .= $operator.' ( ';
                    $operator1 = '';
                    foreach($this->get->search["announcement_status"] as $v){
                        $where .= $operator1.'  `announcement_status` = ?  OR `announcement_status` like ?  OR `announcement_status` like ? OR `announcement_status` like ? ';
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
                if (trim($this->get->search["announcement_status"]) != ""){
                    $where .= $operator.' (  `announcement_status` = ?  OR `announcement_status` like ?  OR `announcement_status` like ? OR `announcement_status` like ? )';
                    $where_values[] = $this->get->search["announcement_status"];
                    $where_values[] = $this->get->search["announcement_status"].',%';
                    $where_values[] = '%,'.$this->get->search["announcement_status"].',%';
                    $where_values[] = '%,'.$this->get->search["announcement_status"];
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

        $params = Pf::event()->trigger("filter","announcement-index-params",$params);

        
        $this->view->page_index = $params['page_index'];
        $this->view->records = $this->announcement_model->fetch($params,true);
        $this->view->total_records = $this->announcement_model->found_rows();
        $total_page = ceil($this->view->total_records/NUM_PER_PAGE);
        if (empty($this->view->records) && $total_page > 0){
            $this->get->{$this->page} = $params['page_index'] = $total_page; 
            $this->view->page_index = $params['page_index'];
            $this->view->records = $this->announcement_model->fetch($params,true);
            $this->view->total_records = $this->announcement_model->found_rows();
        }
        $this->view->pagination = new Pf_Paginator($this->view->total_records, NUM_PER_PAGE, $this->page);
        
        $template = null;
        $template = Pf::event()->trigger("filter","announcement-index-template",$template);
        
        $this->view->render($template);
    }
    
    public function add(){
        $this->announcement_model->rules = Pf::event()->trigger("filter","announcement-adding-validation-rule",$this->announcement_model->rules);
        
        $template = null;
        $template = Pf::event()->trigger("filter","announcement-add-template",$template);
        
        if ($this->request->is_post()){
            $data = array();
                $status             =   !empty($this->post->{"announcement_status"})?1:2;
                $data["announcement_status"] = $status;
                if($this->post->{"announcement_pubdate"} == NULL){
                    $data["announcement_pubdate"] = NULL;
                }else{
                     $data["announcement_pubdate"] = str_to_mysqldate($this->post->{"announcement_pubdate"},$this->announcement_model->elements_value["announcement_pubdate"],"Y-m-d H:i:s");
                }
                if($this->post->{"announcement_unpubdate"} == NULL){
                    $data["announcement_unpubdate"] = NULL;
                }else{
                     $data["announcement_unpubdate"] = str_to_mysqldate($this->post->{"announcement_unpubdate"},$this->announcement_model->elements_value["announcement_unpubdate"],"Y-m-d H:i:s");
                }
                $data["announcement_type"] = $this->post->{"announcement_type"};
                $data["announcement_author"] = current_user('user-name');
                if($this->post->{"announcement_touser"} != NULL){
                    $toUser = explode(',',e($this->post->{"announcement_touser"}));
                }else{
                    $toUser = NULL;
                }
                $toGroup =  !empty($this->post->{"togroup"})?$this->post->{"togroup"}:array();
                $data["announcement_to"] = serialize(array('togroup'=>$toGroup,'touser' =>$toUser));
                $data["announcement_content"] = e($this->post->{"announcement_content"});
            $data = Pf::event()->trigger("filter","announcement-post-data",$data);
            $data = Pf::event()->trigger("filter","announcement-adding-post-data",$data);
            $var = array();
            if (!$this->announcement_model->insert($data)){
                $this->view->errors = $this->announcement_model->errors;
                $var['content'] = $this->view->fetch($template);
                $var['error'] = 1;
            }else{
                Pf::event()->trigger("action","announcement-add-successfully",$this->announcement_model->insert_id(),$data);
                $var['error'] = 0;
                $var['url'] = admin_url($this->action.'=index&ajax=&id=&token=');
            }
            
            echo json_encode($var);
        }else{
        
            $this->view->render($template);
        }
    }
    
    public function edit(){
        $this->announcement_model->rules = Pf::event()->trigger("filter","announcement-editing-validation-rule",$this->announcement_model->rules);
        $template = null;
        $template = Pf::event()->trigger("filter","announcement-edit-template",$template);
        
        $var = array();
        
        if (isset($this->get->id) && isset($this->get->token) && Pf_Plugin_CSRF::is_valid($this->get->token, $this->key.$this->get->id)){
            if ($this->request->is_post()){
                $data = array();
                $data['id'] = $this->get->id;
                $status             =   !empty($this->post->{"announcement_status"})?1:2;
                $data["announcement_status"] = $status;
                if($this->post->{"announcement_pubdate"} == NULL){
                    $data["announcement_pubdate"] = NULL;
                }else{
                    $data["announcement_pubdate"] = str_to_mysqldate($this->post->{"announcement_pubdate"},$this->announcement_model->elements_value["announcement_pubdate"],"Y-m-d H:i:s");
                }
                if($this->post->{"announcement_unpubdate"} == NULL){
                    $data["announcement_unpubdate"] = NULL;
                }else{
                    $data["announcement_unpubdate"] = str_to_mysqldate($this->post->{"announcement_unpubdate"},$this->announcement_model->elements_value["announcement_unpubdate"],"Y-m-d H:i:s");
                }
                $data["announcement_type"] = $this->post->{"announcement_type"};
                $data["announcement_author"] = current_user('user-name');
                if($this->post->{"announcement_touser"} != NULL){
                    $toUser = explode(',',e($this->post->{"announcement_touser"}));
                }else{
                    $toUser = NULL;
                }
                $toGroup =  !empty($this->post->{"togroup"})?$this->post->{"togroup"}:array();
                $data["announcement_to"] = serialize(array('togroup'=>$toGroup,'touser' =>$toUser));
                $data["announcement_content"] = e($this->post->{"announcement_content"});
                $data = Pf::event()->trigger("filter","announcement-post-data",$data);
                $data = Pf::event()->trigger("filter","announcement-editing-post-data",$data);
                $this->view->token = Pf_Plugin_CSRF::token($this->key.$this->get->id);
                if (!$this->announcement_model->save($data)){
                    $this->view->errors = $this->announcement_model->errors;
                    $var['content'] = $this->view->fetch($template);
                    $var['error'] = 1;
                }else{
                    $params = array();
                    $params['where'] = array('id=?',array((int)$this->get->id));
                    $data = $this->announcement_model->fetch_one($params);
                    $to = unserialize($data["announcement_to"]);
                    $data['touser'] = $to['touser'];
                    $data['togroup'] = $to['togroup']; 
                    Pf::event()->trigger("action","announcement-edit-successfully",$data);
                    $this->post->datas($data);
                    $var['error'] = 0;
                    $var['content'] = $this->view->fetch($template);
                    $var['url'] = admin_url($this->action.'=index&ajax=&id=&token=');
                }
            }else{
                if (isset($this->get->id)){
                    $params = array();
                    $params['where'] = array('id=?',array((int)$this->get->id));
                    $data = $this->announcement_model->fetch_one($params);
                    $to = unserialize($data["announcement_to"]);
                    $data['touser'] = $to['touser'];
                    $data['togroup'] = $to['togroup'];
                    $data = Pf::event()->trigger("filter","announcement-database-data",$data);
                    $data = Pf::event()->trigger("filter","announcement-editing-database-data",$data);
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
        $this->announcement_model->rules = Pf::event()->trigger("filter","announcement-copy-validation-rule",$this->announcement_model->rules);
        $template = null;
        $template = Pf::event()->trigger("filter","announcement-edit-template",$template);
        $var = array();
        if (isset($this->get->id) && isset($this->get->token) && Pf_Plugin_CSRF::is_valid($this->get->token, $this->key.$this->get->id)){
            if ($this->request->is_post()){
                $data = array();
                $status             =   !empty($this->post->{"announcement_status"})?1:2;
                $data["announcement_status"] = $status;
                if($this->post->{"announcement_pubdate"} == NULL){
                    $data["announcement_pubdate"] = NULL;
                }else{
                     $data["announcement_pubdate"] = str_to_mysqldate($this->post->{"announcement_pubdate"},$this->announcement_model->elements_value["announcement_pubdate"],"Y-m-d H:i:s");
                }
                if($this->post->{"announcement_unpubdate"} == NULL){
                    $data["announcement_unpubdate"] = NULL;
                }else{
                     $data["announcement_unpubdate"] = str_to_mysqldate($this->post->{"announcement_unpubdate"},$this->announcement_model->elements_value["announcement_unpubdate"],"Y-m-d H:i:s");
                }
                $data["announcement_type"] = $this->post->{"announcement_type"};
                $data["announcement_author"] = current_user('user-id');
                if($this->post->{"announcement_touser"} != NULL){
                    $toUser = explode(',',e($this->post->{"announcement_touser"}));
                }else{
                    $toUser = NULL;
                }
                $toGroup =  !empty($this->post->{"togroup"})?$this->post->{"togroup"}:array();
                $data["announcement_to"] = serialize(array('togroup'=>$toGroup,'touser' =>$toUser));
                $data["announcement_content"] = e($this->post->{"announcement_content"});
                $data = Pf::event()->trigger("filter","announcement-post-data",$data);
                $data = Pf::event()->trigger("filter","announcement-copy-post-data",$data);
                $this->view->token = Pf_Plugin_CSRF::token($this->key.$this->get->id);
                if (!$this->announcement_model->insert($data)){
                    $this->view->errors = $this->announcement_model->errors;
                    $var['content'] = $this->view->fetch($template);
                    $var['error'] = 1;
                }else{
                    Pf::event()->trigger("action","announcement-copy-successfully",$data);
                    $var['error'] = 0;
                    $var['url'] = admin_url($this->action.'=index&ajax=&id=&token=');
                }
            }else{
                if (isset($this->get->id)){
                    $params = array();
                    $params['where'] = array('id=?',array((int)$this->get->id));
                    $data = $this->announcement_model->fetch_one($params);
                    $to = unserialize($data["announcement_to"]);
                    $data['touser'] = $to['touser'];
                    $data['togroup'] = $to['togroup'];
                    $data = Pf::event()->trigger("filter","announcement-database-data",$data);
                    $data = Pf::event()->trigger("filter","announcement-copy-database-data",$data);
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
                            $this->announcement_model->delete('id=?',array($id));
                        }
                    }
                    break;
                case 'publish':
                    if (!empty($this->post->id) && is_array($this->post->id)){
                        foreach ($this->post->id as $id){
                            $params['where'] = array('id=?',array($id));
                            $data = $this->announcement_model->fetch_one($params);
                            $data['announcement_status'] = 1;
                            $this->announcement_model->save($data);
                        }
                    }
                
                    $var['action'] = 'publish';
                    break;
                case 'unpublish':
                    if (!empty($this->post->id) && is_array($this->post->id)){
                        foreach ($this->post->id as $id){
                            $params['where'] = array('id=?',array($id));
                            $data = $this->announcement_model->fetch_one($params);
                            $data['announcement_status'] = 2;
                            $this->announcement_model->save($data);
                        }
                    }
                    $var['action'] = 'unpublish';
                    break;
            }
            Pf::event()->trigger("action","announcement-bulk-action-successfully",$this->get->type,$this->post->id);
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
            if ($this->announcement_model->delete('id=?',array($this->get->id))){
                $var['error'] = 0;
                Pf::event()->trigger("action","announcement-delete-successfully",$this->get->id);
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
        $data = $this->announcement_model->fetch_one($params);
    
        switch ($status) {
            case 'publish':
                $data['announcement_status'] = 1;
                $this->announcement_model->save($data);
                break;
            case 'unpublish':
                $data['announcement_status'] = 2;
                $this->announcement_model->save($data);
                break;
        }
    }
}