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
class Pf_Plugin_Controller
{

    protected $model;
    protected $view;
    protected $input;
    protected $validator;
    protected $data;
    protected $csrf;
    protected $session;
    protected $acl;

    public function __construct()
    {
        $this->view = new Pf_Plugin_View;
        $this->input = new Pf_Plugin_Input;
        $this->validator = Pf::validator();
        $this->session = Pf_Plugin_Session::getInstance();
        $this->data = array(
            'validated' => array(),
            'current' => $this->get_current(),
            'current_param' => $this->get_current_param()
        );
        $this->setting = new Pf_Plugin_Setting;
    }

    protected function get_current($key = 'current')
    {
        return $this->input->has_get($key) ? (int) $this->input->get('current') : 0;
    }

    protected function get_current_param()
    {
        return $this->get_current() > 0 ? '&current=' . $this->get_current() : '';
    }

    public function alertSave($messages, $result)
    {
        $type = $result ? 'success' : 'danger';
        if (array_key_exists($type, $messages)) {
            $this->session->flash($type, $messages[$type]);
        }
    }

    protected function check_acl($acl = array())
    {
        $this->acl = !empty($acl) ? $acl : $this->acl;
        if (!in_array(current_user('user-group'), $this->acl)) {
            Pf_Plugin_Redirect::to('admin-page&sub_page&token&id&status&act');
        }
    }

}
