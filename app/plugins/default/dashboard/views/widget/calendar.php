<?php defined('PF_VERSION') OR header('Location:404.html');?>
<div class="hidden" id="messageErrorJS">
    <ul>
        <li class="confirmDeleteAllEvent"><?php echo __('Are you sure with this action?', 'dashboard'); ?></li>
    </ul>
</div>
<!-- Calendar -->
<?php
$padding = "padding:13px";
if($lay[P] =="left"){
    $padding = "padding-left:11px";
}else if($lay[P] =="right"){
    $padding = "padding-right:11px";
}
?>
<div class="box box-warning"  id="list-sort-<?php echo $id;?>">
    <div class="box-header">
        <i class="fa fa-calendar"></i>
        <div class="box-title" style="cursor: move;"><?php echo __('My Calendar','dashboard');?></div>
        <!-- tools box -->
        <div class="pull-right box-tools">
            <!-- button with a dropdown -->
            <div class="btn-group">
                <button class="btn btn-warning btn-sm dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-bars"></i></button>
                <ul class="dropdown-menu pull-right" role="menu">
                    <li><a href="#" id="add_new_event"><?php echo __('Add new event','dashboard');?></a></li>
                    <li><a href="#" id="clear_event"><?php echo __('Clear events','dashboard');?></a></li>
                    <li class="divider"></li>
                    <li><a href="#" id="clear_add_event"><?php echo __('Clear all events','dashboard');?></a></li>
                </ul>
            </div>
        </div><!-- /. tools -->
    </div><!-- /.box-header -->
    <div class="box-body no-padding">
        <!--The calendar -->
        <div id="calendar">
        </div>
    </div><!-- /.box-body -->
</div><!-- /.box -->
