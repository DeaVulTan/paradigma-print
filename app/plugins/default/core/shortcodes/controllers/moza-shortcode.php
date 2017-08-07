<?php
defined('PF_VERSION') OR header('Location:404.html');
class Moza_Shortcode extends Pf_Shortcode_Controller{
    public function slider($atts, $content = null, $tag) {
        public_css(RELATIVE_PATH.'/media/assets/moza-slider/css/moza.css',true);
        public_css(RELATIVE_PATH.'/media/assets/moza-slider/css/skin/default/style.css',true);
    
        public_js(RELATIVE_PATH.'/media/assets/moza-slider/assets/jquery.easing.js',true);
        public_js(RELATIVE_PATH.'/media/assets/moza-slider/assets/jquery.transform2d.js',true);
        public_js(RELATIVE_PATH.'/media/assets/moza-slider/assets/raphael-min.js',true);
        public_js(RELATIVE_PATH.'/media/assets/moza-slider/assets/utility.transform.js',true);
        public_js(RELATIVE_PATH.'/media/assets/moza-slider/js/moza-transitions.min.js',true);
        public_js(RELATIVE_PATH.'/media/assets/moza-slider/js/moza-slider.min.js',true);
    
        return '<div ' . parse_atts($atts) . '>' . Pf::shortcode()->exec($content) . '</div>';
    }
    
    public function slide($atts, $content = null, $tag) {
    
        return '<div ' . parse_atts($atts) . '>' . Pf::shortcode()->exec($content) . '</div>';
    }
}