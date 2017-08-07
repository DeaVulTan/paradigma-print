<?php
defined('PF_VERSION') OR header('Location:404.html');
class Comment_Shortcode extends Pf_Shortcode_Controller{
    protected $validator;
    protected $data;
    protected $setting;
    protected $attrs;
    
    private $settings = array();
    private $options;
    private $type = 'comment';
    private $id = '';
    
    public function __construct(){
        parent::__construct();
        $this->validator = Pf::validator();
        $this->data['validated'] = array();
        $this->setting = Pf::setting();

        $this->mail = Pf::email_template();
        $this->load_model('comment');
        $this->load_model('user');
        $this->key = isset($attrs['key']) ? $attrs['key'] : '';
        $this->get_setting();
        $this->get_type();
        
    }
    
    /*
     * Geting setting of comment
    */
    
    private function get_setting() {
        $approve_flag = $this->clean_setting('approve_flag');
        $approve = $this->setting->get_element_value('pf_comment','approve');
        if (is_array($approve) && in_array(current_user('user-id'), $approve)) {
            $approve_flag = 0;
        }
        $this->settings = array(
            'approve_flag' => $approve_flag,
            'ordering' => $this->clean_setting('ordering'),
            'maximum_characters' => $this->clean_setting('maximum_characters', 255),
            'maximum_level' => $this->clean_setting('maximum_level', 5),
            'approve' => $approve,
            'site_name' => $this->setting->get_element_value('general', 'site_name'),
            'email' => noreply_email(),
        );
    }
    
    private function clean_setting($key, $default = 0) {
        return $this->setting->get_element_value('pf_comment',$key) == '' ? $default : $this->setting->get_element_value('pf_comment',$key);
    }
    
    private function get_comment($data) {
        $nodes = array();
        $tree = array();
        foreach ($data as &$node) {
            $node['children'] = array();
            $id = $node['id'];
            $parent_id = $node['comment_parent'];
            $nodes[$id] = & $node;
            if (array_key_exists($parent_id, $nodes)) {
                $nodes[$parent_id]['children'][] = & $node;
            } else {
                $tree[] = & $node;
            }
        }
        return $tree;
    }
    
    
    public function display($atts, $content = null,$tag) {
        $atts['key'] = isset($atts['key']) ? $atts['key'] : '';
        $atts['token'] = $this->validator->token($this->key);
        
        $atts['ordering'] = $this->settings['ordering'];
        $atts['approve_flag'] = $this->settings['approve_flag'];
        $atts['maximum_characters'] = $this->settings['maximum_characters'];
        $this->view->atts = $atts;
        if($this->clean_setting('enable') == 1){
            $this->view->render();
        }
    }
    
    public function comment_load_comment() {
        if (!is_ajax()) {
            return;
        }
        $key = $this->post->key;
        $page = $this->post->page > 0 ? $this->post->page - 1 : 0;
        $where = "comment_key = ? ";
        $where_values[] = $key;
        $operator = ' AND ';
        $where .= $operator."comment_status = ? ";
        $where_values[] = '1';
        
        
        if (!empty($where_values)){
            $params['where'] = array($where,$where_values);
        }
        if($this->clean_setting('ordering') == 0){
            $params["order"] = "`".Pf::database ()->escape('comment_parent')."` ".Pf::database ()->escape('ASC').", `".Pf::database ()->escape('id')."` ".Pf::database ()->escape('DESC');
        }
        
        $data = $this->comment_model->fetch($params,true);
        
        $total_records = $this->comment_model->found_rows();
        if ($total_records == 0) {
            return;
        }

        $pagination = new Pf_Paginator($total_records, NUM_PER_PAGE, 'page-comment', $page + 1);
        $comments = $this->get_comment($data);

        if (empty($comments)) {
            return;
        }
        $this->get_avatar($data);
        $avatar = $this->session->get('comments_avatar');
        $template = !empty($this->options['item_php']) ? $this->options['item_php'] : '';
        $meta = array();
        
        echo json_encode(array(
            'comments' => show_all_comment($comments, $key, $avatar, $template, $meta), 
            'total' => $total_records, 
            'pagination' => $pagination->page_links(public_url('', true), '', true), 
            'data_result' => $data
        ));
        
    }
    
