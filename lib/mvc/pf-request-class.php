<?php
class Pf_Request extends Pf_Base_Object{
    public function __construct(){
        parent::__construct($_REQUEST);
    }
    
    public function is_post(){
        return (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST');
    }
    
    public function is_get(){
        return (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'GET');
    }
    
    public function is_put(){
        return (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'PUT');
    }
    
    public function is_delete(){
        return (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'DELETE');
    }
    
    public function is_ajax(){
        return ((isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') || 
                (!empty($_GET['ajax'])) && (int)$_GET['ajax'] == 1);
    }
}