<?php
defined ( 'PF_VERSION' ) or header ( '404.html' );
class User_Shortcode extends Pf_Shortcode_Controller {
    public function __construct() {
        parent::__construct ();
        $this->load_model('message');
    }
    public function signin($atts, $content = null, $tag) {
        $this->view->breadcrumb_title = __('Sign In','user');
        Pf::event()->on("theme-breadcrumb",array($this,'user_breadcrumb'),10);
        if (is_login ()) {
            $this->redirect ( public_base_url () );
        } else {
            $settings = Pf::setting();
            $logintimes = $settings->get_element_value('pf_user', 'login_attemps');
            $login = Pf::auth ();
            $error = '';
            if (! empty ( $_POST ) && $_POST ['type'] == 'login') {
                if (empty ( $_POST ['username'] ) && $error == '') {
                    $error = __ ( 'Please enter your username', 'user' ) . "<br/>";
                }
                
                if (empty ( $_POST ['password'] ) && $error == '') {
                    $error = __ ( 'Please enter your password', 'user' ) . "<br/>";
                }
                
                if (($login->valid_login ( $_POST ['username'] ) > time () || (isset ( $_COOKIE [$_POST ['username'].'logintimes'] ) && $_COOKIE [$_POST ['username'].'logintimes'] >= $logintimes - 1)) && $error == '') {
                    $error = '<strong>' . $_POST ['username'].'</strong>' . __ ( " have login failed " . $logintimes . " times. Please wait 30 minuste to retry!", 'user' ) . "<br/>";
                }
                        
                if ($error == ''){
                    $info = $login->login ( $_POST ['username'], $_POST ['password'] );
                    if (is_array ( $info )) {
                        $active = $info ['user_activation'];
                        if ($active == 1) {
                            if (isset ( $_POST ['remember'] ) && $_POST ['remember'] == 1) {
                                $login->set_cookie ( 'id', $info ['uid'], 360000 );
                            }
                            $login->update_login ( $info ['uid'] );
                            setcookie ($_POST ['username']."logintimes", 0, time () + 1800 );
                        } else {
                            $error = __ ( 'This account is not activated yet! Please check email or contact Administrator. Thanks! ', 'user' ) . "<br/>";
                        }
                    } else {
                        if (! empty ( $logintimes )) {
                            $login_fail = ! empty ( $_COOKIE [$_POST ['username'].'logintimes'] ) ? ($_COOKIE [$_POST ['username'].'logintimes'] + 1) : 1;
                            setcookie ($_POST ['username']."logintimes", $login_fail, time () + 1800 );
                            $error = __ ( 'Wrong username or password', 'user' ) . ' (' . $login_fail . '/' . $logintimes . __ ( 'times', 'user' ) . ')<br/>';
                            if (isset ( $_COOKIE [$_POST ['username'].'logintimes'] ) && $_COOKIE [$_POST ['username'].'logintimes'] >= $logintimes - 1) {
                                $error = '<strong>' . $_POST ['username'].'</strong>' . __ ( " have login failed " . $logintimes . " times. Please wait 30 minuste to retry!", 'user' ) . "<br/>";
                                $login->login_attemp ( $_POST ['username'] );
                                setcookie ( "logintimes", $login_fail, time () + 1800 );
                            }
                        } else {
                            $error .= __ ( 'Wrong username or password', 'user' ) . "<br/>";
                        }
                    }
                }
                    
            }
            
            if (is_ajax()){
                if (!is_login()){
                    echo $error;
                    die();
                }else{
                    echo 'success';
                    die();
                }
            }else{
                if (!is_login()){
                    $this->view->error = $error;
                    $this->view->referer = !empty($_GET['ref']) ? urldecode($_GET['ref']) : public_base_url();
                    $this->view->render ();
                }else{
                    if(empty($_GET['ref']) || $_GET['ref']=='#') $_GET['ref']=  public_base_url();
                    $this->redirect($_GET['ref']);
                }
            }
        }
    }
    
