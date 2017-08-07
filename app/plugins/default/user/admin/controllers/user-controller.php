<?php
defined ( 'PF_VERSION' ) or header ( 'Location:404.html' );
class User_Controller extends Pf_Controller{
	protected $model_auth;
    public function __construct(){
        parent::__construct();
        $this->load_model('user');
        $this->load_model('role');
        $this->model_auth = new Auth();
        $this->view->menu_settings = get_option('admin_menu_setting');
        $this->user_model->rules = Pf::event()->trigger("filter","user-validation-rule",$this->user_model->rules);
    }
    
    public function index(){
    	$params = array();
    	$where = '';
    	$where_values = array();
    	$operator = '';
    	
    	$params['limit'] = NUM_PER_PAGE;
    	$params['page_index'] = (isset($this->get->{$this->page}))?(int)$this->get->{$this->page}:1;
    	
    	
    	$operator = '';
    	$where = 'user_delete_flag = 0';
    	$params['where'] =  array($where);
    	$operator = ' AND ';
    	if (empty($this->get->search)){
    		$this->get->search = array();
    	}
    	
    	if (isset($this->get->search["user_name"]) && trim($this->get->search["user_name"]) != ""){
    		$where .= $operator.' `user_name` like ? ';
    		$where_values[] = '%'.$this->get->search["user_name"].'%';
    		$operator = ' AND ';
    	}
    	
    	if (isset($this->get->search["user_firstname"]) && trim($this->get->search["user_firstname"]) != ""){
    		$where .= $operator.' `user_firstname` like ? ';
    		$where_values[] = '%'.$this->get->search["user_firstname"].'%';
    		$operator = ' AND ';
    	}
    	
    	if (isset($this->get->search["user_lastname"]) && trim($this->get->search["user_lastname"]) != ""){
    		$where .= $operator.' `user_lastname` like ? ';
    		$where_values[] = '%'.$this->get->search["user_lastname"].'%';
    		$operator = ' AND ';
    	}
    	
    	if (isset($this->get->search["user_email"]) && trim($this->get->search["user_email"]) != ""){
    		$where .= $operator.' `user_email` like ? ';
    		$where_values[] = '%'.$this->get->search["user_email"].'%';
    		$operator = ' AND ';
    	}
    	
    	if (isset($this->get->search["user_role"]) && trim($this->get->search["user_role"]) != ""){
    		$where .= $operator.' `user_role` like ? ';
    		$where_values[] = '%'.$this->get->search["user_role"].'%';
    		$operator = ' AND ';
    	}
    	if (isset($this->post->action) && trim($this->post->action) != ""){
    		$where .= $operator.' `user_activation` = ? ';
    		$where_values[] = $this->post->id;
    	}
    	if (isset($this->get->search["user_activation"])){
    		if (is_array($this->get->search["user_activation"])){
    			if (!empty($this->get->search["user_activation"])){
    				$where .= $operator.' ( ';
    				$operator1 = '';
    				foreach($this->get->search["user_activation"] as $v){
    					$where .= $operator1.'  `user_activation` = ?  OR `user_activation` like ?  OR `user_activation` like ? OR `user_activation` like ? ';
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
    			if (trim($this->get->search["user_activation"]) != ""){
    				$where .= $operator.' (  `user_activation` = ?  OR `user_activation` like ?  OR `user_activation` like ? OR `user_activation` like ? )';
    				$where_values[] = $this->get->search["user_activation"];
    				$where_values[] = $this->get->search["user_activation"].',%';
    				$where_values[] = '%,'.$this->get->search["user_activation"].',%';
    				$where_values[] = '%,'.$this->get->search["user_activation"];
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
    	
    	$params = Pf::event()->trigger("filter","user-index-params",$params);
    	
    	
    	$this->view->page_index = $params['page_index'];
    	$this->view->records = $this->user_model->fetch($params,true);
    	$this->view->total_records = $this->user_model->found_rows();
    	$total_page = ceil($this->view->total_records/NUM_PER_PAGE);
    	if (empty($this->view->records) && $total_page > 0){
    		$this->get->{$this->page} = $params['page_index'] = $total_page;
    		$this->view->page_index = $params['page_index'];
    		$this->view->records = $this->user_model->fetch($params,true);
    		$this->view->total_records = $this->user_model->found_rows();
    	}
    	$this->view->pagination = new Pf_Paginator($this->view->total_records, NUM_PER_PAGE, $this->page);
    	
    	$template = null;
    	$template = Pf::event()->trigger("filter","user-index-template",$template);
    	$this->view->render($template);
       
    }
    
    public function add(){
        $this->user_model->rules = Pf::event()->trigger("filter","user-adding-validation-rule",$this->user_model->rules);
        $template = null;
        $template = Pf::event()->trigger("filter","user-add-template",$template);
        if ($this->request->is_post()){
            $data = array();
            $error = array();
            $data["user_email"] = $this->post->{"user_email"};
            $data["user_name"] = $this->post->{"user_name"};
            $data["user_password"] = $this->post->{"user_password"};
            $data['repassword'] =  $this->post->{"repassword"};
            $data["user_firstname"] = $this->post->{"user_firstname"};
            $data["user_lastname"] = $this->post->{"user_lastname"};
            $name = $this->post->{"custom"};
            $custom_field = get_option('user_custom_fields');
            if(!empty($custom_field)){
                foreach ($custom_field as $item){
                    if($item['require']==1 && $item['register'] ==1 && empty($name[$item['name']])){
                        $error[$item['name']][0]  =   $item['label'].' '.__('is required','user');
                    }
                }
            }
            $data['user_custom_fields'] = !empty($this->post->{"custom"})?  serialize($this->post->{"custom"}):array();
            $check_email = $this->user_model->check_email($data["user_email"]);
            if($check_email == FALSE){
                $error['user_email'][0]  =  __('Email is already in use!','user');
            }
            $check_user = $this->user_model->check_user($data["user_name"]);
            if($check_user == FALSE){
                $error['user_name'][0]  =  __('Username is already in use!','user');
            }
            if($this->post->{"user_password"} !=  $this->post->{"repassword"}){
                $error['repassword'][0]  =  __('Confirm password and password are not match','user');
            }
            $data["user_role"] = $this->post->{"role"};
            $data["user_activation"] = !empty($this->post->{"user_activation"})?1:2;
            $data["public_profile"] = !empty($this->post->{"public_profile"})?1:2;
            $data = Pf::event()->trigger("filter","user-post-data",$data);
            $data = Pf::event()->trigger("filter","user-adding-post-data",$data);
            $var = array();
            $this->user_model->validate ($data);
            unset($data['repassword']);
            $data['user_activation_key'] = $this->user_model->set_activation_key();
            $data['user_registered_date'] = date("Y-m-d H:i:s", time());
            $data["user_password"] = $this->user_model->set_pass($data["user_password"]);
            $errors = Pf::validator()->get_readable_errors(false);
            foreach ($errors as $key => $value) {
                $error[$key][0] = $errors[$key][0];
            }
            $this->view->errors =  $error;
            $var['content'] = $this->view->fetch($template);
            if(count($error) > 0){
                $var['error'] = 1;
            }else{
                $this->user_model->insert($data,false);
                $uid = $this->user_model->insert_id();
                if($data['user_activation'] == 2 && $this->user_model->set_activation_option() == 2){
                	$this->user_model->mail_active($uid);
                }else if($data['user_activation'] == 1){
                	$status = Pf::setting()->get_element_value('pf_user', 'welcome_email');
                	if($status == 1){
                		$this->user_model->mail_welcome($data["user_email"],$data["user_name"],$data["user_firstname"],$data["user_lastname"]);
                	}
                }
                Pf::event()->trigger("action","user-add-successfully",$this->user_model->insert_id(),$data);
                $var['error'] = 0;
                $var['url'] = admin_url($this->action.'=index&ajax=&id=&token=');
            }
            
            echo json_encode($var);
        }else{
            $this->view->render($template);
        }
    }
    
    public function edit(){
        $this->user_model->rules = Pf::event()->trigger("filter","user-editing-validation-rule",$this->user_model->rules);
        $template = null;
        $template = Pf::event()->trigger("filter","user-edit-template",$template);
        $var = array();
        if (isset($this->get->id) && isset($this->get->token) && Pf_Plugin_CSRF::is_valid($this->get->token, $this->key.$this->get->id)){
            if ($this->request->is_post()){
                $data = array();
                $error = array();
                $data['id'] = $this->get->id;
                $data["user_email"] = $this->post->{"user_email"};
                $data["user_password"] = $this->post->{"user_password"};
                $data['repassword'] =  $this->post->{"repassword"};
                $data["user_firstname"] = $this->post->{"user_firstname"};
                $data["user_lastname"] = $this->post->{"user_lastname"};
                $name = $this->post->{"custom"};
                $data["user_role"] = $this->post->{"role"};
                $custom_field = get_option('user_custom_fields');
                if(!empty($custom_field)){
                    foreach ($custom_field as $item){
                        if($item['require']==1 && $item['register'] ==1 && empty($name[$item['name']])){
                            $error[$item['name']][0]  =   $item['label'].' '.__('is required','user');
                        }
                    }
                }
                $data['user_custom_fields'] = !empty($this->post->{"custom"})?  serialize($this->post->{"custom"}):array();
                $check_email = $this->user_model->check_email($data["user_email"],$this->get->id);
                if($check_email == FALSE){
                    $error['user_email'][0]  =  __('Email is already in use!','user');
                }
                if($this->post->{"user_password"} !=  $this->post->{"repassword"}){
                    $error['repassword'][0]  =  __('Confirm password and password are not match','user');
                }
                $data['user_custom_fields'] = !empty($this->post->{"custom"})?  serialize($this->post->{"custom"}):array();
                $data["user_activation"] = !empty($this->post->{"user_activation"})?1:2;
                $data["public_profile"] = !empty($this->post->{"public_profile"})?1:2;
                $data = Pf::event()->trigger("filter","user-post-data",$data);
                $data = Pf::event()->trigger("filter","user-editing-post-data",$data);
                $this->view->token = Pf_Plugin_CSRF::token($this->key.$this->get->id);
                $validated = $this->user_model->validate ($data);
                $errors = Pf::validator()->get_readable_errors(false);
                unset($errors['user_name']);
                if($this->post->{"user_password"} == ""){
                    unset($errors['user_password']);
                    unset($errors['repassword']);
                }
                foreach ($errors as $key => $value) {
                    $error[$key][0] = $errors[$key][0];
                }
                unset($data['repassword']);
                $data["user_password"] = $this->user_model->set_pass($data["user_password"]);
                $this->view->errors = $error;
                $data["user_role"] =  $this->post->{"role"};
                $this->post->datas($data);
                $var['content'] = $this->view->fetch($template);
                if(count($error) > 0){
                    $var['error'] = 1;
                }else{
                	if($this->post->{"user_password"} == ""){
                		unset($data["user_password"]);
                	}
                    $this->user_model->save($data,false);
                   
                    $data["user_role"] =  $this->post->{"role"};
                    $this->post->datas($data);
                    Pf::event()->trigger("action","user-edit-successfully",$data);
                    $var['error'] = 0;
                    $var['content'] = $this->view->fetch($template);
                    $var['url'] = admin_url($this->action.'=index&ajax=&id=&token=');
                }
            }else{
                if (isset($this->get->id)){
                    $params = array();
                    $params['where'] = array('id=?',array((int)$this->get->id));
                    $data = $this->user_model->fetch_one($params);
                    $data["user_name"] = $data["user_name"];
                    if(!empty($data['user_login_time'])){
                    	$data['user_login_time'] = date(get_configuration('long_date'),strtotime($data["user_login_time"] ));
                    }else{
                    	$data['user_login_time'] = $data['user_login_time'];
                    }
                    $data["user_role"];
                    $data["public_profile"] = explode(",",$data["public_profile"]);
                    $data = Pf::event()->trigger("filter","user-database-data",$data);
                    $data = Pf::event()->trigger("filter","user-editing-database-data",$data);
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
        $this->user_model->rules = Pf::event()->trigger("filter","user-copy-validation-rule",$this->user_model->rules);
    
        $template = null;
        $template = Pf::event()->trigger("filter","user-edit-template",$template);
        
        $var = array();
        
        if (isset($this->get->id) && isset($this->get->token) && Pf_Plugin_CSRF::is_valid($this->get->token, $this->key.$this->get->id)){
            if ($this->request->is_post()){
                $data = array();
                $error = array();
                $data["user_email"] = $this->post->{"user_email"};
                $data["user_name"] = $this->post->{"user_name"};
                $data["user_password"] = $this->post->{"user_password"};
                $data['repassword'] =  $this->post->{"repassword"};
                $data["user_firstname"] = $this->post->{"user_firstname"};
                $data["user_lastname"] = $this->post->{"user_lastname"};
                $data["user_role"] = $this->post->{"role"};
                $name = $this->post->{"custom"};
                $custom_field = get_option('user_custom_fields');
                if(!empty($custom_field)){
                    foreach ($custom_field as $item){
                        if($item['require']==1 && $item['register'] ==1 && empty($name[$item['name']])){
                            $error[$item['name']][0]  =   $item['label'].' '.__('is required','user');
                        }
                    }
                }
                $data['user_custom_fields'] = !empty($this->post->{"custom"})?  serialize($this->post->{"custom"}):array();
                $check_email = $this->user_model->check_email($data["user_email"]);
                if($check_email == FALSE){
                    $error['user_email'][0]  =  __('Email is already in use!','user');
                }
                $check_user = $this->user_model->check_user($data["user_name"]);
                if($check_user == FALSE){
                    $error['user_name'][0]  =  __('Username is already in use!','user');
                }
                if($data["user_password"] !=  $data['repassword']){
                    $error['repassword'][0]  =  __('Confirm password and password are not match','user');
                }
                $data["user_activation"] = !empty($this->post->{"user_activation"})?1:2;
                $data["public_profile"] = !empty($this->post->{"public_profile"})?1:2;
                $data = Pf::event()->trigger("filter","user-post-data",$data);
                $data = Pf::event()->trigger("filter","user-copy-post-data",$data);
                $this->view->token = Pf_Plugin_CSRF::token($this->key.$this->get->id);
                $this->user_model->validate ($data);
                unset($data['repassword']);
                $data['user_activation_key'] = $this->user_model->set_activation_key();
                $data['user_registered_date'] = date("Y-m-d H:i:s", time());
                $data["user_password"] = $this->user_model->set_pass($data["user_password"]);
                $errors = Pf::validator()->get_readable_errors(false);
                foreach ($errors as $key => $value) {
                    $error[$key][0] = $errors[$key][0];
                }
                $data["user_role"] =  $this->post->{"role"};
                $this->post->datas($data);
                $this->view->errors = $error;
                $var['content'] = $this->view->fetch($template);
                if(count($error) > 0){
                    $var['error'] = 1;
                }else{
                    $this->user_model->insert($data,false);
                    $uid = $this->user_model->insert_id();
                    if($data['user_activation'] == 2 && $this->user_model->set_activation_option() == 2){
                    	$this->user_model->mail_active($uid);
                    }else if($data['user_activation'] == 1){
                    	$status = Pf::setting()->get_element_value('pf_user', 'welcome_email');
                    	if($status == 1){
                    		$this->user_model->mail_welcome($data["user_email"],$data["user_name"],$data["user_firstname"],$data["user_lastname"]);
                    	}
                    }
                    Pf::event()->trigger("action","user-copy-successfully",$data);
                    $var['error'] = 0;
                    $var['url'] = admin_url($this->action.'=index&ajax=&id=&token=');
                }    
            }else{
                if (isset($this->get->id)){
                    $params = array();
                    $params['where'] = array('id=?',array((int)$this->get->id));
                    $data = $this->user_model->fetch_one($params);
                    $data["public_profile"] = explode(",",$data["public_profile"]);
                    $data = Pf::event()->trigger("filter","user-database-data",$data);
                    $data = Pf::event()->trigger("filter","user-copy-database-data",$data);
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
                            Pf::database()->update(''.DB_PREFIX.'users', array('user_delete_flag' => '1'), "".DB_PREFIX."users.`id`=? and ".DB_PREFIX."users.`id`!= ".current_user('user-id')."",array($id));
                        }
                    }
                    break;
                    case 'publish':
                        if (!empty($this->post->id) && is_array($this->post->id)){
                            foreach ($this->post->id as $id){
                                 Pf::database()->update(''.DB_PREFIX.'users', array('user_activation' => '1'), "".DB_PREFIX."users.`id`=? and ".DB_PREFIX."users.`id`!= ".current_user('user-id')."",array($id));
                            }
                        }
                    
                        $var['action'] = 'publish';
                        break;
                    case 'unpublish':
                        if (!empty($this->post->id) && is_array($this->post->id)){
                            foreach ($this->post->id as $id){
                                Pf::database()->update(''.DB_PREFIX.'users', array('user_activation' => '2'), "".DB_PREFIX."users.`id`=? and ".DB_PREFIX."users.`id`!= ".current_user('user-id')."",array($id));
                            }
                        }
                        $var['action'] = 'unpublish';
                        break;
            }
            Pf::event()->trigger("action","user-bulk-action-successfully",$this->get->type,$this->post->id);
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
            if ($this->user_model->delete_user($this->get->id)){
                $var['error'] = 0;
                Pf::event()->trigger("action","user-delete-successfully",$this->get->id);
            }else{
                $var['error'] = 1;
            }
        }
        
        $var['url'] = admin_url($this->action.'=index&ajax=&id=&token=');
        
        echo json_encode($var);
    }
    public function change_status() {
        $status = $this->post->{"status"};
        switch ($status) {
            case 'publish':
                $this->user_model->active_user($this->post->id,1);
                break;
            case 'unpublish':
                $this->user_model->active_user($this->post->id,2);
                break;
        }
    }
    public function change_profile(){
    	$data = array();
    	$template = null;
    	$userid    =  current_user('user-id');
    	$data = $this->user_model->get_info($userid);
    	$error = array();
    	$list_fields    = get_option('user_custom_fields');
    	if(!empty($this->post->{"action"}) && $this->post->{"action"} == 'getpass'){
    		if(isset($this->post->{"oldpass"})){
    			$oldpass =  $this->user_model->set_pass($this->post->{'oldpass'});
    			if ($data[0]['user_password'] == $oldpass)
    				die('done!');
    			else
    				die('wrong');
    		}
    	}
    	if (!empty($this->post->{'oldpass'})) {
    		$oldpass =  $this->user_model->set_pass($this->post->{'oldpass'});
    		$newpass = $this->post->{'newpass'};
    		$confirm = $this->post->{'confirm'};
    		$pass = $this->user_model->set_pass($newpass);
    		if ($data[0]['user_password'] == $oldpass){
    			if ($newpass == $confirm) {
    				$this->user_model->save_pass($userid,$pass);
    				header("Location:".admin_url());
    			}
    		}
    		else
    			echo __("Your current password is incorrect!",'user');
    	}else if(!empty($this->post->{"avatar"})){
    		$avatar =   $this->post->{"avatar"};
    		$this->user_model->save_avatar($userid,$avatar);
    		$this->model_auth->set_session('avatar',$avatar);
    		$data['notif']  =   __('Avatar is changed successfully!','user');
    		$this->post->datas($data);
    	}elseif(isset($this->post->firstname)){
    		$v = Pf::validator();
    		$_POST = $v->sanitize($_POST);
    		$rules  =   array(
    				"email" => "required|valid_email",
    				'firstname' => 'required',
    				'lastname' => 'required',
    		);
    		$v->validation_rules($rules);
    		$validate = $v->run($_POST);
    		$name = $this->post->{"custom"};
    		if (!empty($list_fields)) {
    			foreach ($list_fields as $item) {
    				if ($item['require'] == 1 && $item['register'] ==1 && empty($name[$item['name']])){
    					$error[$item['label']][] = $item['label'] . ' ' . __('is required', 'user');
    					$data['error'] = "2";
    					$this->post->datas($data);
    				}
    			}
    		}
    		if ($validate === false) {
    			$error = array_merge($error,$v->get_readable_errors(false));
    			$data['error'] = "2";
    			$this->post->datas($data);
    		}else{
    			$firstname = !empty($this->post->{'firstname'}) ? htmlspecialchars($this->post->{'firstname'}) : $data[0]['user_firstname'];
    			$lastname = !empty($this->post->{'lastname'}) ? htmlspecialchars($this->post->{'lastname'}) : $data[0]['user_lastname'];
    			$email = !empty($this->post->{'email'}) ? $this->post->{'email'} : $data[0]['user_email'];
    			$public_profile =   !empty($this->post->{"public_profile"})?$this->post->{"public_profile"}:1;
    			$custom_info    =   !empty($this->post->{'custom'})?  serialize($this->post->{'custom'}):array();
    			$check_email = $this->user_model->check_email($email,$userid);
    			if($check_email == FALSE){
    				$error['email'][] = __("Email is already in use!", "user");
    				$data['error'] = "2";
    				$this->post->datas($data);
    			}
    			if(count($error)==0){
    				$this->user_model->save_change($userid,$firstname,$lastname,$email,$public_profile,$custom_info);
    				$this->model_auth->set_session('user-firstname',$firstname);
    				$this->model_auth->set_session('user-lastname',$lastname);
    				$data['sucsess'] = "1";
    				$this->post->datas($data);
    			}
    		}
    		$this->view->errors =  $error;
    	}
    	$this->view->render("myprofile");
    }
}