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
class Pf_Plugin_Shortcode_View {

    public function __construct() {
        $this->session = Pf_Plugin_Session::getInstance();
    }

    private $path;

    public function getPath() {
        return $this->path;
    }

    public function set_path($path) {
        $this->path = $path;
    }

    public function render($name, $data = NULL) {
        if (count($data) && is_array($data)) {
            extract($data, EXTR_OVERWRITE);
        }

        $filename = ABSPATH . $this->getPath() . "/{$name}.php";
        ob_start();
        if (file_exists($filename)) {
            include $filename;
        } else {
            echo 'Not found';
        }
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }

}