    public function signup($atts, $content = null, $tag){
        $this->view->breadcrumb_title = __('Sign up','user');
        Pf::event()->on("theme-breadcrumb",array($this,'user_breadcrumb'),10);
        $this->view->captcha = get_captcha();
        if ($this->view->captcha != false){
            require_once ABSPATH . '/lib/recaptchalib.php';
        }
        
        $this->view->allow = Pf::setting()->get_element_value('pf_user', 'allow_reg');
        $this->view->min_user = Pf::setting()->get_element_value('pf_user', 'user_length');
        $this->view->min_pass = Pf::setting()->get_element_value('pf_user', 'pass_length');
        $this->view->custom_field  = get_option('user_custom_fields');
        
        $this->view->form_firstname = array('name' => 'firstname', 'placeholder' => __('First Name', 'user'));
        $this->view->form_lastname = array('name' => 'lastname', 'placeholder' => __('Last Name', 'user'));
        $this->view->form_username = array('name' => 'username', 'placeholder' => __('Username', 'user'));
        $this->view->form_email = array('name' => 'email', 'placeholder' => __('Email', 'user'));
        
        if (!is_login()) {
            $error = array();
            $auth = new Auth();
            if (!empty($_POST) && $_POST['type'] == 'register' && $this->view->allow == 1) {
                if (empty($_POST['igree']) || $_POST['igree'] != 1) {
                    $error['igree'][0] = __("Please agree to the Terms of Service and Privacy Policy first!", 'user');
                }
                if ($this->view->captcha != false) {
                    $resp = recaptcha_check_answer($this->view->captcha['privatekey'], $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);
                    if (!$resp->is_valid) {
                        $error['captcha_code'][0] = __("Wrong Captcha Code", 'user');
                    }
                }
                $v = Pf::validator();
                $_POST = $v->sanitize($_POST);
                $rules  =   array(
                        "firstname" => "required|max_len,20|min_len,1",
                        "lastname" => "required|max_len,20|min_len,1",
                        "username" => "required|alpha_numeric|max_len,20|min_len,{$this->view->min_user}",
                        "email" => "required|valid_email",
                        "password" => "required|max_len,20|min_len,{$this->view->min_pass}",
                        "repassword" => "required|max_len,20|min_len,{$this->view->min_pass}",
                );
                $v->label(array(
                        'firstname' => __('First Name','user'),
                        'lastname' => __('Last Name','user'),
                        'username' => __('Username','user'),
                        'password' => __('Password','user'),
                        'repassword' => __('Repeat Password','user'),
                ));
                
                $firstname = $_POST['firstname'];
                $lastname = $_POST['lastname'];
                $username = $_POST['username'];
                $email = $_POST['email'];
                $password = $_POST['password'];
                $repassword = $_POST['repassword'];
                $custom =   $_POST['custom'];
                
                $v->validation_rules($rules);
                $validate = $v->run($_POST);
                if ($validate === false) {
                    $errors = $v->get_readable_errors(false);
                    foreach ($errors as $key => $value) {
                        $error[$key][0] = $errors[$key][0];
                    }
                }
                
                if(!empty($this->view->custom_field)){
                    foreach($this->view->custom_field as $item){
                        if($item['require']==1 && $item['register'] ==1 && empty($_POST['custom'][$item['name']])){
                            $error[$item['name']][0]  =   $item['label'].' '.__('is required','user');
                        }
                    }
                }
                if (empty($error['username']) && $auth->is_username_exist($username)) {
                    $error['username'][0] = __("Username is already in use!", 'user');
                }
                if (empty($error['email']) && $auth->is_email_exist($email)) {
                    $error['email'][0] = __("Email is already in use!", "user");
                }
                
                
                if ($password != $repassword && empty($error['repassword'])) {
                    $error['password_do_not_match'][0] = __("Passwords do not match", 'user');
                }
                
                if (empty($error)) {
                    $custom_json    = serialize($custom);
                    $auth->register(array(
                            'user-email'=>$email,
                            'user-name'=>$username,
                            'firstname'=>$_POST['firstname'],
                            'lastname'=>$_POST['lastname'],
                            'password'=>$password,
                            'role'=>5,
                            'custom'    => $custom_json
                    ));
                    if (Pf::setting()->get_element_value('pf_user', 'activa_require') == 1) {
                        $register_success   =   1;
                        $this->view->render('signup-active');
                    } else {
                        set_session($auth->get_identity());
                        $this->view->render('signup-success');
                    }
                }else{
                    $this->view->errors = $error;
                    $this->view->render();
                }
            }else{
                $this->view->errors = $error;
                $this->view->render();
            }
        }else{
            $this->redirect(public_base_url());
        }
    }
    
