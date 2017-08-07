<?php
require_once ABSPATH . '/lib/common/plugin/utiles/pf-plugin-csrf.php';
abstract class Pf_Controller {
    protected $view;
    protected $models = array();
    
    public $controller_type = 'admin';
    
    public $session;
    public $request;
    public $post;
    public $get;
    public $cookie;
    
    public $path;
    public $action = 'action';
    public $page = 'page';
    public $key;
    public $custom_template = null;
    protected $attrs;
    
    public function __construct() {
        $this->session = new Pf_Session();
        $this->post = new Pf_Post();
        $this->get = new Pf_Get();
        $this->request = new Pf_Request();
        $this->cookie = new Pf_Cookie();
        
        $class = get_class($this);
        $reflector = new ReflectionClass($class);
        $this->path = dirname(dirname($reflector->getFileName()));
        $this->key = ((isset($_GET ['admin-page'])) ? $_GET ['admin-page'] : 'dashboard').((isset($_GET ['sub_page'])) ? $_GET ['sub_page'] : '');
        $this->view = new Pf_view ( $this );
        $this->view->key = $this->key;
    }
    
    public function load_model($model){
        $model = str_replace('-', '_', $model);
        $model_array = explode('_', strtolower($model));
        $model_class = '';
        
        foreach ($model_array as $v){
            $model_class .= ucfirst($v).'_';
        }
        
        $model_class .= 'Model';
        if (isset($this->models[$model_class])){
            return $this->models[strtolower($model_class)];
        }else{
            $model_file = str_replace('_', '-', strtolower($model_class)).'.php';
            if (is_file($this->path.'/models/'.$model_file)){
                if (!class_exists($model_class)){
                    require $this->path.'/models/'.$model_file;
                }
                $model_object = new $model_class;
                $model_class = strtolower($model_class);
                $this->models[$model_class] = $model_object;
                
                return $this->models[$model_class];
            }else{
                return false;
            }
        }
    }
    
    public function __get($key){
        $keys = explode('_', $key);
        $ext = strtolower(end($keys));
        switch ($ext){
            case 'model':
                return $this->models[strtolower($key)];
                break;
            default:
                return $this->{$key};
                break;
        }
    }
    
    //public function index() {}
    /**
     * 
     * @param string $url
     */
    protected function redirect($url) {
        if (! headers_sent ()){
            header ( 'Location: ' . $url );
        }else {
            echo '<script type="text/javascript">';
            echo 'window.location.href="' . $url . '";';
            echo '</script>';
            echo '<noscript>';
            echo '<meta http-equiv="refresh" content="0;url=' . $url . '" />';
            echo '</noscript>';
        }
        exit ();
    }
    
    protected function check_by_pattern($str) {
        $output = array();
        preg_match('/\[(.*?)\]/', $str, $output);
        if (count($output) != 2 || $output[1] == '') {
            return false;
        }
        return true;
    }
    
    protected function get_by_pattern($str) {
        $output = array();
        preg_match('/\[(.*?)\]/', $str, $output);
        if (count($output) != 2 || $output[1] == '') {
            return;
        }
    
        $split = explode(':', $output[1]);
        if (count($split) != 2 || !in_array(strtoupper($split[0]), array('GET', 'POST', 'SESSION'))) {
            return;
        }
        $key = $split[1];
        switch (strtolower($split[0])) {
            case 'session':
                $tmp = $this->session->has($key) ? $this->session->get($key) : '';
            break;
            case 'post':
                $tmp = $this->input->has_post($key) ? $this->input->post($key, true) : '';
            break;
            default :
                $tmp = $this->input->has_get($key) ? $this->input->get($key, true) : '';
            break;
        }
        return array($key, $tmp);
    }
    
    protected function get_attr($key) {
        if (!empty($this->attrs[$key])) {
            if ($this->check_by_pattern($this->attrs[$key])) {
                $data = $this->get_by_pattern(($this->attrs[$key]));
                return is_array($data) ? $data[1] : $this->attrs[$key];
            } else {
                return $this->attrs[$key];
            }
        }
        return '';
    }
}