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
class Pf_Plugin_Session {

    private static $instance;

    const FLASHDATA_KEY = 'flash';
    const FLASHDATA_NEW = ':new:';
    const FLASHDATA_OLD = ':old:';

    private function __construct() {
        $this->flashdata_sweep();
        $this->flashdata_mark();
    }

    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new Pf_Plugin_Session;
        }

        return self::$instance;
    }

    public function has($item) {
        return isset($_SESSION[$item]);
    }

    public function has_flash($item) {
        return isset($_SESSION[self::FLASHDATA_KEY . self::FLASHDATA_OLD . $item]);
    }

    public function get($item = null) {
        if (isset($item)) {
            return $this->has($item) ? $_SESSION[$item] : '';
        }
        return isset($_SESSION) ? $_SESSION : array();
    }

    public function put($new_data, $new_value = '') {
        if (is_string($new_data)) {
            $new_data = array($new_data => $new_value);
        }
        foreach ($new_data as $k => $v) {
            $_SESSION[$k] = $v;
        }
    }

    public function forget($new_data) {
        if (is_string($new_data)) {
            $new_data = array($new_data);
        }

        if (count($new_data) > 0) {
            foreach (array_values($new_data) as $key) {
                unset($_SESSION[$key]);
            }
        }
    }

    public function flash($new_data, $new_value = '') {
        if (is_string($new_data)) {
            $new_data = array($new_data => $new_value);
        }
        foreach ($new_data as $key => $value) {
            $flash_key = self::FLASHDATA_KEY . self::FLASHDATA_NEW . $key;
            $_SESSION[$flash_key] = $value;
        }
    }

    public function flash_data($key = null) {
        if (isset($key)) {
            return $this->get(self::FLASHDATA_KEY . self::FLASHDATA_OLD . $key);
        }

        $out = array();
        if (count($this->get()) > 0) {
            foreach ($this->get() as $key => $value) {
                if (strpos($key, self::FLASHDATA_KEY . self::FLASHDATA_OLD) !== false) {
                    $key = str_replace(self::FLASHDATA_KEY . self::FLASHDATA_OLD, '', $key);
                    $out[$key] = $value;
                }
            }
        }
        return $out;
    }

    protected function flashdata_mark() {
        foreach ($this->get() as $name => $value) {
            $parts = explode(self::FLASHDATA_NEW, $name);
            if (count($parts) === 2) {
                $this->put(self::FLASHDATA_KEY . self::FLASHDATA_OLD . $parts[1], $value);
                $this->forget($name);
            }
        }
    }

    protected function flashdata_sweep() {
        $userdata = $this->get();
        foreach (array_keys($userdata) as $key) {
            if (strpos($key, self::FLASHDATA_OLD)) {
                $this->forget($key);
            }
        }
    }

}
