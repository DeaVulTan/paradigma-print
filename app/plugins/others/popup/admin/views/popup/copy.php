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
            <?php echo __('Popup','popup'); ?>  <small><?php echo __('Copy','popup'); ?></small>
        </h3>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <form name="frm_Popup_Copy" id="frm_Popup_Copy" class="form-horizontal" role="form" action="<?php echo admin_url('token='.$this->token); ?>" method="post" onsubmit="return false;">
            
            <div class="form-group <?php $this->error_class("popup_title");?>">
                <label for="subject" class="col-sm-2 control-label">
                   <?php echo __("Title","popup"); ?>
                     <span style="color: red;">*</span>
                </label>
                <div class="col-sm-10">
                    <?php echo form_input("popup_title");?>
                    <?php $this->error_message("popup_title")?>
                </div>
            </div>

            <div class="form-group <?php $this->error_class("popup_url");?>">
                <label for="subject" class="col-sm-2 control-label">
                   <?php echo __("Link text","popup"); ?>
                     <span style="color: red;">*</span>
                </label>
                <div class="col-sm-10">
                    <?php echo form_input("popup_url");?>
                    <?php $this->error_message("popup_url")?>
                </div>
            </div>

            <div class="form-group <?php $this->error_class("popup_type");?>">
                <label for="subject" class="col-sm-2 control-label">
                   <?php echo __("Type","popup"); ?>
                     
                </label>
                <div class="col-sm-10">
                    <?php
                        $popup_type_select = array();
                        $popup_type_select = array_merge($popup_type_select,$this->controller->popup_model->elements_value["popup_type"]);
                        echo form_dropdown("popup_type",$popup_type_select);?>
                    <?php $this->error_message("popup_type")?>
                </div>
            </div>

            <div class="row" style="margin: 0px;">
                <div class="form-group<?php $this->error_class("popup_width");?> col-sm-6">
                    <label for="subject" class="col-sm-4 control-label">
                       <?php echo __("Width","popup"); ?>
                    </label>
                    <div class="col-sm-8">
                        <div class="col-md-12 input-group">
                            <?php echo form_input(array('name' => 'popup_width', 'class' => 'form-control', 'id' => 'popup_width'));?>
                        </div>
                        <div class="col-md-12"  style="padding:0px">
                            <?php $this->error_message("popup_width")?>
                        </div>
                    </div>
                    
                </div>
                <div class="form-group<?php $this->error_class("popup_height");?> col-sm-6 pull-right" style="padding-right: 0px;">
                    <label for="subject" class="col-sm-4 control-label">
                       <?php echo __("Height","popup"); ?>
                    </label>
                    <div class="col-sm-8">
                        <div class="col-md-12 input-group">
                            <?php echo form_input(array('name' => 'popup_height', 'class' => 'form-control', 'id' => 'popup_height'));?>
                        </div>
                        <div class="col-md-12"  style="padding:0px">
                            <?php $this->error_message("popup_height")?>
                        </div>
                    </div>
                    
                </div>
            </div>
            
            <div class="form-group <?php $this->error_class("popup_published_date");?> " >
                <label for="subject" class="col-sm-2 control-label">
                   <?php echo __("Publish Date","popup"); ?>
                     
                </label>
               <div class="col-sm-10" >
                    <div class="input-group date" id="publishedDate" data-date-format="YYYY-MM-DD HH:mm:ss">
                        <?php echo form_input("popup_published_date");?>
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                    <?php $this->error_message("popup_published_date")?>
                </div>
                <script>
                    $(document).ready(function(){
                        $("#publishedDate").datetimepicker({pickTime: false});
                    });   
                </script>
            </div>
            
            <div class="form-group <?php $this->error_class("popup_unpublished_date");?> " >
                <label for="subject" class="col-sm-2 control-label">
                   <?php echo __("Unpublish Date","popup"); ?>
                     
                </label>
               <div class="col-sm-10" >
                    <div class="input-group date" id="unpublishedDate" data-date-format="YYYY-MM-DD HH:mm:ss">
                        <?php echo form_input("popup_unpublished_date");?>
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                    <?php $this->error_message("popup_unpublished_date")?>
                </div>
                <script>
                    $(document).ready(function(){
                        $("#unpublishedDate").datetimepicker({pickTime: false});
                    });   
                </script>
            </div>

            <div class="form-group <?php $this->error_class("popup_description");?>">
                <label for="subject" class="col-sm-2 control-label">
                   <?php echo __("Content","popup"); ?>
                     
                </label>
                <div class="col-sm-10">
                    <?php echo form_editor("popup_description");?>
                    <?php $this->error_message("popup_description")?>
                </div>
            </div>

            <?php echo Pf::event()->trigger("filter","popup-form"); ?>
            <?php echo Pf::event()->trigger("filter","popup-copy-form"); ?>
        </form>
    </div>
</div>
<script>
$(document).ready(function(){
	$('.btn-index').css({display:'none'});
	$('.btn-edit').css({display:'none'});
	$('.btn-copy').css({display:'inline-block'});
	$('.btn-add').css({display:'none'});
	$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
});
function back_to_list(){
	$("#main-content").mask("<?php echo __('Loading...','popup'); ?>");
	try{
       tinymce.remove();
    }catch(e){}
	$('#main-content').load('<?php echo admin_url($this->action.'=index&token='); ?>',function(){
		$("#main-content").unmask();
		$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
	});
}
function Popup_Copy(){
	$("#main-content").mask("<?php echo __('Loading...','popup'); ?>");
	try{
       tinymce.triggerSave(); 
        tinymce.remove();
    }catch(e){}
	$.post($('#frm_Popup_Copy').attr('action'),$('#frm_Popup_Copy').serialize(),function(obj){
		$("#main-content").unmask();
		if (obj.error == 1){
		    $.notification({type:"error",img:"",width:"400",content:"<i class='fa fa-times-circle fa-2x'></i> <?php echo __('There is an error, please check again!','popup'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
			$('#main-content').html(obj.content);
			$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		    $('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
		}else if (obj.error == 0){
			$.notification({type:"success",img:"",width:"400",content:"<i class='fa fa-check fa-2x'></i> <?php echo __('Item is copied successfully','popup'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
			$('#main-content').load(obj.url,function(){
				$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		        $('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
			});
		}
	},'json');
}
</script>