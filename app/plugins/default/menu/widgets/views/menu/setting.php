<?php defined('PF_VERSION') OR header('Location:404.html'); ?>
<div class="form-group">
    <label for="title" ><?php echo __('Widget Title','menu'); ?></label>
    <div><?php echo form_input(array('name'=>'widget-title','id'=>'widget-title')); ?></div>
</div>
<div class="form-group">
    <label for="title" ><?php echo __('Please choose a menu','menu'); ?></label>
    <div><?php echo form_dropdown('menupicker',$this->menu_list); ?></div>
</div>
<div class="form-group">
    <label for="title" ><?php echo __('Menu layout','menu'); ?></label>
    <div><?php echo form_dropdown('menutype',array('vertical'=>'Vertical','horizontal'=>'Horizontal','footer'=>'Footer', 'accordion'=>'Accordion')); ?></div>
</div>
