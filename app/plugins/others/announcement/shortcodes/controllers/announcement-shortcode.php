<?php
defined('PF_VERSION') OR header('Location:404.html');
class Announcement_Shortcode extends Pf_Shortcode_Controller{
    public function __construct(){
        parent::__construct();
        $this->load_model('announcement');
    }
    public function display($atts, $content = null,$tag) {
        $atts['show'] = $this->announcement_model->show();
        $atts['type'] = array(
                1=>'danger',
                2=>'info',
                3=>'warning',
                4=>'success'
        );
        $this->view->atts = $atts;
        $this->view->render();
    }
}