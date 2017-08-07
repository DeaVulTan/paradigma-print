<?php defined('PF_VERSION') OR header('Location:404.html'); ?>
<div class="form-group">
    <label for="title"><?php echo __('Title', 'post'); ?></label>
    <div>
            <?php
            echo form_input ( array (
                    'name' => 'widget-title',
                    'id' => 'widget-title' 
            ) );
            $key = get_key_widget_shortcode_post ();
            echo form_input ( array (
                    'name' => 'widget-key-category',
                    'value' => isset ( $key ['category'] ) ? $key ['category'] : '',
                    'type' => 'hidden' 
            ) );
            ?>
        </div>
</div>
