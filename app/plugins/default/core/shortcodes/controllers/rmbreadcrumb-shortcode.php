<?php
defined('PF_VERSION') OR header('Location:404.html');
class Rmbreadcrumb_Shortcode extends Pf_Shortcode_Controller{
    function __construct() {
        parent::__construct();
    }
    private function get_page_info($url) {
        Pf::database()->select('id,page_title', ''.DB_PREFIX.'pages', '`page_url`=?', array($url));
        $page_info = Pf::database()->fetch_assoc_all();
        if (!empty($page_info[0])){
            return $page_info[0];
        }else{
            return false;
        }
    }
    private function current_page() {
        $page = $this->get_page_info($_GET['pf_page_url']);
        return $page['page_title'];
    }
    
    public function rm($atts, $content = null, $class){
        Pf::event()->on("theme-breadcrumb",array($this,'remove_breadcrumb'),100);
    
    }
    public function remove_breadcrumb($breadcrumb = ''){
        return '<div style="display:none">&nbsp</div>';
    }
}