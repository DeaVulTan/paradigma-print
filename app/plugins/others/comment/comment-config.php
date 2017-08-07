<?php

defined('PF_VERSION') OR header('Location:404.html');
/**
 *
 * @package		Vitubo
 * @author		Vitubo Team 
 * @copyright   Vitubo Team
 * @link		http://www.vitubo.com
 * @since		Version 1.0
 * @filesource
 *
 */
require_once ABSPATH . '/lib/common/plugin/utiles/pf-plugin-setting.php';
require_once ABSPATH . '/lib/common/plugin/helpers/helper.php';
$setting = Pf::setting();
$setting->add_title('pf_comment', __('Comment settings', 'comment'));
$util_setting = new Pf_Plugin_Setting;

$util_setting->set_name('pf_comment');
$util_setting->set_data(array('Newest first', 'Oldest first'));
$util_setting->add_element_radio(__('Enable comment?', 'comment'), 'enable');
$util_setting->add_element_radio(__('Comment approval?', 'comment'), 'approve_flag');
$util_setting->add_element_dropdown(__('Ordering', 'comment'), 'ordering');

$util_setting->set_data(Pf_Plugin_Singleton::list_users('user_role <= ? and user_delete_flag=0', array(2)));
$util_setting->add_element_dropdown(__('Who will approve comment?', 'comment'), 'approve', 'multiple="multiple"');
$util_setting->add_element_input(__('Maximum characters', 'comment'), 'maximum_characters');

//Email template
$email_template = Pf::email_template();
$email_template->add_title('pf_comment_mail_template', __('Comment', 'comment'));
$email_template->add_element(__('Comment notification', 'comment'), 'approve_comment', 'pf_comment_mail_template', ABSPATH . '/app/plugins/others/comment/shortcodes/views/emails/approve.php');
