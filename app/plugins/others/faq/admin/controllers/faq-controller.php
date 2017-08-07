<?php
defined ( 'PF_VERSION' ) or header ( 'Location:404.html' );
class Faq_Controller extends Pf_Controller {
    public function __construct() {
    parent::__construct ();
    $this->load_model ( 'faq' );
    $this->view->menu_settings = get_option ( 'admin_menu_setting' );
    $this->faq_model->rules = Pf::event ()->trigger ( "filter", "faq-validation-rule", $this->faq_model->rules );
    }
    public function index() {
    $params [] = array ();
    $where = '';
    $where_values = array ();
    $operator = '';
    $data = '';
    
    $params ['faq'] = get_option ( 'faq' );
    $params ['limit'] = NUM_PER_PAGE;
    $params ['page_index'] = (isset ( $this->get->{$this->page} )) ? ( int ) $this->get->{$this->page} : 1;
    
    $operator = '';
    $url = admin_url('admin-page=faq', false);
    // view all status 
    if (isset ( $this->post->action ) && trim ( $this->post->action ) != "") {
        $data_faq = get_option ( 'faq' );
        foreach ( $data_faq as $key => $value ) {
            if ($this->post->id == $data_faq [$key] ['status']) {
                $data [$key] = $data_faq [$key];
            } else {
            }
        }
        //search
    }else{
        $data = $params ['faq'];
        if(isset($this->get->search["title"])){
            $title = $this->get->search["title"];
            $data = $this->search_by_key($data, $title, 'title');
            $url .= "&search[title]={$title}";
        }
        
        if(isset($this->get->search["status"])){
            $status = $this->get->search["status"];
            $data = $this->search_by_key($data, $status, 'status');
            $url .= "&search[status]={$title}";
        }
    }
    
    if (! empty ( $where_values )) {
        $params ['where'] = array (
                $where,
                $where_values 
        );
    }
    $params = Pf::event ()->trigger ( "filter", "faq-index-params", $params );
    if (!empty ( $this->get->order_field ) && !empty ( $this->get->order_type )) {
        if($this->get->order_field == 'title' && $this->get->order_type == 'asc'){
            asort($data);
        }else if($this->get->order_field == 'title' && $this->get->order_type == 'desc'){
            arsort($data);
        }
        if($this->get->order_field == 'status' && $this->get->order_type == 'asc'){
           uasort($data, "cmp");
        }else if($this->get->order_field == 'status' && $this->get->order_type == 'desc'){
           uasort($data, "cmp1");
        }
    }
        $this->view->page_index = $params ['page_index'];
        
        $this->view->records = $data;
        $this->view->total_records = count ( $params ['faq'] );
        $total_page = ceil ( $this->view->total_records / NUM_PER_PAGE );
        $this->view->pagination = new Pf_Paginator ( $this->view->total_records, NUM_PER_PAGE, $this->page );
        
        if (!empty($this->view->records) && $total_page > 1){
            //debug($this->page);
            $tesst = $this->pagination($data,$params['page_index']);
            $this->view->records = $tesst;
        }else{
            $this->view->records = $data;
        }
        
        $template = null;
        $template = Pf::event ()->trigger ( "filter", "faq-index-template", $template );
        $this->view->render ( $template );
}
    public function get_list_faq_post() {
        if ($this->post->question) {
            return array (
                    'question' => $this->post->question ? $this->post->question : '',
                    'answer' => $this->post->answer ? $this->post->answer : '' 
            );
        }
    }
    public function find($id) {
    $data = get_option ( 'faq' );
    if (is_array ( $data )) {
        foreach ( $data as $key => $value ) {
            if ($key == $id) {
                return $value;
            }
        }
    }
    return false;
    }
    public function add() {
    $template = null;
    $template = Pf::event ()->trigger ( "filter", "faq-add-template", $template );
    $data ['number_qa'] = $this->post->number_qa ? $this->post->number_qa : 1;
    if ($this->request->is_post ()) {
        $data = get_option ( 'faq' );
        if (! array (
                $data 
        )) {
            $data = array ();
        }
        $faq = array (
                'title' => $this->post->{"title"},
                'status' => $this->post->{"status"},
                'description' => $this->post->{"description"},
                'list' => $this->data_question_answer ( $this->post->{'question'}, $this->post->{'answer'} ) 
        );
        if ($this->post->id) {
            $id = $this->post->id;
        } else {
            $id = generate_id ( 15 );
            while ( isset ( $data [$id] ) ) {
                $id = generate_id ( 15 );
            }
        }
        $data ["{$id}"] = $faq;
        $data = Pf::event ()->trigger ( "filter", "faq-post-data", $data );
        $data = Pf::event ()->trigger ( "filter", "faq-adding-post-data", $data );
        
        $var = array ();
        if (! update_option ( 'faq', $data )) {
            $this->view->errors = $this->faq_model->errors;
            $var ['content'] = $this->view->fetch ( $template );
            $var ['error'] = 1;
        } else {
            Pf::event ()->trigger ( "action", "faq-add-successfully", $this->faq_model->insert_id (), $data );
            $var ['error'] = 0;
            $var ['url'] = admin_url ( $this->action . '=index&ajax=&id=&token=' );
        }
        
        echo json_encode ( $var );
    } else {
        
        $this->post->datas ( $data );
        $this->view->render ( $template );
    }
    }
    private function clean_data($list) {
    $result = array ();
    foreach ( $list as $item ) {
        $result ['answer'] [] = $item ['answer'];
        $result ['question'] [] = $item ['question'];
    }
    return $result;
    }
    public function edit() {
    $this->faq_model->rules = Pf::event ()->trigger ( "filter", "faq-editing-validation-rule", $this->faq_model->rules );
    $template = null;
    $template = Pf::event ()->trigger ( "filter", "faq-edit-template", $template );
    
    $var = array ();
    $id = $this->get->id;
    $data_faq = $this->find ( $id );
    if (isset ( $this->get->id ) && isset ( $this->get->token )) {
        if ($this->request->is_post ()) {
            $data = get_option ( "faq" );
            if (! array ($data)){
                $data = array ();
            }
            $faq = array (
                'title' => $this->post->{"title"},
                'status' => $this->post->{"status"},
                'description' => $this->post->{"description"},
                'list' => $this->data_question_answer ( $this->post->{'question'}, $this->post->{'answer'} ) 
            );
            $data [$id] = $faq;
            $data = Pf::event ()->trigger ( "filter", "faq-post-data", $data );
            $data = Pf::event ()->trigger ( "filter", "faq-editing-post-data", $data );
            $this->view->token = Pf_Plugin_CSRF::token ( $this->key . $this->get->id );
            if (! update_option ( 'faq', $data )) {
                $this->view->errors = $this->faq_model->errors;
                $var ['content'] = $this->view->fetch ( $template );
                $var ['error'] = 1;
            } else {
                $data ['list'] = $this->get_list_faq_post ();
                $data ['number_qa'] = $this->post->number_qa ? $this->post->number_qa : 1;
                $this->post->datas( $data );
                
                $var ['error'] = 0;
                $var ['content'] = $this->view->fetch ( $template );
                Pf::event ()->trigger ( "action", "faq-edit-successfully", $data );
                $var ['url'] = admin_url ( $this->action . '=index&ajax=&id=&token=' );
            }
        } else {
            if (isset ( $this->get->id )) {
                $data = $data_faq;
                if ($this->post->question) {
                    $data ['list'] = $this->get_list_faq_post ();
                    $data ['number_qa'] = $this->post->number_qa ? $this->post->number_qa : 1;
                } else {
                    $data ['number_qa'] = count ( $data_faq ['list'] );
                    $data ['list'] = $this->clean_data ( $data_faq ['list'] );
                }
                $data = Pf::event ()->trigger ( "filter", "faq-database-data", $data );
                $data = Pf::event ()->trigger ( "filter", "faq-editing-database-data", $data );
                $this->post->datas ( $data );
                $this->view->token = Pf_Plugin_CSRF::token ( $this->key . $this->get->id );
                $var ['content'] = $this->view->fetch ( $template );
                $var ['error'] = 0;
            } else {
                $var ['error'] = 1;
                $var ['url'] = admin_url ( $this->action . '=index&ajax=&id=&token=' );
            }
        }
    } else {
        $var ['error'] = 1;
        $var ['url'] = admin_url ( $this->action . '=index&ajax=&id=&token=' );
    }
    echo json_encode ( $var );
    }
    public function copy() {
    $this->faq_model->rules = Pf::event ()->trigger ( "filter", "faq-copy-validation-rule", $this->faq_model->rules );
    
    $template = null;
    $template = Pf::event ()->trigger ( "filter", "faq-edit-template", $template );
    
    $var = array ();
    $id = $this->get->id;
    $data_faq = $this->find ( $id );
    
    if (isset ( $this->get->id ) && isset ( $this->get->token )) {
        if ($this->request->is_post ()) {
            $data = get_option ( 'faq' );
            if (! array (
                    $data 
            )) {
                $data = array ();
            }
            $faq = array (
                    'title' => $this->post->{"title"},
                    'status' => $this->post->{"status"},
                    'description' => $this->post->{"description"},
                    'list' => $this->data_question_answer ( $this->post->{'question'}, $this->post->{'answer'} ) 
            );
            $id = generate_id ( 15 );
            while ( isset ( $data [$id] ) ) {
                $id = generate_id ( 15 );
            }
            $data ["{$id}"] = $faq;
            $data = Pf::event ()->trigger ( "filter", "faq-post-data", $data );
            $data = Pf::event ()->trigger ( "filter", "faq-copy-post-data", $data );
            
            $this->view->token = Pf_Plugin_CSRF::token ( $this->key . $this->get->id );
            
            if (! update_option ( 'faq', $data )) {
                $this->view->errors = $this->faq_model->errors;
                $var ['content'] = $this->view->fetch ( $template );
                $var ['error'] = 1;
            } else {
                Pf::event ()->trigger ( "action", "faq-copy-successfully", $data );
                $var ['error'] = 0;
                $var ['url'] = admin_url ( $this->action . '=index&ajax=&id=&token=' );
            }
        } else {
            if (isset ( $this->get->id )) {
                
                $data = $data_faq;
                if ($this->post->question) {
                    $data ['list'] = $this->get_list_faq_post ();
                    $data ['number_qa'] = $this->post->number_qa ? $this->post->number_qa : 1;
                } else {
                    $data ['number_qa'] = count ( $data_faq ['list'] );
                    $data ['list'] = $this->clean_data ( $data_faq ['list'] );
                }
                $data = Pf::event ()->trigger ( "filter", "faq-database-data", $data );
                $data = Pf::event ()->trigger ( "filter", "faq-copy-database-data", $data );
                
                $this->post->datas ( $data );
                $this->view->token = Pf_Plugin_CSRF::token ( $this->key . $this->get->id );
                $var ['content'] = $this->view->fetch ( $template );
                $var ['error'] = 0;
            } else {
                $var ['error'] = 1;
                $var ['url'] = admin_url ( $this->action . '=index&ajax=&id=&token=' );
            }
        }
    } else {
        $var ['error'] = 1;
        $var ['url'] = admin_url ( $this->action . '=index&ajax=&id=&token=' );
    }
    
    echo json_encode ( $var );
    }
    public function bulk_action() {
    $var = array ();
    $data = array ();
    $params = array ();
    
    if (Pf_Plugin_CSRF::is_valid ( $this->post->token, $this->key )) {
        switch ($this->get->type) {
            case 'delete' :
                
                if (! empty ( $this->post->id ) && is_array ( $this->post->id )) {
                    $data_faq = get_option ( 'faq' );
                    $id = $this->post->id;
                    foreach ( $data_faq as $key => $value ) {
                        foreach ( $id as $key2 => $value2 ) {
                            if ($key == $value2) {
                                unset ( $data_faq [$key] );
                            }
                        }
                    }
                    update_option ( 'faq', $data_faq );
                }
                $var ['action'] = 'delete';
                break;
            case 'publish' :
                
                if (! empty ( $this->post->id ) && is_array ( $this->post->id )) {
                    $data_faq = get_option ( 'faq' );
                    $id = $this->post->id;
                    foreach ( $data_faq as $key => $value ) {
                        foreach ( $id as $key2 => $value2 ) {
                            if ($key == $value2) {
                                $data_faq [$key] ['status'] = 1;
                            }
                        }
                    }
                    update_option ( 'faq', $data_faq );
                }
                $var ['action'] = 'publish';
                break;
            case 'unpublish' :
                if (! empty ( $this->post->id ) && is_array ( $this->post->id )) {
                    $data_faq = get_option ( 'faq' );
                    $id = $this->post->id;
                    foreach ( $data_faq as $key => $value ) {
                        foreach ( $id as $key2 => $value2 ) {
                            if ($key == $value2) {
                                $data_faq [$key] ['status'] = 2;
                            }
                        }
                    }
                    update_option ( 'faq', $data_faq );
                }
                $var ['action'] = 'unpublish';
                break;
        }
        Pf::event ()->trigger ( "action", "faq-bulk-action-successfully", $this->get->type, $this->post->id );
        $var ['error'] = 0;
    } else {
        $var ['error'] = 1;
    }
    $var ['url'] = admin_url ( $this->action . '=index&ajax=&id=&token=&type=' );
    echo json_encode ( $var );
    }
    public function delete() {
    $var = array ();
    if (isset ( $this->get->id ) && isset ( $this->get->token ) && Pf_Plugin_CSRF::is_valid ( $this->get->token, $this->key . $this->get->id )) {
        $data_faq = get_option ( 'faq' );
        foreach ( $data_faq as $key => $value ) {
            if ($key == $this->get->id) {
                unset ( $data_faq [$key] );
                $var ['error'] = 0;
                Pf::event ()->trigger ( "action", "faq-delete-successfully", $this->get->id );
            } else {
                $var ['error'] = 1;
            }
        }
        update_option ( 'faq', $data_faq );
    }
    $var ['url'] = admin_url ( $this->action . '=index&ajax=&id=&token=' );
    echo json_encode ( $var );
    }
    public function change_status() {
    $data = array ();
    $status = $this->post->status;
    $id = $this->post->id;
    $data = get_option ( 'faq' );
    
    if ($status == 'publish') {
        foreach ( $data as $key => $value ) {
            if ($key == $id) {
                $data [$key] ['status'] = 1;
                break;
            }
        }
        update_option ( 'faq', $data );
    } else {
        foreach ( $data as $key => $value ) {
            if ($key == $id) {
                $data [$key] ['status'] = 2;
                break;
            }
        }
        update_option ( 'faq', $data );
    }
    }
    private function data_question_answer($questions, $answers) {
    $result = array ();
    $count = count ( $questions );
    if ($count == count ( $answers )) {
        for($i = 0; $i < $count; $i ++) {
            $id = uniqid ();
            $result ["{$id}"] = array (
                    'question' => $questions [$i],
                    'answer' => $answers [$i] 
            );
        }
    }
    return $result;
    }
    private function search_title($data, $condition) {
        $input = preg_quote(strtolower($condition), '~');
        $result = preg_grep('~' . $input . '~', $data);
        return $result;
    }
    
