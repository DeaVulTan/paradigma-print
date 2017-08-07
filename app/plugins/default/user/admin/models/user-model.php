<?php
defined ( 'PF_VERSION' ) or header ( 'Location:404.html' );
$user_length =  Pf::setting()->get_element_value('pf_user', 'user_length');
define('min_user','required|max_len,20|min_len,'.$user_length .'');
$pass_length =  Pf::setting()->get_element_value('pf_user', 'pass_length');
define('min_pass','required|max_len,20|min_len,'.$pass_length .'');
class User_Model extends Pf_Model{
    protected $mailconfig;
    protected $sitename;
    protected $domain;
    protected $mailer;
    protected $SentFrom;
    public $activation;
    public $rules = array(
		'user_name'=> min_user,
		'user_password'=> min_pass,
		'user_email'=>"required|valid_email",
        'repassword' => min_pass,
	);
    public $elements_value = array(
            'user_activation' => 
                array(
                    '1' => 'Activated',
                    '2' => 'Deactivated',
                ),
            'public_profile' => 
                array(
                    '1' => '',
                ),
        );
    public function __construct(){
        parent::__construct(DB_PREFIX.'users');
        $this->sitename = Pf::setting()->get_element_value('general','site_name');
        $this->mailconfig  = get_configuration('mail_setting');
        Pf::setting()->get_element_value('general','smtp_server');
        Pf::setting()->get_element_value('general','smtp_port');
        $this->domain = public_url('');
        if($this->mailconfig == 'SMTP'){
            $transport = Swift_SmtpTransport::newInstance(Pf::setting()->get_element_value('general','smtp_server'), Pf::setting()->get_element_value('general','smtp_port'))
                        ->setUsername(Pf::setting()->get_element_value('general','smtp_username'))
                        ->setPassword(Pf::setting()->get_element_value('general','smtp_password'));
        }else{
            $transport  = Swift_MailTransport::newInstance();
        }
        $this->mailer   = Swift_Mailer::newInstance($transport);
        $this->SentFrom  =   array(noreply_email() => $this->sitename." Administrator");
    }
    //this is function replce mail
    private function replace_tpl($template, $username='', $firstname='', $lastname='', $email='', $link=''){
        $template   =   str_replace("{username}", $username, $template);
        $template   =   str_replace("{firstname}", $firstname, $template);
        $template   =   str_replace("{lastname}", $lastname, $template);
        $template   =   str_replace("{email}", $email, $template);
        $template   =   str_replace("{link}", $link, $template);
        $template   =   str_replace("{sitename}", $this->sitename, $template);
        return $template;
    }
    //function active email
    public function mail_active($uid) {
        $params = array();
        $where_value = array();
        $where = "id=?";
        $where_value[] =  $uid;
        $params['fields'] = array('user_name','user_activation_key','user_email','user_firstname');
        $params['where'] =  array($where,$where_value);
        $result = $this->fetch($params);
        $username  =   $result[0]['user_name'];
        $email = $result[0]['user_email'];
        $activekey = md5($result[0]['user_activation_key']);
        $firstname = $result[0]['user_firstname'];
        $link   =   $this->domain."user/activation/id:".$uid."/key:".$activekey;
        $content_tpl        =   Pf::email_template()->get_element_body('pf_user_mail_template','mail_active');
        $subject_tpl        =   Pf::email_template()->get_element_subject('pf_user_mail_template','mail_active');
        $content        = $this->replace_tpl($content_tpl, $username,$firstname,$lastname, '', $link);
        $subject        = $this->replace_tpl($subject_tpl, '', $firstname, $lastname, '',$link);
        $message = Swift_Message::newInstance()
        ->setSubject($subject)
        ->setFrom($this->SentFrom)
        ->setTo(array($email => $firstname.' '.$lastname))
        ->setBody($content)
        ;
        $this->mailer->send($message);
    }
    // this is mail welcome
    public function mail_welcome($email, $username, $firstname, $lastname){
    	$content_tpl        =   Pf::email_template()->get_element_body('pf_user_mail_template','mail_welcome');
    	$subject_tpl        =   Pf::email_template()->get_element_subject('pf_user_mail_template','mail_welcome');
    	$content        = $this->replace_tpl($content_tpl, $username,$firstname,$lastname, '', '');
    	$subject        = $this->replace_tpl($subject_tpl, $username,$firstname,$lastname, '', '');
    	$message = Swift_Message::newInstance()
    	->setSubject($subject)
    	->setFrom($this->SentFrom)
    	->setTo(array($email => $firstname.' '.$lastname))
    	->setBody($content)
    	;
    	$this->mailer->send($message);
    }
    //this is fucntion delete user,user_delete_flag
    public function delete_user($id){
        $result = Pf::database()->update(''.DB_PREFIX.'users', array('user_delete_flag' => '1'), "".DB_PREFIX."users.`id`=?",array($id));
        return $result;
    }
    //this is fucntion acvite user
    public function active_user($id,$cid){
        $result = Pf::database()->update(''.DB_PREFIX.'users', array('user_activation' => $cid), "".DB_PREFIX."users.`id`=?",array($id));
        return $result;
    }
    //set password
    public function set_pass($password){
        return  Pf::security()->hash($password,null,true);
    }
    //this is function check email 
    public function check_email($email,$id = ""){
        $email = trim($email);
        $params = array();
        $where_value = array();
        $operator = '';
        if($id != ''){
            $where = $operator.' id != ? ';
            $where_value[] =  $id;
            $operator = ' AND ';
        }
        $where .= $operator.'user_email = ? and user_delete_flag=0';
        $where_value[] =  $email;
        $params['where'] =  array($where,$where_value);
        $result = $this->fetch($params);
        if(count($result) == 0){
            return TRUE;
        }else{
            return FALSE;
        }
    }
    //this is function check user
    public function check_user($user,$id = ""){
        $user = trim($user);
        $params = array();
        $where_value = array();
        $operator = '';
        if($id != ''){
            $where = $operator.' id != ? ';
            $where_value[] =  $id;
            $operator = ' AND ';
        }
        $where .= $operator.'user_name = ? and user_delete_flag=0';
        $where_value[] =  $user;
        $params['where'] =  array($where,$where_value);
        $result = $this->fetch($params);
        if(count($result) == 0){
            return TRUE;
        }else{
            return FALSE;
        }
    }
    //function random string key
    public function rand_string($length) {
        $str = '';
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $size = strlen($chars);
        for ($i = 0; $i < $length; $i++) {
            $str .= $chars[rand(0, $size - 1)];
        }
        return $str . microtime();
    }
    //function activation key
    public function set_activation_key(){
       return $this->rand_string(10);
    }
    //get info user_id
    public function get_info($id){
    	$params = array();
    	$where_value = array();
    	$where = "id=? and user_delete_flag = 0";
    	$where_value[] =  $id;
    	$params['where'] =  array($where,$where_value);
    	return $this->fetch($params);
    }
    //this is function change password
    public function save_pass($id,$pass){
      $result = Pf::database()->update(''.DB_PREFIX.'users', array('user_password' => $pass), "".DB_PREFIX."users.`id`=?",array($id));
      return $result;
    }
    //this is function change avatar
    public function save_avatar($id,$avarta){
    	$result = Pf::database()->update(''.DB_PREFIX.'users', array('user_avatar' => $avarta), "".DB_PREFIX."users.`id`=?",array($id));
    	return $result;
    }
    public function save_change($id,$firstname,$lastname,$email,$public_profile,$custom_info){
    	$result = Pf::database()->update(''.DB_PREFIX.'users', array('user_firstname' => $firstname, 'user_lastname' => $lastname, 'user_email' => $email, 'public_profile'  => $public_profile,'user_custom_fields'=> $custom_info), "".DB_PREFIX."users.`id`=?",array($id));
    	return $result;
    }
    //set activation mail
    public function set_activation_option(){
    	$setting = Pf::setting();
    	$active = $setting->get_element_value('pf_user', 'activa_require');
    	$this->activation = ($active==1)?2:1;
    	return $this->activation;
    }
}