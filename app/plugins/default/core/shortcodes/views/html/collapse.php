<div class="panel panel-default">
    <div class="panel-heading">
        <a data-toggle="collapse" data-parent="<?php echo '#'.$this->accordion_id; ?>" href="<?php echo '#'.$this->collapse_id; ?>">
            <?php echo Pf::shortcode()->exec((!empty($this->atts['title'])) ? $this->atts['title'] : ''); ?> 
        </a>
    </div>
    <?php if ($this->active){ ?> 
    <div id="<?php echo $this->collapse_id; ?>" class="panel-collapse collapse in" style="height: auto;">
        <?php }else{ ?> 
    <div id="<?php echo $this->collapse_id; ?>" class="panel-collapse collapse">
        <?php }?>
        <div class="panel-body"><?php echo Pf::shortcode()->exec($this->content); ?></div>
    </div>
</div>