    /**
     * Check what type of comment is? Comment, Reply, Edit
     */
    private function get_type() {
        if ($this->post->parent) {
            $this->type = 'reply';
        } elseif ($this->post->id) {
            $this->id = $this->post->id;
            $this->type = 'edit';
        }
    }
    
    /**
     * Validate: Token, Input, Data
     * @return boolean
     */
    private function validate() {
        if (!$this->post->token) {
            return set_message_ajax('error_token');
        }
        # Check that the data are not fully REQUEST.
        if ($this->post->message == NULL) {
            return set_message_ajax('error_input');
        }
        
        # Validate Data
        $max_length = get_configuration('maximum_characters', 'pf_comment') ? get_configuration('maximum_characters', 'pf_comment') : 255;
        $count_str = strlen($this->post->message);
        if($count_str > $max_length){
            return set_message_ajax('error_messagelength');
        }
        return true;
    }
    
    /**
     * Perform insert data into tables comment
     * @return type
     */
    private function save_data_comment() {
        if($this->setting->get_element_value('pf_comment','approve_flag') == 1){
            if(in_array(current_user('user-id'),$this->settings['approve'])){
                $status = 1;
            }else{
                $status = 2;
            }
        }else{
            $status = 1;
        }
        
        
        $data = array(
            'comment_content' => $this->post->message,
            'comment_author' => current_user('user-name'),
            'comment_user_id' => current_user('user-id'),
            'comment_key' => $this->post->key,
            'comment_status' =>  $status,
        );

        if ($this->type != 'edit') {
            $parent = $this->post->parent ? $this->post->parent : 0;
            $data['comment_parent'] = $parent;
        }
        if ($this->type == 'edit') {
            $data['id'] = $this->id;
            $data['comment_modified_date'] = date("Y-m-d H:i:s");
            $result = $this->comment_model->save($data);
        } else {
            $data['comment_created_date'] = date("Y-m-d H:i:s");
            $result = $this->comment_model->insert($data);
        }
        return $result;
    }
    
    /**
     * The data returned when successfully implemented
     * @param type $tmp
     * @param type $key
     * @return type
     */
    private function return_data($tmp, $key) {
        return array(
            'id' => $tmp,
            'token_id' => $this->validator->token($key . $tmp),
            'token' => $this->validator->token($key),
            'meta' => ''
        );
    }
    /**
     * Send an email if you need to appove comment
     */
    private function send_email_approve($data) {
        if (!empty($this->settings['approve'])) {
            $users = get_list_user(''.DB_PREFIX.'users.id in(' . generate_where_in($this->settings['approve']) . ')', $this->settings['approve'], 'user_email,user_name');
            if (empty($users)) {
                return;
            }
            $config = array();
            $subject = Pf::email_template()->get_element_subject('pf_comment_mail_template', 'approve_comment');
            $config['subject'] = str_replace('{sitename}', $this->settings['site_name'], $subject);
            $message = Pf::email_template()->get_element_body('pf_comment_mail_template', 'approve_comment');
            foreach ($users as $item) {
                $config['to'][$item['user_email']] = $item['user_name'];
            }
            $data['sitename'] = $this->settings['site_name'];
            foreach ($data as $key => $item) {
                $message = str_replace('{' . $key . '}', $item, $message);
            }
            $config['from'] = $this->settings['email'];
            $this->mail->send($config, $message);
        }
    }
    
