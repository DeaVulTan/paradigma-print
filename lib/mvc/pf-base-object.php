<?php
abstract class Pf_Base_Object {
    protected $data = array ();
    public function __construct(& $data = array()) {
        $this->data = & $data;
    }
    public function data($key, $value = null) {
        if (empty ( $key ) || (is_string ( $key ) && trim ( $key ) == ''))
            return $this;
        if ($value === null) {
            return (isset ( $this->data [$key] )) ? $this->data [$key] : '';
        } else {
            $this->data [$key] = $value;
            
            return $this;
        }
    }
    public function datas($datas = array()) {
        if (! empty ( $datas ) && is_array ( $datas )) {
            foreach ( $datas as $key => $value ) {
                $this->data ( $key, $value );
            }
        }
        
        return $this;
    }
    
    public function has($key){
        return isset($this->data[$key]);
    }
    
    public function remove($key){
        unset($this->data[$key]);
    }
    
    public function __isset($key){
        return isset($this->data[$key]);
    }
    
    public function __unset($key){
        unset($this->data[$key]);
    }
    
    public function __set($key, $value) {
        $this->data ( $key, $value );
    }
    public function __get($key) {
        return $this->data ( $key );
    }
}