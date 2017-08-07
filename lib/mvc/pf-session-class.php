<?php
class Pf_Session extends Pf_Base_Object{
    public function __construct() { 
        parent::__construct($_SESSION);
    }
    
    public function flash($key, $message = null, $class = 'alert alert-success'){
        if (!empty($key) && is_string($key)){
            if ($message == null){
                if (isset($this->data['__::FLASH::__']) && isset($this->data['__::FLASH::__'][$key])){
                    echo  '<div class="'.$this->data['__::FLASH::__'][$key]['class'].'">'.$this->data['__::FLASH::__'][$key]['message'].'</div>';
                    unset($this->data['__::FLASH::__'][$key]);
                }
            }else{
                $this->data['__::FLASH::__'][$key]['message'] = $message;
                $this->data['__::FLASH::__'][$key]['class'] = $class;
            }
        }
    }
    
    public function has($item) {
        return isset($_SESSION[$item]);
    }
    
    public function has_flash($item) {
        return isset($_SESSION[self::FLASHDATA_KEY . self::FLASHDATA_OLD . $item]);
    }
    
    public function get($item = null) {
        if (isset($item)) {
            return $this->has($item) ? $_SESSION[$item] : '';
        }
        return isset($_SESSION) ? $_SESSION : array();
    }
    public function put($new_data, $new_value = '') {
        if (is_string($new_data)) {
            $new_data = array($new_data => $new_value);
        }
        foreach ($new_data as $k => $v) {
            $_SESSION[$k] = $v;
        }
    }
}