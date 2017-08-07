<?php defined('PF_VERSION') OR header('Location:404.html');?>
<?php add_toolbar_button(form_button("<i class='fa fa-check'></i> " . __('Save changes', 'configuration'), array('onclick' => 'Admin_Menu_Settings.save();', 'class' => 'btn btn-primary'))); ?>
<div class="row">
    <div class="col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo __('Admin menu','configuration');?>
                </h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-4">
                        <div id="admin-menu-design">
                            <h2 style="margin-top:0px"><?php echo __('Show','configuration');?></h2>
                            <div class="list-group list-group-show"></div>
                            <h2 style="margin-top:0px"><?php echo __('Hide','configuration');?></h2>
                            <div class="list-group list-group-hide"></div>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div id="admin-menu-settings"></div>
                    </div>
                </div><!-- /.row -->
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div><!-- /.col (MAIN) -->
</div>
<div class="modal fade" id="menu-icon-picker-modal" style="display:none;">
  <div class="modal-dialog" style="width:900px;">
    <div class="modal-content">
      <div class="modal-body" style="height:600px; overflow: auto;">
        <div id="menu-icon-picker">
            <?php require dirname(__FILE__).'/font-awesome-template.php';?>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<input type="hidden" id="load-template-url" value="<?php echo admin_url('action=load&template=setting-menu-item')?>" >
<input type="hidden" id="load-sidebar-menu-url" value="<?php echo admin_url('action=load&template=sidebar-menu')?>" >
<input type="hidden" id="save-change-url" value="<?php echo admin_url('action=save')?>" >
<script>
Admin_Menu_Settings.updated_successfully_message = "<?php echo __('Admin menu setting is updated successfully','configuration');?>";
<?php if (empty($settings)){?>
Admin_Menu_Settings.data = {};
<?php }else{ ?>
Admin_Menu_Settings.data = <?php echo json_encode($settings);?>;
<?php }?>
</script>