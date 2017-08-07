<?php
defined ( 'PF_VERSION' ) or header ( 'Location:404.html' );
class Slider_Shortcode extends Pf_Shortcode_Controller {
    public function jcarousel($atts, $content = null) {
        public_js(RELATIVE_PATH.'/media/assets/jcarousel/jquery.jcarousel.min.js',true);
        public_js(RELATIVE_PATH.'/media/assets/jcarousel/jcarousel.responsive.js',true);
        $content = ! empty ( $content ) ? "[" . Pf::shortcode ()->exec ( $content ) . "]" : '[]';
        $array = json_decode ( $content, true );
        $class = isset ( $atts ['class'] ) ? $atts ['class'] : '';
        $this->view->array = $array;
        $this->view->class = $class;
        $this->view->render();
    }
    
    public function carousel($atts, $content = null) {
        $data = json_decode("[" . Pf::shortcode()->exec($content) . "]", true);
        $id = !empty($atts['id']) ? $atts['id'] : uniqid('pf-carousel');
        $class = isset($atts['class']) ? $atts['class'] : '';
        $this->view->data = $data;
        $this->view->id = $id;
        $this->view->class = $class;
        $this->view->render();
    }
    
    public function img($atts, $content = null) {
        if (empty($atts['src']))
            $atts['src'] = '';
        if (empty($atts['text']))
            $atts['text'] = '';
        if (empty($atts['desc']))
            $atts['desc'] = '';
        if (empty($atts['style']))
            $atts['style'] = '';
        if (empty($atts['data']))
            $atts['data'] = '';
        $atts['link'] = isset($atts['link']) ? $atts['link'] : null;
        $alt = isset($atts['alt']) ? $atts['alt'] : '';
        $result = json_encode(array(
            'src' => Pf::shortcode()->exec("{php:url url='" . $atts['src'] . "'}{/php:url}"),
            'text' => $atts['text'],
            'desc' => $atts['desc'],
            'link' => $atts['link'],
            'style'=> $atts['style'],
            'data'  => $atts['data'],
            'alt' => $alt,
        ));
        return $result;
    }
    
}