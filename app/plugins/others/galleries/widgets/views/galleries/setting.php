<?php defined('PF_VERSION') OR header('Location:404.html'); ?>
<div class="form-group">
    <label for="title" ><?php echo __('Widget Title','galleries'); ?></label>
    <div><?php echo form_input(array('name' => 'widget-title', 'id' => 'widget-title')); ?></div>
</div>
<div class="form-group">
    <label for="title" ><?php echo __('Gallery page','galleries'); ?></label>
    <div>
            <?php echo form_dropdown('gallerypage', Pf_Plugin_Singleton::list_page()); ?>
        </div>
</div>
<div class="form-group">
    <label for="content" ><?php echo __('Image sources','galleries'); ?></label>
    <div>
            <?php
            $list = $this->controller->galleries_model->gallery_list ();
            $list [''] = __ ( 'All Galleries', 'galleries' );
            $list ['rand'] = __ ( 'Random Gallery', 'galleries' );
            echo form_dropdown ( 'data', $list );
            ?>
        </div>
</div>
