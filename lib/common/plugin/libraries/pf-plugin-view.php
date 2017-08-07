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
class Pf_Plugin_View
{

    public function __construct()
    {
        $this->session = Pf_Plugin_Session::getInstance();
    }

    private $path;

    public function getPath()
    {
        return $this->path;
    }

    public function set_path($path, $admin = false)
    {
        $this->path = ($admin ? DEFAULT_PLUGIN_PATH:PLUGIN_PATH) . "/{$path}/views/";
    }

    public function render($name, $data = NULL)
    {
        $filename = "{$this->path}{$name}.php";
        if (count($data) && is_array($data)) {
            extract($data, EXTR_OVERWRITE);
        }
        if (file_exists($filename)) {
            include $filename;
        } else {
            echo 'Not found';
        }
    }

    /**
     *
     * @param unknown $link
     * @param string $file
     */
    public function css($link, $file = '')
    {
        if (is_array($link)) {
            foreach ($link as $v) {
                if (is_string($v) && !empty($v)) {
                    admin_css($v, $file);
                }
            }
        } else if (is_string($link) && !empty($link)) {
            admin_css($link, $file);
        }
    }

    /**
     *
     * @param unknown $js
     * @param string $file
     */
    public function js($js, $file = '')
    {
        if (is_array($js)) {
            foreach ($js as $v) {
                if (is_string($v) && !empty($v)) {
                    admin_js($v, $file);
                }
            }
        } else if (is_string($js) && !empty($js)) {
            admin_js($js, $file);
        }
    }

}
