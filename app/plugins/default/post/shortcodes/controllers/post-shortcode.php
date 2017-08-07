<?php
defined('PF_VERSION') OR header('Location:404.html');
class Post_Shortcode extends Posts_Shortcode{
    private $settings = array();
    
    public function __construct(){
        parent::__construct();
        $this->load_model('posts');
        $this->setting = Pf::setting();
        $this->get_setting();
    }
    public function display($atts, $content = null,$tag) {
        $page_url = $this->get->pf_page_url;
        $arr_id = explode('/',$page_url);
        $arr_id2 = explode('-',$arr_id[1]);
        $id = max(array_keys($arr_id2));
        $id = $arr_id2[$id];
        if($atts['id'] != '[get:id]'){
            $post_id = array('0' => $atts['id']);
        }else{
            $post_id = array('0' => $id);
        }
        $result = array(
            'data' => null
        );
        $get_cat = !empty($atts['get_cat']) ? $atts['get_cat'] : 'cat';
        $post_detail = $this->get_post_info($post_id[0]);
        $list = $this->build_category($post_detail['post_category'], $get_cat) . "<li class='active'>" . cut($post_detail['post_title'],70) . "</li>";
        $this->view->breadcrumb_title = __($post_detail['post_title'],'post');
        $this->view->breadcrumb_breadcrumb = __($list,'post');
        Pf::event()->on("theme-breadcrumb",array($this,'post_breadcrumb'),10);
        
        $show_comment = '';
        $show_rating = '';
        if (!empty($post_id[0]) && $post_id[0] > 0) {
            $params = array();
            $where = '';
            $where_values = array();
            $operator = '';
            
            $where .= $operator.' `'.DB_PREFIX.'posts`.`id` = ? ';
            $where_values[] = $post_id[0];
            
            $result['attrs'] = array(
                'select_columns' => $this->select_columns(),
                'attrs_old' => $this->attrs_old
            );
            $arr_select = implode(",", $result['attrs']['select_columns']);
            $params['fields'] = $arr_select;
            
            $params['join'] = array(
                '0' => array(
                    'LEFT',
                    ''.DB_PREFIX.'post_categories',
                    ''.DB_PREFIX.'posts.post_category = '.DB_PREFIX.'post_categories.id'
                ),
                '1' => array(
                    'LEFT',
                    ''.DB_PREFIX.'users',
                    ''.DB_PREFIX.'posts.post_author = '.DB_PREFIX.'users.id'
                ),
                '3' => array(
                    'LEFT',
                    ''.DB_PREFIX.'post_tags',
                    ''.DB_PREFIX.'posts.id = '.DB_PREFIX.'post_tags.post_tag_post_id'
                )
            );
            
            if (!empty($where_values)){
                $params['where'] = array($where,$where_values);
            }
            
            $item = $this->posts_model->fetch_one($params);
            if (!empty($item)) {
                $data['id'] = $post_id[0];
                $data['post_views'] = $item['post_views'] + 1;
                $this->posts_model->save($data);
                
                $tag = $this->get_tag_post($post_id, get_page_url_by_id(get_configuration('page_lists', 'pf_post')));
                $item['tags'] = isset($tag[$item['id']]) ? implode(' ', $tag[$item['id']]) : '';
                $item['comments'] = isset($comment[$item['id']]) ? $comment[$item['id']] : '';
                $item['link_detail'] = '';
                $show_comment = '';
                if ($this->settings['comment'] == 1 && $this->settings['enable_comment'] == 1 && $item['post_allow_comment'] == 1 && check_plugin_active('comment')) {
                    $show_comment = Pf::shortcode()->exec('{comment:display key=post_' . $post_id[0] . '}');
                }
                $show_rating = $this->settings['enable_rating'] == 1 && get_configuration('enable', 'pf_rating') == 1 && $item['post_allow_rating'] == 1 && check_plugin_active('rating') ? Pf::shortcode()->exec('{pf:rating key=post_' . $post_id[0] . '}') : '';
                $result = array(
                    'data' => array($item)
                );
            }
        }
        $atts['posts'] = $this->get_content($result,$atts,  'detail') . $show_rating . $show_comment;
        
        $this->view->atts = $atts;
        $this->view->render();
    }
    
    private function get_setting() {
        $this->settings['comment'] = $this->setting->get_element_value('pf_comment','enable');
        $this->settings['page_lists'] = $this->setting->get_element_value('pf_post','page_lists');
        $this->settings['enable_comment'] = $this->setting->get_element_value('pf_post','enable_comment');
        $this->settings['enable_rating'] = $this->setting->get_element_value('pf_post','enable_rating');
        return $this->settings;
    }
    
    public function post_breadcrumb($breadcrumb = ''){
        return $this->view->fetch('breadcrumb');
    }
}