<?php
defined('PF_VERSION') OR header('Location:404.html');
/**
 * 
 * @author vudoanthang
 *
 */
abstract class Pf_Plugin {
    public $name;
    public $version;
    public $author;
    public $description;
    
    /**
     * Activate plugin
     */
    public function activate() {
    }
    /**
     * Deactive plugin
     */
    public function deactivate() {
    }
    /**
     * This method is executed when public page is loaded
     */
    public function public_init() {
    }
    /**
     * This method is executed when admin page is loaded
     */
    public function admin_init() {
    }
    /**
     * 
     * @param unknown $ns
     * @param unknown $shortcode
     * @param string $fnc
     */
    protected function add_shortcode($ns,$shortcode,$fnc = null){
        $fnc = ($fnc === null)?$shortcode:$fnc;
        $ns = strtolower(trim($ns));
        $controller_array = explode('_', str_replace('-', '_', $ns));
        $controller_file = implode('-', $controller_array).'-shortcode.php';
        $controller_class = '';
        foreach ($controller_array as $v){
            $controller_class .= ucfirst($v).'_';
        }
        $controller_class .= 'Shortcode';
        
        $class = get_class($this);
        $reflector = new ReflectionClass($class);
        $plugin_path = dirname($reflector->getFileName());
        
        
        if (is_file($plugin_path.'/shortcodes/controllers/'.$controller_file)){
            if (!class_exists($controller_class)){
                require $plugin_path.'/shortcodes/controllers/'.$controller_file;
            }
            $controller_obj = new $controller_class;
            $controller_obj->custom_template = $fnc;
            
            $fnc = strtolower(trim($fnc));
            $shortcode = strtolower($shortcode);
            $_call_back = array($controller_obj,$fnc);
            
            if (method_exists($controller_obj, $fnc) && is_callable($_call_back, true)) {
                Pf::shortcode()->add( $shortcode, $_call_back, $ns);
            }
        }
    }
    
    public function add_event($event,$ns, $fnc, $priority = 10, $arg_num = 1){
        $ns = strtolower(trim($ns));
        $controller_array = explode('_', str_replace('-', '_', $ns));
        $controller_file = implode('-', $controller_array).'-controller.php';
        $controller_class = '';
        foreach ($controller_array as $v){
            $controller_class .= ucfirst($v).'_';
        }
        $controller_class .= 'Controller';
        
        $class = get_class($this);
        $reflector = new ReflectionClass($class);
        $plugin_path = dirname($reflector->getFileName());
        if (is_file($plugin_path.'/events/controllers/'.$controller_file)){
            if (!class_exists($controller_class)){
                require $plugin_path.'/events/controllers/'.$controller_file;
            }
            $controller_obj = new $controller_class;
            $fnc = strtolower(trim($fnc));
            $_call_back = array($controller_obj,$fnc);
            
            if (method_exists($controller_obj, $fnc) && is_callable($_call_back, true)) {
                Pf::event()->on($event,$_call_back,$priority,$arg_num);
            }
            
        }
        
    }
    
    /**
     *
     * @param unknown $icon_class            
     * @param unknown $name            
     * @param string $callback            
     */
    protected function admin_menu($icon_class, $name, $key, $callback) {
        admin_menu ( get_class ( $this ), $icon_class, $name, $key, $callback );
    }
    /**
     *
     * @param unknown $icon_class            
     * @param unknown $name            
     * @param unknown $callback            
     * @param unknown $parent            
     */
    protected function admin_children_menu($icon_class, $name, $key, $callback, $parent) {
        admin_menu ( get_class ( $this ), $icon_class, $name, $key, $callback, $parent );
    }
    /**
     * 
     * @param unknown $link
     * @param string $file
     */
    protected function css($link, $file = '') {
        if (is_array($link)){
            foreach ($link as $v){
                if (is_string($v) && !empty($v)){
                    admin_css ( $v, $file );
                }
            }
        }else if (is_string($link) && !empty($link)){
            admin_css ( $link, $file );
        }
    }
    /**
     * 
     * @param unknown $js
     * @param string $file
     */
    protected function js($js, $file = ''){
        if (is_array($js)){
            foreach ($js as $v){
                if (is_string($v) && !empty($v)){
                    admin_js($v, $file);
                }
            }
        }else if (is_string($js) && !empty($js)){
            admin_js($js, $file);
        }
    }
}