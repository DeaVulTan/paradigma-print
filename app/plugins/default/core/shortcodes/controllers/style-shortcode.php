<?php
defined('PF_VERSION') OR header('Location:404.html');
class Style_Shortcode extends Pf_Shortcode_Controller{
    public function colorbox($atts, $content = null) {
        $color = !empty($atts['color']) ? $atts['color'] : 'red';
        $content = !empty($content) ? Pf::shortcode()->exec($content) : '';
        
        $this->view->color = $color;
        $this->view->content = $content;
        $this->view->render();
    }
}