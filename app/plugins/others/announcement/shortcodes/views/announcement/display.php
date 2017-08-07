<div>
<?php if (!empty($this->atts['show'])) {
    foreach ($this->atts['show'] as $item) { ?>
            <div class="alerts alert-<?php echo e($this->atts['type'][$item['type']]); ?> alert-dismissable">
                <i class="fa fa-<?php echo $this->controller->announcement_model->get_icon($item['type'],$this->atts['type']); ?>"></i>
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <div class='container'><?php echo e($item['content']); ?></div>
            </div>
    <?php }
} ?>
</div>