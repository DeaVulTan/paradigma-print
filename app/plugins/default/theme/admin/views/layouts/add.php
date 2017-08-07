<script type="text/javascript" src="<?php echo RELATIVE_PATH;?>/app/plugins/default/theme/admin/assets/theme-layouts.js"></script>
<?php
    defined ( 'PF_VERSION' ) or header ( 'Location:404.html' );
    if(isset($this->controller->post->pattern) && $this->controller->post->pattern == 1){
?>
<div class="row">
	<div class="col-xs-12" style="overflow: auto;">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">
					<i class="fa fa-list"></i> <?php echo __('Layouts','theme');?>
                    <span class="small"><?php echo __('Layout designer','theme');?></span>
				</h3>
			</div>
			<div class="panel-body">
			     <form method="post"  method="post" name="frm_layout_New" id="frm_layout_New" action="<?php echo admin_url('action=save'); ?>" id="frm_layout_New">
					<div class="row pad">
						<div class="col-md-6">
							<div
								class="form-group <?php if (!empty($error['layout_name'])){?> has-error <?php }?>">
								<label for="disabledTextInput"><?php echo __('Layout name','theme');?></label>
						          <?php echo form_input('layout_name'); ?>
						          <?php if (!empty($error['layout_name'])){?><p
									class="help-block"><?php echo $error['layout_name'][0]?></p><?php }?>
						          <div id="show_error"></div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="disabledTextInput"><?php echo __('Layout type','theme');?></label>
								<div>
						          <?php echo form_dropdown('layout_type',array('1' => 'Default', '2' => '100% width'),array('1'))?>
                                </div>
							</div>
						</div>
					</div>
					<div class="row pad">
						<div class="col-sm-3" id="plugins">
							<ul class="sortable-list">
								<?php foreach ($this->list_widgets as $k => $widget) { ?>
								    <?php if (!in_array($k, $this->activate_widgets)) continue;?>
                                    <li class="sortable-item" id="widget_<?php echo $k;?>"><?php echo htmlspecialchars_decode($widget['name']);?></li>
                                <?php }?>
							</ul>
						</div>
						<div class="col-sm-9">
							<div id="layout_buider_container">
								<table class="table table-bordered">
									<tr>
										<td>
											<ul class="sortable-list ui-sortable" id="panel_1"></ul>
										</td>
									</tr>
									<tr valign="middle" align="center">
										<td>
											<div style="font-size: 3em; padding: 100px 0;">
												<strong><?php echo __('Main','theme');?></strong>
											</div>
										</td>
									</tr>
									<tr>
										<td>
											<ul class="sortable-list ui-sortable" id="panel_4"></ul>
										</td>
									</tr>
								</table>
							</div>
						</div>
					</div>
					<?php echo form_hidden('url_save',admin_url('action=add&type=save'));?>
					<?php echo form_hidden('old_pattern',4); ?>
					<?php echo form_hidden('pattern',4); ?>
					<?php echo form_hidden('json_data','{}');?>
					<?php echo form_hidden('setting_data','{}');?>
				</form>
			</div>
		</div>
	</div>
</div>
<?php }else if(isset($this->controller->post->pattern) && $this->controller->post->pattern == 2){?>
<div class="row">
    <div class="col-xs-12" style="overflow: auto;">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo __('Layouts','theme');?>
                    <span class="small"><?php echo __('Layout designer','theme');?></span>
                </h3>
            </div>
            <div class="panel-body">
                <form method="post"  method="post" name="frm_layout_New" id="frm_layout_New" action="<?php echo admin_url('action=save'); ?>" id="frm_layout_New">
                    <div class="row pad">
                        <div class="col-md-5">
                            <div class="form-group <?php if (!empty($error['layout_name'])){?> has-error <?php }?>">
                                <label for="disabledTextInput"><?php echo __('Layout name','theme');?></label>
						          <?php echo form_input('layout_name'); ?>
						          <?php if (!empty($error['layout_name'])){?><p class="help-block"><?php echo $error['layout_name'][0]?></p><?php }?>
						    </div>
                        </div>
                    </div>
                    <div class="row pad">
                        <div class="col-sm-3" id="plugins">
							<ul class="sortable-list">
								<?php foreach ($this->list_widgets as $k => $widget) { ?>
								    <?php if (!in_array($k, $this->activate_widgets)) continue;?>
                                    <li class="sortable-item" id="widget_<?php echo $k;?>"><?php echo htmlspecialchars_decode($widget['name']);?></li>
                                <?php }?>
							</ul>
						</div>
                        <div class="col-sm-9">
                            <div id="layout_buider_container">
                                <table class="table table-bordered">
                                    <tr>
                                        <td colspan="2">
                                            <ul
                                                class="sortable-list ui-sortable"
                                                id="panel_1"></ul>
                                        </td>
                                    </tr>
                                    <tr valign="middle" align="center">
                                        <td>
                                            <div
                                                style="font-size: 3em; padding: 100px 0;">
                                                <strong><?php echo __('Main','theme');?></strong>
                                            </div>
                                        </td>
                                        <td style="width: 400px;">
                                            <ul
                                                class="sortable-list ui-sortable"
                                                id="panel_3"></ul>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <ul
                                                class="sortable-list ui-sortable"
                                                id="panel_4"></ul>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
					<?php echo form_hidden('url_save',admin_url('action=add&type=save'));?>
					<?php echo form_hidden('old_pattern',4); ?>
					<?php echo form_hidden('pattern',4); ?>
					<?php echo form_hidden('json_data','{}');?>
					<?php echo form_hidden('setting_data','{}');?>
				</form>
                <div class="row pad">
                    <div class="col-md-12">
                        <?php add_toolbar_button(form_button('<i class="fa fa-angle-double-left"></i> &nbsp; '. __('Back','theme') ,
						array('id'=>"back-add-pattern",'class' => 'btn btn-default')));?>
                            
                        <?php add_toolbar_button(form_button('<i class="fa fa-floppy-o"></i> &nbsp; '. __('Save','theme') ,
						array('id'=>"btn_builder_layout",'class' => 'btn btn-primary')));?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php }else if(isset($this->controller->post->pattern) && $this->controller->post->pattern == 3){ ?>
<div class="row">
    <div class="col-xs-12" style="overflow: auto;">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo __('Layouts','theme');?>
                    <span class="small"><?php echo __('Layout designer','theme');?></span>
                </h3>
            </div>
            <div class="panel-body">
                <form method="post"  method="post" name="frm_layout_New" id="frm_layout_New" action="<?php echo admin_url('action=save'); ?>" id="frm_layout_New">
                    <div class="row pad">
                        <div class="col-md-5">
                            <div class="form-group <?php if (!empty($error['layout_name'])){?> has-error <?php }?>">
                                <label for="disabledTextInput"><?php echo __('Layout name','theme');?></label>
						          <?php echo form_input('layout_name'); ?>
						          <?php if (!empty($error['layout_name'])){?><p class="help-block"><?php echo $error['layout_name'][0]?></p><?php }?>
						    </div>
                        </div>
                    </div>
                    <div class="row pad">
                        <div class="col-sm-3" id="plugins">
							<ul class="sortable-list">
								<?php foreach ($this->list_widgets as $k => $widget) { ?>
								    <?php if (!in_array($k, $this->activate_widgets)) continue;?>
                                    <li class="sortable-item" id="widget_<?php echo $k;?>"><?php echo htmlspecialchars_decode($widget['name']);?></li>
                                <?php }?>
							</ul>
						</div>
                        <div class="col-sm-9">
                            <div id="layout_buider_container">
                                <table class="table table-bordered">
                                    <tr>
                                        <td colspan="2">
                                            <ul
                                                class="sortable-list ui-sortable"
                                                id="panel_1"></ul>
                                        </td>
                                    </tr>
                                    <tr valign="middle" align="center">
                                        <td style="width: 400px;">
                                            <ul
                                                class="sortable-list ui-sortable"
                                                id="panel_2"></ul>
                                        </td>
                                        <td>
                                            <div
                                                style="font-size: 3em; padding: 100px 0;">
                                                <strong><?php echo __('Main','theme');?></strong>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <ul
                                                class="sortable-list ui-sortable"
                                                id="panel_4"></ul>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
					<?php echo form_hidden('url_save',admin_url('action=add&type=save'));?>
					<?php echo form_hidden('old_pattern',4); ?>
					<?php echo form_hidden('pattern',4); ?>
					<?php echo form_hidden('json_data','{}');?>
					<?php echo form_hidden('setting_data','{}');?>
				</form>
                <div class="row pad">
                    <div class="col-md-12">
                        <?php add_toolbar_button(form_button('<i class="fa fa-angle-double-left"></i> &nbsp; '. __('Back','theme') ,
						array('id'=>"back-add-pattern",'class' => 'btn btn-default')));?>
                            
                        <?php add_toolbar_button(form_button('<i class="fa fa-floppy-o"></i> &nbsp; '. __('Save','theme') ,
						array('id'=>"btn_builder_layout",'class' => 'btn btn-primary')));?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php }else{ ?>
<div class="row">
    <div class="col-xs-12" style="overflow: auto;">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo __('Layouts','theme');?>
                    <span class="small"><?php echo __('Layout designer','theme');?></span>
                </h3>
            </div>
            <div class="panel-body">
                <form method="post"  method="post" name="frm_layout_New" id="frm_layout_New" action="<?php echo admin_url('action=save'); ?>" id="frm_layout_New">
                    <div class="row pad">
                        <div class="col-md-5">
                            <div class="form-group <?php if (!empty($error['layout_name'])){?> has-error <?php }?>">
                                <label for="disabledTextInput"><?php echo __('Layout name','theme');?></label>
						          <?php echo form_input('layout_name'); ?>
						          <?php if (!empty($error['layout_name'])){?><p class="help-block"><?php echo $error['layout_name'][0]?></p><?php }?>
						    </div>
                        </div>
                    </div>
                    <div class="row pad">
                        <div class="col-sm-3" id="plugins">
							<ul class="sortable-list">
								<?php foreach ($this->list_widgets as $k => $widget) { ?>
								    <?php if (!in_array($k, $this->activate_widgets)) continue;?>
                                    <li class="sortable-item" id="widget_<?php echo $k;?>"><?php echo htmlspecialchars_decode($widget['name']);?></li>
                                <?php }?>
							</ul>
						</div>
                        <div class="col-sm-9">
                            <div id="layout_buider_container">
                                <table class="table table-bordered">
                                    <tr>
                                        <td colspan="3">
                                            <ul
                                                class="sortable-list ui-sortable"
                                                id="panel_1"></ul>
                                        </td>
                                    </tr>
                                    <tr valign="middle" align="center">
                                        <td style="width: 300px;">
                                            <ul
                                                class="sortable-list ui-sortable"
                                                id="panel_2"></ul>
                                        </td>
                                        <td>
                                            <div
                                                style="font-size: 3em; padding: 100px 0;">
                                                <strong><?php echo __('Main','theme');?></strong>
                                            </div>
                                        </td>
                                        <td style="width: 300px;">
                                            <ul
                                                class="sortable-list ui-sortable"
                                                id="panel_3"></ul>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3">
                                            <ul
                                                class="sortable-list ui-sortable"
                                                id="panel_4"></ul>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
					<?php echo form_hidden('url_save',admin_url('action=add&type=save'));?>
					<?php echo form_hidden('old_pattern',4); ?>
					<?php echo form_hidden('pattern',4); ?>
					<?php echo form_hidden('json_data','{}');?>
					<?php echo form_hidden('setting_data','{}');?>
				</form>
                <div class="row pad">
                    <div class="col-md-12">
                        <?php add_toolbar_button(form_button('<i class="fa fa-angle-double-left"></i> &nbsp; '. __('Back','theme') ,
						array('id'=>"back-add-pattern",'class' => 'btn btn-default')));?>
                            
                        <?php add_toolbar_button(form_button('<i class="fa fa-floppy-o"></i> &nbsp; '. __('Save','theme') ,
						array('id'=>"btn_builder_layout",'class' => 'btn btn-primary')));?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>


<?php echo form_hidden('setting_form_url',admin_url('action=setting_form'))?>
<style type="text/css">
section#main-content {
	position: initial !important;
}
</style>
<script>
    pf_layout_setting.relative_path = "<?php echo RELATIVE_PATH; ?>";
    pf_layout_setting.delete_widget_message = "<?php echo __('Are you sure to delete widget?','theme'); ?>";
    pf_layout_setting.ok_button_message = "<?php echo __('Ok','theme'); ?>";
    pf_layout_setting.delete_button_message = "<?php echo __('Delete','theme'); ?>";
    pf_layout_setting.cancel_button_message = "<?php echo __('Cancel','theme'); ?>";
    $(document).ready(function(){
    	$('.btn-index').css({display:'none'});
    	$('.btn-edit').css({display:'none'});
    	$('.btn-copy').css({display:'none'});
    	$('.btn-add').css({display:'none'});
    	$('.back_to_add').css({display:'inline-block'});
    	$('.btn_builder_layout').css({display:'inline-block'});

    	$('.btn_builder_layout1').click(function(){
    		alert("ok"); return false;
    		$('#json_data').val(pf_build_layout_json_data());
    		$('#frm_layout_New').attr('action',$('#url_save').val());
    		$('#frm_layout_New').submit();
    	});
    
        if ($('#old_pattern').val() != $('#pattern').val()){
        	$('#json_data').val('{}');
        }
        $('#old_pattern').val($('#pattern').val());
        pf_rebuild_layout();
    });
    function back_to_add(){
    	$("#main-content").mask("<?php echo __('Loading...','theme'); ?>");
    	try{
    	   tinymce.remove();
    	}catch(e){}
    	$('#main-content').load('<?php echo admin_url($this->action.'=get_pattern'); ?>',function(){
    		$("#main-content").unmask();
    	});
    }
    function layout_new(){
    	$('#json_data').val(pf_build_layout_json_data());
    	layout_name = $("#layout_name").val();
    	if(layout_name == ''){
    	    $("#layout_name").parent().addClass("has-error");
    	    $("#show_error").html('<span class="help-block"><?php echo __('The field is required','theme') ?></span>');
    	    $.notification({type:"error",img:"",width:"400",content:"<i class='fa fa-times-circle fa-2x'></i> <?php echo __('There is an error, please check again!','theme'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
    	    return false;
    	}
    	$("#main-content").mask("<?php echo __('Loading...','theme'); ?>");
    	try{
    	   tinymce.triggerSave(); 
            tinymce.remove();
        }catch(e){}
    	$.post($('#frm_layout_New').attr('action'),$('#frm_layout_New').serialize(),function(obj){
    		$("#main-content").unmask();
    		if (obj.error == 1){
    			$('#main-content').html(obj.content);
    		}else if (obj.error == 0){
    			$.notification({type:"success",img:"",width:"400",content:"<i class='fa fa-check fa-2x'></i> <?php echo __('Layout is created successfully','theme'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
    			$('#main-content').load(obj.url,function(){
    			});
    		}
    	},'json');
    }
</script>