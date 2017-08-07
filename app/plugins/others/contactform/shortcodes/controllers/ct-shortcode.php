<?php
defined('PF_VERSION') OR header('Location:404.html');
class Ct_Shortcode extends Pf_Shortcode_Controller{
    public function text($atts, $content = null,$tag) {
        global $contactform_validator_error;
        if(!isset($atts['type'])){
            return ;
        }else if(!isset($atts['name'])){
            return;
        }else{
           $data = array();
           $data = $atts;
           unset($data['required']);
           $atts['test'] = form_input($data);
           return $atts['test'] . alert_error_validator($data['name'], $contactform_validator_error, true);
        }
    }
    
    public function hidden($atts, $content = null,$tag) {
        if(!isset($atts['type'])){
            return ;
        }else if(!isset($atts['name'])){
            return;
        }else{
            $data = array();
            $data = $atts;
            unset($data['required']);
            $atts['test'] = form_input($data);
            return $atts['test'];
        }
    }
    
    public function email($atts, $content = null,$tag) {
        global $contactform_validator_error;
        if(!isset($atts['type'])){
            return ;
        }else if(!isset($atts['name'])){
            return;
        }else{
            $data = array();
            $data = $atts;
            unset($data['required']);
            $atts['test'] = form_input($data);
            return $atts['test'] . alert_error_validator($data['name'], $contactform_validator_error, true);
        }
    }
    
    public function radio($atts, $content = null,$tag) {
        global $contactform_validator_error;
        if(!isset($atts['type'])){
            return ;
        }else if(!isset($atts['name'])){
            return;
        }else{
            $data = array();
            $data = $atts;
            $html = '';
            $items_tmp = explode('|', $data['items']);
            foreach ($items_tmp as $value){
                $data['value'] = $value;
                unset($data['items']);
                unset($data['required']);
                $html .= '<label class="item-check">' . form_radio($data) . " {$value}" . '</label>';
            }
            $atts['test'] = $html;
            return $atts['test']. alert_error_validator($data['name'], $contactform_validator_error, true);
        }
    }
    
    public function checkbox($atts, $content = null,$tag) {
        global $contactform_validator_error;
        if(!isset($atts['type'])){
            return ;
        }else if(!isset($atts['name'])){
            return;
        }else{
            $data = array();
            $data = $atts;
            $html = '';
            $items_tmp = explode('|', $data['items']);
            foreach ($items_tmp as $value){
                $data['value'] = $value;
                unset($data['items']);
                unset($data['required']);
                $html .= '<label class="item-check">' . form_checkbox($data) . " {$value}" . '</label>';
            }
            $atts['test'] = $html;
            return $atts['test'] . alert_error_validator($data['name'], $contactform_validator_error, true);;
        }
    }
    
    public function url($atts, $content = null,$tag) {
        global $contactform_validator_error;
        if(!isset($atts['type'])){
            return ;
        }else if(!isset($atts['name'])){
            return;
        }else{
            $data = array();
            $data = $atts;
            unset($data['required']);
            $atts['test'] = form_input($data);
            return $atts['test']. alert_error_validator($data['name'], $contactform_validator_error, true);;
        }
    }
    
    public function textarea($atts, $content = null,$tag) {
        global $contactform_validator_error;
        if(!isset($atts['type'])){
            return ;
        }else if(!isset($atts['name'])){
            return;
        }else{
            $data = array();
            $data = $atts;
            unset($data['value']);
            unset($data['required']);
            $atts['test'] = form_textarea($data);
            return $atts['test']. alert_error_validator($data['name'], $contactform_validator_error, true);;
        }
    }
    
    public function submit($atts, $content = null,$tag) {
        if(!isset($atts['type'])){
            return ;
        }else if(!isset($atts['name'])){
            return;
        }else{
            $data = array();
            $data = $atts;
            $atts['test'] =   '<input ' . _parse_attributes($data, array()) ." />\n";
            return $atts['test'];
        }
    }
    
