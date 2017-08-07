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
            <?php echo __('Comment','comment'); ?>  <small><?php echo __('Edit','comment'); ?></small>
        </h3>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <form name="frm_Comment_Edit" id="frm_Comment_Edit" class="form-horizontal" role="form" action="<?php echo admin_url('token='.$this->token); ?>" method="post" onsubmit="return false;">
            <div class="row pad">
                <div class="col-md-12">
                    <div class="row form-group <?php $this->error_class("comment_content");?>">
                        <div class="col-md-2 ">
                                <label for="subject" class="control-label">
                                   <?php echo __("Content","comment"); ?>
                                     <span style="color: red;">*</span>
                                </label>
                        </div>
                        <div class="col-md-10">
                            <?php echo form_textarea("comment_content");?>
                            <?php $this->error_message("comment_content")?>
                        </div>
                        <?php echo Pf::event()->trigger("filter","comment-form"); ?>
                        <?php echo Pf::event()->trigger("filter","comment-editing-form"); ?>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
$(document).ready(function(){
	$('.btn-index').css({display:'none'});
	$('.btn-edit').css({display:'inline-block'});
	$('.btn-copy').css({display:'none'});
	$('.btn-add').css({display:'none'});
	$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
});

function back_to_list(){
	$("#main-content").mask("<?php echo __('Loading...','comment'); ?>");
	try{
       tinymce.remove();
    }catch(e){}
	$('#main-content').load('<?php echo admin_url($this->action.'=index&token='); ?>',function(){
		$("#main-content").unmask();
		$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
	});
}
function Comment_Edit(){
	$("#main-content").mask("<?php echo __('Loading...','comment'); ?>");
	try{
       tinymce.triggerSave(); 
        tinymce.remove();
    }catch(e){}
	$.post($('#frm_Comment_Edit').attr('action'),$('#frm_Comment_Edit').serialize(),function(obj){
		$("#main-content").unmask();
		if (obj.error == 1){
		    $.notification({type:"error",img:"",width:"400",content:"<i class='fa fa-times-circle fa-2x'></i> <?php echo __('There is an error, please check again!','comment'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
			$('#main-content').html(obj.content);
			$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		    $('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
		}else if (obj.error == 0){
			$.notification({type:"success",img:"",width:"400",content:"<i class='fa fa-check fa-2x'></i> <?php echo __('Comment is updated successfully','comment'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
			$('#main-content').load(obj.url,function(){
				$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		        $('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
			});
		}
	},'json');
}

function Comment_Apply(){
	$("#main-content").mask("<?php echo __('Loading...','comment'); ?>");
	try{
       tinymce.triggerSave(); 
        tinymce.remove();
    }catch(e){}
	$.post($('#frm_Comment_Edit').attr('action'),$('#frm_Comment_Edit').serialize(),function(obj){
		$("#main-content").unmask();
		$('#main-content').html(obj.content);
		if (obj.error == 0){
			$.notification({type:"success",img:"",width:"400",content:"<i class='fa fa-check fa-2x'></i> <?php echo __('Comment is updated successfully','comment'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
		}else{
	        $.notification({type:"error",img:"",width:"400",content:"<i class='fa fa-times-circle fa-2x'></i> <?php echo __('There is an error, please check again!','comment'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
		}
		$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
	},'json');
}
</script>