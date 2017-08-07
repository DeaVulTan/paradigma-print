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
class Pf_Plugin_Input {

    private $inputs;
    private $security;

    public function __construct() {
        $this->security = new Pf_Plugin_Security;
        $this->init();
    }

    private function init() {
        $this->inputs = array(
            'get' => $_GET,
            'post' => $_POST
        );
    }

    private function has($type, $index) {
        if (is_array($index)) {
            foreach ($index as $key) {
                if (!isset($this->inputs[$type][$key])) {
                    return false;
                }
            }
            return true;
        }

        if (isset($this->inputs[$type][$index])) {
            return true;
        }
        return false;
    }

    public function has_get($index) {
        return $this->has('get', $index);
    }

    public function has_post($index) {
        return $this->has('post', $index);
    }

    private function fetch_from_array($array, $index = '', $xss_clean = false) {
        if (!isset($array[$index])) {
            return false;
        }
        if ($xss_clean === true) {
            return $this->security->xss_clean($array[$index]);
        }
        return $array[$index];
    }

    private function get_value($type, $index = '', $xss_clean = false) {
        $keys = array();
        if (is_array($index)) {
            $keys = $index;
        } elseif ($index === '' && !empty($this->inputs[$type])) {
            $keys = array_keys($this->inputs[$type]);
        } else {
            return $this->fetch_from_array($this->inputs[$type], $index, $xss_clean);
        }

        foreach ($keys as $key) {
            $data[$key] = $this->fetch_from_array($this->inputs[$type], $key, $xss_clean);
        }
        return $data;
    }

    public function post($index = '', $xss_clean = false) {
        return $this->get_value('post', $index, $xss_clean);
    }

    public function get($index = '', $xss_clean = false) {
        return $this->get_value('get', $index, $xss_clean);
    }

    public function get_except($index, $xss_clean = false) {
        $this->except('get', $index);
        return $this->get('', $xss_clean);
    }

    public function post_except($index, $xss_clean = false) {
        $this->except('post', $index);
        return $this->post('', $xss_clean);
    }

    private function except($type, $index) {
        if (!count($this->inputs[$type])) {
            return;
        }
        if (is_array($index)) {
            foreach ($index as $key) {
                if ($this->has($type, $key)) {
                    unset($this->inputs[$type][$key]);
                }
            }
        } else {
            if ($this->has($type, $index)) {
                unset($this->inputs[$type][$index]);
            }
        }
    }

    private function flash($type, $index) {
        if (!count($this->inputs[$type])) {
            return;
        }
        $data = array();
        if (is_array($index)) {
            foreach ($index as $value) {
                $data[] = $this->inputs[$type][$value];
                echo $this->inputs[$type][$value];
                unset($this->inputs[$type][$value]);
            }
        } else {
            $data = $this->inputs[$type][$index];
            unset($this->inputs[$type][$index]);
        }
        return $data;
    }

    public function flash_post($index) {
        return $this->flash('post', $index);
    }

    public function flash_get($index) {
        return $this->flash('get', $index);
    }

    private function get_all($type) {
        if (!in_array($type, array('post', 'get'))) {
            return;
        }
        $data = array();
        foreach ($this->inputs[$type] as $key => $item) {
            $data[$key] = $this->get_value($type, $key);
        }
        return $data;
    }

    public function posts() {
        return $this->get_all('post');
    }

    public function gets() {
        return $this->get_all('get');
    }

}
