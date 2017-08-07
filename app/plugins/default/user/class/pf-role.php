<?php defined('PF_VERSION') OR header('Location:404.html');?>
<?php
define('DB_ROLE', DB_PREFIX."role");
class Pf_Role{
    private $db;
    private $table = DB_ROLE;
    public function __construct(){
        $this->db = Pf::database();
    }
    public function get_list_role(){
        $this->db->select('`id`,`role_name`',$this->table);
        $list   =   $this->db->fetch_assoc_all();
        foreach ($list as $item){
            $role[$item['id']] =    $item['role_name'];
        }
        return $role;
    }
}