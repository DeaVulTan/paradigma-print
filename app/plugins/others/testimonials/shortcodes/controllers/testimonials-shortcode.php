<?php
defined('PF_VERSION') OR header('Location:404.html');
class Testimonials_Shortcode extends Pf_Shortcode_Controller{
    public function __construct(){
        parent::__construct();
        $this->load_model('testimonials');
    }
    public function display($atts, $content = null,$tag) {
        $page_url = $this->get->pf_page_url;
        $current_info = $this->get_page_info($page_url);
        $this->view->breadcrumb_title = __($current_info['page_title'],'testimonials');
        Pf::event()->on("theme-breadcrumb",array($this,'testimonials_breadcrumb'),10);
        $testid = (!empty($atts['id'])) ? $atts['id'] : '';
        $param = array();
        $operator = '';
        $params['limit'] = NUM_PER_PAGE;
        $params['page_index'] = (isset($this->get->{$this->page}))?(int)$this->get->{$this->page}:1;
        
        if(!empty($testid)){
            $where = "id = ? ";
            $where_values[] = $testid;
            $operator = ' AND ';
            $where .= $operator."testimonial_status = ? ";
            $where_values[] = '1';
        }else{
            $where = "testimonial_status = ? ";
            $where_values[] = '1';
        }
        
        if (!empty($where_values)){
            $params['where'] = array($where,$where_values);
        }

        $atts['page_index'] = $params['page_index'];
        $atts['records'] = $this->testimonials_model->fetch($params,true);
        $atts['total_records'] = $this->testimonials_model->found_rows();
        $atts['$total_page'] = ceil($atts['total_records']/NUM_PER_PAGE);
        
        if (empty($atts['records']) && $atts['$total_page'] > 0){
            $this->get->{$this->page} = $params['page_index'] = $atts['$total_page'];
            $atts['page_index'] = $params['page_index'];
            $atts['records'] = $this->testimonials_model->fetch($params,true);
            $atts['total_records'] = $this->testimonials_model->found_rows();
        }
        $atts['pagination'] = new Pf_Paginator($atts['total_records'], NUM_PER_PAGE, $this->page);
        
        $this->view->atts = $atts;
        $this->view->render();
    }
    
    public function testimonials_breadcrumb($breadcrumb = ''){
        return $this->view->fetch('breadcrumb');
    }
    
    public function get_page_info($url) {
        Pf::database()->select('id,page_title', ''.DB_PREFIX.'pages', '`page_url`=?', array($url));
        $page_info = Pf::database()->fetch_assoc_all();
        if (!empty($page_info[0])){
            return $page_info[0];
        }else{
            return false;
        }
    }
}