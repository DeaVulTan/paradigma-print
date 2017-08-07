<?php
defined('PF_VERSION') OR header('Location:404.html');
class Pf_Email_Template{
    public $properties = array();
    private $data = array();
    private $setting = array();
    
    public function __construct()
    {
        $setting = Pf::setting();
        $security = $setting->get_element_value('general', 'smtp_port') == 25 ? '' : 'tls';
        $this->setting = array(
                'mail_setting' => $setting->get_element_value('general', 'mail_setting'),
                'site_name' => $setting->get_element_value('general', 'site_name'),
                'server' => $setting->get_element_value('general', 'smtp_server'),
                'username' => $setting->get_element_value('general', 'smtp_username'),
                'password' => $setting->get_element_value('general', 'smtp_password'),
                'port' => $setting->get_element_value('general', 'smtp_port'),
                'security' => $security
        );
    }
    
    public function set_setting($setting)
    {
        $this->setting = $setting;
    }
    
    public function set_transport()
    {
    
        if ($this->setting['mail_setting'] === 'PHP' ||
                empty($this->setting['server']) ||
                empty($this->setting['username']) ||
                empty($this->setting['password'])||
                empty($this->setting['port'])) {
                    $transport = Swift_MailTransport::newInstance();
                } else {
                    $transport = Swift_SmtpTransport::newInstance($this->setting['server'], $this->setting['port'], $this->setting['security'])
                    ->setUsername($this->setting['username'])
                    ->setPassword($this->setting['password']);
                }
                return Swift_Mailer::newInstance($transport);
    }
    
    public function set_message($config, $message)
    {
        $as_html = isset($config['use_as_html']) && $config['use_as_html'] == 1 ? 'text/html' : 'text/plain';
        $to = is_array($config['to']) ? $config['to'] : array($config['to'] => $config['to']);
        $name = isset($config['name']) ? $config['name'] : $this->setting['site_name'];
        $from = isset($config['from']) ? $config['from'] : $this->setting['username'];
        return Swift_Message::newInstance($config['subject'])
        ->setFrom(array($from => $name))
        ->setTo($to)
        ->setBody($message, $as_html);
    }
    
    public function send($config, $message)
    {
        try {
            $mailer = $this->set_transport();
            $message = $this->set_message($config, $message);
            !$mailer->send($message);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
            //throw new Exception(__('An error has occurred! Please try again later', 'system'));
        }
    }
    
    public function add_title($title_key, $title){
        if (empty ( $this->properties [$title_key] )) {
            $this->properties [$title_key] ['title'] = $title;
        }
    }
    public function add_element($label, $elenent_id, $title_key,$helper_file = ''){
        if (! empty ( $this->properties [$title_key] )) {
            $this->properties [$title_key] ['elements'][$elenent_id] ['label'] = $label;
            $this->properties [$title_key] ['elements'][$elenent_id] ['helper'] = '';
            if (is_file($helper_file)){
                ob_start();
                require $helper_file;
                $helper = ob_get_contents();
                ob_get_clean();
                $this->properties [$title_key] ['elements'][$elenent_id] ['helper'] = $helper;
            }else{
                $this->properties [$title_key] ['elements'][$elenent_id] ['helper'] = $helper_file;
            }
        }
    }
    public function get_element_subject($title_key, $element_id) {
        $val = '';
        $email_templates = get_option ( 'email-templates' );
        if (! empty ( $email_templates ) && is_array ( $email_templates ) && ! empty ( $email_templates [$title_key] ) && isset ( $email_templates [$title_key] [$element_id.'_subject'] )) {
            $val = $email_templates [$title_key] [$element_id.'_subject'];
        }
    
        return $val;
    }
    
    public function get_element_body($title_key, $element_id) {
        $val = '';
        $email_templates = get_option ( 'email-templates' );
        if (! empty ( $email_templates ) && is_array ( $email_templates ) && ! empty ( $email_templates [$title_key] ) && isset ( $email_templates [$title_key] [$element_id.'_body'] )) {
            $val = $email_templates [$title_key] [$element_id.'_body'];
        }
    
        return $val;
    }
    
}