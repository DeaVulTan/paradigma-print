<?php
defined('PF_VERSION') OR header('Location:404.html');
class Posts_Shortcode extends Pf_Shortcode_Controller{
    protected $attrs;
    protected $attrs_old;
    protected $content;
    protected $conditions = '';
    protected $param = array();
    protected $setting;
    protected $homepage_li;
    
    public function __construct(){
        parent::__construct();
        $this->load_model('posts');
        $this->load_model('posts-tag');

        $this->setting = Pf::setting();
        
    }
    public function display($atts, $content = null,$tag) {
        $page_url = $this->get->pf_page_url;
        if (isset($this->get->title)) {
            $current_info['page_title'] = 'Search Results';
        }else if($this->get->cat){
            $get = (isset($atts['get']) && !empty($atts['get'])) ? $atts['get'] : 'cat';
            $list_id = isset($_GET[$get]) ? $_GET[$get] : null;
            $list = $this->get_post_categories_info($list_id);
            $current_info['page_title'] = $list;
        }else{
            $current_info = $this->get_page_info($page_url);
        }
        $this->view->breadcrumb_title = __($current_info['page_title'],'posts');
        Pf::event()->on("theme-breadcrumb",array($this,'posts_breadcrumb'),10);
        
        $atts['display_rating'] = '';
        $this->attrs_old = $atts;
        $data_theme = $this->clear_theme($atts);
        $params = array();
        $where = '';
        $where_values = array();
        $operator = '';
        
        if (isset($this->get->title)) {
            $params['limit'] = NUM_PER_PAGE;
        }else{
            $params['limit'] = $this->attrs_old['number_items'];
        }
        $params['page_index'] = (isset($this->get->{$this->page}))?(int)$this->get->{$this->page}:1;
        
        if (($title = $this->get->title)) {
            $where .= $operator.' `post_title` like ? ';
            $where_values[] = '%' . $title . '%';
            $operator = ' AND ';
        }
        
        $where .= $operator.' `post_status` = ? ';
        $where_values[] = 1;
        $operator = ' AND ';
        
        $where .= $operator.' `category_status` = ? ';
        $where_values[] = 1;
        $operator = ' AND ';
        
        $where .= $operator.'(';
        $where .= ' `post_published_date` <= "'.get_current_date().'"';
        $operator = ' OR ';
        $where .= $operator.' (`post_published_date`) = 0 ';
        $where .= ')';
        $operator = ' AND ';
        $where .= $operator.'(';
        $where .= ' `post_unpublished_date` > "'.get_current_date().'"';
        $operator = ' OR ';
        $where .= $operator.' (`post_unpublished_date`) = 0 ';
        $where .= ')';
        
        if(isset($this->get->cat) && $this->get->cat != NULL){
            $operator = ' AND ';
            $where .= $operator.' `post_category` = ? ';
            $where_values[] = $this->get->cat;
        }
        
        if(isset($this->get->tag) && $this->get->tag != NULL){
            $operator = ' AND ';
            $where .= $operator.' `post_tag_rewrite` = ? ';
            $where_values[] = $this->get->tag;
        }
        
        $order = $this->get_order($atts);
        $result['attrs'] = array(
            'select_columns' => $this->select_columns(),
            'attrs_old' => $this->attrs_old
        );
        $arr_select = implode(",", $result['attrs']['select_columns']);
        $params['fields'] = $arr_select;
        if(isset($this->get->tag)){
            $params['fields'] .= ',post_tag_name';
        }
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
            )
        );
        if(isset($this->get->tag)){
            $params['join']['3'] = array(
                'LEFT',
                ''.DB_PREFIX.'post_tags',
                ''.DB_PREFIX.'posts.id = '.DB_PREFIX.'post_tags.post_tag_post_id'
            );
        }
        if (!empty($where_values)){
            $params['where'] = array($where,$where_values);
        }
        if($this->get->sort_by){
            if($this->get->sort_by == 'relevance'){
                $params["order"] = "`".Pf::database ()->escape('post_category')."` ".Pf::database ()->escape('DESC');
            }else if($this->get->sort_by == 'date'){
                $params["order"] = "`".Pf::database ()->escape('post_created_date')."` ".Pf::database ()->escape('DESC');
            }else{
                $params["order"] = "`".Pf::database ()->escape($order[0])."` ".Pf::database ()->escape($order[1]);
            }
        }else{
            $params["order"] = "`".Pf::database ()->escape($order[0])."` ".Pf::database ()->escape($order[1]);
        }
        $atts['page_index'] = $params['page_index'];
        $posts = $this->posts_model->fetch($params,true);
        $atts['total_records'] = $this->posts_model->found_rows();
        $total_page = ceil($atts['total_records']/$this->attrs_old['number_items']);
        if (empty($posts) && $total_page > 0){
            $this->get->{$this->page} = $params['page_index'] = $total_page;
            $atts['page_index'] = $params['page_index'];
            $posts = $this->posts_model->fetch($params,true);
            $atts['total_records'] = $this->posts_model->found_rows();
        }
        $pagination = $this->get_pagination($data_theme);
        
        if (count($posts)) {
            $result['attrs']['total'] = !empty($pagination['total']) ? $pagination['total'] : '';;//!empty($total_page) ? $total_page : '';
            $result['data'] = $this->get_comment_tag($posts,$atts);
            $result['link'] = !empty($pagination['link']) ? $pagination['link'] : '';
        }
        
        if (isset($this->get->title)) {
            $atts['search_results'] = $posts;
            $atts['pagination'] = new Pf_Paginator($atts['total_records'], NUM_PER_PAGE, 'page');
            $this->view->atts = $atts;
            $this->view->render('search-results');
        }else{
            $atts['data'] = $this->get_content($result,$data_theme);
            $this->view->atts = $atts;
            $this->view->render();
        }
    }
    
    
    
    protected function get_order($atts) {
        if (isset($atts['order_field']) && isset($atts['order_type'])) {
            $field = $atts['order_field'];
            $type = $atts['order_type'];
            $field = is_array($field) ? $field : array($field);
            $type = is_array($type) ? $field : array($type);
            if (($atts['order_field'] != '[get:order]') && ($atts['order_type'] != '[get:type]')) {
                $order_fields = array(
                    'id' => 'id',
                    'date' => 'post_published_date',
                    'title' => 'post_title',
                    'views' => 'post_views'
                );
                $type = in_array($type[0], array('desc', 'asc')) ? $type[0] : ' desc';
                $field = in_array($field[0], array_keys($order_fields)) ? $order_fields[$field[0]] : 'id';
                $conditions = array($field,$type);
            } else {
                if($this->setting->get_element_value('pf_post','ordering') == 1){
                    $conditions = array('id','desc');
                }else{
                    $conditions = array('id','asc');
                }
            }
        } else {
            if($this->setting->get_element_value('pf_post','ordering') == 1){
                $conditions = array('id','desc');
            }else{
                $conditions = array('id','asc');
            }
        }
        return $conditions;
    }
    
    private function clear_theme($data) {
        if (isset($data['theme'])) {
            $tmp = explode('-', $data['theme']);
            if (isset($tmp[0]) && $tmp[0] == 'timeline' && isset($tmp[1])) {
                $data['theme'] = $tmp[0];
                $data['theme_position'] = $tmp[1];
            }
        }
        return $data;
    }
    
    /**
     * Columns select from table
     * @param array $columns
     */
    protected function select_columns($columns = '') {
        if (!empty($columns)) {
            
            $columns = is_array($columns) ? $columns : func_get_args();
        }
        if (empty($columns) || count($columns) === 0) {
            $columns = array(
                'post_title',
                'post_author',
                'post_category',
                'post_created_date',
                'post_published_date',
                'post_content',
                'post_thumbnail',
                'post_allow_comment',
                'post_allow_rating',
                'post_views',
                'category_name',
                'category_status',
                'user_firstname',
                'user_lastname',
                'user_avatar',
                'user_name',
            );
        }
        array_unshift($columns, ''.DB_PREFIX.'posts.id');
        return $columns;
    }
    
    
    /**
     * Get comments, rating, tag, if the condition is true
     * @param type $data
     * @return type
     */
    private function get_comment_tag($data,$atts) {
        $post_id = get_all_id_object($data);
        $comment = array();
        $tag = array();
        $rating = '';
        if ((!empty($atts['display_comment']) && $atts['display_comment'] != 'false') && get_configuration('enable', 'pf_comment') && get_configuration('enable_comment', 'pf_post') &&  check_plugin_active('comment')) {
        }
        if ((!empty($atts['display_tag']) && $atts['display_tag'] != 'false')) {
            $tag = $this->get_tag_post($post_id, $this->setting->get_element_value('pf_post','page_lists'));
        }
        if (is_array($data)){
            foreach ($data as $key => $item) {
                if ($atts['display_rating'] != 'false' && get_configuration('enable_rating', 'pf_post') == 1 && get_configuration('enable', 'pf_rating') == 1  && check_plugin_active('rating')) {
                    $rating = Pf::shortcode()->exec('{pf:rating key=post_' . $item['id'] . ' read_only = true}');
                }
                $item['tags'] = !empty($tag[$item['id']]) ? implode(' ', $tag[$item['id']]) : '';
                $item['comments'] = !empty($comment[$item['id']]) ? $comment[$item['id']] : '';
                $item['link_detail'] = public_url(get_page_url_by_id($this->setting->get_element_value('pf_post','page_detail')), false);
                $item['rating'] = $rating;
                $data[$key] = $item;
            }
        }
        return $data;
    }
    
    protected function get_comments($post_id) {
        $result = array();
        if(check_plugin_active('comment')){
            $data = is_array($post_id) ? array_map('generate_param_count_comment', $post_id) : array('key_' . $post_id);
            $comment = $this->model->table(''.DB_PREFIX.'comments')->select('count(id) as counted, comment_key')->set_joins(array())
            ->conditions('Where comment_key in(' . generate_where_in($data) . ') and comment_status = 1 group by comment_key')
            ->param($data)->get();
    
            foreach ($post_id as $item) {
                $result[$item] = find_comment_item($item, $comment);
            }
        }
        return $result;
    }
    
    /**
     * Get Theme for Post
     * @param string $page
     * @return string
     */
    protected function get_theme($atts,$page = 'lists') {

        # Check to see if the file exists or not containing theme.
        $theme_actived = strtolower(get_option('active_theme'));
        if (!file_exists($path = ABSPATH . '/app/themes/' . $theme_actived . '/post_templates.php')) {
            return;
        }
        
        # If does not exist or has no content, then returns.
        $themes = require_once $path;
        $name = isset($atts['theme']) ? $atts['theme'] : 'default';
        
        if (empty($themes[$page][$name])) {
            return;
        }
        #
        $theme = $themes[$page][$name];
        if (is_array($theme) || (isset($atts['theme']) && $atts['theme'] == 'multiple-columns')) {
            return $this->get_content_theme($theme,$atts);
        }
        return $theme;
    }
        
    /**
     * If the Multiple Column or Timeline is calculated to obtain the template for the Post
     * @param string | array $theme
     * @return type
     */
    private function get_content_theme($theme,$atts) {
        # If the Timeline is taken under the corresponding positions Theme
        if ($atts['theme'] == 'timeline' && is_array($theme)) {
            $theme = !empty($theme[$atts['theme_position']]) ? $theme[$atts['theme_position']] : '';
        }
        # If the Multiple-columns are divided into columns for columns
        if ($atts['theme'] == 'multiple-columns') {
            $theme_column_size = 4;
            if (!empty($atts['theme_columns']) && 12 % (int) $atts['theme_columns'] == 0) {
                $theme_column_size = 12 / (int) $atts['theme_columns'];
                }
                $theme = str_replace("{theme_column_size}", "col-md-{$theme_column_size}", $theme);
            }
            return $theme;
    }
    
    public function get_content($data,$atts, $page = 'lists') {
        $this->content = $this->get_theme($atts,$page);
        $post_data = is_array($data) ? array_filter($data) : $data;
        if (empty($post_data['data'])) {
            $have = $this->remove_tag('have_post');
            $no_post = $this->get_tag('no_post');
            if (isset($no_post[1])) {
                return $have ? str_replace($no_post[0], $no_post[1], $this->content) : $no_post[1];
            } else {
                return '<p>' . __('No post', 'pf_post') . '</p>';
            }
        }
        
        $transforms = array_map('transformer_post', $post_data['data']);
        
        $this->remove_tag('no_post');
        $have_post = $this->get_tag('have_post');
        $replace = isset($have_post[1]) ? $have_post[1] : $this->content;
        $posts = '';
        $parity = false;
        if (isset($atts['theme']) && $atts['theme'] == 'timeline' && $atts['theme_position'] == 'mid') {
            $parity = true;
        }
        
        $i = 0;
        foreach ($transforms as $item) {
            $item['parity'] = $parity == true && $i % 2 == 0 ? 'pull-left' : 'pull-right';
            $posts .= $this->replace_tag($item, $replace);
            $i++;
        }
        
        if (isset($have_post[1])) {
            $posts = str_replace($have_post[0], $posts, $this->content);
        }
        
        if (isset($data['attrs'])) {
            $attrs = base64_encode(Pf::security()->cipher(json_encode($data['attrs']), 'ajax_posts'));
            $posts = str_replace('{attrs}', $attrs, $posts);
        }
        
        if (isset($data['link'])) {
            $posts = $atts['pagination_position'] == 'top' ? $data['link'] . $posts : $posts . $data['link'];
        }
        return $posts;
    }
    
    /**
     * Tag of content shortcode
     */
    protected function get_tag($tag_name) {
        $tag = find_tag($tag_name, $this->content);
        return !empty($tag) ? $tag : array();
    }
    
    protected function replace_tag($item, $content) {
        $keys = array_keys($item);
        foreach ($keys as $v) {
            $content = str_replace('{' . $v . '}', $item[$v], $content);
        }
        return $content;
    }
    
    protected function remove_tag($tag_remove) {
        $tag = find_tag($tag_remove, $this->content);
        $have = true;
        if (isset($tag[0])) {
            $this->content = str_replace($tag[0], '', $this->content);
        } else {
            $have = false;
        }
        return $have;
    }
    
    /**
     * Pagination pagination and retrieve interface list
     * @return array
     */
    protected function get_pagination($atts,$show_theme = true, $using_page = null) {
        $number_items = NUM_PER_PAGE;
        $result = array();
        # If paging is implemented and followed by the theme
        if (isset($atts['pagination']) && $atts['pagination'] == 'true') {
            if (isset($atts['number_items'])) {
                $number_items = (int) $atts['number_items'];
            }
            $total = $this->posts_model->found_rows();
            $pages = isset($atts['page']) ? $atts['page'] : 1;
            if ($total < 0 || empty($pages)) {
                return;
            }
            $page = isset($pages[1]) && (int) $pages[1] > 0 ? $pages[1] : 1;
            if (!empty($using_page)) {
                $page = (int) $using_page;
            }
            $total_page = ceil($total / $number_items);
            # Find the corresponding interface for theme
            $theme = isset($atts['theme']) ? $atts['theme'] : '';
            $pagination_theme = require_once ABSPATH. '/app/plugins/default/post/shortcodes/views/posts/pagination.php';
            $result = array(
                'total' => $total_page,
                'page' => $page
            );
            # Build theme
            $theme_pagination = '';
            $per_page = (int) ($page - 1) * $number_items;
    
            if ($show_theme == true && $total_page > 1 && $per_page < $total) {
                switch ($theme) {
                    case 'multiple-columns':
                        $theme_pagination = isset($pagination_theme[$theme]) ? $pagination_theme[$theme] : '';
                    break;
                    case 'timeline':
                        $tmp = isset($pagination_theme[$theme]) ? $pagination_theme[$theme] : '';
                        $theme_pagination = str_replace('{theme_position}', $atts['theme_position'], $tmp);
                        break;
                    default:
                        $paging = new Pf_Paginator($total, $number_items, 'page');
                        $url = public_url($this->build_url(), true) . (substr((public_url($this->build_url(), true)), -1) !== '/' ? '/' : '');
                        $theme_pagination = $paging->page_links($url, '', true);
                    break;
                }
            }
            
            $this->conditions .= "LIMIT {$per_page},{$number_items}";
            $result['link'] = $theme_pagination;
        } elseif (isset($atts['number_items'])) {
            $this->conditions .= " LIMIT {$atts['number_items']} ";
        }
        return $result;
    }
    
    protected function build_url($atts) {
        $params = array('id', 'category', 'author', 'order_field', 'order_type', 'tag', 'title');
        $attrs = $atts;
        $url = array();
        foreach ($attrs as $key => $value) {
            $get_value = $this->get_by_pattern($value);
            $tmp = is_array($get_value) ? array_filter($get_value) : $get_value;
            if (in_array($key, $params) && count($tmp) === 2) {
                $url[] = "{$tmp[0]}:{$tmp[1]}";
            }
        }
        return is_array($url) ? implode('/', $url) : '';
    }
    
    protected function get_tag_post($post_id, $url) {
        $tmp = is_array($post_id) ? $post_id : array($post_id);
        $where = ' `post_tag_post_id` = ? ';
        $where_values[] = $tmp[0];
        
        if (!empty($where_values)){
            $params['where'] = array($where,$where_values);
        }
        $tags = $this->posts_tag_model->fetch($params,true);
        if(empty($tags)){
            return array();
        }
        $result = array();
        foreach ($tags as $tag) {
            if (in_array($tag['post_tag_post_id'], $post_id)) {
                $result[$tag['post_tag_post_id']][] = '<a href="'. public_url($url . '/tag:' . $tag['post_tag_rewrite']) . (get_configuration('show_tag_name_url', 'pf_post') == 0 ? '/' . url_title(removesign($tag['post_tag_post_id'])) : '') .'" class="background-color bg-hover-color">'.$tag['post_tag_name'].'</a>';//$tag['post_tag_name'];//find_tag_item($post_id[0], $url, $tags);
            }
        }
        return $result;
    }
    
    /**
     * Load data ajax
     * @return json
     */
    public function load_page() {
        if (!is_ajax()) {
            return;
        }
        $params = array();
        $where = '';
        $where_values = array();
        $operator = '';
        
        
        # Key decode encrypted
        $attrs = Pf::security()->cipher(base64_decode($this->post->attrs), 'ajax_posts');
        $data = json_decode($attrs, true);

        # Checks if the data is not array (), the return
        if (!is_array($data) || !isset($data['total']) || $this->post->page > $data['total']) {
            return;
        }
        
        @$this->attrs = $data['attrs'];
        $this->attrs_old = $data['attrs_old'];
        $data_theme = $this->clear_theme($this->attrs_old);
        
        $params['limit'] = $this->attrs_old['number_items'];
        $params['page_index'] = $this->post->page;
        
        $where .= $operator.' `post_status` = ? ';
        $where_values[] = 1;
        $operator = ' AND ';
        
        $where .= $operator.' `category_status` = ? ';
        $where_values[] = 1;
        $operator = ' AND ';
        
        $where .= $operator.'(';
        $where .= ' `post_published_date` <= "'.get_current_date().'"';
        $operator = ' OR ';
        $where .= $operator.' (`post_published_date`) = 0 ';
        $where .= ')';
        $operator = ' AND ';
        $where .= $operator.'(';
        $where .= ' `post_unpublished_date` > "'.get_current_date().'"';
        $operator = ' OR ';
        $where .= $operator.' (`post_unpublished_date`) = 0 ';
        $where .= ')';
        
        $order = $this->get_order($this->attrs_old);
        $result['attrs'] = array(
            'select_columns' => $this->select_columns(),
            'attrs' => array('pagination' => $this->attrs_old['pagination'],'number_items' => $this->attrs_old['number_items']),
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
            )
        );
        
        if (!empty($where_values)){
            $params['where'] = array($where,$where_values);
        }
        
        $params["order"] = "`".Pf::database ()->escape($order[0])."` ".Pf::database ()->escape($order[1]);
        
        $posts = $this->posts_model->fetch($params,true);
        $pagination = $this->get_pagination($data_theme,false, $this->post->page);
        $comment_tag = $this->get_comment_tag($posts,$this->attrs);
        $ratings = array_map('get_rating_show', $comment_tag);
        $result = array(
            'posts' => array_map('transformer_post', $comment_tag),
            'ratings' => $ratings,
            'pagination' => isset($pagination['total']) && isset($pagination['page']) && $pagination['total'] > $pagination['page'] ? true : false
        );
        
        return json_encode($result);
    }
    
    public function build_category($id, $link = 'cat') {
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
    
    public function get_page_info($url) {
        Pf::database()->select('id,page_title', ''.DB_PREFIX.'pages', '`page_url`=?', array($url));
        $page_info = Pf::database()->fetch_assoc_all();
        if (!empty($page_info[0])){
            return $page_info[0];
        }else{
            return false;
        }
    }
    
    public function get_post_info($id) {
        Pf::database()->select('post_title, post_category', ''.DB_PREFIX.'posts', 'id=?', array($id));
        $post_info = Pf::database()->fetch_assoc_all();
        return $post_info[0];
    }
    
    public function get_post_categories_info($id) {
        Pf::database()->select('category_name, id', ''.DB_PREFIX.'post_categories', 'id=?', array($id));
        $categories_info = Pf::database()->fetch_assoc_all();
        return $categories_info[0]['category_name'];
    }
    
    public function current_page() {
        $page = $this->get_page_info($_GET['pf_page_url']);
        return $page['page_title'];
    }
    
    public function posts_breadcrumb($breadcrumb = ''){
        return $this->view->fetch('breadcrumb');
    }
    
}