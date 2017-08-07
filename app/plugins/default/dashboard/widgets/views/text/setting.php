<?php defined('PF_VERSION') OR header('Location:404.html'); ?>
<div class="form-group">
    <label for="title"><?php echo __('Title', 'dashboard'); ?></label>
    <div><?php echo form_input(array('name'=>'title','id'=>'title')); ?></div>
</div>
<div class="form-group">
    <label for="content"><?php echo __('Content', 'dashboard'); ?></label>
    <div><?php echo form_textarea(array('name'=>'content','id'=>'content')); ?></div>
</div>
