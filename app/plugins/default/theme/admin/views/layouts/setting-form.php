<div class="form-group">
    <label for="inputEmail3"><?php echo __('Container','theme');?></label>
    <div>
        <label class="radio-inline"> 
            <?php echo form_radio(array('name' => 'container', 'id' => 'container_yes', 'value' => 'yes')); ?> <?php echo __('Yes','theme');?>
        </label> <label class="radio-inline"> 
            <?php echo form_radio(array('name' => 'container', 'id' => 'container_no', 'value' => 'no')); ?> <?php echo __('No','theme');?>
        </label>
    </div>
</div>

<div class="form-group">
    <label for="inputEmail3"><?php echo __('Background color','theme');?></label>
    <div>
        <?php echo form_input("background_color");?>
    </div>
</div>