<?php
defined ( 'PF_VERSION' ) or header ( 'Location:404.html' );
class Faq_Shortcode extends PF_Shortcode_Controller{
    public function __construct(){
        parent::__construct();
        $this->load_model('faq');
    }
    private function clean_list_faqs($data)
    {
        $list = array();
        if (!empty($data)) {
            foreach ($data as $id => $value) {
                if ($value['status'] == 1) {
                    $list[$id] = $value;
                }
            }
        }
        return $list;
    }
    //pagination list faq
    private function get_lists($faqs, $page)
    {
        $clean = $this->clean_list_faqs($faqs);
        $total = count($clean);
        $data = array();
        if (!empty($clean) && $total >= $page) {
            $start = ($page - 1) * NUM_PER_PAGE;
            $pages = new PF_Paginator($total, NUM_PER_PAGE, 'page-faq', $page);
            $data['faqs'] = array_slice($clean, $start,NUM_PER_PAGE );
            $data['pagination'] = $pages->page_links(public_url('', true), '', true);
        }
        return $data;
    }
    //pagination question
    private function get_lists_question($faqs, $page)
    {
        $total = count($faqs);
        $data = array();
        if (!empty($faqs) && $total >= $page) {
            $start = ($page - 1) * NUM_PER_PAGE;
            $pages = new PF_Paginator($total, NUM_PER_PAGE, 'page-faq', $page);
            $data['question'] = array_slice($faqs, $start,NUM_PER_PAGE );
            $data['pagination_question'] = $pages->page_links(public_url('', true), '', true);
        }
        return $data;
    }
    public function display($atts,$conten = null,$tag){
        $faqid = (!empty($atts['id'])) ? $atts['id'] : '';
        $type = (!empty($atts['type'])) ? $atts['type'] : 'list';
        $data_faqs = get_option('faq');
        $limit_question = NUM_PER_PAGE;
        if(!empty($faqid)){
            $data_faq1[$faqid] = $data_faqs[$faqid];
        }else{
            $data_faq1 = $data_faqs;
        }
        $data_faq = $this->clean_list_faqs($data_faq1);
        $atts['faqss'] = $data_faq;
        $atts['faqid'] = $faqid;
        if($type == 'accordion'){
            $page = $this->get->{$this->page} ?  $this->get->{$this->page} : 1;
            $atts = array_merge($atts, $this->get_lists($data_faq, $page));
            $this->view->atts = $atts;
            $this->view->render('main');
        }else{
            foreach ($data_faq as $id => $value){
                $atts['title_qes'] = $data_faq[$id]['title'];
                foreach ($value['list'] as $id2 => $value2){
                    $list_question[$id2] = $value2['question'];
                }
                break;
            }
            $total_question = count($list_question);
            $total_page = ceil($total_question / $limit_question);
            if($list_question != NULL && $total_page > 0){
                $atts['list_question'] = $list_question;
            }
            $page = $this->get->{$this->page} ?  $this->get->{$this->page} : 1;
            $atts = array_merge($atts, $this->get_lists_question($atts['list_question'], $page));
            $this->view->atts = $atts;
            $this->view->render('display');
        }
    }
    public function get_faq()
    {
        if (!is_ajax()) {
            return false;
        }
        if ($this->post->id) {
            $id = $this->post->id;
            $data_faqs = get_option('faq');
            $faqs = $this->clean_list_faqs($data_faqs);
            if (isset($faqs[$id])) {
                echo json_encode($faqs[$id]['list']);
            }
        }
    }
    public function faq_load_list_faqs()
    {
        if (!is_ajax()) {
            return false;
        }
        if ($this->post->page) {
            $data_faqs = get_option('faq');
            $faqs = $this->clean_list_faqs($data_faqs);
            $data = $this->get_lists($faqs, $this->post->page);
            echo!empty($data) ? json_encode($data) : '';
        }
    }
    public function load_pagination(){
        if(!is_ajax()) {
            return false;
        }
        $page = $this->post->page;
        if($this->post->page){
            $data_faqs = get_option('faq');
            $faqs = $this->clean_list_faqs($data_faqs);
            foreach ($faqs as $id => $value){
                foreach ($value['list'] as $id2 => $value2){
                    $list_question[$id2] = $value2['question'];
                    
                }
                break;
            }
            $faqs = array_merge($faqs, $this->get_lists_question($list_question, $page));
            $data['pagination_question'] = $faqs['pagination_question'];
            foreach ($faqs['question'] as $id => $value){
                $data['list_question'][] = "<li><a href='javascript:void(0);' name='".$id."' class='view_answer'>".$value."<a/></li>";
            }
            return json_encode($data);
        }
    }
    