    public function signout($atts, $content = null, $tag){
        $auth = Pf::auth();
        if(empty($_GET['ref']) || $_GET['ref']=='#') $_GET['ref']=  public_base_url();
        if(is_login()){
            $auth->destroy_session();
            if($auth->check_cookie('id')){
                $auth->destroy_cookie('id');
            }
        }
        
        $this->redirect(urldecode($_GET['ref']));
    }
    
    public function activation($atts, $content = null, $tag){
        $this->view->message = '';
        if(isset($_GET['id']) && isset($_GET['key'])){
            $uid = $_GET['id'];
            $key = $_GET['key'];
            $user = new Pf_User();
            $setting = Pf::setting();
            $mail   = new Pf_Mail;
            $user->set_id($uid);
            $info =  $user->get_info('id as uid,user_email,user_name,user_activation,user_firstname,user_lastname,user_activation_key,user_registered_date,user_avatar,user_role');
            if ($info[0]['user_activation'] == 1) {
                $this->view->message = __("This user is already activated!",'user'); 
                $this->view->render();
            } else {
                if (!empty($info)) {
                    $email     =    $info[0]['user_email'];
                    $username  =    $info[0]['user_name'];
                    $firstname=   $info[0]['user_firstname'];
                    $lastname=   $info[0]['user_lastname'];
                    $activekey =    md5($info[0]['user_activation_key']);
                    $registime = strtotime($info[0]['user_registered_date']);
                    $expdate = $setting->get_element_value('pf_user', 'active_time');
                    $lasttime = $registime + ($expdate * 3600 * 24);
                    $status = $setting->get_element_value('pf_user', 'welcome_email');
                    if($activekey==$key){
                        if (time() < $lasttime ) {
                            $user->save(array('user_activation' => 1,'user_activation_key'=>''));
                            if($status == 1){
                                $mail->mail_welcome($email, $username, $firstname,$lastname);
                            }
                            Pf::auth()->set_session_info_login($info[0]);
                            $this->view->render('signup-success');
                        }else{
                            $this->view->message = __("Activation link is out-of-date! Please sign up again ","user");
                            $this->view->render();
                        }
                    }else{
                        $this->view->message = __("Wrong activation key!","user");
                        $this->view->render();
                    }
                }
            }
        }else{
            $this->view->render();
        }
    }
    
    public function recover($atts, $content = null, $tag){
        if (!is_login()){
            if (is_ajax()){
                $var = array();
                $var['error'] = 0;
                if (!isset($this->post->recover_email)){
                    $var['message'] = __('Please enter your recovery email',"user");
                    $var['error'] = 1;
                }else{
                    $auth = new Auth();
                    $mail = new Pf_Mail();
                    $key = $auth->forgot_password($this->post->recover_email);
                    if($key!=false){
                        $mail->mail_forgot($this->post->recover_email,$key);
                        $var['message'] = __('An email to reset your password has been sent', 'user');
                    }else{
                        $var['message'] = __('Your email does not exist', 'user');
                        $var['error'] = 1;
                    }
                }
                echo json_encode($var);
            }else{
                $min_pass = Pf::setting()->get_element_value('pf_user', 'pass_length');
                if(!empty($this->get->key)){
                    $security_user = new Pf_User_Security();
                    $decrypt = $security_user->decrypt(base64_decode($this->get->key));
                    $time = isset($decrypt['time'])?strtotime($decrypt['time']):time();
                    $message = '';
                    $error = true;
                    if ($time> time() && is_array($decrypt)) {
                        $id = $decrypt['id'];
                        $auth = Pf::auth();
                        $user = new Pf_User();
                        $user->set_id($id);
                        $info = $user->get_info('id as uid,user_avatar,user_password,user_firstname,user_lastname,user_role,user_name,user_email,user_forgot_pass_key',true);
                        if ($info[0]['user_forgot_pass_key'] == $this->get->key) {
                            if (count($_POST) > 0){
                                if (empty($_POST['password'])) {
                                    $message =  __('New Password','user');
                                    $error = true;
                                } else {
                                    $newpass = $_POST['password'];
                                    $confirm = !empty($_POST['confirm']) ? $_POST['confirm'] : '';
                                    if ($newpass != $confirm) {
                                        $message =  __('Confirm password and password are not match','user');
                                        $error = true;
                                    }elseif(strlen($newpass)<$min_pass){
                                        $message = sprintf(__("Your password must from %s-20 characters!",'user'),$min_pass);
                                        $error = true;
                                    }else {
                                        $user->save(array('user_forgot_pass_key'=>'','user_password' => $user->set_password($newpass)));
                                        $mail= new Pf_Mail();
                                        $mail->mail_success_forgot($info[0]['user_email']);
                                        $message = __("Your password has been changed successfully!",'user');
                                        $error = false;
                                    }
                                }
                            }
                        } else {
                            $message = __("Wrong reset code",'user');
                            $error = true;
                        }
                    } else {
                        $message = __("Your reset code is out-of-date. Please try again",'user');
                        $error = true;
                    }
                    
                    $this->view->error = $error;
                    $this->view->message = $message;
                    $this->view->render();
                }else{
                    $this->redirect(public_base_url());
                }
            }
        }
    }
    
