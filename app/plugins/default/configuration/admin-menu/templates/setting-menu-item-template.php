<form class="form-horizontal">
    <div class="form-group">
        <label class="col-sm-2 control-label"><h3><?php echo __('Item','configuration');?></h3></label>
        <div class="col-sm-10">
            <h3 class="form-control-static" id="menu-item-name"></h3>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label"><?php echo __('Visibility','configuration');?></label>
        <div class="col-sm-10">
            <p class="form-control-static" id="menu-item-visibility">
                <a id="show"><?php echo __('Show','configuration');?></a> 
                <a id="hide"><?php echo __('Hide','configuration');?></a>
            </p>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label"><?php echo __('Icon','configuration');?></label>
        <div class="col-sm-10">
            <p class="form-control-static" id="menu-item-icon"></p>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label"><?php echo __('Icon color','configuration');?></label>
        <div class="col-sm-10">
            <div class="input-group">
              <input type="text" class="form-control" id="menu-item-icon-color-border">
              <span class="input-group-addon" id="menu-item-icon-color">&nbsp;</span>
            </div>
        </div>
    </div>
</form>