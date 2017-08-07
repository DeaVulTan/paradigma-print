<?php
defined('PF_VERSION') OR header('Location:404.html');
class Popup_Shortcode extends Pf_Shortcode_Controller{
    protected $format_date;
    public function __construct(){
        parent::__construct();
        $this->load_model('popup');
        $this->format_date = $this->format_date_sql(date("d-m-Y H:i:s"));
    }
    public function display($atts, $content = null,$tag) {
        $popup_id = (!empty($atts['id'])) ? $atts['id'] : '';
        $param = array();
        $operator = '';
        $params['limit'] = NUM_PER_PAGE;
        $params['page_index'] = (isset($this->get->{$this->page}))?(int)$this->get->{$this->page}:1;
        
        if(!empty($popup_id)){
            $where = "id = ? ";
            $where_values[] = $popup_id;
            $operator = ' AND ';
            $where .= $operator."popup_status = ? ";
            $where_values[] = '1';
            $operator = ' AND ';
        }else{
            $where = "popup_status = ? ";
            $where_values[] = '1';
            $operator = ' AND ';
        }
        
        $where .= $operator.'(';
        $where .= ' `popup_published_date` <= "'.$this->format_date.'"';
        $operator = ' OR ';
        $where .= $operator.' (`popup_published_date`) = 0 ';
        $where .= ')';
        $operator = ' AND ';
        $where .= $operator.'(';
        $where .= ' `popup_unpublished_date` > "'.$this->format_date.'"';
        $operator = ' OR ';
        $where .= $operator.' (`popup_unpublished_date`) = 0 ';
        $where .= ')';
        
        if (!empty($where_values)){
            $params['where'] = array($where,$where_values);
        }

        $atts['page_index'] = $params['page_index'];
        $atts['records'] = $this->popup_model->fetch($params,true);
        $atts['total_records'] = $this->popup_model->found_rows();
        $atts['$total_page'] = ceil($atts['total_records']/NUM_PER_PAGE);
        
        if (empty($atts['records']) && $atts['$total_page'] > 0){
            $this->get->{$this->page} = $params['page_index'] = $atts['$total_page'];
            $atts['page_index'] = $params['page_index'];
            $atts['records'] = $this->popup_model->fetch($params,true);
            $atts['total_records'] = $this->popup_model->found_rows();
        }
        $atts['pagination'] = new Pf_Paginator($atts['total_records'], NUM_PER_PAGE, $this->page);
        
        $this->view->atts = $atts;
        $this->view->render();
    }
    private function format_date_sql($d){
        $date = date('d-m-Y H:i:s', strtotime($d));
        $date = new DateTime($date);
        $result = $date->format('Y-m-d H:i:s');
        return $result;
    }
}