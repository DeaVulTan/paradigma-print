<?php
defined('PF_VERSION') OR header('Location:404.html');
class Livestream_Shortcode extends Pf_Shortcode_Controller{
    public function video($atts, $content = null,$tag) {
        $atts['acounts'] = (!empty($atts['acounts'])) ? $atts['acounts'] : '';
        $atts['events'] = (!empty($atts['events'])) ? $atts['events'] : '';
        $atts['videos'] = (!empty($atts['videos'])) ? $atts['videos'] : '';
        $atts['width'] = (!empty($atts['width'])) ? $atts['width'] : '600';
        $atts['height'] = (!empty($atts['height'])) ? $atts['height'] : '360';
        $atts['autoplay'] = (!empty($atts['autoplay'])) ? $atts['autoplay'] : 'false';
        $this->view->atts = $atts;
        $this->view->render();
    }
}