<?php defined('PF_VERSION') OR header('Location:404.html');?>
<?php
$prefix = DB_PREFIX;
include_once dirname(__FILE__)."/mail-class.php";
class Pf_User{
    private  $uid;
    public $username;
    public $firstname;
    public $lastname;
    public $password;
    public $email;
    public $role;
    public $custom;
    public $registered_date;
    public $activation_key;
    public $activation;
    public $login_time;
    public $login_ip;
    public $login_attemp;
    public $tmpass;
    private $dateformat;
    private $table;
    private  $db;
    private $validator;
    private $mail_swift;
    public  $send_mail;
    public  $public=1;

    public function __construct(){
        global $prefix;
        $this->table = $prefix."users";
        $this->db = Pf::database();
        $this->validator = Pf::validator();
        $this->dateformat   = get_configuration('long_date');
        $this->mail_swift= new Pf_Mail;
        $this->init();
    }
    private function init(){
        $this->uid = null;
        $this->username = "";
        $this->firstname = "";
        $this->lastname =   "";
        $this->password = "";
        $this->email = "";
        $this->role = "";

        $this->login_ip = $this->get_ip();
        $this->login_attemp = "0000-00-00 00:00:00";
        $this->tmpass = "0000-00-00 00:00:00";
        $this->user_avatar = "";
        $this->activation = 2;
        $this->send_mail =false;
        $this->set_activation_key();

    }
    private function get_ip() {
        if(!empty($_SERVER['HTTP_CLIENT_IP'])){
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }else if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }else{
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
    public function get_id(){
        return $this->uid;
    }
    public function set_id($id){
        if (isset($id)) {
            
            $id = (is_numeric($id) && $id > -1) ? intval($id) : false;
            if ($id!=false) {
                $this->uid = $id;
            }
        }
        return $this;
    }
    public function set_username($username){
        $this->username = $username;
    }
    public function set_role($role){
        if (isset($role)) {
            $role = (is_numeric($role) && $role > -1) ? intval($role) : false;
            if ($role!=false) {
                $this->role = $role;
            }
        }
    }
    public function add_data(array $array){
        if(isset($array['user-name'])){
            $this->set_username($array['user-name']);
        }
        if(isset($array['user-email'])){
            $this->set_email($array['user-email']);
        }
        if(isset($array['password'])){
            $this->set_password($array['password']);
        }
        if(isset($array['firstname'])){
            $this->firstname = $array['firstname'];
        }
        if(isset($array['lastname'])){
            $this->lastname = $array['lastname'];
        }
        if(isset($array['role'])){
            $this->set_role($array['role']);
        }
        if(isset($array['activation'])){
            $this->activation = $array['activation'];
        }
        if(isset($array['custom'])){
            $this->custom = $array['custom'];
        }
        if(isset($array['public'])) {
            $this->public   =   $array['public'];
        }
    }

    public function rand_string($length) {
        $str = '';
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $size = strlen($chars);
        for ($i = 0; $i < $length; $i++) {
            $str .= $chars[rand(0, $size - 1)];
        }
        return $str . microtime();
    }
    public function set_password($password, $encrypt = true)
    {
        $this->password = ($encrypt ? Pf::auth()->encrypt($password) : trim($password));
        return $this->password;
    }
    public function set_activation_option(){
        $setting = Pf::setting();
        $active = $setting->get_element_value('pf_user', 'activa_require');
        $this->activation = ($active==1)?2:1;
    }
    public function set_activation_key(){
        $this->activation_key = $this->rand_string(10);
    }
    public function send_email(){
        if($this->send_mail){
            if ($this->activation == 2) {
                $this->mail_swift->mail_active($this->email, $this->firstname, $this->lastname);
            }else{
				$setting = Pf::setting();
				$status = $setting->get_element_value('pf_user', 'welcome_email');
				if($status == 1){
					$this->mail_swift->mail_welcome($this->email,$this->username,$this->firstname, $this->lastname);
				}
            }
        }
    }
    public function set_email($email){
        if($this->check_email($email)){
            $this->email = $email;
        }
        return $this;
    }
    public function check_user($username){
        $where = "`user_name`=? and user_delete_flag=0 ";
        $params = array();
        $params[] = $username;
        if(isset($this->uid)){
            $where.=" and id =?";
            $params[] =$this->uid;
        }
        $this->db->select(''.DB_PREFIX.'users.id as uid',$this->table,$where,$params);
        $result     =   $this->db->fetch_assoc_all();
        return (count($result)==0)?true:false;
    }
    public function check_email($email){
        $email = trim($email);
        $params = array();
        $validated = $this->validator->validate(
            array('email'=>$email), array('email'=>'required|valid_email')
        );
        if($validated === TRUE){
            $where = "`user_email`=? and user_delete_flag=0 ";
            $params[] = $email;
            if(isset($this->uid)){
                $where.=" and id !=?";
                $params[] =$this->uid;
            }
            $this->db->select(''.DB_PREFIX.'users.id as uid',$this->table,$where,$params);
            $result     =   $this->db->fetch_assoc_all();
            return (count($result)==0);
        }
        return false;
    }
    public function save($columns=array()){
        if(!count($columns)){
            $array = array(
                "user_name" => $this->username,
                "user_firstname" => $this->firstname,
                "user_lastname" => $this->lastname,
                "user_email" =>  $this->email,
                "user_role" => $this->role,
                "user_activation"=>$this->activation,
                "user_avatar"=>$this->user_avatar,
                "public_profile"=> $this->public,
                "user_custom_fields"=> $this->custom
            );
        }else{
            $array = $columns;
        }
        if(isset($this->uid)){
            if(!empty($this->password)){
                $array['user_password'] = $this->password;
            }
            if(empty($this->username)){
                unset($array['user_name']);
            }
            $this->db->update($this->table,$array,"".DB_PREFIX."users.`id`=?",array($this->uid));
        }else{
            $array['user_activation_key'] = $this->activation_key;
            $array['user_password'] = $this->password;
            $array['user_registered_date'] = date("Y-m-d H:i:s", time());
            $this->db->insert($this->table,$array);
            $this->uid =$this->db->insert_id();
            $this->send_email();
        }
        return $this->uid;
    }
    public function delete(){
        if(isset($this->uid)){
            $this->db->update(''.DB_PREFIX.'users', array('user_delete_flag' => '1'), "".DB_PREFIX."users.`id`=?",array($this->uid));
        }
    }
    public function get_info($columns = "*",$user_delete_flag = false){
        $where="".DB_PREFIX."users.id =?";
        if($user_delete_flag){
            $where.=" and user_delete_flag =0";
        }
        $this->db->select($columns, $this->table,$where,array($this->uid));
        return $this->db->fetch_assoc_all();
    }
    public function select_user($columns,$where,$param=array()) {
        if (isset($where)) {
            $this->db->select($columns, ''.DB_PREFIX.'users', $where,$param);
        } else {
            $this->db->select($columns, ''.DB_PREFIX.'users');
        }
        $_result = $this->db->fetch_assoc_all();
        return $_result;
    }
    public function get_user_all($where='',$param=array(),$columns = "*",$limit='',$type="select"){
        if($type == "dcount"){
            return $this->db->dcount($columns,$this->table, $where,$param, '', $limit);
        }
        $this->db->select($columns, $this->table, $where,$param, '', $limit);
        return $this->db->fetch_assoc_all();
    }
    public function action($action, $uid) {
        switch ($action) {
            case 'del':
                $this->db->update(''.DB_PREFIX.'users', array('user_delete_flag' => '1'), "".DB_PREFIX."users.`id`=? and ".DB_PREFIX."users.`id`!= ".current_user('user-id')."",array($uid));
                break;
            case 'active':
                $this->db->update(''.DB_PREFIX.'users', array('user_activation' => '1'), "".DB_PREFIX."users.`id`=? and ".DB_PREFIX."users.`id`!= ".current_user('user-id')."",array($uid));
                break;
            case 'deactive':
                $this->db->update(''.DB_PREFIX.'users', array('user_activation' => '2'), "".DB_PREFIX."users.`id`=? and ".DB_PREFIX."users.`id`!= ".current_user('user-id')."",array($uid));
                break;
        }
    }
}