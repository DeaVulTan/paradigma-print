<?php defined('PF_VERSION') OR header('Location:404.html'); ?>
<div class="panel panel-default">
    <div class="panel-heading">
            <?php echo (isset($this->controller->setting['widget-title']) && trim($this->controller->setting['widget-title']) != '') ? $this->controller->setting['widget-title'] : ''; ?>
        </div>
    <div class="panel-body" style="background: <?php echo $this->controller->setting['background_color']; ?>">
        <div class="blog-tags">
                <?php
                $base = empty ( $this->controller->setting ['widget-key-tag'] ) ? '#' : get_page_url_by_id ( get_configuration ( 'page_lists', 'pf_post' ) ) . '/' . $this->controller->setting ['widget-key-tag'] . ':';
                $max = isset ( $this->controller->setting ['widget-maximum-tag'] ) && ( int ) $this->controller->setting ['widget-maximum-tag'] > 0 ? ( int ) $this->controller->setting ['widget-maximum-tag'] : 10;
                $tags = show_widget_tags ( $max );
                // Check whether or not to use Cloud?
                if (! empty ( $this->controller->setting ['widget-type'] ) && $this->controller->setting ['widget-type'] == 1) {
                    $tagsCloud = array ();
                    foreach ( $tags as $tag ) {
                        $tagsCloud [] = array (
                                        'text' => $tag ['post_tag_name'],
                                        'weight' => $tag ['total'] / 100,
                                        'link' => public_url () . $base . $tag ['post_tag_rewrite'] 
                        );
                    }
                    $id = generate_id ( 10 );
                    public_js ( RELATIVE_PATH . '/media/assets/jqcloud/jqcloud-1.0.4.min.js', true );
                    public_css ( RELATIVE_PATH . '/media/assets/jqcloud/jqcloud.css', true );
                    ?>
                    <!-- Tag Cloud Show -->
            <div id="<?php echo $id; ?>" style="<?php echo!empty($this->controller->setting['widget-style']) ? $this->controller->setting['widget-style'] : 'height: 200px'; ?>">
            </div>
            <script type="text/javascript">
                        $(function() {
                            var lists = <?php echo json_encode($tagsCloud) ?>;
                            $("#<?php echo $id; ?>").jQCloud(lists);
                        });
                    </script>
                    <?php
                } else {
                    foreach ( $tags as $tag ) :
                        ?>
                        <a
                href="<?php echo public_url() . $base . $tag['post_tag_rewrite']; ?>"
                class="background-color a-tag"><?php echo $tag['post_tag_name'] ?></a>
                        <?php
                    endforeach
                    ;
                }
                ?>
            </div>
        </div>
</div>