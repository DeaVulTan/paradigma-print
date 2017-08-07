<?php
defined('PF_VERSION') OR header('Location:404.html');
class Soundcloud_Shortcode extends Pf_Shortcode_Controller{
    public function audio($atts, $content = null) {
        $atts['url'] = (!empty($atts['url'])) ? $atts['url'] : '';
        $atts['width'] = (!empty($atts['width'])) ? $atts['width'] : '100%';
        $atts['height'] = (!empty($atts['height'])) ? $atts['height'] : 81;
        $atts['comments'] = (!empty($atts['comments'])) ? $atts['comments'] : 'true';
        $atts['auto_play'] = (!empty($atts['auto_play'])) ? $atts['auto_play'] : 'true';
        $atts['color'] = (!empty($atts['color'])) ? $atts['color'] : 'ff7700';

        if ($atts['comments'] == 'yes') {
            $atts['comments'] = 'true';
        } elseif ($atts['comments'] == 'no') {
            $atts['comments'] = 'false';
        }

        if ($atts['auto_play'] == 'yes') {
            $atts['auto_play'] = 'true';
        } elseif ($atts['auto_play'] == 'no') {
            $atts['auto_play'] = 'false';
        }

        if ($atts['color']) {
            $atts['color'] = str_replace('#', '', $atts['color']);
        }
        
        $this->view->atts = $atts;
        $this->view->render();
    }
}