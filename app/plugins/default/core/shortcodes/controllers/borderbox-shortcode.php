<?php
defined ( 'PF_VERSION' ) or header ( 'Location:404.html' );
class Borderbox_Shortcode extends Pf_Shortcode_Controller {
    public function borderbox($atts, $content = NULL, $class) {
        $class = !empty($class) ? $class : 'normal';
        $type = !empty($atts['type']) ? $atts['type'] : 'solid';
        $color = !empty($atts['color']) ? $atts['color'] : '#72c02c';
        $width = !empty($atts['width']) ? $atts['width'] : '2px';
        $style = !empty($atts['style']) ? $atts['style'] : '';
        $content = !empty($content) ? Pf::shortcode()->exec($content) : '';
        
        $this->view->content = $content;
        $this->view->class = $class;
        $this->view->width = $width;
        $this->view->type = $type;
        $this->view->style = $style;
        $this->view->render();
    }
}