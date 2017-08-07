<?php
defined('PF_VERSION') OR header('Location:404.html');
class Breadcrumb_Shortcode extends Pf_Shortcode_Controller{
    protected $homepage;
    protected $home_info;
    protected $homepage_li;
    
    function __construct() {
        parent::__construct();
        
        $this->conf_homepage = get_configuration('default_page');
        Pf::database()->select('page_url',''.DB_PREFIX.'pages','id=?',array($this->conf_homepage));
        $data   =   Pf::database()->fetch_assoc_all();
        $this->homepage =   $data[0]['page_url'];
        $this->home_info = $this->get_page_info($this->homepage);
        $this->homepage_li = "<li><a href='" . public_base_url() . "'>" . $this->home_info['page_title'] . "</a></li>";
    }
    public function show($atts, $content = null, $class) {
        switch ($class) {
            case 'page':
                $page_url = $_GET['pf_page_url'];
                if ($page_url == $this->homepage) {
                    $list = "<li class='active'>" . $this->home_info['page_title'] . "</li>";
                } else {
                    $current_info = $this->get_page_info($page_url);
                    $list = $this->homepage_li . "<li class='active'>" . $current_info['page_title'] . "</li>";
                }
                break;
            case 'post-category':
                $get = (isset($atts['get']) && !empty($atts['get'])) ? $atts['get'] : 'id';
                $list_id = isset($_GET[$get]) ? $_GET[$get] : null;
                $list = $this->build_category($list_id);
                break;
            case 'post':
    
                $get_cat = !empty($atts['get_cat']) ? $atts['get_cat'] : 'cat';
                $post_detail = $this->get_post_info(get_post_detail_id());
                $list = $this->build_category($post_detail['post_category'], $get_cat) . "<li class='active'>" . $post_detail['post_title'] . "</li>";
                break;
            case 'gallery':
                $id = !empty($_GET['gallery']) ? $_GET['gallery'] : 'all';
                $list = $this->get_gallery_detail($id);
                break;
            case 'portfolio':
                $id = !empty($_GET['portfolio-id']) ? $_GET['portfolio-id'] : '1';
                $url = !empty($_GET['pf_page_url']) ? $_GET['pf_page_url'] : 'portfolio';
                $list = $this->get_portfolio_detail($id, $url);
                break;
            default:
                break;
        }
        
        $this->view->list = $list;
        $this->view->render();
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
    
    private function get_post_info($id) {
        Pf::database()->select('post_title, post_category', ''.DB_PREFIX.'posts', 'id=?', array($id));
        $post_info = Pf::database()->fetch_assoc_all();
        return $post_info[0];
    }
    
    private function build_category($id, $link = 'cat') {
        Pf::database()->select('category_name,category_parent', ''.DB_PREFIX.'post_categories', '`id`=?', array($id));
        $category_info = Pf::database()->fetch_assoc_all();
        $post_page = get_page_url_by_id(get_configuration('page_lists', 'pf_post'));
        $result = $this->homepage_li;
        if (!isset($category_info[0]['category_parent'])) {
            return $result;
        }
        if ($category_info[0]['category_parent'] == 0) {
            $result .= "<li><a href='" . public_url($post_page . "/" . $link . ":" . $id) . "'>" . $category_info[0]['category_name'] . "</a></li>";
        } else {
            $result .= $this->build_category($category_info[0]['category_parent'], $link) . "<li><a href='" . public_url($post_page . "/" . $link . ":" . $id) . "'>" . $category_info[0]['category_name'] . "</a></li>";
        }
        return $result;
    }
    
    private function get_gallery_detail($id) {
        if ($id == 'all') {
            $result = $this->homepage_li . "<li class='active'>" . $this->current_page() . "</li>";
        } else {
            Pf::database()->select('gallery_name', ''.DB_PREFIX.'galleries', '`id`=? AND `gallery_status`=?', array($id, 1));
            $gallery_detail = Pf::database()->fetch_assoc_all();
            if (count($gallery_detail) != 0) {
                $result = $this->homepage_li . "<li><a href='" . public_url($_GET['pf_page_url']) . "'>" . $this->current_page() . "</a></li>"
                        . "<li class='active'>" . $gallery_detail[0]['gallery_name'] . "</li>";
            } else {
                $result = $this->homepage_li . "<li class='active'><a href='" . public_url($_GET['pf_page_url']) . "'>" . $this->current_page() . "</a></li>";
            }
        }
        return $result;
    }
    
    private function get_portfolio_detail($id, $link = 'portfolio') {
        $result = isset($_GET['portfolio-id']) ? $this->homepage_li . "<li><a href='" . public_url($_GET['pf_page_url']) . "'>" . $this->current_page() . "</a></li>" : $this->homepage_li . "<li class='active'>" . $this->current_page() . "</li>";
        if (isset($_GET['portfolio-id'])) {
            Pf::database()->select('portfolio_name,portfolio_category', ''.DB_PREFIX.'portfolios', '`id`=?', array($id));
            $portfolio_detail = Pf::database()->fetch_assoc_all();
            Pf::database()->select('category_name', ''.DB_PREFIX.'portfolio_categories', '`id`=?', array($portfolio_detail[0]['portfolio_category']));
            $category_detail = Pf::database()->fetch_assoc_all();
            $result .= "<li><a href='" . public_url($link . "/portfolio-cat:" . $portfolio_detail[0]['portfolio_category']) . "'>" . $category_detail[0]['category_name'] . "</a></li><li class='active'>" . $portfolio_detail[0]['portfolio_name'] . "</li>";
        }
        return $result;
    }
    
    private function current_page() {
        $page = $this->get_page_info($_GET['pf_page_url']);
        return $page['page_title'];
    }
    public function remove($atts, $content = null, $class){
        Pf::event()->on("theme-breadcrumb",array($this,'remove_breadcrumb'),100);
    
    }
    public function remove_breadcrumb($breadcrumb = ''){
        return '<div style="display:none">&nbsp</div>';
    }
}