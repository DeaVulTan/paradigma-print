<?php defined('PF_VERSION') OR header('Location:404.html');?>
<?php
$prefix = DB_PREFIX;
class Auth{
    public $type = 'session';
    private  $auth_namespace = 'user';
    public $user_table;
    public $user_column = 'user_name';
    public $pass_column = 'user_password';
    public $user_level = 'user_role';
    protected $dateformat;
    public function __construct($id = null){
        global $prefix;
        $this->user_table = $prefix."users";
        $this->db = Pf::database();
        $this->dateformat   = get_configuration('long_date');
        $this->user = new Pf_User();
        $this->cookie = Pf::cookie();
        if(!is_null($id)){
            $this->user->set_id($id);
        }
    }
    public function get_identity(){
       return $this->user->get_id();
    }
    public function forgot_password($email){
        $info = $this->user->select_user('`user_email`,id',"`user_email` = ? and user_delete_flag =0 ",array($email));
        if (count($info)==1) {
            return $this->set_time_reset($email,$info[0]);
        } else{
            return false;
        }
    }
    public function encrypt($password){
        return Pf::security()->hash($password,null,true);
    }
    public function is_username_exist($username){
        return !$this->user->check_user($username);
    }
    public function is_email_exist($email){
        return !$this->user->check_email($email);
    }
    public function register(array $array){
        $this->user->add_data($array);
        $this->user->send_mail = true;
        $this->user->set_activation_option();
        $this->user->save();
    }
    public function login($username,$password,$encrypt = true){
        if($encrypt){
            $password = $this->encrypt($password);
        }
        $this->db->select(''.DB_PREFIX.'users.id as `uid`,
                            `user_name`,
                            `user_firstname`,
                            `user_lastname`,
                            `user_role`,`user_avatar`,`user_delete_flag`,
                            `'.$this->pass_column.'`,
                            `user_activation`',$this->user_table, "`".$this->user_column."`=? and user_delete_flag=0 ",array($username));

        $result=$this->db->fetch_assoc_all();

        if(count($result) > 1){
            return false;
        }
        if(is_array($result)){
            foreach($result as $row){
                if($row[$this->pass_column] == $password){
                    if($row['user_activation'] ==1){
                        $this->set_session_info_login($row);
                    }
                    return $row;
                }
            }
        }
        return false;
    }
    public function set_session_info_login($row){
        if(isset($row['user_name'])
            && isset($row['user_firstname'])
            && isset($row['user_lastname'])
            && isset($row['user_role'])
            && isset($row['uid'])
        ){
            $this->set_session('user-name',$row['user_name']);
            $this->set_session('user-firstname',$row['user_firstname']);
            $this->set_session('user-lastname',$row['user_lastname']);
            $this->set_session('user-group',$row['user_role']);
            $this->set_session('user-id',$row['uid']);
            $this->set_session('avatar',(empty($row['user_avatar'])||is_null($row['user_avatar']))?"":$row['user_avatar']);
        }
    }
    public function set_session($name, $value) {
        $_SESSION[$this->auth_namespace][$name] = $value;
    }
    public function get_session($name){
        return isset($_SESSION[$this->auth_namespace][$name])?$_SESSION[$this->auth_namespace][$name]:null;
    }
    public function destroy_session() {
        unset($_SESSION[$this->auth_namespace]);
    }
    public function set_cookie($name, $value, $time ,$path="/") {
        $this->cookie->set($name,$value,$time);
    }
    public function get_cookie($name,$default='') {
        return $this->cookie->get($name,$default);
    }
    public function check_cookie($name){
        return $this->cookie->exists($name);
    }

    public function destroy_cookie($name) {
        $this->cookie->delete($name);
    }
    private function get_ip() {
        if(!empty($_SERVER['HTTP_CLIENT_IP'])){
            //check ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }else if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            //to check ip is pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }else{
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
    public function logout() {
        if ( $this->type == 'session' ) {
            $this->destroy_session();
        }
    }
    public function login_attemp($username) {
        $time   =   date("Y-m-d H:i:s",time()+1800);
        if(!empty($username)){
            $this->db->update(''.DB_PREFIX.'users',array('login_attemp'=>$time),"`user_name`=?",array($username));
        }
    }
    public function update_login($id) {
        $time =   date("Y-m-d H:i:s",time());
        $ip   =   $this->get_ip();
        $this->db->update(''.DB_PREFIX.'users',array('user_login_time'=>$time, 'user_login_ip'=>$ip),"`id`=?",array($id));
    }
    public function check_time($time) {
        if($time!==''){
            $time_ago   =   time()- $time;
            $day_ago    =   round($time_ago / 86400);
            if($day_ago>2){
                return FALSE;
            }
            else{
                return TRUE;
            }
        }
        else
            return FALSE;
    }
    public function valid_login($username) {
        $this->db->select('login_attemp',''.DB_PREFIX.'users',"`user_name`=?",array($username));
        $result     =   $this->db->fetch_assoc_all();
        if(count($result)>0){
            $login_att  =   $result[0]['login_attemp'];
            return strtotime($login_att);
        }
        else
            return 0;
    }
    public function set_time_reset($email,$info) {
        $security_user = new Pf_User_Security();
        $time   =   date("Y-m-d H:i:s",  (time()+(4*3600)));
        $encrypt = base64_encode($security_user->encrypt(array('id'=>$info['id'],'time'=>$time)));
        if(!empty($email)){
            $this->db->update(''.DB_PREFIX.'users',array('user_forgot_pass_key'=>$encrypt),"`user_email`=?",array($email));
            return $encrypt;
        }
        return false;
    }
    public function get_username(){
        if($this->is_logged_in()){
            return $_SESSION[$this->auth_namespace]['user-name'];
        }
    }
    public function get_user_id(){
        if($this->is_logged_in()){
            return $_SESSION[$this->auth_namespace]['user-id'];
        }
        return 0;
    }
    public function is_logged_in(){
        if (!empty($_SESSION[$this->auth_namespace]['user-id']))
            return TRUE;
        else
            return FALSE;
    }
    public function is_admin(){
        if ($this->is_logged_in() == TRUE && $_SESSION [$this->auth_namespace] ['user-group'] == 1)
            return TRUE;
        else
            return FALSE;
    }
    function is_editor()
    {
        if ($this->is_logged_in() == TRUE && $_SESSION [$this->auth_namespace] ['user-group'] == 2)
            return TRUE;
        else
            return FALSE;
    }

    function is_author()
    {
        if ($this->is_logged_in() == TRUE && $_SESSION [$this->auth_namespace] ['user-group'] == 3)
            return TRUE;
        else
            return FALSE;
    }

    function is_contributor()
    {
        if ($this->is_logged_in() == TRUE && $_SESSION [$this->auth_namespace] ['user-group'] == 4)
            return TRUE;
        else
            return FALSE;
    }

    function is_user()
    {
        if ($this->is_logged_in() == TRUE && $_SESSION [$this->auth_namespace] ['user-group'] == 5)
            return TRUE;
        else
            return FALSE;
    }
}