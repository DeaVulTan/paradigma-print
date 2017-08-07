<?php defined('PF_VERSION') OR header('Location:404.html');?>
<?php
if (is_dir(ABSPATH . '/installs')):
    ?>
<div class="row">
    <div class="col-lg-12" style="padding-right: 20px;">
        <div class=" alerts alert-danger fade in">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4>
                <?php echo __('Please rename or remove installation directory', 'dashboard'); ?>
            </h4>
        </div>
    </div>
</div>
<?php
endif;

?>
<div class="row">
<?php render_layout_dashboard($layout, $dash); ?>
</div>
<?php require_once ABSPATH . '/lib/common/plugin/views/alert.php'; ?>
<div class="hidden" id="messageErrorJs">
    <ul>
        <li class="confirmResize"><?php echo __('Are you sure to change this event?', 'dashboard'); ?></li>
        <li class="confirmDrop"><?php echo __('Are you sure to drop this event?', 'dashboard'); ?></li>
        <li class="validatorContent"><?php echo __('Content must be at least [1] characters and no more than [2] characters', 'dashboard'); ?></li>
        <li class="validatorContentEmpty"><?php echo __('Content should not be empty', 'dashboard'); ?></li>
        <li class="validatorDateFormat"><?php echo __('Wrong format!', 'dashboard'); ?></li>

    </ul>
</div>
<div class="modal fade" id="ModelCalendarView"
     tabindex="-1" role="dialog"
     aria-labelledby="myModalLabelView"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <input type="hidden" id="id_view" value=""/>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">
                    <?php echo __('View Event', 'dashboard'); ?> <span id="myModalLabel1"></span>
                </h4>
            </div>
            <div class="modal-body">
                <div id="alertMessage">

                </div>
                <table class="table table-bordered form">
                    <colgroup>
                        <col span="1" class="col-md-2 header">
                        <col span="1" class="col-md-4">
                        <col span="1" class="col-md-2 header">
                        <col span="1" class="col-md-4">
                    </colgroup>
                    <tr>
                        <td>
                            <label class="control-label"><?php echo __('Title', 'dashboard') ?></label>
                        </td>
                        <td colspan="3" id="title">

                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label class="control-label"><?php echo __('Url', 'dashboard') ?></label>
                        </td>
                        <td colspan="3" id="url">
                            <a href="" target="_blank"><?php echo __("Go to this URL", 'dashboard') ?></a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label class="control-label">
                                <?php echo __('Start Date', 'dashboard') ?>
                            </label>
                        </td>
                        <td id="time_start">
                            <div class="input-group date">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>

                        </td>
                        <td>
                            <label for="Published date" class="control-label"><?php echo __('End Date', 'post'); ?></label>
                        </td>
                        <td id="time_end">
                            <div class="input-group date" id="date_end" data-date-format="YYYY-MM-DD">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>

                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label class="control-label"><?php echo __('Content', 'dashboard') ?></label>
                        </td>
                        <td colspan="3">
                            <textarea style="background: #FFFFFF;" disabled="true" name="content" class="form-control" cols="40" rows="10" id="c_content"></textarea>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button id="btnDelete" type="button" class="btn btn-danger"><?php echo __('Delete', 'dashboard'); ?></button>
                <button id="btnEdit" type="button" class="btn btn-primary"><?php echo __('Edit', 'dashboard'); ?></button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('Close', 'dashboard'); ?></button>
            </div>
        </div>
    </div>
</div><!--end add category -->
<div class="modal fade" id="ModelCalendar"
     tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel1"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" id="form_calendar"
                  role="form" method="post"
                  accept-charset="utf-8">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <input type="hidden" name="id" id="id" value="0"/>
                    <h4 class="modal-title">
                        <?php echo __('Event Manager', 'dashboard'); ?> <span id="myModalLabel1"></span>
                    </h4>
                </div>
                <div class="modal-body">
                    <div id="alertMessage">

                    </div>
                    <table class="table table-bordered form">
                        <colgroup>
                            <col span="1" class="col-md-2 header">
                            <col span="1" class="col-md-4">
                            <col span="1" class="col-md-2 header">
                            <col span="1" class="col-md-4">
                        </colgroup>
                        <tr>
                            <td>
                                <label class="control-label"><?php echo __('Title', 'dashboard') ?> <span class="style">*</span></label>
                            </td>
                            <td colspan="3">
                                <?php
                                echo form_input(array('data-label' => __('Title', 'dashboard'), 'name' => 'title', 'class' => 'form-control', 'id' => 'title', 'tabindex' => 1));
                                ?>
                                <p class="error"></p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class="control-label"><?php echo __('Url', 'dashboard') ?></label>
                            </td>
                            <td colspan="3">
                                <?php
                                echo form_input(array('data-label' => __('Url', 'dashboard'), 'name' => 'url', 'class' => 'form-control', 'id' => 'url', 'tabindex' => 1));
                                ?>
                                <p class="error"></p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class="control-label">
                                    <?php echo __('Start Date', 'dashboard') ?> <span>*</span>
                                </label>
                            </td>
                            <td>
                                <div class="input-group date" id="date_start"
                                     data-date-format="YYYY-MM-DD">
                                    <?php
                                    echo form_input(array('data-label' => __('Start Date', 'dashboard'), 'name' => 'start', 'class' => 'form-control', 'tabindex' => 5));
                                    ?>
                                    <span class="input-group-addon"><span style="color: #000000" class="glyphicon glyphicon-calendar"></span>
                                    </span>

                                </div>
                                <p class="error"></p>
                            </td>
                            <td>
                                <label for="Published date" class="control-label"><?php echo __('End Date', 'post'); ?></label>
                            </td>
                            <td>
                                <div class="input-group date" id="date_end" data-date-format="YYYY-MM-DD">
                                    <?php
                                    echo form_input(array('data-label' => __('End Date', 'dashboard'), 'name' => 'end', 'class' => 'form-control', 'tabindex' => 4));
                                    ?>
                                    <span class="input-group-addon"><span style="color: #000000" class="glyphicon glyphicon-calendar"></span>
                                    </span>

                                </div>
                                <p class="error"></p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class="control-label"><?php echo __('Content', 'dashboard') ?> <span>*</span></label>
                            </td>
                            <td colspan="3" class="<?php echo state_validator('content', $validated); ?>">
                                <?php
                                echo form_textarea(array('data-label' => __('Content', 'dashboard'), 'name' => 'content', 'class' => 'form-control'));
                                ?>
                                <p class="error"></p>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('Close', 'dashboard'); ?></button>
                    <button type="button" class="btn btn-primary" id="btnSaveEdit"><?php echo __('Save', 'dashboard'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div><!--end add category -->
<div class="modal fade" id="editComment" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" role="form" method="post" accept-charset="utf-8">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><?php echo __('View ', 'dashboard'); ?> <span id="myModalLabel"></span></h4>
                </div>
                <div class="modal-body">
                    <div id="alertMessage">

                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-12"><?php echo __('Content', 'dashboard'); ?></label>
                        <div class="col-sm-12">
                            <textarea disabled="true" name="content" class="form-control" cols="40" rows="10" id="content"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('Close', 'dashboard'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div><!--end add category -->
