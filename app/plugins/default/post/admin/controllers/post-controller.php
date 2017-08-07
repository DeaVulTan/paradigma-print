<?php
defined ( 'PF_VERSION' ) or header ( 'Location:404.html' );
class Post_Controller extends Pf_Controller{
    public function __construct(){
        parent::__construct();
        $this->load_model('post');
        $this->load_model('category');
        $this->load_model('tags');
        $this->load_model('post-tag');
        $this->view->menu_settings = get_option('admin_menu_setting');
        $this->setting = Pf::setting();
        if (($max_tag = $this->setting->get_element_value('pf_post','maximum_tag')) > 0) {
            $data['max_tags'] = $max_tag;
        } else {
            $data['max_tags'] = 10;
        }
        $this->post_model->rules = Pf::event()->trigger("filter","post-validation-rule",$this->post_model->rules);
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

        if (isset($this->get->search["post_title"]) && trim($this->get->search["post_title"]) != ""){
            $where .= $operator.' `post_title` like ? ';
            $where_values[] = '%'.$this->get->search["post_title"].'%';
            $operator = ' AND ';
        }

        if (isset($this->get->search["post_category"]) && trim($this->get->search["post_category"]) != ""){
            $where .= $operator.' `post_category` = ? ';
            $where_values[] = $this->get->search["post_category"];
            $operator = ' AND ';
        }

        //Search Status
        if (isset($this->get->search["post_status"])){
            if (is_array($this->get->search["post_status"])){
                if (!empty($this->get->search["post_status"])){
                    $where .= $operator.' ( ';
                    $operator1 = '';
                    foreach($this->get->search["post_status"] as $v){
                        $where .= $operator1.'  `post_status` = ?  OR `post_status` like ?  OR `post_status` like ? OR `post_status` like ? ';
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
                if (trim($this->get->search["post_status"]) != ""){
                    $where .= $operator.' (  `post_status` = ?  OR `post_status` like ?  OR `post_status` like ? OR `post_status` like ? )';
                    $where_values[] = $this->get->search["post_status"];
                    $where_values[] = $this->get->search["post_status"].',%';
                    $where_values[] = '%,'.$this->get->search["post_status"].',%';
                    $where_values[] = '%,'.$this->get->search["post_status"];
                    $operator = ' AND ';
                }
            }
        }

        //view all status
        if (isset($this->post->action) && trim($this->post->action) != ""){
            $where .= $operator.' `post_status` = ? ';
            $where_values[] = $this->post->id;
        }
        
        $params['fields'] = array(DB_PREFIX.'posts.id,post_title,post_created_date,post_status,category_name');
        $params['join'] = array('0' => array('LEFT',''.DB_PREFIX.'post_categories',''.DB_PREFIX.'posts.post_category = '.DB_PREFIX.'post_categories.id'));
        if (!empty($where_values)){
            $params['where'] = array($where,$where_values);
        }
        
        
        if (!empty($this->get->order_field) && !empty($this->get->order_type)){
            $params["order"] = "`".Pf::database ()->escape($this->get->order_field)."` ".Pf::database ()->escape($this->get->order_type);
        }

        $params = Pf::event()->trigger("filter","post-index-params",$params);

        
        $this->view->page_index = $params['page_index'];
        $this->view->records = $this->post_model->fetch($params,true);
        $this->view->total_records = $this->post_model->found_rows();
        $total_page = ceil($this->view->total_records/NUM_PER_PAGE);
        $list_category = $this->get_category();
        if(isset($list_category) && $list_category != NULL){
            foreach($list_category as $key => $value){
                $list_all_category[$value['id']] = $value['category_name'];
            }
        }
        $this->view->list_category = $list_all_category;
        if (empty($this->view->records) && $total_page > 0){
            $this->get->{$this->page} = $params['page_index'] = $total_page; 
            $this->view->page_index = $params['page_index'];
            $this->view->records = $this->post_model->fetch($params,true);
            $this->view->total_records = $this->post_model->found_rows();
        }
        $this->view->pagination = new Pf_Paginator($this->view->total_records, NUM_PER_PAGE, $this->page);
        
        $template = null;
        $template = Pf::event()->trigger("filter","post-index-template",$template);
        
        $this->view->render($template);
    }
    
    public function add(){
        $this->post_model->rules = Pf::event()->trigger("filter","post-adding-validation-rule",$this->post_model->rules);
        
        $template = null;
        $template = Pf::event()->trigger("filter","post-add-template",$template);
        
        if ($this->request->is_post()){
            $data = array();
            $tags = $this->post->{"tags"};
            
            $data["post_title"] = $this->post->{"post_title"};
            $data["post_category"] = $this->post->{"post_category"};
            $data["post_status"] = $this->post->{"post_status"};
            $data["post_allow_comment"] = $this->post->{"post_allow_comment"};
            $data["post_published_date"] = str_to_mysqldate($this->post->{"post_published_date"},$this->post_model->elements_value["post_published_date"],"Y-m-d H:i:s");
            $data["post_unpublished_date"] = str_to_mysqldate($this->post->{"post_unpublished_date"},$this->post_model->elements_value["post_unpublished_date"],"Y-m-d H:i:s");
            $data["post_allow_rating"] = $this->post->{"post_allow_rating"};
            $data["post_thumbnail"] = $this->post->{"post_thumbnail"};
            $data["post_content"] = $this->post->{"post_content"};
            $data["post_author"] = current_user('user-id');
            $data["post_created_date"] = date("Y-m-d H:i:s");
            $data = Pf::event()->trigger("filter","post-post-data",$data);
            $data = Pf::event()->trigger("filter","post-adding-post-data",$data);
            
            $var = array();
            if (!$this->post_model->insert($data)){
                $this->view->errors = $this->post_model->errors;
                
                //get list category
                $list_category = $this->get_category();
                if(isset($list_category) && $list_category != NULL){
                    foreach($list_category as $key => $value){
                        $list_all_category[$value['id']] = $value['category_name'];
                    }
                }
                $data['list_all_category'] = $list_all_category;
                $this->post->datas($data);
                $var['content'] = $this->view->fetch($template);
                $var['error'] = 1;
            }else{
                $id = $this->post_model->insert_id();
                if ($id) {
                    $this->tags($tags, $id);
                }
                Pf::event()->trigger("action","post-add-successfully",$this->post_model->insert_id(),$data);
                $var['error'] = 0;
                $var['url'] = admin_url($this->action.'=index&ajax=&id=&token=');
            }
            
            echo json_encode($var);
        }else{
            //get list category
            $list_category = $this->get_category();
            if(isset($list_category) && $list_category != NULL){
                foreach($list_category as $key => $value){
                    $list_all_category[$value['id']] = $value['category_name'];
                }
            }
            $data['list_all_category'] = $list_all_category;
            $data['num_tags'] = (int) $data['max_tags'] > 0 ? (int) $data['max_tags'] : 10;
            $this->post->datas($data);

            $this->view->render($template);
        }
    }
    
    public function edit(){
        $this->post_model->rules = Pf::event()->trigger("filter","post-editing-validation-rule",$this->post_model->rules);
        $template = null;
        $template = Pf::event()->trigger("filter","post-edit-template",$template);
        
        $var = array();
        
        if (isset($this->get->id) && isset($this->get->token) && Pf_Plugin_CSRF::is_valid($this->get->token, $this->key.$this->get->id)){
            if ($this->request->is_post()){
                $data = array();
                $tags = $this->post->{"tags"};
                $data['id'] = $this->get->id;
                
                
                $data["post_title"] = $this->post->{"post_title"};
                $data["post_category"] = $this->post->{"post_category"};
                $data["post_status"] = $this->post->{"post_status"};
                $data["post_allow_comment"] = $this->post->{"post_allow_comment"};
                $data["post_published_date"] = str_to_mysqldate($this->post->{"post_published_date"},$this->post_model->elements_value["post_published_date"],"Y-m-d H:i:s");
                $data["post_unpublished_date"] = str_to_mysqldate($this->post->{"post_unpublished_date"},$this->post_model->elements_value["post_unpublished_date"],"Y-m-d H:i:s");
                $data["post_allow_rating"] = $this->post->{"post_allow_rating"};
                $data["post_thumbnail"] = $this->post->{"post_thumbnail"};
                $data["post_content"] = $this->post->{"post_content"};
                
                $data = Pf::event()->trigger("filter","post-post-data",$data);
                $data = Pf::event()->trigger("filter","post-editing-post-data",$data);
                
                $this->view->token = Pf_Plugin_CSRF::token($this->key.$this->get->id);
                
                if (!$this->post_model->save($data)){
                    $this->view->errors = $this->post_model->errors;
                    //get list category
                    $list_category = $this->get_category();
                    if(isset($list_category) && $list_category != NULL){
                        foreach($list_category as $key => $value){
                            $list_all_category[$value['id']] = $value['category_name'];
                        }
                    }
                    $data['list_all_category'] = $list_all_category;
                    $this->post->datas($data);
                    $var['content'] = $this->view->fetch($template);
                    $var['error'] = 1;
                }else{
                    Pf::event()->trigger("action","post-edit-successfully",$data);
                    $this->del_list_tag($data['id']);
                    $this->tags($tags, $data['id']);
                    //get list category
                    $list_category = $this->get_category();
                    if(isset($list_category) && $list_category != NULL){
                        foreach($list_category as $key => $value){
                            $list_all_category[$value['id']] = $value['category_name'];
                        }
                    }
                    $data['list_all_category'] = $list_all_category;
                    $this->post->datas($data);
                    $var['error'] = 0;
                    $var['content'] = $this->view->fetch($template);
                    $var['url'] = admin_url($this->action.'=index&ajax=&id=&token=');
                }
            }else{
                if (isset($this->get->id)){
                    $params = array();
                    $params['where'] = array('id=?',array((int)$this->get->id));
                    $data = $this->post_model->fetch_one($params);
                    
                    $data["post_published_date"] =  str_to_mysqldate($data["post_published_date"],"Y-m-d",$this->post_model->elements_value["post_published_date"]);
                    $data["post_unpublished_date"] =  str_to_mysqldate($data["post_unpublished_date"],"Y-m-d",$this->post_model->elements_value["post_unpublished_date"]);
                    
                    $data = Pf::event()->trigger("filter","post-database-data",$data);
                    $data = Pf::event()->trigger("filter","post-editing-database-data",$data);
                    
                    //get list category
                    $list_category = $this->get_category();
                    if(isset($list_category) && $list_category != NULL){
                        foreach($list_category as $key => $value){
                            $list_all_category[$value['id']] = $value['category_name'];
                        }
                    }
                    $data['list_all_category'] = $list_all_category;
                    
                    //list tag
                    $list_edit_tag = $this->get_list_tag($data['id']);
                    foreach($list_edit_tag as $value){
                        $all_tag[] = $value['post_tag_name'];
                    }
                    if($all_tag != NULL){
                        $list_tag = implode(',', $all_tag);
                    }else{
                        $list_tag = '';
                    }
                    $data['list_all_tag'] = $list_tag;
                    
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
        $this->post_model->rules = Pf::event()->trigger("filter","post-copy-validation-rule",$this->post_model->rules);
    
        $template = null;
        $template = Pf::event()->trigger("filter","post-edit-template",$template);
        
        $var = array();
        
        if (isset($this->get->id) && isset($this->get->token) && Pf_Plugin_CSRF::is_valid($this->get->token, $this->key.$this->get->id)){
            if ($this->request->is_post()){
                $data = array();
                $tags = $this->post->{"tags"};
                
                $data["post_title"] = $this->post->{"post_title"};
                $data["post_category"] = $this->post->{"post_category"};
                $data["post_status"] = $this->post->{"post_status"};
                $data["post_allow_comment"] = $this->post->{"post_allow_comment"};
                $data["post_published_date"] = str_to_mysqldate($this->post->{"post_published_date"},$this->post_model->elements_value["post_published_date"],"Y-m-d H:i:s");
                $data["post_unpublished_date"] = str_to_mysqldate($this->post->{"post_unpublished_date"},$this->post_model->elements_value["post_unpublished_date"],"Y-m-d H:i:s");
                $data["post_allow_rating"] = $this->post->{"post_allow_rating"};
                $data["post_thumbnail"] = $this->post->{"post_thumbnail"};
                $data["post_content"] = $this->post->{"post_content"};
                $data["post_author"] = current_user('user-id');
                $data["post_created_date"] = date("Y-m-d H:i:s");
                
                $data = Pf::event()->trigger("filter","post-post-data",$data);
                $data = Pf::event()->trigger("filter","post-copy-post-data",$data);
                
                $this->view->token = Pf_Plugin_CSRF::token($this->key.$this->get->id);
                
                if (!$this->post_model->insert($data)){
                    $this->view->errors = $this->post_model->errors;
                    //get list category
                    $list_category = $this->get_category();
                    if(isset($list_category) && $list_category != NULL){
                        foreach($list_category as $key => $value){
                            $list_all_category[$value['id']] = $value['category_name'];
                        }
                    }
                    $data['list_all_category'] = $list_all_category;
                    $this->post->datas($data);
                    
                    $var['content'] = $this->view->fetch($template);
                    $var['error'] = 1;
                }else{
                    $id = $this->post_model->insert_id();
                    if ($id) {
                        $this->tags($tags, $id);
                    }
                    Pf::event()->trigger("action","post-copy-successfully",$data);
                    $var['error'] = 0;
                    $var['url'] = admin_url($this->action.'=index&ajax=&id=&token=');
                }
                
            }else{
                if (isset($this->get->id)){
                    $params = array();
                    $params['where'] = array('id=?',array((int)$this->get->id));
                    $data = $this->post_model->fetch_one($params);
                    
                    $data["post_published_date"] =  str_to_mysqldate($data["post_published_date"],"Y-m-d",$this->post_model->elements_value["post_published_date"]);
                    $data["post_unpublished_date"] =  str_to_mysqldate($data["post_unpublished_date"],"Y-m-d",$this->post_model->elements_value["post_unpublished_date"]);
                    
                    $data = Pf::event()->trigger("filter","post-database-data",$data);
                    $data = Pf::event()->trigger("filter","post-copy-database-data",$data);
                    
                    //get list category
                    $list_category = $this->get_category();
                    if(isset($list_category) && $list_category != NULL){
                        foreach($list_category as $key => $value){
                            $list_all_category[$value['id']] = $value['category_name'];
                        }
                    }
                    $data['list_all_category'] = $list_all_category;
                    
                    //list tag
                    $list_edit_tag = $this->get_list_tag($this->get->id);
                    foreach($list_edit_tag as $value){
                        $all_tag[] = $value['post_tag_name'];
                    }
                    $list_tag = implode(',', $all_tag);
                    $data['list_all_tag'] = $list_tag;
                    
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
                            $this->post_model->delete('id=?',array($id));
                        }
                    }
                    $var['action'] = 'delete';
                break;
                case 'publish':
                    if (!empty($this->post->id) && is_array($this->post->id)){
                        foreach ($this->post->id as $id){
                            $params['where'] = array('id=?',array($id));
                            $data = $this->post_model->fetch_one($params);
                            $data['post_status'] = 1;
                            $this->post_model->save($data);
                        }
                    }
                    $var['action'] = 'publish';
                break;
                case 'unpublish':
                    if (!empty($this->post->id) && is_array($this->post->id)){
                        foreach ($this->post->id as $id){
                            $params['where'] = array('id=?',array($id));
                            $data = $this->post_model->fetch_one($params);
                            $data['post_status'] = 2;
                            $this->post_model->save($data);
                        }
                    }
                    $var['action'] = 'unpublish';
                break;
            }
            Pf::event()->trigger("action","post-bulk-action-successfully",$this->get->type,$this->post->id);
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
            if ($this->post_model->delete('id=?',array($this->get->id))){
                $var['error'] = 0;
                Pf::event()->trigger("action","post-delete-successfully",$this->get->id);
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
        $data = $this->post_model->fetch_one($params);
    
        switch ($status) {
            case 'publish':
                $data['post_status'] = 1;
                $this->post_model->save($data);
                break;
            case 'unpublish':
                $data['post_status'] = 2;
                $this->post_model->save($data);
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
    
    /* Tags manager */
    
    public function ajax_load_tag(){
        if (!is_ajax()) {
            return;
        }
        
        $params = array();
        $where = '';
        $where_values = array();
        $operator = '';
        
        $params['limit'] = NUM_PER_PAGE;
        $params['page_index'] = (isset($this->get->{$this->page}))?(int)$this->get->{$this->page}:1;
        if (!empty($where_values)){
            $params['where'] = array($where,$where_values);
        }
        
        $params["order"] = "`".Pf::database ()->escape('id')."` ".Pf::database ()->escape('DESC');
        
        $params = Pf::event()->trigger("filter","tags-index-params",$params);
        
        $this->view->page_index = $params['page_index'];
        $this->view->records = $this->tags_model->fetch($params,true);
        $this->view->total_records = $this->tags_model->found_rows();
        $total_page = ceil($this->view->total_records/NUM_PER_PAGE);
        if (empty($this->view->records) && $total_page > 0){
            $this->post->{$this->get} = $params['page_index'] = $total_page;
            $this->view->page_index = $params['page_index'];
            $this->view->records = $this->tags_model->fetch($params,true);
            $this->view->total_records = $this->tags_model->found_rows();
        }
        $this->view->pagination = new Pf_Paginator($this->view->total_records, NUM_PER_PAGE, $this->page);
        $result = array(
            'table' => show_table_tag($this->view->records),
            'pagination' => $this->view->pagination->page_links(admin_url('?', false))
        );
        
        echo json_encode($result);
    }
    
    private function insert_post_tag($tag_list, $post_id){
        if (!empty($tag_list)) {
            $data = array_unique($tag_list);
            $data_insert = array();
            foreach ($data as $value) {
                $data_insert[] = array($post_id,$value);
            }
            
            foreach($data_insert as $key => $value){
                $data_tag[$key]['post_tag_post_id'] = $value[0];
                $data_tag[$key]['post_tag_name'] = $value[1];
                $data_tag[$key]['post_tag_rewrite'] = url_title($value[1]);
            }

            $this->post_tag_model->insert_bulk($data_tag);
        }
    }
    
    private function tags($tags, $post_id){
    
        if ($tags !== '') {
            $max_tags = (int) $data['max_tags'] > 0 ? (int) $data['max_tags'] : 10;
            $tag_lists = array_slice(explode(',', $tags), 0, $max_tags);
            $this->insert_post_tag($tag_lists, $post_id);
        }
    }
    
    private function get_list_tag($id){
        $where = ' `post_tag_post_id` = ? ';
        $where_values[] = $id;
        
        if (!empty($where_values)){
            $params['where'] = array($where,$where_values);
        }
        
        return $this->post_tag_model->fetch($params,true);
    }
    
    private function del_list_tag($id){
        $this->post_tag_model->delete('post_tag_post_id=?',array($id));
    }
}