<?php
defined ( 'PF_VERSION' ) or header ( 'Location:404.html' );
class Portfolio_Controller extends Pf_Controller{
    public function __construct(){
        parent::__construct();
        $this->load_model('portfolio');
        $this->load_model('category');
        $this->load_model('meta');
        $this->view->menu_settings = get_option('admin_menu_setting');
        $this->portfolio_model->rules = Pf::event()->trigger("filter","portfolio-validation-rule",$this->portfolio_model->rules);
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
        if (isset($this->get->search["portfolio_name"]) && trim($this->get->search["portfolio_name"]) != ""){
            $where .= $operator.' `portfolio_name` like ? ';
            $where_values[] = '%'.$this->get->search["portfolio_name"].'%';
            $operator = ' AND ';
        }
        if (isset($this->get->search["portfolio_description"]) && trim($this->get->search["portfolio_description"]) != ""){
            $where .= $operator.' `portfolio_description` like ? ';
            $where_values[] = '%'.$this->get->search["portfolio_description"].'%';
            $operator = ' AND ';
        }
        if (isset($this->post->action1) && trim($this->post->action1) != ""){
            if($this->post->id != '' && $this->post->cid != ''){
                $where .= $operator.' `portfolio_status` = ? ';
                $where_values[] = $this->post->id;
                $operator = ' AND ';
                $where .= $operator.' `portfolio_category` = ? ';
                $where_values[] = $this->post->cid;
                $operator = ' AND ';
            }else if ($this->post->cid == '' && $this->post->id != ''){
                $where .= $operator.' `portfolio_status` = ? ';
                $where_values[] = $this->post->id;
                $operator = ' AND ';
            }
            else if($this->post->cid != ''){
                $where .= $operator.' `portfolio_category` = ? ';
                $where_values[] = $this->post->cid;
                $operator = ' AND ';
            }
            else if($this->post->cid == ''){
                $where = '';
                $where_values[] = '';
                $operator = ' AND ';
            }
           
        }
        if (isset($this->post->action) && trim($this->post->action) != ""){
              if($this->post->cid != '' && $this->post->id != ''){
                   $where .= $operator.' `portfolio_category` = ? ';
                   $where_values[] = $this->post->cid;
                   $operator = ' AND ';
                   $where .= $operator.' `portfolio_status` = ? ';
                   $where_values[] = $this->post->id;
                   $operator = ' AND ';
               }else if($this->post->id != ''){
                   $where .= $operator.' `portfolio_status` = ? ';
                   $where_values[] = $this->post->id;
                   $operator = ' AND ';
               }else if($this->post->cid != '' && $this->post->id == ''){
                   $where .= $operator.' `portfolio_category` = ? ';
                   $where_values[] = $this->post->cid;
                   $operator = ' AND ';
               }else if($this->post->id == ''){
                   $where = '';
                   $where_values[] = '';
                   $operator = ' AND ';
               }
        }
        if (isset($this->get->search["portfolio_status"])){
                if (is_array($this->get->search["portfolio_status"])){
                    if (!empty($this->get->search["portfolio_status"])){
                        $where .= $operator.' ( ';
                        $operator1 = '';
                        foreach($this->get->search["portfolio_status"] as $v){
                            $where .= $operator1.'  `portfolio_status` = ?  OR `portfolio_status` like ?  OR `portfolio_status` like ? OR `portfolio_status` like ? ';
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
                    if (trim($this->get->search["portfolio_status"]) != ""){
                        $where .= $operator.' (  `portfolio_status` = ?  OR `portfolio_status` like ?  OR `portfolio_status` like ? OR `portfolio_status` like ? )';
                        $where_values[] = $this->get->search["portfolio_status"];
                        $where_values[] = $this->get->search["portfolio_status"].',%';
                        $where_values[] = '%,'.$this->get->search["portfolio_status"].',%';
                        $where_values[] = '%,'.$this->get->search["portfolio_status"];
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
        $params = Pf::event()->trigger("filter","portfolio-index-params",$params);
        
        $this->view->page_index = $params['page_index'];
        $this->view->records = $this->portfolio_model->fetch($params,true);
        $this->view->total_records = $this->portfolio_model->found_rows();
        $total_page = ceil($this->view->total_records/NUM_PER_PAGE);
        if (empty($this->view->records) && $total_page > 0){
            $this->get->{$this->page} = $params['page_index'] = $total_page; 
            $this->view->page_index = $params['page_index'];
            $this->view->records = $this->portfolio_model->fetch($params,true);
            $this->view->total_records = $this->portfolio_model->found_rows();
        }
        $this->view->pagination = new Pf_Paginator($this->view->total_records, NUM_PER_PAGE, $this->page);
        $template = null;
        $template = Pf::event()->trigger("filter","portfolio-index-template",$template);
        $this->view->render($template);
    }
    
    public function add(){
        $this->portfolio_model->rules = Pf::event()->trigger("filter","portfolio-adding-validation-rule",$this->portfolio_model->rules);
        
        $template = null;
        $template = Pf::event()->trigger("filter","portfolio-add-template",$template);
        
        if ($this->request->is_post()){
                $data = array();
                $data["portfolio_name"] = e($this->post->{"portfolio_name"});
                $data["portfolio_category"] = $this->post->{"portfolio_category"};
                $data["portfolio_status"] = $this->post->{"portfolio_status"};
                $images = isset($this->post->{"portfolio_avatar"}) ? $this->post->{"portfolio_avatar"} : array();
                if(!empty($this->post->{"avatar"})){
                    $data['portfolio_avatar'] = $this->post->{"avatar"};
                }else{
                    $data['portfolio_avatar'] = $images[0];
                }
                $port_custom_name = isset($this->post->{"field_name"}) ? $this->post->{"field_name"} : array();
                $port_custom_value = isset($this->post->{"field_value"}) ? $this->post->{"field_value"} : array();
                $data["portfolio_items"] = serialize($images);
                $data["portfolio_description"] = e($this->post->{"portfolio_description"});
            $data = Pf::event()->trigger("filter","portfolio-post-data",$data);
            $data = Pf::event()->trigger("filter","portfolio-adding-post-data",$data);
            $var = array();
            Pf::database()->query('START TRANSACTION');
            $inserted = $this->portfolio_model->insert($data);
            if($inserted === false){
                Pf::database()->query('ROLLBACK');
            }else{
                $new_id = $this->portfolio_model->insert_id();
                $insert_meta = true;
                if(count($port_custom_name) > 0){
                    $custom = array();
                    $int = count($port_custom_name);
                    for ($i = 0; $i < $int ; $i++) {
                        $custom = array(
                                'meta_portfolio' => $new_id,
                                'meta_name' => e($port_custom_name[$i]),
                                'meta_value' => e($port_custom_value[$i]),
                        );
                        $insert_meta = $this->meta_model->insert($custom);
                    }
                    if($insert_meta === false){
                        Pf::database()->query('ROLLBACK');
                        break;
                    }else{
                        Pf::database()->query('COMMIT');
                    }
                }
                Pf::database()->query('COMMIT');
            }
            if (!$inserted){
                $this->view->errors = $this->portfolio_model->errors;
                $var['content'] = $this->view->fetch($template);
                $var['error'] = 1;
            }else{
                Pf::event()->trigger("action","portfolio-add-successfully",$this->portfolio_model->insert_id(),$data);
                $var['error'] = 0;
                $var['url'] = admin_url($this->action.'=index&ajax=&id=&token=');
            }
            
            echo json_encode($var);
        }else{
        
            $this->view->render($template);
        }
    }
    
    public function edit(){
        $this->portfolio_model->rules = Pf::event()->trigger("filter","portfolio-editing-validation-rule",$this->portfolio_model->rules);
        $template = null;
        $template = Pf::event()->trigger("filter","portfolio-edit-template",$template);
        $var = array();
        if (isset($this->get->id) && isset($this->get->token) && Pf_Plugin_CSRF::is_valid($this->get->token, $this->key.$this->get->id)){
            if ($this->request->is_post()){
                $data = array();
                $data['id'] = $this->get->id;
                $data["portfolio_name"] = e($this->post->{"portfolio_name"});
                $data["portfolio_category"] = $this->post->{"portfolio_category"};
                $data["portfolio_status"] = $this->post->{"portfolio_status"};
                $images = isset($this->post->{"portfolio_avatar"}) ? $this->post->{"portfolio_avatar"} : array();
                if(!empty($this->post->{"avatar"})){
                    $data['portfolio_avatar'] = $this->post->{"avatar"};
                }else{
                    $data['portfolio_avatar'] = $images[0];
                }
                $port_custom_name = isset($this->post->{"field_name"}) ? $this->post->{"field_name"} : array();
                $port_custom_value = isset($this->post->{"field_value"}) ? $this->post->{"field_value"} : array();
                $data["portfolio_items"] = serialize($images);
                $data["portfolio_description"] = e($this->post->{"portfolio_description"});
                $data = Pf::event()->trigger("filter","portfolio-post-data",$data);
                $data = Pf::event()->trigger("filter","portfolio-editing-post-data",$data);
                $this->view->token = Pf_Plugin_CSRF::token($this->key.$this->get->id);
                Pf::database()->query('START TRANSACTION');
                $save = $this->portfolio_model->save($data);
                if($save === false){
                    Pf::database()->query('ROLLBACK');
                }else{
                    $this->meta_model->delete('meta_portfolio=?',array($this->get->id));
                    $new_id = $this->get->id;
                    $insert_meta = true;
                    if(count($port_custom_name) > 0){
                        $custom = array();
                        $int = count($port_custom_name);
                        for ($i = 0; $i < $int ; $i++) {
                            $custom = array(
                                    'meta_portfolio' => $new_id,
                                    'meta_name' => e($port_custom_name[$i]),
                                    'meta_value' => e($port_custom_value[$i]),
                            );
                            $insert_meta = $this->meta_model->insert($custom);
                        }
                        if($insert_meta === false){
                            Pf::database()->query('ROLLBACK');
                            break;
                        }else{
                            Pf::database()->query('COMMIT');
                        }
                    }
                    Pf::database()->query('COMMIT');
                }
                if (!$save){
                    $this->view->errors = $this->portfolio_model->errors;
                    $var['content'] = $this->view->fetch($template);
                    $var['error'] = 1;
                }else{
                    Pf::event()->trigger("action","portfolio-edit-successfully",$data);
                    $var['error'] = 0;
                    $var['content'] = $this->view->fetch($template);
                    $var['url'] = admin_url($this->action.'=index&ajax=&id=&token=');
                }
            }else{
                if (isset($this->get->id)){
                    $params = array();
                    $params['where'] = array('id=?',array((int)$this->get->id));
                    $data = $this->portfolio_model->fetch_one($params);
                    $data['avatar'] = $data['portfolio_avatar'];
                    $data['portfolio_avatar'] = unserialize($data['portfolio_items']);
                    $data['custom'] = $this->meta_model->get_meta($this->get->id);
                    $data = Pf::event()->trigger("filter","portfolio-database-data",$data);
                    $data = Pf::event()->trigger("filter","portfolio-editing-database-data",$data);
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
        $this->portfolio_model->rules = Pf::event()->trigger("filter","portfolio-copy-validation-rule",$this->portfolio_model->rules);
    
        $template = null;
        $template = Pf::event()->trigger("filter","portfolio-edit-template",$template);
        
        $var = array();
        
        if (isset($this->get->id) && isset($this->get->token) && Pf_Plugin_CSRF::is_valid($this->get->token, $this->key.$this->get->id)){
            if ($this->request->is_post()){
                $data = array();
                $data["portfolio_name"] = e($this->post->{"portfolio_name"});
                $data["portfolio_category"] = $this->post->{"portfolio_category"};
                $data["portfolio_status"] = $this->post->{"portfolio_status"};
                $images = isset($this->post->{"portfolio_avatar"}) ? $this->post->{"portfolio_avatar"} : array();
                if(!empty($this->post->{"avatar"})){
                    $data['portfolio_avatar'] = $this->post->{"avatar"};
                }else{
                    $data['portfolio_avatar'] = $images[0];
                }
                $port_custom_name = isset($this->post->{"field_name"}) ? $this->post->{"field_name"} : array();
                $port_custom_value = isset($this->post->{"field_value"}) ? $this->post->{"field_value"} : array();
                $data["portfolio_items"] = serialize($images);
                $data["portfolio_description"] = e($this->post->{"portfolio_description"});
                $data = Pf::event()->trigger("filter","portfolio-post-data",$data);
                $data = Pf::event()->trigger("filter","portfolio-copy-post-data",$data);
                $this->view->token = Pf_Plugin_CSRF::token($this->key.$this->get->id);
                Pf::database()->query('START TRANSACTION');
                $inserted = $this->portfolio_model->insert($data);
                if($inserted === false){
                    Pf::database()->query('ROLLBACK');
                }else{
                    $new_id = $this->portfolio_model->insert_id();
                    $insert_meta = true;
                    if(count($port_custom_name) > 0){
                        $custom = array();
                        $int = count($port_custom_name);
                        for ($i = 0; $i < $int ; $i++) {
                            $custom = array(
                                    'meta_portfolio' => $new_id,
                                    'meta_name' => e($port_custom_name[$i]),
                                    'meta_value' => e($port_custom_value[$i]),
                            );
                            $insert_meta = $this->meta_model->insert($custom);
                        }
                        if($insert_meta === false){
                            Pf::database()->query('ROLLBACK');
                            break;
                        }else{
                            Pf::database()->query('COMMIT');
                        }
                    }
                    Pf::database()->query('COMMIT');
                }
                if (!$inserted){
                    $this->view->errors = $this->portfolio_model->errors;
                    $var['content'] = $this->view->fetch($template);
                    $var['error'] = 1;
                }else{
                    Pf::event()->trigger("action","portfolio-copy-successfully",$data);
                    $var['error'] = 0;
                    $var['url'] = admin_url($this->action.'=index&ajax=&id=&token=');
                }
                
            }else{
                if (isset($this->get->id)){
                    $params = array();
                    $params['where'] = array('id=?',array((int)$this->get->id));
                    $data = $this->portfolio_model->fetch_one($params);
                    $data['avatar'] = $data['portfolio_avatar'];
                    $data['portfolio_avatar'] = unserialize($data['portfolio_items']);
                    $data['custom'] = $this->meta_model->get_meta($this->get->id);
                    $data = Pf::event()->trigger("filter","portfolio-database-data",$data);
                    $data = Pf::event()->trigger("filter","portfolio-copy-database-data",$data);
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
                            $this->portfolio_model->delete('id=?',array($id));
                            $this->meta_model->delete('meta_portfolio=?',array($id));
                        }
                    }
                    break;
                    case 'publish':
                        if (!empty($this->post->id) && is_array($this->post->id)){
                            foreach ($this->post->id as $id){
                                $params['where'] = array('id=?',array($id));
                                $data = $this->portfolio_model->fetch_one($params);
                                $data['portfolio_status'] = 1;
                                $this->portfolio_model->save($data);
                            }
                        }
                    
                        $var['action'] = 'publish';
                        break;
                    case 'unpublish':
                        if (!empty($this->post->id) && is_array($this->post->id)){
                            foreach ($this->post->id as $id){
                                $params['where'] = array('id=?',array($id));
                                $data = $this->portfolio_model->fetch_one($params);
                                $data['portfolio_status'] = 0;
                                $this->portfolio_model->save($data);
                            }
                        }
                        $var['action'] = 'unpublish';
                        break;
            }
            Pf::event()->trigger("action","portfolio-bulk-action-successfully",$this->get->type,$this->post->id);
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
            if ($this->portfolio_model->delete('id=?',array($this->get->id))){
                $var['error'] = 0;
                Pf::event()->trigger("action","portfolio-delete-successfully",$this->get->id);
                
            }else{
                $var['error'] = 1;
            }
            $this->meta_model->delete('meta_portfolio=?',array($this->get->id));
        }
        $var['url'] = admin_url($this->action.'=index&ajax=&id=&token=');
        echo json_encode($var);
    }
    public function change_status() {
        $data = array();
        $status = $this->post->{"status"};
        $params = array();
        $params['where'] = array('id=?',array((int)$this->post->id));
        $data = $this->portfolio_model->fetch_one($params);
    
        switch ($status) {
            case 'publish':
                $data['portfolio_status'] = 1;
                $this->portfolio_model->save($data);
                break;
            case 'unpublish':
                $data['portfolio_status'] = 0;
                $this->portfolio_model->save($data);
                break;
        }
    }
}