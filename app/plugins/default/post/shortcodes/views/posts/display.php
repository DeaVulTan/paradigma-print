<?php
public_css(get_path_file('app/plugins/default/post/shortcodes/assets/post.css'),true);
public_js(get_path_file('media/assets/masonry/masonry.min.js'),true);
public_js(get_path_file('media/assets/handlebars/js/handlebars.js'),true);
public_js(get_path_file('app/plugins/default/post/shortcodes/assets/post.js'),true);
require_once dirname(__FILE__) . '/template.php';
echo $this->atts['data'];