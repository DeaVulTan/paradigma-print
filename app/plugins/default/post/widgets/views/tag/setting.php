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
                    'name' => 'widget-key-tag',
                    'value' => isset ( $key ['tag'] ) ? $key ['tag'] : '',
                    'type' => 'hidden' 
            ) );
            ?>
        </div>
</div>
<div class="form-group">
    <label for="type"><?php echo __('Display type', 'post'); ?></label>
    <div>
            <?php
            echo form_dropdown ( 'widget-type', array (
                    0 => __ ( 'Default', 'post' ),
                    1 => __ ( 'Cloud', 'post' ) 
            ) );
            ?>
        </div>
</div>
<div class="form-group">
    <label for="maximum_tags"><?php echo __('Maximum number of tags', 'post'); ?></label>
    <div>
            <?php
            echo form_input ( array (
                    'name' => 'widget-maximum-tag',
                    'id' => 'widget-maximum-tag' 
            ) );
            ?>
        </div>
</div>
<div class="form-group">
    <label for="title"><?php echo __('Style', 'post'); ?></label>
    <div>
            <?php
            echo form_input ( array (
                    'name' => 'widget-style',
                    'id' => 'widget-style' 
            ) );
            ?>
        </div>
</div>
