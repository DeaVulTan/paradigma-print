<?php defined('PF_VERSION') OR header('Location:404.html'); ?>
<div class="widgets row text-widget">
    <div class="col-xs-12" style="background: <?php echo $this->controller->setting['background_color']; ?>">
        <?php if(isset($this->controller->setting['title'])): echo '<h3 class="widget-title text-color">' . e($this->controller->setting['title']) . '</h3>'; endif; ?>
        <div class="widget-content"><?php echo isset($this->controller->setting['content']) ? e($this->controller->setting['content']) : ''; ?></div>
    </div>
</div>