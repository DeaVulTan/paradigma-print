<?php
defined('PF_VERSION') OR header('Location:404.html');
class Portfolio_Shortcode extends Pf_Shortcode_Controller{
    public function __construct(){
        parent::__construct();
        $this->load_model('portfolio');
        $this->load_model('category');
        $this->load_model('meta');
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
    public function display($atts, $content = null,$tag) {
        $id = $_GET['portfolio-id'];
        $page = $this->current_page();
        if(!isset($_GET['portfolio-id'])){
            $result .= "<li class='active'>".$this->current_page()."</li>";
        }
        if (isset($_GET['portfolio-id'])) {
            $link = explode("/",$_GET['pf_page_url']);
            Pf::database()->select('portfolio_name,portfolio_category', ''.DB_PREFIX.'portfolios', '`id`=?', array($id));
            $portfolio_detail = Pf::database()->fetch_assoc_all();
            Pf::database()->select('category_name', ''.DB_PREFIX.'portfolio_categories', '`id`=?', array($portfolio_detail[0]['portfolio_category']));
            $category_detail = Pf::database()->fetch_assoc_all();
            $result .= '<li><a href="'.public_url($link[0]).'">'. $link[0].' </a></li>'. '<li><a href="'.public_url($link[0]).'/'."portfolio-cat:". $portfolio_detail[0]['portfolio_category'].'">'.$category_detail[0]['category_name'] .'</a></li>'. '<li class="active">'.$portfolio_detail[0]['portfolio_name'].'</li>' ;
            $page = $category_detail[0]['category_name'];
        }
        $this->view->breadcrumb_title =  __($result,'portfolio');
        Pf::event()->on("theme-breadcrumb",array($this,'portfolio_breadcrumb'),10);
        $this->view->page_title =  __($page,'portfolio');
        Pf::event()->on("theme-breadcrumb",array($this,'portfolio_page_breadcrumb'),10);
        $atts['url'] = public_url('',true);
        $portid = !empty($_GET['portfolio-id']) ? $_GET['portfolio-id'] : '';
        if (empty($portid)) {
            $portid = !empty($atts['portid']) ? $atts['portid'] : '';
        }
        $cat_id = !empty($_GET['portfolio-cat']) ? $_GET['portfolio-cat'] : '';
        if(!empty($portid)){
            $atts['portfolio'] = $this->portfolio_model->get_port($portid);
            $atts['meta'] = $this->meta_model->get_meta($portid);
            $atts['items'] = unserialize($atts['portfolio']['portfolio_items']);
            $this->view->atts = $atts;
            $this->view->render('display_detail');
        }else{
            if(!empty($cat_id)){
                $atts['get_cat'] = $this->category_model->get_cate($cat_id);
                $atts['list_cat'] = $this->category_model->list_cat();
                $atts['list_port'] = $this->portfolio_model->list_port();
                $this->view->atts = $atts;
                $this->view->render();
            }else{
                $atts['list_cat'] = $this->category_model->list_cat();
                $atts['list_port'] = $this->portfolio_model->list_port();
                $this->view->atts = $atts;
                $this->view->render();
            }   
        }
    }
    public function portfolio_breadcrumb($breadcrumb = ''){
        return $this->view->fetch('breadcrumb');
    }
    public function portfolio_page_breadcrumb($breadcrumb = ''){
        return $this->view->fetch('breadcrumb');
    }
}