    public function profile($atts, $content = null, $tag){
        $this->view->ref = !empty($_GET['ref']) ? urldecode($_GET['ref']) : public_base_url();
        switch (strtolower($this->get->action)){
            case 'change-password':
                $this->profile_change_password();
                break;
            case 'edit':
                $this->profile_edit();
                break;
            case 'view':
            default:
                $this->profile_view();
                break;
        }
    }
    
    public function lostpassword($atts, $content = null, $tag){
        if (!is_login()){
            $this->view->render();
        }else{
            $this->redirect(public_base_url());
        }
    }
    
    private function profile_edit(){
        $this->view->breadcrumb_title = __('My Profile','user');
        Pf::event()->on("theme-breadcrumb",array($this,'user_breadcrumb'),10);
        if (is_login()){
            $message = array();
            $is_error = false;
            
            $this->view->list_fields    =   get_option('user_custom_fields');
            
            if (!empty($this->post->form_type) && $this->post->form_type = 'profile_change_password'){
                $user = new Pf_User();
                $auth = new Auth();
                $userid = $auth->get_user_id();
                $user->set_id($userid);
            
                $v = Pf::validator();
                $_POST = $v->sanitize($_POST);
                $rules  =   array(
                        'firstname' => 'required',
                        'lastname' => 'required',
                        "email" => "required|valid_email",
                );
                $v->label(array(
                    'firstname' => __('First Name','user'),
                    'lastname' => __('Last Name','user'),
                    'email' => __('Email','user'),
                ));
                $v->validation_rules($rules);
                $validate = $v->run($_POST);
                if (!empty($this->view->list_fields)) {
                    foreach ($this->view->list_fields as $item) {
                        if ($item['require'] == 1 && empty($_POST['custom'][$item['name']])) {
                            $message[$item['label']][] = $item['label'] . ' ' . __('is required', 'user');
                            $is_error = true;
                        }
                    }
                }
                if ($validate === false) {
                    $message = array_merge($message,$v->get_readable_errors(false));
                    $is_error = true;
                } else {
                    $custom_info    =   !empty($_POST['custom'])?  serialize($_POST['custom']):array();
                    if (!$user->check_email($this->post->email)) {
                        $message['email'][] = __("Email is already in use!", "user");
                        $is_error = true;
                    }
            
                    if(count($message)==0){
                        $user->save(array('user_firstname' => $this->post->firstname,
                                'user_lastname'=> $this->post->lastname ,
                                'user_email' => $this->post->email,
                                'public_profile'  => $this->post->public_profile,
                                'user_avatar' => $this->post->user_avatar,
                                'user_custom_fields'=> $custom_info));
            
                        $auth->set_session('user-firstname',$this->post->firstname);
                        $auth->set_session('user-lastname',$this->post->lastname);
                        $auth->set_session('avatar',$this->post->user_avatar);
                        $message['success'][]  =  __('Profile is changed successfully!','user');
                    }
                }
            }
            
            $user_name  = current_user('user-name');
            $this->profile_user_data($user_name);
            
            $this->view->form_firstname = array('name' => 'firstname', 'value' => $this->view->info['user_firstname'],'id'=>'firstnameform');
            $this->view->form_lastname = array('name' => 'lastname', 'value' => $this->view->info['user_lastname'],'id'=>'lastnameform');
            $this->view->form_email = array('name' => 'email', 'value' => $this->view->info['user_email']);
            $this->view->check =   array($this->view->info['public_profile'] =>"checked='checked'");
            
            $this->view->message = $message;
            $this->view->message_type = ($is_error)?'danger':'success';
            
            $this->view->render('profile/edit');
        }else{
            $this->redirect(public_url());
        }
    }
    