    //Send email
    private function send_email() {
        if ($this->settings['approve_flag']) {
            $messages = array(
                'username' => current_user('user-name'),
                'comment' => $this->post->message,
                'comment-link' => site_url() . RELATIVE_PATH . '/'.ADMIN_FOLDER.'/?admin-page=comment'
            );
            $this->send_email_approve($messages);
        }
    }
    /**
     * Implementation note: Comment, Reply, Edit
     * @return boolean
     */
    public function comment_post() {
        if (!is_ajax()) {
            return false;
        }
        $validate = $this->validate();
        if ($validate !== true) {
            return $validate;
        }
        
        # Insert data vaof bang comment
        $result = $this->save_data_comment();
        if ($result > 0) {
            $db = Pf::database();
            $sql = "SELECT MAX(`id`) as max FROM `".DB_PREFIX."comments`";
            $tmp = $db->insert_id();
            $this->send_email();
            echo json_encode($this->return_data($tmp, $this->post->key));
            return;
        }
        
        echo set_message_ajax('error_cud');
    }
    
    /**
     * Delete comment
     * @return boolean
     */
    public function comment_delete() {
        if (!is_ajax()) {
            return false;
        }
        $token = $this->post->token;
        $key = $this->post->key;
        $id = $this->post->id;
        if (!$this->validator->is_valids($token, $key . $id)) {
            set_message_ajax('error_token');
            return;
        }
        $where = "comment_key = ? ";
        $where_values[] = $key;
        $operator = ' AND ';
        $where .= $operator."comment_status = ? ";
        $where_values[] = '1';
        
        if (!empty($where_values)){
            $params['where'] = array($where,$where_values);
        }
        
        $data = $this->comment_model->fetch($params,true);
        $result = array();
        $this->get_comment($data);
        get_all_children($data, $result, $id, 'comment_parent');
        array_unshift($result, $id);
        foreach($result as $key => $val){
            if ($this->comment_model->delete('id=?',array($val))){
            }else{
                echo set_message_ajax('error_cud');
            }
        }
    }
    
    /**
     * GET all avatar of users
     * @param type $comments
     * @return type
     */
    public function get_avatar($comments) {
        $users_comment = array();
        foreach ($comments as $comment) {
            if (!in_array($comment['comment_user_id'], $users_comment)) {
                $users_comment[] = $comment['comment_user_id'];
            }
        }
        $users_no_avatar_in_session = array();
        $users_avatar = $this->session->get('comments_avatar');
        
        if (empty($users_comment)) {
            return;
        }
        if (!empty($users_avatar)) {
            $users_no_avatar_in_session = array_diff($users_comment, array_keys($users_avatar));
        } else {
            $users_no_avatar_in_session = $users_comment;
        }

        if (!empty($users_no_avatar_in_session)) {
            $where = "user_delete_flag = ? ";
            $where_values[] = '0';
            if (!empty($where_values)){
                $params['where'] = array($where,$where_values);
            }
            
            $users = $this->user_model->fetch($params,true);
            
            foreach ($users as $key => $user) {
                
                $users_avatar[$user['id']] = $user['user_avatar'];
            } 
            $this->session->put('comments_avatar', $users_avatar);

        }
        
        
    }
    
    
    
    /**
     * Load data edit comment
     */
    private function get_form_edit($meta) {
        $form = json_decode($this->options['origin']['form'], true);
        $keys = $this->options['array_comment'];
        $html = '';
        if (!empty($form)) {
            foreach ($form as $item) {
                if (!in_array($item['name'], $keys)) {
                    continue;
                }
                $item['value'] = isset($meta[$item['name']]) ? $meta[$item['name']] : '';
                $html .= build_item_form($item);
            }
        }
        return $html;
    }
    
    public function comment_edit() {
        if (!is_ajax()) {
            return;
        }
        $id = $this->post->id;
        if (!$this->validator->is_valids($this->post->token, $this->post->key . $id)) {
            echo json_encode(array('code' => 'error_token', 'token' => $this->validator->token($this->post->key . $id)));
            return;
        }
        
        $token = $this->validator->token($this->post->key . $id);
        $meta = array();
        $result = array(
                'meta' => $meta,
                'form' => '',
                'token' => $token
        );
        echo json_encode($result);
    }
}