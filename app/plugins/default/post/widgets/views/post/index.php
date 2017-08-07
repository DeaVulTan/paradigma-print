<?php defined('PF_VERSION') OR header('Location:404.html'); ?>
<div class="panel panel-default">
    <div class="panel-heading"><?php echo (isset($this->controller->setting['widget-title']) && $this->controller->setting['widget-title'] != '') ? $this->controller->setting['widget-title'] : __('Post list', 'post'); ?></div>
    <div class="panel-body" style="background: <?php echo $this->controller->setting['background_color']; ?>">
        <ul class="b-categories-list">
            <?php 
                $order = isset($this->controller->setting['widget-type']) && $this->controller->setting['widget-type'] == 'views' ? 'views' : 'id'; 
                $thumbnails = isset($this->controller->setting['thumbnails']) && $this->controller->setting['thumbnails'] == 'yes' ? 'yes' : 'no';
            ?>
            {widgets:display select='id,post_title,post_thumbnail,post_status' number_items=<?php echo isset($this->controller->setting['widget-number-posts']) ? (int) $this->controller->setting['widget-number-posts'] : 0; ?> number_string=<?php echo isset($this->controller->setting['widget-number-string']) ? (int) $this->controller->setting['widget-number-string'] : 60; ?>
            order_field=<?php echo $order; ?> thumbnails=<?php echo $thumbnails; ?> order_type=desc display_tag=false display_comment=false display_rating=false cat=1}
            {/widgets:display}
        </ul>
    </div>
</div>