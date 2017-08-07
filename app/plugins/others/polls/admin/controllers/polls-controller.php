<?php
defined ( 'PF_VERSION' ) or header ( 'Location:404.html' );
class Polls_Controller extends Pf_Controller{
    public function __construct(){
        parent::__construct();
        $this->load_model('polls');
        $this->load_model('ip');
        $this->load_model('answers');
        $this->view->menu_settings = get_option('admin_menu_setting');
        $this->polls_model->rules = Pf::event()->trigger("filter","polls-validation-rule",$this->polls_model->rules);
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

        if (isset($this->get->search["polls_question"]) && trim($this->get->search["polls_question"]) != ""){
            $where .= $operator.' `polls_question` like ? ';
            $where_values[] = '%'.$this->get->search["polls_question"].'%';
            $operator = ' AND ';
        }

        if (isset($this->post->action) && trim($this->post->action) != ""){
            $where .= $operator.' `polls_status` = ? ';
            $where_values[] = $this->post->id;
        }
        
        if (isset($this->get->search["polls_status"])){
            if (is_array($this->get->search["polls_status"])){
                if (!empty($this->get->search["polls_status"])){
                    $where .= $operator.' ( ';
                    $operator1 = '';
                    foreach($this->get->search["polls_status"] as $v){
                        $where .= $operator1.'  `polls_status` = ?  OR `polls_status` like ?  OR `polls_status` like ? OR `polls_status` like ? ';
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
                if (trim($this->get->search["polls_status"]) != ""){
                    $where .= $operator.' (  `polls_status` = ?  OR `polls_status` like ?  OR `polls_status` like ? OR `polls_status` like ? )';
                    $where_values[] = $this->get->search["polls_status"];
                    $where_values[] = $this->get->search["polls_status"].',%';
                    $where_values[] = '%,'.$this->get->search["polls_status"].',%';
                    $where_values[] = '%,'.$this->get->search["polls_status"];
                    $operator = ' AND ';
                }
            }
        }

        if (isset($this->get->search["polls_totalvote"]) && trim($this->get->search["polls_totalvote"]) != ""){
            $where .= $operator.' `polls_totalvote` like ? ';
            $where_values[] = '%'.$this->get->search["polls_totalvote"].'%';
            $operator = ' AND ';
        }

        
        if (!empty($where_values)){
            $params['where'] = array($where,$where_values);
        }
        
        
        if (!empty($this->get->order_field) && !empty($this->get->order_type)){
            $params["order"] = "`".Pf::database ()->escape($this->get->order_field)."` ".Pf::database ()->escape($this->get->order_type);
        }

        $params = Pf::event()->trigger("filter","polls-index-params",$params);

        
        $this->view->page_index = $params['page_index'];
        $this->view->records = $this->polls_model->fetch($params,true);
        $this->view->total_records = $this->polls_model->found_rows();
        $total_page = ceil($this->view->total_records/NUM_PER_PAGE);
        if (empty($this->view->records) && $total_page > 0){
            $this->get->{$this->page} = $params['page_index'] = $total_page; 
            $this->view->page_index = $params['page_index'];
            $this->view->records = $this->polls_model->fetch($params,true);
            $this->view->total_records = $this->polls_model->found_rows();
        }
        $this->view->pagination = new Pf_Paginator($this->view->total_records, NUM_PER_PAGE, $this->page);
        
        $template = null;
        $template = Pf::event()->trigger("filter","polls-index-template",$template);
        
        $this->view->render($template);
    }
    
    public function add(){
        $this->polls_model->rules = Pf::event()->trigger("filter","polls-adding-validation-rule",$this->polls_model->rules);
        
        $template = null;
        $template = Pf::event()->trigger("filter","polls-add-template",$template);
        if ($this->request->is_post()){
            $data = array();
            $data["polls_question"] = $this->post->{"polls_question"};
            $data["polls_pubdate"] = str_to_mysqldate($this->post->{"polls_pubdate"},$this->polls_model->elements_value["polls_pubdate"],"Y-m-d H:i:s");
            $data["polls_unpubdate"] = str_to_mysqldate($this->post->{"polls_unpubdate"},$this->polls_model->elements_value["polls_unpubdate"],"Y-m-d H:i:s");
            if (is_array($this->post->{"polls_status"})){
                $data["polls_status"] = implode(",",$this->post->{"polls_status"});
            }else{
                $data["polls_status"] = $this->post->{"polls_status"};
            }
            $port_answer = isset($this->post->{"answer"}) ? $this->post->{"answer"} : array();
            $data = Pf::event()->trigger("filter","polls-post-data",$data);
            $data = Pf::event()->trigger("filter","polls-adding-post-data",$data);
            $var = array();
            $pollq_multiple_yes = intval($this->post->{'pollq_multiple_yes'});
            $data['polls_multiple'] = 0;
            if ($pollq_multiple_yes == 1) {
                if(intval($this->post->{'pollq_multiple'}) > count($port_answer)){
                    $data['polls_multiple'] = 1;
                }else{
                    $data['polls_multiple'] = intval($this->post->{'pollq_multiple'});
                }
            } else {
                $data['polls_multiple'] = 1;
            }
            //debug($data);
            Pf::database()->query('START TRANSACTION');
            $inserted = $this->polls_model->insert($data);
            if($inserted === false){
                Pf::database()->query('ROLLBACK');
            }else{
                $new_id = $this->polls_model->insert_id();
                $insert_meta = true;
                if(count($port_answer) > 0){
                    $custom = array();
                    $int = count($port_answer);
                    for ($i = 0; $i < $int ; $i++) {
                        if(!empty($port_answer[$i])){
                            $custom = array(
                                    'pollsa_qid' => $new_id,
                                    'pollsa_answers' => e($port_answer[$i]),
                            );
                        }
                        $insert_meta = $this->answers_model->insert($custom);
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
            $errors = Pf::validator()->get_readable_errors(false);
            foreach ($errors as $key => $value) {
                $error[$key][0] = $errors[$key][0];
            }
            $this->view->errors =  $error;
            $var['content'] = $this->view->fetch($template);
            if (count($error) > 0){
                $var['error'] = 1;
            }else{
                Pf::event()->trigger("action","polls-add-successfully",$this->polls_model->insert_id(),$data);
                $var['error'] = 0;
                $var['url'] = admin_url($this->action.'=index&ajax=&id=&token=');
            }
            
            echo json_encode($var);
        }else{
        
            $this->view->render($template);
        }
    }
    
    public function edit(){
        $this->polls_model->rules = Pf::event()->trigger("filter","polls-editing-validation-rule",$this->polls_model->rules);
        $template = null;
        $template = Pf::event()->trigger("filter","polls-edit-template",$template);
        
        $var = array();
        
        if (isset($this->get->id) && isset($this->get->token) && Pf_Plugin_CSRF::is_valid($this->get->token, $this->key.$this->get->id)){
            if ($this->request->is_post()){
                $data = array();
                $data['id'] = $this->get->id;
                $data["polls_question"] = $this->post->{"polls_question"};
                $data["polls_pubdate"] = str_to_mysqldate($this->post->{"polls_pubdate"},$this->polls_model->elements_value["polls_pubdate"],"Y-m-d H:i:s");
                $data["polls_unpubdate"] = str_to_mysqldate($this->post->{"polls_unpubdate"},$this->polls_model->elements_value["polls_unpubdate"],"Y-m-d H:i:s");
                if (is_array($this->post->{"polls_status"})){
                    $data["polls_status"] = implode(",",$this->post->{"polls_status"});
                }else{
                    $data["polls_status"] = $this->post->{"polls_status"};
                }
                $port_answer = isset($this->post->{"answer"}) ? $this->post->{"answer"} : array();
                $pollq_multiple_yes = intval($this->post->{'pollq_multiple_yes'});
                $get_answers = $this->answers_model->get_answers($this->get->id);
                $number_answer = count($port_answer) + count($get_answers);
                $data['polls_multiple'] = 0;
                if ($pollq_multiple_yes == 1) {
                    if(intval($this->post->{'pollq_multiple'}) > $number_answer){
                        $data['polls_multiple'] = 1;
                    }else{
                        $data['polls_multiple'] = intval($this->post->{'pollq_multiple'});
                    }
                } else {
                    $data['polls_multiple'] = 1;
                }
                $data = Pf::event()->trigger("filter","polls-post-data",$data);
                $data = Pf::event()->trigger("filter","polls-editing-post-data",$data);
                $this->view->token = Pf_Plugin_CSRF::token($this->key.$this->get->id);
                if (!$this->polls_model->save($data)){
                    $data['get_answers'] = $this->answers_model->get_answers($this->get->id);
                    $this->post->datas($data);
                    $this->view->errors = $this->polls_model->errors;
                    $var['content'] = $this->view->fetch($template);
                    $var['error'] = 1;
                }else{
                    $data['polls_aid'] = $this->answers_model->get_aid($this->get->id);
                    $polls_aids = array();
                    if($data['polls_aid']){
                        foreach ($data['polls_aid'] as $get_polls_aid){
                            $polls_aids[] = $get_polls_aid;
                        }
                        foreach ($polls_aids as $polla_aid){
                            $id = 'polla_aid_'.$polla_aid['id'];
                            $polls_answers = $this->post->{$id};
                            $this->answers_model->update_answers($polla_aid['id'],$polls_answers);
                        }
                    }
                    if(isset($this->post->{'answer'})){
                        Pf::database()->query('START TRANSACTION');
                        $new_id = $this->get->id;
                        if(count($port_answer) > 0){
                            $custom = array();
                            $int = count($port_answer);
                            for ($i = 0; $i < $int ; $i++) {
                                if(!empty($port_answer[$i])){
                                    $custom = array(
                                            'pollsa_qid' => $new_id,
                                            'pollsa_answers' => e($port_answer[$i]),
                                    );
                                }
                                $insert_meta = $this->answers_model->insert($custom);
                            }
                            if($insert_meta === false){
                                Pf::database()->query('ROLLBACK');
                                break;
                            }else{
                                Pf::database()->query('COMMIT');
                            }
                        }
                    }
                    $data['get_answers'] = $this->answers_model->get_answers($this->get->id);
                    $this->post->datas($data);
                    Pf::event()->trigger("action","polls-edit-successfully",$data);
                    $var['error'] = 0;
                    $var['content'] = $this->view->fetch($template);
                    $var['url'] = admin_url($this->action.'=index&ajax=&id=&token=');
                }
            }else{
                if (isset($this->get->id)){
                    $params = array();
                    $params['where'] = array('id=?',array((int)$this->get->id));
                    $data = $this->polls_model->fetch_one($params);
                    $data["polls_pubdate"] =  str_to_mysqldate($data["polls_pubdate"],"Y-m-d",$this->polls_model->elements_value["polls_pubdate"]);
                    $data["polls_unpubdate"] =  str_to_mysqldate($data["polls_unpubdate"],"Y-m-d",$this->polls_model->elements_value["polls_unpubdate"]);
                    $data["polls_status"] = explode(",",$data["polls_status"]);
                    $data['get_answers'] = $this->answers_model->get_answers($this->get->id);
                    $data = Pf::event()->trigger("filter","polls-database-data",$data);
                    $data = Pf::event()->trigger("filter","polls-editing-database-data",$data);
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
        $this->polls_model->rules = Pf::event()->trigger("filter","polls-copy-validation-rule",$this->polls_model->rules);
    
        $template = null;
        $template = Pf::event()->trigger("filter","polls-edit-template",$template);
        
        $var = array();
        
        if (isset($this->get->id) && isset($this->get->token) && Pf_Plugin_CSRF::is_valid($this->get->token, $this->key.$this->get->id)){
            if ($this->request->is_post()){
                $data = array();
                $data["polls_question"] = $this->post->{"polls_question"};
                $data["polls_pubdate"] = str_to_mysqldate($this->post->{"polls_pubdate"},$this->polls_model->elements_value["polls_pubdate"],"Y-m-d H:i:s");
                $data["polls_unpubdate"] = str_to_mysqldate($this->post->{"polls_unpubdate"},$this->polls_model->elements_value["polls_unpubdate"],"Y-m-d H:i:s");
                if (is_array($this->post->{"polls_status"})){
                    $data["polls_status"] = implode(",",$this->post->{"polls_status"});
                }else{
                    $data["polls_status"] = $this->post->{"polls_status"};
                }
                $port_answer = isset($this->post->{"answer"}) ? $this->post->{"answer"} : array();
                $data = Pf::event()->trigger("filter","polls-post-data",$data);
                $data = Pf::event()->trigger("filter","polls-copy-post-data",$data);
                $pollq_multiple_yes = intval($this->post->{'pollq_multiple_yes'});
                $get_answers = $this->answers_model->get_answers($this->get->id);
                $number_answer = count($port_answer) + count($get_answers);
                $data['polls_multiple'] = 0;
                if ($pollq_multiple_yes == 1) {
                    if(intval($this->post->{'pollq_multiple'}) > $number_answer){
                        $data['polls_multiple'] = 1;
                    }else{
                        $data['polls_multiple'] = intval($this->post->{'pollq_multiple'});
                    }
                } else {
                    $data['polls_multiple'] = 1;
                }
                $this->view->token = Pf_Plugin_CSRF::token($this->key.$this->get->id);
                Pf::database()->query('START TRANSACTION');
                $inserted = $this->polls_model->insert($data);
                if($inserted === false){
                    Pf::database()->query('ROLLBACK');
                }else{
                    $new_id = $this->polls_model->insert_id();
                    $insert_meta = true;
                    if(count($port_answer) > 0){
                        $custom = array();
                        $int = count($port_answer);
                        for ($i = 0; $i < $int ; $i++) {
                            if(!empty($port_answer[$i])){
                                $custom = array(
                                        'pollsa_qid' => $new_id,
                                        'pollsa_answers' => e($port_answer[$i]),
                                );
                            }
                            $insert_meta = $this->answers_model->insert($custom);
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
                    $this->view->errors = $this->polls_model->errors;
                    $var['content'] = $this->view->fetch($template);
                    $var['error'] = 1;
                }else{
                    Pf::event()->trigger("action","polls-copy-successfully",$data);
                    $var['error'] = 0;
                    $var['url'] = admin_url($this->action.'=index&ajax=&id=&token=');
                }
                
            }else{
                if (isset($this->get->id)){
                    $params = array();
                    $params['where'] = array('id=?',array((int)$this->get->id));
                    $data = $this->polls_model->fetch_one($params);
                    $data['answers'] = $this->answers_model->get_answers($this->get->id);
                    $data["polls_status"] = explode(",",$data["polls_status"]);
                    $data = Pf::event()->trigger("filter","polls-database-data",$data);
                    $data = Pf::event()->trigger("filter","polls-copy-database-data",$data);
                    
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
                            $this->polls_model->delete('id=?',array($id));
                            $this->ip_model->delete('pollsip_qid=?',array($id));
                            $this->answers_model->delete('pollsa_qid=?',array($id));
                        }
                    }
                    break;
                    break;
                case 'publish':
                    if (!empty($this->post->id) && is_array($this->post->id)){
                        foreach ($this->post->id as $id){
                            $params['where'] = array('id=?',array($id));
                            $data = $this->polls_model->fetch_one($params);
                            $data['polls_status'] = 1;
                            $this->polls_model->save($data);
                        }
                    }
                
                    $var['action'] = 'publish';
                    break;
                case 'unpublish':
                    if (!empty($this->post->id) && is_array($this->post->id)){
                        foreach ($this->post->id as $id){
                            $params['where'] = array('id=?',array($id));
                            $data = $this->polls_model->fetch_one($params);
                            $data['polls_status'] = 0;
                            $this->polls_model->save($data);
                        }
                    }
                    $var['action'] = 'unpublish';
                    break;
            }
            Pf::event()->trigger("action","polls-bulk-action-successfully",$this->get->type,$this->post->id);
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
            if ($this->polls_model->delete('id=?',array($this->get->id))){
                $var['error'] = 0;
                Pf::event()->trigger("action","polls-delete-successfully",$this->get->id);
            }else{
                $var['error'] = 1;
            }
        }
        $this->ip_model->delete('pollsip_qid=?',array($this->get->id));
        $this->answers_model->delete('pollsa_qid=?',array($this->get->id));
        $var['url'] = admin_url($this->action.'=index&ajax=&id=&token=');
        
        echo json_encode($var);
    }
    public function change_status() {
        $data = array();
        $status = $this->post->{"status"};
        $params = array();
        $params['where'] = array('id=?',array((int)$this->post->id));
        $data = $this->polls_model->fetch_one($params);
    
        switch ($status) {
            case 'publish':
                $data['polls_status'] = 1;
                $this->polls_model->save($data);
                break;
            case 'unpublish':
                $data['polls_status'] = 0;
                $this->polls_model->save($data);
                break;
        }
    }
    public function delete_ansewer(){
        $data = array();
        $aid = $this->post->{"id"};
        $this->ip_model->delete('pollsip_aid=?',array($aid));
        $this->answers_model->delete('id=?',array($aid));
    }
}