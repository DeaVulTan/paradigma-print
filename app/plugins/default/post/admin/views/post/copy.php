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
            post  <small><?php echo __('Copy','post'); ?></small>
        </h3>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <form name="frm_Post_Copy" id="frm_Post_Copy" class="form-horizontal" role="form" action="<?php echo admin_url('token='.$this->token); ?>" method="post" onsubmit="return false;">
            
            <div class="form-group<?php $this->error_class("post_title");?>">
                <label for="subject" class="col-sm-2 control-label">
                   <?php echo __("Title ","post"); ?> <span style="color: red;">*</span>
                     
                </label>
                <div class="col-sm-10">
                    <?php echo form_input("post_title");?>
                    <?php $this->error_message("post_title")?>
                </div>
            </div>

            <div class="row" style="margin: 0px;">
                <div class="form-group<?php $this->error_class("post_category");?> col-sm-6">
                    <label for="subject" class="col-sm-4 control-label">
                       <?php echo __("Category","post"); ?> <span style="color: red;">*</span>
                    </label>
                    <div class="col-sm-8" >
                        <?php
                            $post_category_select = array ();
                            $post_category_select [""] = "No parent";
                            $category_select = $this->controller->post->list_all_category;
                            if($category_select != NULL){
                                foreach($category_select as $key => $value){
                                    $post_category_select[$key] = $value;
                                }
                            }
                            $post_category_select =  $post_category_select;
                            echo form_dropdown ("post_category", $post_category_select);
                        ?>
                        <?php $this->error_message("post_category")?>
                    </div>
                </div>
    
                <div class="form-group<?php $this->error_class("post_published_date");?> col-sm-6 pull-right" style="padding-right: 0px;">
                    <label for="subject" class="col-sm-4 control-label">
                       <?php echo __("Published date","post"); ?>
                        
                    </label>
                    <div class="col-sm-8" >
                        <div class="input-group date" id="publishedDate" data-date-format="YYYY-MM-DD HH:mm:ss">
                            <?php
                                echo form_input("post_published_date","",$this->controller->post_model->elements_value["post_published_date"]);
                            ?>
                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                        <?php $this->error_message("post_published_date")?>
                    </div>
                </div>
            </div>
            <div class="form-group<?php $this->error_class("post_status");?> col-sm-6">
                <label for="subject" class="col-sm-4 control-label">
                   <?php echo __("Status","post"); ?>
                </label>
                <div class="col-sm-8" >
                    <?php
                    $post_status_select = array ();
                    $post_status_select = $this->controller->post_model->get_status_post ();
                    echo form_dropdown ( 'post_status', $post_status_select, array () );
                    ?>
                    <?php $this->error_message("post_status")?>
                </div>
            </div>
            <div class="form-group<?php $this->error_class("post_unpublished_date");?> col-sm-6 pull-right" style="padding-right: 0px;">
                <label for="subject" class="col-sm-4 control-label">
                   <?php echo __("Unpublished date","post"); ?>
                    
                </label>
                <div class="col-sm-8" >
                    <div class="input-group date" id="unpublishedDate" data-date-format="YYYY-MM-DD HH:mm:ss">
                        <?php
                            echo form_input("post_unpublished_date","",$this->controller->post_model->elements_value["post_unpublished_date"]);
                        ?>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                    <?php $this->error_message("post_unpublished_date")?>
                </div>
            </div>
            <div class="form-group<?php $this->error_class("post_allow_comment");?> col-sm-6">
                <label for="subject" class="col-sm-4 control-label">
                   <?php echo __("Allow comment","post"); ?>
                </label>
                <div class="col-sm-8" >
                    <?php
                    $post_allow_comment_select = array ();
                    $post_allow_comment_select = $this->controller->post_model->get_allow_comment ();
                    echo form_dropdown ( 'post_allow_comment', $post_allow_comment_select, array () );
                    ?>
                    <?php $this->error_message("post_allow_comment")?>
                </div>
            </div>
            <div class="form-group<?php $this->error_class("post_allow_rating");?> col-sm-6 pull-right" style="padding-right: 0px;">
                <label for="subject" class="col-sm-4 control-label">
                   <?php echo __("Allow rating","post"); ?>
                </label>
                <div class="col-sm-8">
                    <?php
                    $post_allow_rating_select = array ();
                    $post_allow_rating_select = $this->controller->post_model->get_allow_rating ();
                    echo form_dropdown ( 'post_allow_rating', $post_allow_rating_select, array () );
                    ?>
                    <?php $this->error_message("post_allow_rating")?>
                </div>
            </div>
           
            <div class="form-group<?php $this->error_class("post_thumbnail");?> ">
                <label for="subject" class="col-sm-2 control-label">
                   <?php echo __("Thumbnail","post"); ?>
                     
                </label>
                <div class="col-sm-10">
                    <?php echo form_media("post_thumbnail");?>
                    <?php $this->error_message("post_thumbnail")?>
                </div>
            </div>

            <div class="form-group<?php $this->error_class("post_content");?>">
                <label for="subject" class="col-sm-2 control-label">
                   <?php echo __("Content","post"); ?> <span style="color: red;">*</span>
                     
                </label>
                <div class="col-sm-10">
                    <?php echo form_editor("post_content");?>
                    <?php $this->error_message("post_content")?>
                </div>
            </div>
            
            <div class="row" style="margin: 0px;">
                <div class="form-group">
                    <label for="subject" class="col-sm-2 control-label">
                       <?php echo __("Tags","post"); ?>
                         
                    </label>
                    <div class="col-sm-10">
                        <?php echo form_input(array('name' => 'tags', 'data-role' => 'tagsinput', 'placeholder' => __('Add tags', 'post'), 'value' => $this->controller->post->list_all_tag, 'tabindex' => 9)); ?>
                        <!-- button type="button" id="btnTagsManager" class="btn btn-warning btn-sm"><?php echo __('Tags manager', 'post') ?></button -->
                        <p class="help-block">
                            <?php
                            printf(__('Notice: You are allowed to use up to %s tag and you can add multiple tags at once. Each tag separated by a comma', 'post'), $this->controller->post->num_tags);
                            ?>
                        </p>
                    </div>
                </div>
            </div>

            <?php echo Pf::event()->trigger("filter","post-form"); ?>
            <?php echo Pf::event()->trigger("filter","post-copy-form"); ?>
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
function Post_Copy(){
	$("#main-content").mask("<?php echo __('Loading...','post'); ?>");
	try{
       tinymce.triggerSave(); 
        tinymce.remove();
    }catch(e){}
	$.post($('#frm_Post_Copy').attr('action'),$('#frm_Post_Copy').serialize(),function(obj){
		$("#main-content").unmask();
		if (obj.error == 1){
		    $.notification({type:"error",img:"",width:"400",content:"<i class='fa fa-times-circle fa-2x'></i> <?php echo __('There is an error, please check again!','post'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
			$('#main-content').html(obj.content);
			$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		    $('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
		}else if (obj.error == 0){
			$.notification({type:"success",img:"",width:"400",content:"<i class='fa fa-check fa-2x'></i> <?php echo __('Post is copied successfully','post'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
			$('#main-content').load(obj.url,function(){
				$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		        $('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
			});
		}
	},'json');
}
</script>

<link href="<?php echo RELATIVE_PATH;?>/app/plugins/default/post/admin/assets/post.css" rel="stylesheet">
<script src="<?php echo RELATIVE_PATH;?>/media/assets/bootbox/js/bootbox.min.js"></script>
<link href="<?php echo RELATIVE_PATH;?>/media/assets/bootstrap-tagsinput/css/bootstrap-tagsinput.css" rel="stylesheet">
<script src="<?php echo RELATIVE_PATH;?>/media/assets/bootstrap-tagsinput/js/bootstrap-tagsinput.min.js"></script> 
<script src="<?php echo RELATIVE_PATH;?>/lib/common/plugin/assets/base.js"></script>
<script src="<?php echo RELATIVE_PATH;?>/app/plugins/default/post/admin/assets/post.js"></script>