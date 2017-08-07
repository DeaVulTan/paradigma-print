<?php defined('PF_VERSION') OR header('Location:404.html');?>
<?php


//mail
require_once ABSPATH . "/lib/Swift/lib/swift_required.php";
class Pf_Mail extends Swift {
    protected $setting ;
    protected $sitename;
    protected $mailer;
    protected $db;
    public function __construct() {
            $this->setting    =   Pf::setting();
            $this->sitename =   $this->setting->get_element_value('general','site_name');
            $this->mailconfig   = get_configuration('mail_setting');
            if($this->mailconfig == 'SMTP'){
            $transport = Swift_SmtpTransport::newInstance(Pf::setting()->get_element_value('general','smtp_server'), Pf::setting()->get_element_value('general','smtp_port'))
                        ->setUsername(Pf::setting()->get_element_value('general','smtp_username'))
                        ->setPassword(Pf::setting()->get_element_value('general','smtp_password'));
            }
            else{
                $transport  = Swift_MailTransport::newInstance();
            }
            $this->mailer   = Swift_Mailer::newInstance($transport);
            $this->domain   = public_url('');
            $this->SentFrom  =   array(noreply_email() => $this->sitename." Administrator"); 
            $this->db   =   Pf::database();
        }
        private function replace_tpl($template, $username='', $firstname='', $lastname='', $email='', $link=''){
            $template   =   str_replace("{username}", $username, $template);
            $template   =   str_replace("{firstname}", $firstname, $template);
            $template   =   str_replace("{lastname}", $lastname, $template);
            $template   =   str_replace("{email}", $email, $template);
            $template   =   str_replace("{link}", $link, $template);
            $template   =   str_replace("{sitename}", $this->sitename, $template);
            return $template;
        }
        public function mail_welcome($email, $username, $firstname, $lastname){
            $content_tpl        =   Pf::email_template()->get_element_body('pf_user_mail_template','mail_welcome');
            $subject_tpl        =   Pf::email_template()->get_element_subject('pf_user_mail_template','mail_welcome');
            $content        = $this->replace_tpl($content_tpl, $username,$firstname,$lastname, '', '');
            $subject        = $this->replace_tpl($subject_tpl, $username,$firstname,$lastname, '', ''); 
                $message = Swift_Message::newInstance()
                            ->setSubject($subject)
                            ->setFrom($this->SentFrom)
                            ->setTo(array($email => $firstname.' '.$lastname))
                            ->setBody($content)
                            ;
                $this->mailer->send($message);
        }
        public function mail_forgot($email,$key) {
            $this->db->select(''.DB_PREFIX.'users.id as uid,user_firstname,user_lastname,user_password',''.DB_PREFIX.'users',"`user_email`='$email' and user_delete_flag=0");
            $info   =   $this->db->fetch_assoc_all();
            $firstname      = rtrim($info[0]['user_firstname']);
            $lastname       =   $info[0]['user_lastname'];
            $uid            =   $info[0]['uid'];
            $password       =   $info[0]['user_password'];
            $link           =   $this->domain."user/recover/key:".$key;
            $content_tpl        =   Pf::email_template()->get_element_body('pf_user_mail_template','mail_forgot');
            $subject_tpl        =   Pf::email_template()->get_element_subject('pf_user_mail_template','mail_forgot');
            $content        = $this->replace_tpl($content_tpl, '',$firstname,$lastname, '', $link);
            $subject        = $this->replace_tpl($subject_tpl, '',$firstname,$lastname, '', $link); 
            $message = Swift_Message::newInstance()
                            ->setSubject($subject)
                            ->setFrom($this->SentFrom)
                            ->setTo(array($email => $firstname.' '.$lastname))
                            ->setBody($content)
                            ;
                $this->mailer->send($message);
        }
        public function mail_active($email, $firstname, $lastname) {
            $this->db->select(''.DB_PREFIX.'users.id as uid, user_name, user_activation_key',''.DB_PREFIX.'users',"`user_email`=?",array($email));
            $info   =   $this->db->fetch_assoc_all();
            $uid =   $info[0]['uid'];
            $username   =   $info[0]['user_name'];
            $activekey  =   md5($info[0]['user_activation_key']);
            $link   =   $this->domain."user/activation/id:".$uid."/key:".$activekey;
            $content_tpl        =   Pf::email_template()->get_element_body('pf_user_mail_template','mail_active');
            $subject_tpl        =   Pf::email_template()->get_element_subject('pf_user_mail_template','mail_active');
            $content        = $this->replace_tpl($content_tpl, $username,$firstname,$lastname, '', $link);
            $subject        = $this->replace_tpl($subject_tpl, '', $firstname, $lastname, ''); 
            $message = Swift_Message::newInstance()
                            ->setSubject($subject)
                            ->setFrom($this->SentFrom)
                            ->setTo(array($email => $firstname.' '.$lastname))
                            ->setBody($content)
                            ;
                $this->mailer->send($message);
        }
        public function mail_success_forgot($email) {
            $this->db->select('user_firstname,user_lastname,user_name',''.DB_PREFIX.'users',"`user_email`='$email' and user_delete_flag=0");
            $info   =   $this->db->fetch_assoc_all();
            $firstname    =   $info[0]['user_firstname'];
            $lastname    =   $info[0]['user_lastname'];
            $username       =   $info[0]['user_name'];
            $content_tpl        =   Pf::email_template()->get_element_body('pf_user_mail_template','mail_success_forgot');
            $subject_tpl        =   Pf::email_template()->get_element_subject('pf_user_mail_template','mail_success_forgot');
            $content        = $this->replace_tpl($content_tpl, $username,$firstname,$lastname, '', '');
            $subject        = $this->replace_tpl($subject_tpl, '', $firstname,$lastname, ''); 
            $message = Swift_Message::newInstance()
                            ->setSubject($subject)
                            ->setFrom($this->SentFrom)
                            ->setTo(array($email => $firstname.' '.$lastname))
                            ->setBody($content)
                            ;
                $this->mailer->send($message);
        }
}