    private function search_status($data, $condtion) {
        $status = array();
        foreach ($data as $key => $value) {
            foreach ($condtion as $key1 => $value1)
            if ($value == $value1) {
                $status[$key] = $condtion[$key1];
            }
        }
        return $status;
    }
    
    private function search_by_key($data, $condition, $key_search) {
        $data_get = array_map('get_' . $key_search, $data);
        $result_search = is_string($condition) ? $this->search_title($data_get, $condition) : $this->search_status($data_get, $condition);
        $data_search = array();
        if (count($result_search)) {
            $data_search = array_intersect_key($data, $result_search);
        }
    
        return $data_search;
    }
    
    private function pagination($data, $curent_page) {
        if (is_array($data)) {
            $total = count($data);
            $total_page = ceil($total / NUM_PER_PAGE);
            if ($curent_page > $total_page) {
                $curent_page = $total_page;
            }
    
            $start = ($curent_page - 1) * NUM_PER_PAGE;
            return array_slice($data, $start, NUM_PER_PAGE);
        }
    }
    function generate_id($length) {
    $random = '';
    for($i = 0; $i < $length; $i ++) {
        $random .= chr ( rand ( ord ( 'a' ), ord ( 'z' ) ) );
    }
    return $random;
    }
}

function cmp($a, $b)
{
    return strcmp($a["status"], $b["status"]);
}
function cmp1($a, $b)
{
    return strcmp($b["status"], $a["status"]);
}