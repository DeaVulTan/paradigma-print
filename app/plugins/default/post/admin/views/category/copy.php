<?php defined ( 'PF_VERSION' ) or header ( 'Location:404.html' ); ?>
<div class="row" style="margin-bottom: 20px; margin-top: -15px;">
    <div class="col-sm-12">
        <h3>
            <?php if (!empty($this->menu_settings[$this->controller->get->{"admin-page"}])){ ?>
                <?php echo '<i class="' . $this->menu_settings[$this->controller->get->{"admin-page"}]['icon'] . '" style="color:'.$this->menu_settings[$this->controller->get->{"admin-page"}]['icon_color'].'"></i>'; ?>
            <?php }else{ ?>
                <?php global $_admin_menu; ?>
                <?php echo (!empty($_admin_menu[$this->controller->get->{"admin-page"}]['icon_class'])) ? '<i class="' . $_admin_menu[$this->controller->get->{"admin-page"}]['icon_class'] . '"></i>' : ''; ?>
            <?php } ?>
            <?php echo __('Category','post'); ?>  <small><?php echo __('Copy','post'); ?></small>
        </h3>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <form name="frm_Category_Copy" id="frm_Category_Copy"
            class="form-horizontal" role="form"
            action="<?php echo admin_url('token='.$this->token); ?>"
            method="post" onsubmit="return false;">

            <div
                class="form-group <?php $this->error_class("category_name");?>">
                <label for="subject" class="col-sm-2 control-label">
                   <?php echo __("Name","post"); ?>
                     <span style="color: red;">*</span>
                </label>
                <div class="col-sm-10">
                    <?php echo form_input("category_name");?>
                    <?php $this->error_message("category_name")?>
                </div>
            </div>

            <div
                class="form-group <?php $this->error_class("category_status");?>">
                <label for="subject" class="col-sm-2 control-label">
                   <?php echo __("Status","post"); ?>
                     
                </label>
                <div class="col-sm-10">
                    <?php
                    $category_status_select = array ();
                    $category_status_select = $this->controller->category_model->get_status_category ();
                    echo form_dropdown ( 'category_status', $category_status_select, array () );
                    ?>
                </div>
            </div>

            <div
                class="form-group <?php $this->error_class("category_parent");?>">
                <label for="subject" class="col-sm-2 control-label">
                   <?php echo __("Parent","post"); ?>
                     
                </label>
                <div class="col-sm-10">
                    <select name="category_parent" class="form-control">
                        <option value="0" selected="selected">No parent</option>
                        <?php
                            if(isset($this->controller->post->list_category) && $this->controller->post->list_category != NULL){
                                foreach($this->controller->post->list_category as $items){
                        ?>
                                    <option value="<?php echo $items['id']; ?>"<?php echo $this->controller->post->category_parent == $items['id'] ? 'selected="selected"' : '' ?>><?php echo $items['category_name']; ?></option>
                        <?php } }?>
                    </select>
                    <?php $this->error_message("category_parent")?>
                </div>
            </div>

            <div
                class="form-group <?php $this->error_class("category_description");?>">
                <label for="subject" class="col-sm-2 control-label">
                   <?php echo __("Description","post"); ?>
                     <span style="color: red;">*</span>
                </label>
                <div class="col-sm-10">
                    <?php echo form_textarea("category_description");?>
                    <?php $this->error_message("category_description")?>
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
	$("#main-content").mask("<?php echo __('Loading...','post'); ?>");
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
	$("#main-content").mask("<?php echo __('Loading...','post'); ?>");
	try{
       tinymce.triggerSave(); 
        tinymce.remove();
    }catch(e){}
	$.post($('#frm_Category_Copy').attr('action'),$('#frm_Category_Copy').serialize(),function(obj){
		$("#main-content").unmask();
		if (obj.error == 1){
		    $.notification({type:"error",img:"",width:"400",content:"<i class='fa fa-times-circle fa-2x'></i> <?php echo __('There is an error, please check again!','post'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
			$('#main-content').html(obj.content);
			$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		    $('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
		}else if (obj.error == 0){
			$.notification({type:"success",img:"",width:"400",content:"<i class='fa fa-check fa-2x'></i> <?php echo __('Category is copied successfully','post'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
			$('#main-content').load(obj.url,function(){
				$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		        $('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
			});
		}
	},'json');
}
</script>