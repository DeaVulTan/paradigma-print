<?php
defined ( 'PF_VERSION' ) or header ( 'Location:404.html' );
class Post_Widget extends Pf_Widget {
    public $name = 'Posts List Widget';
    public $version = '1.0';
    public $description = 'This is description';
    
    public function __construct($properties, $setting) {
        parent::__construct ( $properties, $setting );
    }
    
    public function setting() {
        $this->view->render();
    }
    
    public function index() {
        $this->view->render();
    }
}
