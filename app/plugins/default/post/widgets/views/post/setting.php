<?php defined('PF_VERSION') OR header('Location:404.html'); ?>
<div class="form-group">
    <label for="title" ><?php echo __('Title', 'post'); ?></label>
    <div><?php echo form_input(array('name' => 'widget-title', 'id' => 'widget-title')); ?></div>
</div>
<div class="form-group">
    <label for="type" ><?php echo __('Type', 'post'); ?></label>
    <div><?php echo form_dropdown('widget-type', array('new' => 'New posts', 'views' => 'Most viewed'), null, 'id="widget-type"'); ?></div>
</div>
<div class="form-group">
    <label for="title" ><?php echo __('Number of posts', 'post'); ?></label>
    <div><?php echo form_input(array('name' => 'widget-number-posts', 'id' => 'widget-number-posts')); ?></div>
</div>
<div class="form-group">
    <label for="inputEmail3"><?php echo __('Thumbnails', 'post'); ?></label>
    <div>
        <label class="radio-inline"> 
            <?php echo form_radio(array('name' => 'thumbnails', 'id' => 'thumbnails_yes', 'value' => 'yes')); ?> <?php echo __('Yes','post');?>
        </label>
        <label class="radio-inline">
            <?php echo form_radio(array('name' => 'thumbnails', 'id' => 'thumbnails_no', 'value' => 'no')); ?> <?php echo __('No','post');?>
        </label>
    </div>
</div>
<div class="form-group">
    <label for="title" ><?php echo __('Number of characters', 'post'); ?></label>
    <div><?php echo form_input(array('name' => 'widget-number-string', 'id' => 'widget-number-string')); ?></div>
</div>