    public function list_question_faq(){
        if (!is_ajax()) {
            return false;
        }
        $data = array();
        $data_faqs = get_option('faq');
        $limit_question = NUM_PER_PAGE;

        $faqs = $this->clean_list_faqs($data_faqs);
        $id_faq = $this->post->id;
        foreach ($faqs as $key => $value){
            if($key == $id_faq){
                $title = $faqs[$key]['title'];
                foreach($value['list'] as $key2 =>  $value2){
                    $list_question[$key2] = $value2['question'];
                }
                break;
            }
        }
        $total_question = count($list_question);
        $total_page = ceil($total_question / $limit_question);
        if($list_question != NULL && $total_page > 0){
            $atts['list_question'] = $list_question;
        }
        $page = $this->get->{$this->page} ?  $this->get->{$this->page} : 1;
        $atts = array_merge($atts, $this->get_lists_question($atts['list_question'], $page));
        foreach ($atts['question'] as $id => $value){
            $data['list_question'][] = "<li><a href='javascript:void(0);' name='".$id."' class='view_answer'>".$value."<a/></li>";
        }
        $data['pagination-div'] = "<div class='pagination-load-question' name='$id_faq'></div>";
        $data['pagination-question']= $atts['pagination_question'];
        $data['title'] = "<h3 class='headline'><span>".$title."</span></h3>";
        return json_encode($data);
    }
    public function list_answer_faq(){
        if (!is_ajax()) {
            return false;
        }
        $data = array();
        $data_faqs = get_option('faq');
        $faqs = $this->clean_list_faqs($data_faqs);
        $id = $this->post->id;
        foreach ($faqs as $key => $value){
            foreach($value['list'] as $key2 =>  $value2){
                if($key2 == $id){
                    $data['answer'] = "<p>".$value2['answer']."</p>";
                    $data['title'] = "<h3 class='headline'><span> ".$value2['question']."</span></h3>";
                }
            }
        }
        return json_encode($data);
    }
    public function load_question_pagination(){
     if (!is_ajax()){
         return false;
     }
     $data = array();
     $data_faqs = get_option('faq');
     $limit_question = NUM_PER_PAGE;
     
     $faqs = $this->clean_list_faqs($data_faqs);
     $id_faq = $this->post->id;
     $page = $this->post->page;
     
     foreach ($faqs as $key => $value){
         if($key == $id_faq){
             foreach($value['list'] as $key2 =>  $value2){
                 $list_question[$key2] = $value2['question'];
             }
             break;
         }
     }
     $total_question = count($list_question);
     $total_page = ceil($total_question / $limit_question);
     if($list_question != NULL && $total_page > 0){
         $atts['list_question'] = $list_question;
     }
     $atts = array_merge($atts, $this->get_lists_question($atts['list_question'], $page));
    foreach ($atts['question'] as $id => $value){
                $data['list_question'][] = "<li><a href='javascript:void(0);' name='".$id."' class='view_answer'>".$value."<a/></li>";
            }
     $data['pagination_question'] = $atts['pagination_question'];
     return json_encode($data);
    }
    private function search_title($data, $condition) {
        $input = preg_quote(strtolower($condition), '~');
        $result = preg_grep('~' . $input . '~', $data);
        return $result;
    }
    
    private function search_by_key($data, $condition, $key_search) {
        foreach ($data as $key => $value){
            $lowercase_data = strtolower($value['question']);
            $data_get[$key] = $lowercase_data;
        }
        $result_search = is_string($condition) ? $this->search_title($data_get, $condition) : $this->search_status($data_get, $condition);
        $data_search = array();
        if (count($result_search)) {
            $data_search = array_intersect_key($data, $result_search);
        }
        return $data_search;
    }
    public function load_list_question_search(){
        if (!is_ajax()) {
            return false;
        }
        $data_question = '';
        $faqid = $this->post->id;
        $data_faqs = get_option('faq');
        if(!empty($faqid)){
          $data_faqss[$faqid] = $data_faqs[$faqid];
        }else{
          $data_faqss = $data_faqs;  
        }
        $data = $this->clean_list_faqs($data_faqss);
        $limit_question = NUM_PER_PAGE;
        $key = $this->post->key;
        $page = $this->post->page;
        foreach ($data as $id => $value){
           foreach ($value['list'] as $id2 => $value2){
               $data_search[$id2] = $value2;
           }
        }
        $data_new = $this->search_by_key($data_search, $key, 'question');
        foreach ($data_new as $id => $value){
            $data_question[$id] = $value['question'];
            $data_ansewr[$id] = $value['answer'];
        }
        $total_question = count($data_question);
        $total_page = ceil($total_question / $limit_question);
        if($data_question != NULL && $total_page > 0){
            $atts['$data_question'] = $data_question;
        
        $atts = array_merge($atts, $this->get_lists_question($atts['$data_question'], $page));
        $data_search_question = $atts;
        if($atts['question'] != NULL){
            foreach ($atts['question'] as $id => $value){
                $data['question'][] = "<li><a href='javascript:void(0);' name='".$id."' class='view_answer'>".$value."<a/></li>";
            }
            $_SESSION['data_pagination_question'] = $data_question;
            $data['pagination_question'] = $atts['pagination_question'];
        }
        }else{
            $data['question'][] = "<p>".__('No answer yet','faq')."</p>";
            $data['pagination_question'] = "";
        }
        $data['title'] = "<h3 class='headline'><span> ".__('Search Result', 'faq')."</span></h3>";   
        return json_encode($data);
    }
    public function pagination_search(){
        if(!is_ajax()){
            return false;
        }
        $data = array();
        $limit_question = 3;
        $data_question = $_SESSION['data_pagination_question'];
        $page = $this->post->page;
        $total_question = count($data_question);
        $total_page = ceil($total_question / $limit_question);
        
        if($data_question != NULL && $total_page > 0){
            $atts['list_question'] = $data_question;
        }
        $atts = array_merge($atts, $this->get_lists_question($atts['list_question'], $page));
        foreach ($atts['question'] as $id => $value){
            $data['list_question'][] = "<li><a href='javascript:void(0);' name='".$id."' class='view_answer'>".$value."<a/></li>";
        }
        $data['pagination_question'] = $atts['pagination_question'];
        return json_encode($data);
     
    }
}