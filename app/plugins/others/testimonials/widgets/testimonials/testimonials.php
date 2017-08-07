<?php
    defined('PF_VERSION') OR header('Location:404.html');
require_once 'class.php';
class Testimonials_Widget extends Pf_Widget{
    public $name = 'Testimonials';
    public $version = '1.0';
    public $description = 'This is testimonials widget description';
    protected $db;


    public function __construct($properties,$setting) {
        parent::__construct ( $properties,$setting );
        $this->db   =   Pf::database();
    }
    
    public function setting_form(){
        require 'testimonials-form.php';
    }
    
    public function main(){
        $list   = Pf_get_testimonial();
        require 'testimonials-views.php';
    }
}
