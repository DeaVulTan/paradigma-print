<?php 
defined('PF_VERSION') OR header('Location:404.html');

class Menu_Widget extends Pf_Widget {

    public $name = 'Menu';
    public $version = '1.0';
    public $description = 'This is Menu Widget description';
    protected $menu_class;

    public function __construct($properties, $setting) {
        parent::__construct($properties, $setting);
    }

    public function setting() {
        $this->view->menu_list = $this->menu_list();
        $this->view->render();
    }

    public function index() {
        $this->view->render();
    }
    
    private function menu_list(){
        $all = get_option('menu');
        foreach ($all as $menu) {
            $list[$menu['id']] = $menu['name'];
        }
        return $list;
    }

}
