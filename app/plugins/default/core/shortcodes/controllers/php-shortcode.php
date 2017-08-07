<?php
defined('PF_VERSION') OR header('Location:404.html');
class Php_Shortcode extends Pf_Shortcode_Controller{
    public function url($atts, $content = null, $tag) {
        $url = isset($atts['url']) ? $atts['url'] : '';
        $relative = RELATIVE_PATH;
        $regex = '/^(?:(?:https?|ftps?|file|news|gopher):\\/\\/)?(?:(?:(?:25[0-5]|2[0-4]\d|(?:(?:1\d)?|[1-9]?)\d)\.){3}(?:25[0-5]|2[0-4]\d|(?:(?:1\d)?|[1-9]?)\d)'
                . '|(?:[0-9a-z]{1}[0-9a-z\\-]*\\.)*(?:[0-9a-z]{1}[0-9a-z\\-]{0,62})\\.(?:[a-z]{2,6}|[a-z]{2}\\.[a-z]{2,6})'
                        . '(?::[0-9]{1,4})?)(?:\\/?|\\/[\\w\\-\\.,\'@?^=%&:;\/~\\+#]*[\\w\\-\\@?^=%&\/~\\+#])$/i';
        if (preg_match($regex, $url)){
            return $url;
        }else{
            if (empty($relative)) {
                return site_url() . '/' . $url;
            }
            return site_url() . "{$relative}/{$url}";
        }
    }
    
    public function datetime($atts, $content = null) {
        $format = isset($atts['format']) ? $atts['format'] : 'Y';
        $timestamp = isset($atts['timestamp']) ? $atts['timestamp'] : time();
        return date($format, $timestamp);
    }
    
    public function comment($content = null) {
        return "<!-- {$content} -->";
    }
}