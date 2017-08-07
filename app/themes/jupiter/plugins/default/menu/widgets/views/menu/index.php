<?php defined('PF_VERSION') OR header('Location:404.html'); ?>
<?php $title = isset($this->controller->setting['widget-title']) && trim($this->controller->setting['widget-title']) != '' ? $this->controller->setting['widget-title'] : '';
?>
<div class="widgets row widget-menu panel panel-default" style="margin-left: 0px;margin-right: 0px;">
    <div class="panel-heading">
        <?php echo (isset($this->controller->setting['widget-title']) && trim($this->controller->setting['widget-title']) != '') ? $this->controller->setting['widget-title'] : ''; ?>
    </div>
    <div class="panel-body" style="background: <?php echo $this->controller->setting['background_color']; ?>">
        <div class="widget-content">
            <?php
                if (!empty($this->controller->setting['menupicker'])) {
                    $menu_type = !empty($this->controller->setting['menutype']) ? $this->controller->setting['menutype'] : '';
                    echo "{menu:display id=" . $this->controller->setting['menupicker'] . " type=" . $menu_type . "}";
                } else {
                    echo __("Please choose a menu", 'menu');
                }
            ?>
        </div>
    </div>
</div>