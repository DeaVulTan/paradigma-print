<?php
defined('PF_VERSION') OR header('Location:404.html');
class List_Shortcode extends Pf_Shortcode_Controller{
    public function li($atts, $content = null) {
        $content = !empty($content) ? Pf::shortcode()->exec($content) : '';
        $style = !empty($atts['style']) ? "style='" . $atts['style'] . "'" : '';
        $result = "<li $style>$content</li>";
        return $result;
    }
    public function ol($atts, $content = null) {
        $content = !empty($content) ? Pf::shortcode()->exec($content) : '';
        $style = !empty($atts['style']) ? $atts['style'] : '';
        $class = !empty($atts['class']) ? $atts['class'] : '';
        $result = "<ol class='$class' style='$style'>$content</ol>";
        return $result;
    }
    public function ul($atts, $content = null) {
        $content = !empty($content) ? Pf::shortcode()->exec($content) : '';
        $style = !empty($atts['style']) ? $atts['style'] : '';
        $class = !empty($atts['class']) ? $atts['class'] : '';
        $result = "<ul class='$class' style='$style'>$content</ul>";
        return $result;
    }
}