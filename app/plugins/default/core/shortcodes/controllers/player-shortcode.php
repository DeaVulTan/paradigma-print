<?php
defined('PF_VERSION') OR header('Location:404.html');
class Player_Shortcode extends Pf_Shortcode_Controller{
    public function video($attrs, $content = '', $tag) {
        $allow_type = array('mp4', 'webm', 'ogg');
        require_once dirname(__FILE__) . '/helpers.php';
        $config_js = player_clean_attrs($attrs);
        $source = player_get_source($attrs, $allow_type, 'mp4');
        if (empty($source)) {
            return '';
        }
        public_js(RELATIVE_PATH.'/media/assets/podlove-web-player/podlove-web-player.js',true);
        public_css(RELATIVE_PATH.'/media/assets/podlove-web-player/css/podlove-web-player.css',true);
        
        $id = 'a' . uniqid();
    
        $config_video = array('width', 'height', 'preload', 'poster', 'fullscreen', 'captions');
        $atts_video = array();
        foreach($config_video as $item){
            if(isset($attrs[$item])){
                $atts_video[$item] = $attrs[$item];
            }
        }
        
        
        $this->view->atts_video = $atts_video;
        $this->view->id = $id;
        $this->view->source = $source;
        $this->view->config_js = $config_js;
        
        $this->view->render();
    }
    
    public function audio($attrs, $content = '') {
        $allow_type = array('mp4', 'mp3', 'ogg', 'opus');
        require_once dirname(__FILE__) . '/helpers.php';
        $config_js = player_clean_attrs($attrs);
        $source = player_get_source($attrs, $allow_type);
        if (empty($source)) {
            return;
        }
        public_js('media/assets/plugins/podlove-web-player/podlove-web-player.js');
        public_css('media/assets/plugins/podlove-web-player/css/podlove-web-player.css');
        $id = 'a' . uniqid();
        
        $this->view->config_js = $config_js;
        $this->view->id = $id;
        $this->view->source = $source;
        
        $this->view->render();
    }
}