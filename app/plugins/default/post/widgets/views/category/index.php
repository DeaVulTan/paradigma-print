<?php defined('PF_VERSION') OR header('Location:404.html'); ?>
<div class="panel panel-default">
    <div class="panel-heading"><?php echo (isset($this->controller->setting['widget-title']) && trim($this->controller->setting['widget-title']) != '') ? $this->controller->setting['widget-title'] : ''; ?></div>
    <div class="panel-body" style="background: <?php echo $this->controller->setting['background_color']; ?>">
        <?php
            $base = empty($this->controller->setting['widget-key-category']) ? '#' : get_page_url_by_id(get_configuration('page_lists', 'pf_post')) . '/' . $this->controller->setting['widget-key-category'] . ':';
            echo @get_ul_category(@$this->category_list, $base);
        ?>
    </div>
</div>