    public function reset($atts, $content = null,$tag) {
        if(!isset($atts['type'])){
            return ;
        }else if(!isset($atts['name'])){
            return;
        }else{
            $data = array();
            $data = $atts;
            $atts['test'] =   '<input ' . _parse_attributes($data, array()) . $extra . " />\n";
            return $atts['test'];
        }
    }
    
    public function button($atts, $content = null,$tag) {
        if(!isset($atts['type'])){
            return ;
        }else if(!isset($atts['name'])){
            return;
        }else{
            $data = array();
            $data = $atts;
            $atts['test'] =   '<input ' . _parse_attributes($data, array()) . $extra . " />\n";
            return $atts['test'];
        }
    }
    public function date($atts, $content = null,$tag) {
        global $contactform_validator_error;
        if(!isset($atts['type'])){
            return ;
        }else if(!isset($atts['name'])){
            return;
        }else{
            $data = array();
            $data = $atts;
            $data['data-type'] = 'date';
            $data['type'] = 'text';
            unset($data['required']);
            $atts['test'] = form_input($data);
            return $atts['test']. alert_error_validator($data['name'], $contactform_validator_error, true);;
        }
    }
    
    public function number($atts, $content = null,$tag) {
        global $contactform_validator_error;
        if(!isset($atts['type'])){
            return ;
        }else if(!isset($atts['name'])){
            return;
        }else{
            $data = array();
            $data = $atts;
            unset($data['required']);
            $atts['test'] = form_input($data);
            return $atts['test'] . alert_error_validator($data['name'], $contactform_validator_error, true);
        }
    }
    public function acceptance($atts, $content = null,$tag) {
        if(!isset($atts['type'])){
            return ;
        }else{
            $data = array();
            $data = $atts;
            $data['type'] = 'checkbox';
            $data['name'] = 'acceptance';
            $data['value'] = 1;
            $html = '';
            $html .= '<label class="item-check">' . form_checkbox($data) . " {$data['label']}" . '</label>';
            $atts['test'] = $html;
            return $atts['test'];
        }
    }
    
    public function captcha($atts, $content = null,$tag){
        global $contactform_validator_error;
        if(!isset($atts['type'])){
            return ;
        }else{
            $data = array();
            $data = $atts;
            if($data['type'] === 'captcha'){
                $atts['test'] =  recaptcha_get_html(Pf::setting()->get_element_value('general', 'recaptcha_public_key'));
                $data['name'] = 'captcha';
            }else{
                $this->clean_control_name($data['name']);
            }
            return $atts['test']. alert_error_validator($data['name'], $contactform_validator_error, true);
        }
    }
    
    public function dropdown($atts, $content = null,$tag) {
        global $contactform_validator_error;
        if(!isset($atts['type'])){
            return ;
        }else if(!isset($atts['name'])){
            return;
        }else{
            $data = array();
            $data = $atts;
            $name = $data['name'];
            if($name === 'list_recipient'){
              return;
            }else{
                $items_tmp = explode('|', $data['items']);
                if (empty($items_tmp)) {
                    return '';
                }
                foreach ($items_tmp as $value) {
                    $items[$value] = $value;
                }
                $atts['test'] = form_dropdown($name,$items);
            }
            return $atts['test'] . alert_error_validator($data['name'], $contactform_validator_error, true);;
        }
    }
    
    public function clean_control_name($str)
    {
        return strtolower(preg_replace(array('/[^a-zA-Z0-9-]/'), array(''), $str));
    }
}

if (!function_exists('alert_error_validator')) {

    function alert_error_validator($key, $data = array(), $return = false) {

        if (has_error($key, $data)) {
            foreach ($data[$key] as $value) {
                $error = "<p class='help-block'>{$value}</p>";
                if ($return) {
                    return $error;
                }

                echo $error;
            }
        }
    }

}

if (!function_exists('has_error')) {

    function has_error($key, $data = array()) {
        if (isset($data[$key])) {
            return true;
        }

        return false;
    }

}
