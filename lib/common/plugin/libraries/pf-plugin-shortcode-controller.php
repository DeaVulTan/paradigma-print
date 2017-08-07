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
class Pf_Plugin_Shortcode_Controller {

    protected $view;
    protected $input;
    protected $validator;
    protected $session;
    protected $data;
    protected $setting;
    protected $attrs;

    public function __construct() {
        $this->view = new Pf_Plugin_Shortcode_View;
        $this->input = new Pf_Plugin_Input;
        $this->validator = Pf::validator();
        $this->session = Pf_Plugin_Session::getInstance();
        $this->data['validated'] = array();
        $this->setting = new Pf_Plugin_Setting;
        $this->mail = new Pf_Plugin_Mail();
    }

    /**
     * Set session message save
     * @param string $messages
     * @param string $result
     */
    public function alertSave($messages, $result) {
        $type = $result ? 'success' : 'danger';
        if (array_key_exists($type, $messages)) {
            $this->session->flash($type, $messages[$type]);
        }
    }

    protected function check_by_pattern($str) {
        $output = array();
        preg_match('/\[(.*?)\]/', $str, $output);
        if (count($output) != 2 || $output[1] == '') {
            return false;
        }
        return true;
    }

    protected function get_by_pattern($str) {
        $output = array();
        preg_match('/\[(.*?)\]/', $str, $output);
        if (count($output) != 2 || $output[1] == '') {
            return;
        }

        $split = explode(':', $output[1]);
        if (count($split) != 2 || !in_array(strtoupper($split[0]), array('GET', 'POST', 'SESSION'))) {
            return;
        }
        $key = $split[1];
        switch (strtolower($split[0])) {
            case 'session':
                $tmp = $this->session->has($key) ? $this->session->get($key) : '';
                break;
            case 'post':
                $tmp = $this->input->has_post($key) ? $this->input->post($key, true) : '';
                break;
            default :
                $tmp = $this->input->has_get($key) ? $this->input->get($key, true) : '';
                break;
        }
        return array($key, $tmp);
    }

    protected function get_attr($key) {
        if (!empty($this->attrs[$key])) {
            if ($this->check_by_pattern($this->attrs[$key])) {
                $data = $this->get_by_pattern(($this->attrs[$key]));
                return is_array($data) ? $data[1] : $this->attrs[$key];
            } else {
                return $this->attrs[$key];
            }
        }
        return '';
    }

    /**
     * Get value of attr shortcode
     * @param string $key
     * @return array
     */
    protected function get_value_attr($key) {
        if (!isset($this->attrs[$key]) || $this->attrs[$key] === '') {
            return;
        }
        $data = $this->get_by_pattern($this->attrs[$key]);
        if (isset($data[1]) && !is_null($data[1])) {
            $item = array($data[1]);
        } else {
            $item = explode('|', $this->attrs[$key]);
        }
        return count($item) > 0 ? $item : array();
    }

    protected function generate_conditons($key, $field) {
        $item = $this->get_attr($key);
        if (!empty($item)) {
            $item = is_array($item) ? $item : array($item);
            return array(generate_where_or($item, $field), $item);
        }
        return array();
    }

    /**
     * Tag of content shortcode
     */
    protected function get_tag($tag_name) {
        $tag = find_tag($tag_name, $this->content);
        return !empty($tag) ? $tag : array();
    }

    protected function replace_tag($item, $content) {
        $keys = array_keys($item);
        foreach ($keys as $v) {
            $content = str_replace('{' . $v . '}', $item[$v], $content);
        }
        return $content;
    }

    protected function remove_tag($tag_remove) {
        $tag = find_tag($tag_remove, $this->content);
        $have = true;
        if (isset($tag[0])) {
            $this->content = str_replace($tag[0], '', $this->content);
        } else {
            $have = false;
        }
        return $have;
    }

}