    private function profile_change_password(){
        $this->view->breadcrumb_title = __('My Profile','user');
        Pf::event()->on("theme-breadcrumb",array($this,'user_breadcrumb'),10);
        if (is_login()){
            $user_name  = current_user('user-name');
            $this->profile_user_data($user_name);
            
            $message = '';
            $error = false;
            if (!empty($this->post->form_type) && $this->post->form_type = 'profile_change_password'){
                $user = new Pf_User();
                $user->set_id(current_user('user-id'));
                $oldpass = $user->set_password($this->post->old_password);
                $row = $user->get_info("user_password");
                
                if ($this->post->old_password == ''){
                    $message =  __('Current password','user');
                    $error = true;
                }
                
                if ($error == false && $oldpass != $row[0]['user_password']){
                    $message =  __('Your current password is incorrect!','user');
                    $error = true;
                }
                
                if ($error == false && $this->post->new_password == ''){
                    $message =  __('New Password','user');
                    $error = true;
                }
                
                $min_pass   = get_configuration('pass_length','pf_user');
                
                if($error == false && (strlen($this->post->new_password) < $min_pass) || strlen($this->post->new_password) > 20){
                    $message = sprintf(__("Your password must from %s-20 characters!",'user'),$min_pass);
                    $error = true;
                }
                
                if ($error == false && $this->post->repeat_new_password == ''){
                    $message =  __('Repeat Password','user');
                    $error = true;
                }
                
                if ($error == false && $this->post->new_password != $this->post->repeat_new_password){
                    $message =  __('Confirm password and password are not match','user');
                    $error = true;
                }
                
                if ($error == false){
                    $user->save(array('user_password' => $user->set_password($this->post->new_password)));
                    $message = __('Password is reset successfully','user');
                }
            }
            if ($error == false){
                $this->view->message_type = 'success';
            }else{
                $this->view->message_type = 'danger';
            }
            $this->view->message = $message;
            $this->view->render('profile/change-password');
        }else{
            $this->redirect(public_url());
        }
        
    }
    
    private  function profile_view(){
        $this->view->breadcrumb_title = __('My Profile','user');
        Pf::event()->on("theme-breadcrumb",array($this,'user_breadcrumb'),10);
        if (empty($_GET['user'])) {
            if(is_login()){
                $user_name  = current_user('user-name');
            }else{
                header(public_url());
            }
        }else{
            $user_name = $_GET['user'];
        }
        
        if(!empty($user_name)){
            $this->profile_user_data($user_name);
            $this->view->render('profile/view');
        }else{
            $this->redirect(public_url());
        }
    } 
    
    public function user_breadcrumb($breadcrumb = ''){
        return $this->view->fetch('breadcrumb');
    }
    /**
     * 
     * @param string $user_name
     */
    private function profile_user_data($user_name){
        $list_fields    = get_option('user_custom_fields');
        $min_pass   = get_configuration('pass_length','pf_user');
        $user = new Pf_User();
        $info = $user->select_user("".DB_PREFIX."users.id as `uid`,`user_name`,`user_email`,`user_avatar`,`user_role`,`user_registered_date`,`user_login_time`,`user_firstname`,`user_lastname`,`public_profile`,`user_custom_fields`","`user_name`=? and user_delete_flag=0",array($user_name));
        if(isset($info[0]) && ($info[0]['public_profile']==1 || $user_name==  current_user('user-name'))){
            $info   =   $info[0];
            $role   =   new Pf_Role();
            $user_role = $role->get_list_role();
            if ($info['uid'] == current_user('user-id')) {
                $auth = Pf::auth();
                $user->set_id(current_user('user-id'));
            }
        
            $this->view->list_fields = $list_fields;
            $this->view->info = $info;
            $this->view->user_role = $user_role;
        }else{
            $this->redirect(public_url());
        }
    }
    
