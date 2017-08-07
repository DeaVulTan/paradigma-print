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
class Pf_Plugin_Bootstrap {

    public $controller;
    private $method;
    protected $parent;
    private $plugin_name;
    private $path;


    public function __construct($admin = false)
    {
        $this->path = $admin ? DEFAULT_PLUGIN_PATH:PLUGIN_PATH;
    }

    /*
     * @parent string
     */

    public function set_parent($parent) {
        $this->parent = $parent;
    }

    public function set_plugin_name($plugin_name) {
        $this->plugin_name = $plugin_name;
    }

    public function start() {
        $name = $_GET['admin-page'];
        if (isset($_GET['sub_page'])) {
            $name = $_GET['sub_page'];
        }
        $base_name = ($this->parent == '') ? "pf-{$name}-" : "pf-{$this->parent}-{$name}-";
        $controller_name = $base_name . 'controller';
        $model_name = $base_name . 'model';
        $controller_file = $this->path . '/' . $this->plugin_name . '/controllers/' . strtolower($controller_name) . '.php';
        $model_file = $this->path . '/' . $this->plugin_name . '/models/' . strtolower($model_name) . '.php';

        if (file_exists($model_file)) {
            require_once $model_file;
        }

        if (file_exists($controller_file)) {
            require_once $controller_file;
            $controller_clean = str_replace('-', '_', $controller_name);
            $this->controller = new $controller_clean;
        }
        $this->get_method();
        $this->call_method();
    }

    private function get_method() {

        $method = isset($_GET['act']) ? $_GET['act'] : 'main';
        $this->method = $method;
    }

    private function call_method() {

        if (method_exists($this->controller, $this->method)) {
            $reflection = new ReflectionMethod($this->controller, $this->method);
            if (!$reflection->isPublic()) {
                $this->controller->error();
            } else {
                $this->controller->{$this->method}();
            }
        }
    }

}
