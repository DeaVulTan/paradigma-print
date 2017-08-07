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
            <?php echo __('Category','portfolio'); ?>  <small><?php echo __('Copy','portfolio'); ?></small>
        </h3>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <form name="frm_Category_Copy" id="frm_Category_Copy" class="form-horizontal" role="form" action="<?php echo admin_url('token='.$this->token); ?>" method="post" onsubmit="return false;">
            
            <div class="form-group <?php $this->error_class("category_name");?>">
                <label for="subject" class="col-sm-2 control-label">
                   <?php echo __("Category name","portfolio"); ?>
                     <span style="color: red;">*</span>
                </label>
                <div class="col-sm-10">
                    <?php echo form_input("category_name");?>
                    <?php $this->error_message("category_name")?>
                </div>
            </div>

            <div class="form-group <?php $this->error_class("category_description");?>">
                <label for="subject" class="col-sm-2 control-label">
                   <?php echo __("Category description","portfolio"); ?>
                     
                </label>
                <div class="col-sm-10">
                    <?php echo form_textarea("category_description");?>
                    <?php $this->error_message("category_description")?>
                </div>
            </div>

            <div class="form-group <?php $this->error_class("category_status");?>">
                <label for="subject" class="col-sm-2 control-label">
                   <?php echo __("Status","portfolio"); ?>
                     
                </label>
                <div class="col-sm-10">
                    <label class="checkbox-inline" style="padding-left:0px;">
                        <?php echo form_checkbox('category_status','1'); ?>
                        <?php echo __('Published','portfolio'); ?>
                    </label>
                    <?php $this->error_message("category_status")?>
                </div>
            </div>

            <?php echo Pf::event()->trigger("filter","category-form"); ?>
            <?php echo Pf::event()->trigger("filter","category-copy-form"); ?>
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
	$("#main-content").mask("<?php echo __('Loading...','portfolio'); ?>");
	try{
       tinymce.remove();
    }catch(e){}
	$('#main-content').load('<?php echo admin_url($this->action.'=index&token='); ?>',function(){
		$("#main-content").unmask();
		$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
	});
}
function Category_Copy(){
	$("#main-content").mask("<?php echo __('Loading...','portfolio'); ?>");
	try{
       tinymce.triggerSave(); 
        tinymce.remove();
    }catch(e){}
	$.post($('#frm_Category_Copy').attr('action'),$('#frm_Category_Copy').serialize(),function(obj){
		$("#main-content").unmask();
		if (obj.error == 1){
		    $.notification({type:"error",img:"",width:"400",content:"<i class='fa fa-times-circle fa-2x'></i> <?php echo __('There was an error. Please try again!','portfolio'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
			$('#main-content').html(obj.content);
			$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		    $('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
		}else if (obj.error == 0){
			$.notification({type:"success",img:"",width:"400",content:"<i class='fa fa-check fa-2x'></i> <?php echo __('Category is copied successfully','portfolio'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
			$('#main-content').load(obj.url,function(){
				$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		        $('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
			});
		}
	},'json');
}
</script>