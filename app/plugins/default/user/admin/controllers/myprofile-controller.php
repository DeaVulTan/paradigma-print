<?php 
class Myprofile_Controller extends Pf_Controller{
	protected $model_auth;
    public function __construct(){
        parent::__construct();
        $this->load_model('user');
        $this->model_auth = new Auth();
        $this->view->menu_settings = get_option('admin_menu_setting');
        $this->user_model->rules = Pf::event()->trigger("filter","user-validation-rule",$this->user_model->rules);
    }
    public function index(){
    	$data = array();
        $template = null;
        $template = Pf::event()->trigger("filter","myprofile-index-template",$template);
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
        $this->view->render($template);
    }
    public function change_profile(){
    	$data = array();
    	$template = null;
    	$template = Pf::event()->trigger("filter","myprofile-index-template",$template);
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
    	$this->view->render("index");
    }
    
}