<?php
defined ( 'PF_VERSION' ) or header ( 'Location:404.html' );
class Pricings_Shortcode extends Pf_Shortcode_Controller {
    public function option($atts, $content = null) {
        $option = !empty($atts['id']) ? $atts['id'] : '';
        $content = !empty($content) ? Pf::shortcode()->exec($content) : '';
        $this->view->id = $option;
        $this->view->content = $content;
        $this->view->render();
    }

    public function inner($atts, $content = null) {
        $content = !empty($content) ? Pf::shortcode()->exec($content) : '';
        $class = !empty($atts['class']) ? $atts['class'] : '';
        $this->view->class = $class;
        $this->view->content = $content;
        $this->view->render();
    }

    public function header($atts, $content = null) {
        $content = !empty($content) ? Pf::shortcode()->exec($content) : '';
        $this->view->content = $content;
        $this->view->render();
    }
    public function number($atts, $content = null) {
        $content = !empty($content) ? Pf::shortcode()->exec($content) : '';
        $this->view->content = $content;
        $this->view->render();   
    }
    public function body($atts, $content = null) {
        $content = !empty($content) ? Pf::shortcode()->exec($content) : '';
        $class = !empty($atts['class']) ? $atts['class'] : '';
        $this->view->class = $class;
        $this->view->content = $content;
        $this->view->render();
    
    }
    public function joint_inner($atts, $content = null) {
        $content = !empty($content) ? Pf::shortcode()->exec($content) : '';
        $this->view->content = $content;
        $this->view->render();
    }
    public function table_responsive($atts, $content = null) {
        $content = !empty($content) ? Pf::shortcode()->exec($content) : '';
        $this->view->content = $content;
        $this->view->render();
    }
}