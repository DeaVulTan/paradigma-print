<?php defined ( 'PF_VERSION' ) or header ( 'Location:404.html' ); ?>
<div class="row" style="margin-bottom: 20px;margin-top:-15px;">
    <div class="col-sm-12">
        <h3>
            <?php if (!empty($this->menu_settings[$this->controller->get->{"admin-page"}])){ ?>
                <?php echo '<i class="' . $this->menu_settings[$this->controller->get->{"admin-page"}]['icon'] . '" style="color:'.$this->menu_settings[$this->controller->get->{"admin-page"}]['icon_color'].'"></i>'; ?>
            <?php }else{ ?>
                <?php global $_admin_menu; ?>
                <?php echo (!empty($_admin_menu[$this->controller->get->{"admin-page"}]['icon_class'])) ? '<i class="' . $_admin_menu[$this->controller->get->{"admin-page"}]['icon_class'] . '"></i>' : ''; ?>
            <?php } ?>
            <?php echo __('Announcement','announcement'); ?>  <small><?php echo __('Add','announcement'); ?></small>
        </h3>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <form name="frm_Announcement_New" id="frm_Announcement_New" class="form-horizontal" role="form" action="<?php echo admin_url(); ?>" method="post" onsubmit="return false;">
            <div class="form-group <?php $this->error_class("announcement_pubdate");?> col-sm-6">
                <label for="subject" class="col-sm-4 control-label">
                   <?php echo __("Publish date","announcement"); ?>
                    
                </label>
                <div class="col-sm-8" >
                    <div class="input-group date" id="publishedDate" data-date-format="YYYY-MM-DD HH:mm:ss">
                        <?php
                            echo form_input("announcement_pubdate","",$this->controller->announcement_model->elements_value["announcement_pubdate"]);
                        ?>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                    <?php $this->error_message("announcement_pubdate")?>
                </div>
            </div>

            <div class="form-group <?php $this->error_class("announcement_unpubdate");?> col-sm-6 pull-right">
                <label for="subject" class="col-sm-4 control-label">
                   <?php echo __("Unpublish date","announcement"); ?>
                     
                </label>
                <div class="col-sm-8">
                     <div class="input-group date" id="publishedDate" data-date-format="YYYY-MM-DD HH:mm:ss">
                        <?php
                            echo form_input("announcement_unpubdate","",$this->controller->announcement_model->elements_value["announcement_unpubdate"]);
                        ?>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                    <?php $this->error_message("announcement_unpubdate")?>
                </div>
            </div>
            <div class="form-group <?php $this->error_class("announcement_type");?> col-sm-6">
                <label for="subject" class="col-sm-4 control-label">
                   <?php echo __("Type","announcement"); ?>
                </label>
                <div class="col-sm-8">
                    <?php
                        $currenttype=   !empty($currenttype)?$currenttype:1;
                        $type = $this->controller->announcement_model->elements_value["announcement_type"];
                        echo form_dropdown('announcement_type',$type,$currenttype); ?>
                    <?php $this->error_message("announcement_type")?>
                </div>
            </div>
            <div class="form-group <?php $this->error_class("announcement_to");?> col-sm-6 pull-right">
                <label for="subject" class="col-sm-4 control-label">
                   <?php echo __("Announce to user:","announcement"); ?>
                </label>
                <div class="col-sm-8">
                    <?php echo form_input('announcement_touser',''); ?>
                    <div id="ms-right">
                    </div>
                    <script type="text/javascript">
                       $('#announcement_touser').attr('type','hidden');
                        var ms = $('#ms-right').magicSuggest({
                          valueField: 'name',
                          data: [<?php echo $this->controller->announcement_model->get_list_user();?>],
                         });
                         $(ms).on(
                          'selectionchange', function(e, cb, s){
                             $('#announcement_touser').attr('value',cb.getValue());
                          }
                        );
                    </script>
                    <?php $this->error_message("announcement_to")?>
                </div>
            </div>
            <div class="form-group <?php $this->error_class("announcement_to");?>">
                <label for="subject" class="col-sm-2 control-label">
                   <?php echo __("Announce to group:","announcement"); ?>
                </label>
                <div class="col-sm-10" id="togr">
                    <?php 
                         echo '<label class="checkbox-inline" style="padding-left:0px;">';
                            echo form_checkbox('togroup[]',100,null,'class="all"').__('All','announcement');
                         echo '</label>';
                    ?>
                    <?php
                        foreach($this->controller->announcement_model->list_role() as $role){?><label class="checkbox-inline"><?php  echo form_checkbox("togroup[]",$role['id'],null,'class="one"');?> <?php echo $role['role_name']; ?></label><?php } ?>
                    <script type="text/javascript">
                        $("#togroup").on('ifChecked', function(event) {
                            $("input[type='checkbox']", "#togr").iCheck("disable");
                            $("#togroup").iCheck('enable');
                        });
                        $("#togroup").on('ifUnchecked', function(event) {
                            $("input[type='checkbox']", "#togr").iCheck("enable");
                        });
                    </script>
                </div>
            </div>
            
            <div class="form-group <?php $this->error_class("announcement_content");?>">
                <label for="subject" class="col-sm-2 control-label">
                   <?php echo __("Content","announcement"); ?>
                     <span style="color: red;">*</span>
                </label>
                <div class="col-sm-10" id="econtent">
                    <?php echo form_textarea("announcement_content");?>
                    <?php $this->error_message("announcement_content")?>
                    <div id="err_content" class="help-block"></div>
                </div>
            </div>
            <div class="form-group <?php $this->error_class("announcement_status");?>">
                <label for="subject" class="col-sm-2 control-label">
                   <?php echo __("Status","announcement"); ?>
                </label>
                <div class="col-sm-10">
                    <?php
                        $checked    =   isset($checked)?$checked:true;
                    ?>
                    <label class="checkbox-inline" style="padding-left:0px;">
                        <?php echo form_checkbox('announcement_status','1',$checked); ?>
                        <?php echo __('Published','announcement'); ?>
                    </label>
                    <?php $this->error_message("announcement_status")?>
                </div>
            </div>
            <?php echo Pf::event()->trigger("filter","announcement-form"); ?>
            <?php echo Pf::event()->trigger("filter","announcement-adding-form"); ?>
        </form>
    </div>
