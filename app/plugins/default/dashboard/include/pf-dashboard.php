<?php defined('PF_VERSION') OR header('Location:404.html');?>
<?php
/**
 *
 * @package		Vitubo
 * @author		Vitubo Team 
 * @copyright   Vitubo Team
 * @link		http://www.vitubo.com
 * @since		Version 1.0
 * @filesource
 *
 */
class Pf_Dashboard{
    protected $config;
    protected $db;
    protected $model_array=array();
    protected $view_array = array();

    public function __construct(){
        $this->db = Pf::database();
        $this->input = new Pf_Plugin_Input;
        $path = abs_plugin_path(__FILE__) . '/dashboard/dashboard-config-array.php';
        if(file_exists($path)){
            $this->config = include $path;
        }else{
            throw new Exception("File Path:".$path." not exits");
        }
    }
    public function get_config(){
        return $this->config;
    }
    public function check_exits_key($token_key){
        foreach($this->config as $k=>$v){
            if(Pf_Plugin_CSRF::is_valid($token_key,$k)){
                return $v;
            }
        }
        return array();
    }
    public function set_config($key,$value = ""){
        if(is_array($key)){
            $this->config = array_merge($this->config,$value);
        }else if(is_string($key)){
            $this->config[$key] = $value;
        }
    }
    public function run_action($name_wget,$action){
        $this->view_array = array();
        $this->_action($this->config[$name_wget],$name_wget,$action);
        return $this->view_array;
    }
    public function build(){
        foreach($this->config as $key=>$value){
           $this->_action($value,$key);
        }
        return $this;
    }
    private function table_exits($table){
        return $this->db->fetch_assoc($this->db->query('SHOW TABLES LIKE ?', array($table)));
    }
    /**
     * @param $array array
     * @param $key string
     */
    private function _action($array,$key,$action=null){
        if(is_array($array)){
              if(isset($array['table'])){
                  $table = $array['table'];
                  if(!is_null($this->table_exits($table))){
                      if(is_null($action)){
                          foreach($array['action'] as $k=>$value){
                              $_value = (is_array($value))?$k:$value;
                              if(method_exists($this,'_'.$_value)){
                                  $_method = '_'.$_value;
                                  $this->{$_method}($table,$value,$key);
                              }
                          }
                      }else{
                          $value = $array['action'][$action];
                          $_value = (is_array($value))?$action:$value;
                          if(method_exists($this,'_'.$_value)){
                              $_method = '_'.$_value;
                              $this->{$_method}($table,$value,$key);
                          }
                      }
                  }
              }else{
                  foreach($array['action'] as $k=>$value){
                      $_value = (is_array($value))?$k:$value;
                      if(method_exists($this,'_'.$_value)){
                          $_method = '_'.$_value;
                          if($_value =='plugin_active'){
                               $this->{$_method}($value,$key);
                          }
                      }
                  }
              }
        }
    }
    /**
     * @param $table string
     * @param $array array
     * @param $key string
     */
    private function _plugin_active($array,$key){
        $actived_plugins = get_option ( 'active_plugins' );
        if(is_array($actived_plugins) && count($actived_plugins)){
            foreach($actived_plugins as $row){
                if(dirname($row) == $key){
                    $this->view_array['plugin_active'][$key]['active'] = true;
                    return true;
                }
            }
        }
        $this->view_array['plugin_active'][$key]['active'] = false;
    }
    private function _count($table,$array,$key){
        $this->view_array['count'][$key]['label'] = isset($array['label'])?$array['label']:"Default ".$key;

        if(isset($array['approve']) && is_string($array['approve'])){
           empty($array['approve']) || $this->view_array['count'][$key]['count'] =  $this->db->dcount($array['columns_count'],$table);
        }
        $this->view_array['count'][$key]['icon'] =  isset($array['label'])?$array['icon']:"ion ion-bag";
        $this->view_array['count'][$key]['url'] =  isset($array['url'])?$array['url']:"#";
        $this->view_array['count'][$key]['backgorund_box'] =  isset($array['backgorund_box'])?$array['backgorund_box']:"bg-aqua";
    }
    /**
     * @param $table string
     * @param $array array
     * @param $key string
     */
    private function _approve($table,$array,$key){

        if(isset($array['columns']) && is_array($array['columns'])){
            $key_primary = (isset($array['columns']['id']))?$array['columns']['id']:"id";
            $this->view_array['approve'][$key]['label'] = isset($array['label'])?$array['label']:$key;
            $this->view_array['approve'][$key]['token'] = isset($array['token'])?$array['token']:"id";
            $this->view_array['approve'][$key]['url'] = isset($array['url'])?$array['url']:"#";
            $this->view_array['approve'][$key]['acl'] = isset($array['acl'])?$array['acl']:true;
            $this->view_array['approve'][$key]['active'] = isset($array['active'])?$array['active']:true;
            $where = $array['columns']['status']." = 0";
            $string_column = implode(",",$array['columns']);
            $this->db->select($string_column,$table,$where,'',"`{$key_primary}` DESC",'10');
            $_result = $this->db->fetch_assoc_all();
            $list = array();
            foreach($_result as $value){
                foreach($array['columns'] as $k=>$row_column){
                    if(isset($value[$row_column])){
                        $list[$value['id']][$k] = $value[$row_column];
                    }
                }
            }
            $this->view_array['approve'][$key]['count_unpublished'] = count($_result);
            $this->view_array['approve'][$key]['status'] = isset($array['columns']['status'])?$array['columns']['status']:$key.'_status';
            $this->view_array['approve'][$key]['rows'] = $list ;
        }
    }
    public function getViewArray(){
        return $this->view_array;
    }
}