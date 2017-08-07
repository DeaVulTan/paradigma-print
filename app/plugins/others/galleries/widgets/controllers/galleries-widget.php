<?php
defined ( 'PF_VERSION' ) or header ( 'Location:404.html' );
class Galleries_Widget extends Pf_Widget {
    public $name = 'Galleries';
    public $version = '1.0';
    public $description = 'This is Gallery description';
    public function __construct($properties, $setting) {
        parent::__construct ( $properties, $setting );
        $this->load_model ( 'galleries' );
    }
    public function setting() {
        $this->view->render ();
    }
    public function index() {
        $this->view->render ();
    }
}