    //Inbox
    public function inbox($atts, $content = null, $tag){
        $this->view->ref = !empty($_GET['ref']) ? urldecode($_GET['ref']) : public_base_url();
        switch (strtolower($this->get->action)){
            case 'view':
                $this->inbox_view();
            break;
            default:
                $this->inbox_list();
            break;
        }
    }
    
    private  function inbox_list(){
        $this->view->breadcrumb_title = __('Inbox','user');
        Pf::event()->on("theme-breadcrumb",array($this,'user_breadcrumb'),10);
        if (empty($_GET['user'])) {
            if(is_login()){
                $user_name  = current_user('user-name');
            }else{
                header(public_url());
            }
        }else{
            $user_name = $_GET['user'];
        }
    
        if(!empty($user_name)){
            $params = array();
            $params['limit'] = NUM_PER_PAGE;
            $params['page_index'] = (isset($this->get->{$this->page}))?(int)$this->get->{$this->page}:1;
            $where = '';
            $where_values = array();
            $operator = '';
            
            if (($title = $this->get->keywords)) {
                $where .= $operator.' `message_title` like ? ';
                $where_values[] = '%' . $title . '%';
                $operator = ' AND ';
            }
            
            $where .= $operator.' `message_user_receive` = ? ';
            $where_values[] = $user_name;
            
            $params['fields'] = ''.DB_PREFIX.'message.*,user_firstname,user_lastname,user_avatar';
            $params['join'] = array(
                '0' => array(
                    'LEFT',
                    ''.DB_PREFIX.'users',
                    ''.DB_PREFIX.'message.message_user_send = '.DB_PREFIX.'users.user_name'
                )
            );
            if (!empty($where_values)){
                $params['where'] = array($where,$where_values);
            }
            $params["order"] = "`".Pf::database ()->escape('id')."` ".Pf::database ()->escape('DESC');
            $atts['records'] = $this->message_model->fetch($params,true);
            $atts['total_records'] = $this->message_model->found_rows();
            $data['total_page'] = ceil($atts['total_records']/$params['limit']);
            
            if (empty($atts['records']) && $atts['total_page'] > 0){
                $this->get->{$this->page} = $params['page_index'] = $data['total_page'];
                $data['page_index'] = $params['page_index'];
                $atts['records'] = $this->message_model->fetch($params,true);
                $atts['total_records'] = $this->message_model->found_rows();
            }
            $atts['pagination'] = new Pf_Paginator($atts['total_records'], $params['limit'], 'page');
            $this->view->atts = $atts;
            
            $this->profile_user_data($user_name);
            $this->view->render('profile/inbox-list');
        }else{
            $this->redirect(public_url());
        }
    }
    
    private  function inbox_view(){
        $this->view->breadcrumb_title = __('Inbox','user');
        Pf::event()->on("theme-breadcrumb",array($this,'user_breadcrumb'),10);
        if (empty($_GET['user'])) {
            if(is_login()){
                $user_name  = current_user('user-name');
            }else{
                header(public_url());
            }
        }else{
            $user_name = $_GET['user'];
        }
    
        if(!empty($user_name)){
            $params = array();
            $params['limit'] = NUM_PER_PAGE;
            $params['page_index'] = (isset($this->get->{$this->page}))?(int)$this->get->{$this->page}:1;
            $where = '';
            $where_values = array();
            $operator = '';
    
            if (($title = $this->get->keywords)) {
                $where .= $operator.' `message_title` like ? ';
                $where_values[] = '%' . $title . '%';
                $operator = ' AND ';
            }
    
            $where .= $operator.' `message_user_receive` = ? ';
            $where_values[] = $user_name;
    
            $params['fields'] = ''.DB_PREFIX.'message.*,user_firstname,user_lastname,user_avatar';
            $params['join'] = array(
                '0' => array(
                    'LEFT',
                    ''.DB_PREFIX.'users',
                    ''.DB_PREFIX.'message.message_user_send = '.DB_PREFIX.'users.user_name'
                )
            );
            if (!empty($where_values)){
                $params['where'] = array($where,$where_values);
            }
            $params['where'] = array(DB_PREFIX.'message.id=?',array((int)$this->get->id));
            $atts = $this->message_model->fetch_one($params);
            $this->view->atts = $atts;
            //debug($this->view->atts);
    
            $this->profile_user_data($user_name);
            $this->view->render('profile/inbox-view');
        }else{
            $this->redirect(public_url());
        }
    }
}