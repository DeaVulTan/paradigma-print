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
class Pf_Plugin_Setting {

    private $setting;
    private $name;
    private $data;

    public function __construct() {
        $this->setting = Pf::setting();
    }

    public function set_name($name) {
        $this->name = $name;
    }

    public function set_data($data) {
        $this->data = $data;
    }

    public function get_value($key) {
        return $this->setting->get_element_value($this->name, $key);
    }

    private function add_radio($key, $extra = '') {
        $checked = ($this->get_value($key) == 1) ? array(true, false) : array(false, true);
        $html = '<label class="margin-right-10"> '.__("Yes ","includes") . form_radio($key, 1, $checked[0], $extra) . '</label> ';
        $html .= '<label>' .__("No","includes"). form_radio($key, 0, $checked[1], $extra) . '</label>';
        return $html;
    }
    private function add_input($key, $data = '') {
        $value = $this->get_value($key) != '' ? $this->get_value($key) : $data;
        return form_input(array('name' => $key, 'class' => 'form-control', 'type' => 'text'), $value);
    }

    private function add_dropdown($key, $extra = '') {
        return form_dropdown($key, $this->data, $this->get_value($key), $extra);
    }

    public function add_element_radio($title, $key, $extra = '') {
        $this->setting->add_element($title, $this->add_radio($key, $extra), $this->name);
    }

    public function add_element_input($title, $key, $data = '') {
        $this->setting->add_element($title, $this->add_input($key, $data), $this->name);
    }

    public function add_element_dropdown($title, $key, $extra = '') {
        $this->setting->add_element($title, $this->add_dropdown($key, $extra), $this->name);
    }

}