</div>
<script>
$(document).ready(function(){
	$('.btn-index').css({display:'none'});
	$('.btn-edit').css({display:'none'});
	$('.btn-copy').css({display:'none'});
	$('.btn-add').css({display:'inline-block'});
	$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
	$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
});
function back_to_list(){
	$("#main-content").mask("<?php echo __('Loading...','announcement'); ?>");
	try{
	   tinymce.remove();
	}catch(e){}
	$('#main-content').load('<?php echo admin_url($this->action.'=index&token='); ?>',function(){
		$("#main-content").unmask();
		$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
	});
}
$('#publishedDate,#unpublishedDate').datetimepicker();
function Announcement_New(){
	$("#main-content").mask("<?php echo __('Loading...','announcement'); ?>");
	try{
	   tinymce.triggerSave(); 
        tinymce.remove();
    }catch(e){}
	$.post($('#frm_Announcement_New').attr('action'),$('#frm_Announcement_New').serialize(),function(obj){
		$("#main-content").unmask();
		if (obj.error == 1){
		    $.notification({type:"error",img:"",width:"400",content:"<i class='fa fa-times-circle fa-2x'></i> <?php echo __('There is an error, please check again!','announcement'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
			$('#main-content').html(obj.content);
			$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		    $('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
		}else if (obj.error == 0){
			$.notification({type:"success",img:"",width:"400",content:"<i class='fa fa-check fa-2x'></i> <?php echo __('Announcement is added successfully','announcement'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
			$('#main-content').load(obj.url,function(){
			$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		    $('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
			});
		}
	},'json');
}
</script>