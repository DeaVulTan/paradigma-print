<?php
defined('PF_VERSION') OR header('Location:404.html');
/*
 * * 
 * @package  Vitubo
 * @author  Vitubo Team 
 * @copyright Vitubo Team
 * @link  http://www.vitubo.com
 * @since  Version 1.0
 * @filesource
 *
 */

$setting = Pf::setting();
$setting->add_title('pf_user', __('User settings','user'));
$allow_reg_selected = Pf::setting()->get_element_value('pf_user', 'allow_reg');
$activa_require_selected = Pf::setting()->get_element_value('pf_user', 'activa_require');
$login_selected = Pf::setting()->get_element_value('pf_user', 'login_attemps');
$array_reg = array('<label> '.__("Yes",'user').' ' . form_radio('allow_reg', '1') . '</label>', ' &nbsp; <label>'.__("No","user").' ' . form_radio('allow_reg', '2', TRUE) . '</label>');
$setting->add_element(__('Allow Registration?', 'user'), $array_reg, 'pf_user');
$array_act = array('<label> '.__("Yes","user").' ' . form_radio('activa_require', '1') . '</label>', ' &nbsp; <label>'.__("No","user").' ' . form_radio('activa_require', '2') . '</label>');
$array_email = array('<label> '.__("Yes","user").' ' . form_radio('welcome_email', '1',TRUE) . '</label>', ' &nbsp; <label>'.__("No","user").' ' . form_radio('welcome_email', '2') . '</label>');
$array_sign_in = array('<label> '.__("Yes","user").' ' . form_radio('sign_in_option', '1',TRUE) . '</label>', ' &nbsp; <label>'.__("No","user").' ' . form_radio('sign_in_option', '0') . '</label>');
$setting->add_element(__('Enable Activation?', 'user'), $array_act, 'pf_user');
$setting->add_element(__('Welcome Email?', 'user'), $array_email, 'pf_user');
$setting->add_element(__('Show Sign In on frontpage', 'user'), $array_sign_in, 'pf_user');
$setting->add_element(__('Username minimum length?', 'user'),form_input('user_length',3),'pf_user');
$setting->add_element(__('Password minimum length?', 'user'),form_input('pass_length',6),'pf_user');
$drop_login = array(0=>'', 3=>3, 5=>5, 10=>10, 15=>15);
$setting->add_element(__('Login attemps', 'user'), "<div class='col-md-2 no-padding'>" . form_dropdown('login_attemps', $drop_login, $login_selected) . '</div>', 'pf_user');

$setting->add_element(__('Activation link exists in', 'user'), "<div class='col-md-2 no-padding'>" . form_dropdown('active_time',array(1=>1,2=>2,3=>3,5=>5,10=>10),'' ) ." &nbsp; </div>".__('day(s)','user'), 'pf_user');

//email
$email_template = Pf::email_template();
        $email_template->add_title('pf_user_mail_template',__('User','user'));
        $email_template->add_element(__('Activation','user'),'mail_active','pf_user_mail_template',abs_plugin_path(__FILE__).'/user/admin/views/emails/active.php');
        $email_template->add_element(__('Welcome','user'),'mail_welcome','pf_user_mail_template',abs_plugin_path(__FILE__).'/user/admin/views/emails/welcome.php');
        $email_template->add_element(__('Reset new password','user'),'mail_reset_password','pf_user_mail_template',abs_plugin_path(__FILE__).'/user/admin/views/emails/reset.php');
        $email_template->add_element(__('Password Reset','user'),'mail_forgot','pf_user_mail_template',abs_plugin_path(__FILE__).'/user/admin/views/emails/forgot.php');
        $email_template->add_element(__('Password is reset successfully','user'),'mail_success_forgot','pf_user_mail_template',abs_plugin_path(__FILE__).'/user/admin/views/emails/welcome.php');