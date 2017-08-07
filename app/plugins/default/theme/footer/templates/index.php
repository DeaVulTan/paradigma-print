<?php defined('PF_VERSION') OR header('Location:404.html');?>
<div class="row">
    <div class="col-xs-12" style="overflow: auto;">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo __('Footer', 'theme'); ?>
                    <span class="small"><?php echo __('Footer description', 'theme'); ?></span>
                </h3>
            </div>
            <div class="panel-body">
                <form method="post"
                      action="<?php echo admin_url('action=save&type=pattern'); ?>"
                      id="add-builder-form">
                    <div class="row pad">
                        <div class="col-sm-12">
                            <div id="layout_buider_container">
                                <table class="table table-bordered">
                                    <colgroup>
                                        <col span="4" class="col-md-3"/>
                                    </colgroup>
                                    <tr>
                                        <td>
                                            <ul
                                                class="sortable-list ui-sortable"
                                                id="panel_1"></ul>
                                        </td>
                                        <td>
                                            <ul
                                                class="sortable-list ui-sortable"
                                                id="panel_2"></ul>

                                        </td>
                                        <td>
                                            <ul
                                                class="sortable-list ui-sortable"
                                                id="panel_3"></ul>
                                        </td>
                                        <td>
                                            <ul
                                                class="sortable-list ui-sortable"
                                                id="panel_4"></ul>

                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="row pad">
                            <div class="col-sm-3" id="plugins">
                                <ul class="sortable-list list-inline">
                                    <?php foreach ($widgets as $k => $widget) { ?>
                                        <?php if (!in_array($k, $active_widgets)) continue; ?>
                                        <li class="sortable-item" id="widget_<?php echo $k; ?>"><?php echo htmlspecialchars_decode($widget['name']); ?></li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <?php echo form_hidden('url_save', admin_url('action=save')); ?>
                    <?php echo form_hidden('json_data'); ?>
                    <?php echo form_hidden('setting_data', '{}'); ?>
                </form>
                <div class="row pad">
                    <div class="col-md-12">
                        <?php add_toolbar_button(form_button('<i class="fa fa-angle-double-left"></i> &nbsp; ' . __('Back', 'theme'), array('href' => admin_url('action=&id='), 'class' => 'btn btn-default')));
                        ?>

                        <?php add_toolbar_button(form_button('<i class="fa fa-floppy-o"></i> &nbsp; ' . __('Save', 'theme'), array('id' => "btn_builder_layout", 'class' => 'btn btn-primary')));
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo form_hidden('setting_form_url', admin_url('action=setting-form')) ?>
<script>
    pf_layout_setting.relative_path = "<?php echo RELATIVE_PATH; ?>";
    pf_layout_setting.admin_folder = "<?php echo ADMIN_FOLDER; ?>";
    pf_layout_setting.delete_widget_message = "<?php echo __('Are you sure to delete widget?', 'theme'); ?>";
    pf_layout_setting.ok_button_message = "<?php echo __('Ok', 'theme'); ?>";
    pf_layout_setting.delete_button_message = "<?php echo __('Delete', 'theme'); ?>";
    pf_layout_setting.cancel_button_message = "<?php echo __('Cancel', 'theme'); ?>";

    $(document).ready(function() {
        $('#btn_builder_layout').click(function() {
            $('#json_data').val(pf_build_layout_json_data());
            $('#add-builder-form').attr('action', $('#url_save').val());
            $('#add-builder-form').submit();
        });
        $('#old_pattern').val($('#pattern').val());
        pf_rebuild_layout();
    });
</script>