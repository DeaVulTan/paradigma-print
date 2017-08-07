<?php
defined('PF_VERSION') OR header('Location:404.html');
class Contactform_Shortcode extends Pf_Shortcode_Controller{
   
    public function __construct(){
        parent::__construct();
        $this->load_model('contactform');
    }
    private function get_page_info($url) {
        Pf::database()->select('id,page_title', ''.DB_PREFIX.'pages', '`page_url`=?', array($url));
        $page_info = Pf::database()->fetch_assoc_all();
        if (!empty($page_info[0])){
            return $page_info[0];
        }else{
            return false;
        }
    }
    private function current_page() {
        $page = $this->get_page_info($_GET['pf_page_url']);
        return $page['page_title'];
    }
    public function display($atts, $content = null,$tag) {
        $link = $this->current_page();
        $this->view->breadcrumb_title = __($link,'contactform');
        Pf::event()->on("theme-breadcrumb",array($this,'contactform_breadcrumb'),10);
        $id = !empty($atts['id'])? $atts['id']: '';
        $data = get_option('contactform');
        if (empty($data) || empty($data[$id]['form']) || $data[$id]['status'] == 0) {
            return '';
        }
        $contact = $data[$id];
        if (isset($this->post->{'token'}) && Pf_Plugin_CSRF::is_valid($this->post->{'token'},$id)) {
            global $contactform_validator_error;
            $rules = $contact['validator'];
            $input_all = $this->replace_data($rules);
            $this->replace_data($rules, $input_all);
            if (isset($rules['captcha'])) {
                $recaptcha = recaptcha_check_answer(Pf::setting()->get_element_value('general', 'recaptcha_private_key'), $_SERVER["REMOTE_ADDR"],$this->post->{'recaptcha_challenge_field'},$this->post->{'recaptcha_response_field'});
                $contactform_validator_error['captcha'] = !$recaptcha->is_valid ? array(__('Captcha is not correct', 'Contactform')) : array();
                unset($rules['captcha']);
            }
            Pf::validator()->validation_rules($rules);
            Pf::validator()->run($input_all);
            $contactform_validator_error = !empty($contactform_validator_error) ? array_merge($contactform_validator_error,  Pf::validator()->get_readable_errors(false)) :  Pf::validator()->get_readable_errors(false);
            if($contactform_validator_error['captcha'][0] == ""){
                unset($contactform_validator_error['captcha']);
            }
                if (empty($contactform_validator_error) ) {
                    $this->post->datas();
                    $html = $this->sc_build_message($contact['mail']['message']);
                    $mail_config = $contact['mail']['config'];
                    if($contact['mail']['config']['recipient'] == 1){
                        $mail_config['to'] = array(
                                $mail_config['to'] => $mail_config['name']
                        );
                        $html = str_replace('[ipaddress]',$this->contactform_model->get_client_ip(), $html);
                        unset($mail_config['name']);
                        Pf::email_template()->send($mail_config, $html);
                     }else if($contact['mail']['config']['recipient'] == 0){
                        $mail_config['to'] = array(
                               $this->post->{'list_recipient'} => $mail_config['name']
                        );
                        $html = str_replace('[ipaddress]',$this->contactform_model->get_client_ip(), $html);
                        unset($mail_config['name']);
                        Pf::email_template()->send($mail_config, $html);
                    }
                    $notify = !empty($contact['mail']['notify']) ? $contact['mail']['notify'] : '<p>' . __('Your message has been sent successfully', 'contactform') . '</p>';
                    $atts['notify'] = $notify;
                    $this->view->atts = $atts;
                    return $this->view->render('display-notify');
                }
        }
        
        $class_attr = empty($contact['form_class']) ? '' : $contact['form_class'];
        $id_attr = empty($contact['form_id']) ? '' : $contact['form_id'];
        //list email
        $list_email = $contact['mail']['config']['from_list'];
        $arr = explode("\n",$list_email);
        foreach($arr as $key => $val){
            if($val == ""){
                unset($key);
            }else{
                $arr_list[$key] = explode(",",$val);
            }
        }
        $selectbox = '<select class="form-control" name="list_recipient">';
        if(isset($arr_list) && !empty($arr_list)){
            foreach($arr_list as $key => $items){
                $selectbox .='<option value='.$items[1].'>'.$items[0].'</option>';
            }
        }
        $selectbox .='</select>';
        $html = "<form class='" . e($class_attr) . "' method='post' role='form' ";
        $html .= (!empty($contact['form_id']) ? "id='" . e($id_attr) . "'" : '') . ">";
        $html .= get_token_input(600, $id);
        if($contact['mail']['config']['recipient'] == 0){
            $html_full = str_replace("{ct:dropdown type='dropdown' name='list_recipient'}",$selectbox, $contact['form']);
        }else{
            $html_full = str_replace("{ct:dropdown  type='dropdown' name='list_recipient'}",'', $contact['form']);
            $html_full = str_replace('<label for="list-email-contact">Send email to</label>','&nbsp;', $html_full);
        }
        $html .= Pf::shortcode()->exec($html_full);
        $html .='</form>';
        $atts['form'] = $html;
        $this->view->atts = $atts;
        return $this->view->render('display');
    }
  
    public function contactform_breadcrumb($breadcrumb = ''){
        return $this->view->fetch('breadcrumb');
    }
    
    private function replace_data($rules) {
     $data = $_POST;
        foreach ($rules as $key => $value) {
            $tmp = explode('|', $value);
            if (in_array('date', $tmp) && isset($data[$key])) {
                $data[$key] = convert_date($data[$key], 'Y-m-d');
            }
        }
        return  $data;
    }
    
    private function sc_build_message($data){
        $output = array();
        preg_match_all('/\{(.*?)\}/', $data, $output);
        if (count($output) === 2) {
            foreach ($output[1] as $k => $v) {
                $tmp = $this->get_value_control($v);
                $data = str_replace($output[0][$k], $tmp, $data);
            }
        }
        return $data;
    }
    
    private function get_value_control($name)
    {
        $clean = clean_control_name($name);
        if (isset($_POST[$clean])) {
            $data = $_POST[$clean];
            return is_array($data) ? strip_tags(implode(', ', $data)) : $data;
        }
        return '';
    }
}
function clean_control_name($str)
{
    return strtolower(preg_replace(array('/[^a-zA-Z0-9-]/'), array(''), $str));
}