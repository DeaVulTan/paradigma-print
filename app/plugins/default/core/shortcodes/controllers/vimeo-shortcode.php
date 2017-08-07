<?php
defined('PF_VERSION') OR header('Location:404.html');
class Vimeo_Shortcode extends Pf_Shortcode_Controller{
    public function video($atts, $content = null) {
        $atts['id'] = (!empty($atts['id'])) ? $atts['id'] : '';
        $atts['class'] = (!empty($atts['class'])) ? $atts['class'] : '';
        $atts['width'] = (!empty($atts['width'])) ? $atts['width'] : '600';
        $atts['height'] = (!empty($atts['height'])) ? $atts['height'] : '360';
        
        $this->view->atts = $atts;
        $this->view->render();
    }
}