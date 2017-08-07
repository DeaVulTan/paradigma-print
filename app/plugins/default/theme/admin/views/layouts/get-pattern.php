<?php 
    defined('PF_VERSION') OR header('Location:404.html');
?>
<script type="text/javascript" src="<?php echo RELATIVE_PATH;?>/app/plugins/default/theme/admin/assets/theme-layouts.js"></script>
<div class="row">
    <div class="col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo __('Layouts','theme');?>
                    <span class="small"><?php echo __('Please choose the layout pattern','theme');?></span>
                </h3>
            </div>
            <div class="panel-body">
                <div class="row pad">
                    <div class="col-md-3">
                        <div id="pattern-label-1" class="pattern-label">
                            <img alt="pattern 1" class="img-thumbnail pattern-active"
                                id="pattern-img-1"
                                src="<?php echo RELATIVE_PATH;?>/app/plugins/default/theme/admin/images/patterns/1.png" />
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div id="pattern-label-2" class="pattern-label">
                            <img alt="pattern 2" class="img-thumbnail"
                                id="pattern-img-2"
                                src="<?php echo RELATIVE_PATH;?>/app/plugins/default/theme/admin/images/patterns/2.png" />
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div id="pattern-label-3" class="pattern-label">
                            <img alt="pattern 3" class="img-thumbnail"
                                id="pattern-img-3"
                                src="<?php echo RELATIVE_PATH;?>/app/plugins/default/theme/admin/images/patterns/3.png" />
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div id="pattern-label-4" class="pattern-label">
                            <img alt="pattern 4" class="img-thumbnail"
                                id="pattern-img-4"
                                src="<?php echo RELATIVE_PATH;?>/app/plugins/default/theme/admin/images/patterns/4.png" />
                        </div>
                    </div>
                </div>
                <form method="post"
                    action="<?php echo admin_url('action=add&type=builder'); ?>"
                    id="add-pattern-form">
					<?php echo form_hidden('layout_name'); ?>
					<?php echo form_hidden('old_pattern',4); ?>
					<?php echo form_hidden('pattern',1); ?>
					<?php echo form_hidden('json_data','{}')?>
				</form>
            </div>
        </div>
    </div>
</div>
<style type="text/css">
    section#main-content {position: initial !important;}
</style>
<script>
$(document).ready(function(){
	$('.btn-index').css({display:'none'});
	$('.btn-edit').css({display:'none'});
	$('.btn-copy').css({display:'none'});
	$('.btn-add-2').css({display:'none'});
	$('.btn-add').css({display:'inline-block'});
	$('.back_to_add').css({display:'none'});
	$('.btn_builder_layout').css({display:'none'});
	$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
	$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
});
function pattern_active(index) {
	$('#pattern-img-' + index).addClass('pattern-active');
	pattern_inactive(index);
	$('#pattern').val(index);
}

function pattern_inactive(index) {
	for (var i = 1; i <= 4; i++) {
		if (i != index)
			$('#pattern-img-' + i).removeClass('pattern-active');
	}
}
$(document).ready(function(){
	pattern_active($('#pattern').val());
	$('#pattern-label-1').click(function() {
		pattern_active(1);
	});
	$('#pattern-label-2').click(function() {
		pattern_active(2);
	});
	$('#pattern-label-3').click(function() {
		pattern_active(3);
	});
	$('#pattern-label-4').click(function() {
		pattern_active(4);
	});
});
function back_to_list(){
	$("#main-content").mask("<?php echo __('Loading...','theme'); ?>");
	try{
	   tinymce.remove();
	}catch(e){}
	$('#main-content').load('<?php echo admin_url($this->action.'=index'); ?>',function(){
		$("#main-content").unmask();
		$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
	});
}
function next_new_layout(){
	type = $(".pattern-active").attr('id');
	pattern = type.substr(12,1)
	$("#main-content").mask("<?php echo __('Loading...','theme'); ?>");
	$('#main-content').load('<?php echo admin_url($this->action.'=add');?>',{pattern:pattern} , function(){
		$("#main-content").unmask();
	});
}
</script>