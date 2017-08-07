<?php
defined('PF_VERSION') OR header('Location:404.html');
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
class Pf_Plugin_Shortcode_Bootstrap {

    private $controller;
    private $method;
    private $shortcode_name;
    private $path;
    private $model_file_name;
    private $code;
    private $atts;
    private $content;

    public function set_shortcode_name($shortcode_name) {
        $this->shortcode_name = $shortcode_name;
    }

    public function set_path($path) {
        $this->path = $path;
    }

    public function set_model_file_name($model_file_name) {
        $this->model_file_name = $model_file_name;
    }

    public function set_code($code) {
        $this->code = $code;
    }

    public function set_attrs($atts) {
        $this->atts = $atts;
    }

    function __construct($shortcode_name, $path, $model_file_name, $code = '', $atts = array(), $content = '') {
        $this->shortcode_name = $shortcode_name;
        $this->path = $path;
        $this->model_file_name = $model_file_name;
        $this->code = $code;
        $this->atts = $atts;
        $this->content = $content;
    }

    public function start() {
        $base_name = "pf-{$this->shortcode_name}-";
        $controller_name = $base_name . 'shortcode';
        $model_file = ABSPATH . $this->path . '/models/' . strtolower($this->model_file_name) . '.php';
        $controller_file = ABSPATH . $this->path . '/public/' . strtolower($controller_name) . '.php';
        if (file_exists($model_file)) {
            require_once $model_file;
        }
        if (file_exists($controller_file)) {
            require_once $controller_file;
            $controller_clean = str_replace('-', '_', $controller_name);
            $this->controller = new $controller_clean($this->atts, $this->content);
        }
        $this->get_method();
        return $this->call_method();
    }

    private function get_method() {
        $method = isset($_GET["{$this->code}-act"]) ? $_GET["{$this->code}-act"] : 'main';
        $this->method = $this->code != '' ? $this->code . '_' . $method : $method;
    }

    private function call_method() {
        $content = '';
        if (method_exists($this->controller, $this->method)) {
            $reflection = new ReflectionMethod($this->controller, $this->method);
            if (!$reflection->isPublic()) {
                $content = $this->controller->error();
            } else {
                $content = $this->controller->{$this->method}();
                
            }
        }
        return $content;
    }

}
