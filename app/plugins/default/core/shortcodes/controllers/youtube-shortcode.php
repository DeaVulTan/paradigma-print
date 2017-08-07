<?php
defined('PF_VERSION') OR header('Location:404.html');
class Youtube_Shortcode extends Pf_Shortcode_Controller{
    public function video($atts, $content = null,$tag) {
        $atts['id'] = (!empty($atts['id'])) ? $atts['id'] : '';
        $atts['width'] = (!empty($atts['width'])) ? $atts['width'] : '600';
        $atts['height'] = (!empty($atts['height'])) ? $atts['height'] : '360';
        if(!empty($atts['autoplay']) && $atts['autoplay'] == 'true'){
            $atts['autoplay'] = 1;
        }else{
            $atts['autoplay'] = 0;
        }
        $this->view->atts = $atts;
        $this->view->render();
    }
}