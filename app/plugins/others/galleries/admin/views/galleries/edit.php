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
            <?php echo __('Galleries','galleries'); ?> <small><?php echo __('Edit','galleries'); ?></small>
        </h3>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <form name="frm_Galleries_Edit" id="frm_Galleries_Edit" class="form-horizontal" role="form" action="<?php echo admin_url('token='.$this->token); ?>" method="post" onsubmit="return false;">
            
            <div class="form-group <?php $this->error_class("gallery_name");?>">
                <label for="subject" class="col-sm-2 control-label">
                   <?php echo __("Gallery name","galleries"); ?>
                     <span style="color: red;">*</span>
                </label>
                <div class="col-sm-10" id="econtent">
                    <?php echo form_input("gallery_name");?>
                    <?php $this->error_message("gallery_name")?>
                    <div id="show_error"></div>
                </div>
            </div>

            <div class="form-group <?php $this->error_class("gallery_status");?>">
                <label for="subject" class="col-sm-2 control-label">
                   <?php echo __("Status","galleries"); ?>
                     
                </label>
                <div class="col-sm-10" style="width:170px">
                    <?php $status_gallery = form_input("gallery_status");?>                 
                    <?php 
                    	$status_gallery = array();
                    	$status_gallery = $this->controller->galleries_model->get_status_gallery();
                    	echo form_dropdown('gallery_status',$status_gallery, array());
                    ?>
                </div>
            </div>

            <div class="form-group <?php $this->error_class("gallery_description");?>">
                <label for="subject" class="col-sm-2 control-label">
                   <?php echo __("Description","galleries"); ?>
                </label>
                <div class="col-sm-10">
                    <?php echo form_textarea("gallery_description");?>
                    <?php $this->error_message("gallery_description")?>
                </div>
            </div>
            <input type="hidden" name="cover" id="cover" value="<?php echo $this->controller->post->gallery_cover;?>" /> 
			<div id="append"  >
            	  <?php
            	  $data = array();
            	  $data = $this->controller->post->gallery_data;       	
                    if (!empty($data)) {
                        $data = unserialize(($data));
                        for ($i = 0; $i < count($data[0]); $i++) {
                            $id = uniqid() . rand();
                            ?>
                            <div class="form-group custom-cols">
                                <div class="col-sm-12 control-label">
                                    <div class="col-sm-2">
                                        <div class="input-append">
                                            <div class='col-sm-10 no-pad col-xs-offset-2'><img class="width100" src="<?php echo (!empty($data[0][$i])&& is_file(urldecode(ABSPATH.'/'.$data[0][$i])))? RELATIVE_PATH.'/'.$data[0][$i]:  no_image(); ?>" id='img-<?php echo $id; ?>' />
                                                <input name="image_url[]" id="<?php echo $id; ?>" type="text" value="<?php echo $data[0][$i]; ?>" style='display: none'>
                                                <a class="col-sm-3 width24 thumbcheck" data-toggle="tooltip" data-placement="bottom" title="<?php echo __('Gallery Cover','galleries'); ?>" data-id="<?php echo $id; ?>"><i class="fa <?php echo ($data[0][$i] == $this->controller->post->gallery_cover)?'fa-dot-circle-o':'fa-circle-o'; ?>"></i></a>
                                                <a class="col-sm-8 no-pad boxGetFile" data-id="<?php echo $id; ?>" href="<?php echo RELATIVE_PATH . "/app/plugins/default/media/filemanager/dialog.php?type=1&field_id=" . $id; ?>"><h6><?php echo __('select image', 'galleries'); ?></h6></a>
                                                
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-7 padding-left-8">
                                        <input type="text" name='image_title[]' class="form-control" placeholder='<?php echo __('Image title','galleries'); ?>' value="<?php echo $data[1][$i]; ?>" />
                                        <input type="text" name='image_alt[]' class="form-control" placeholder='<?php echo __('Image alt','galleries'); ?>' value="<?php echo $data[2][$i]; ?>" />
                                    </div>
                                    <div class="col-sm-1"><a href="#" onclick="return false;" class="glyphicon glyphicon-remove btn-remove"></a></div>
                                </div>
                            </div>
                            <?php
                        }
                    }
                    ?> 
           	</div>
          	<div class="form-group">
           		<label class="col-sm-2 control-label"></label>
		   		<div class="col-sm-9">
            		<a class='btn btn-primary btn-xs' id='btnAdd'><?php echo __('Add Image', 'galleries'); ?></a>
            	</div>
           	</div>
            <?php echo Pf::event()->trigger("filter","gallery-form"); ?>
            <?php echo Pf::event()->trigger("filter","gallery-editing-form"); ?>
        </form>
    </div>
</div>
<div id='baseurl' style="display:none"><?php echo site_url(false) . RELATIVE_PATH; ?>/app/plugins/default/media/filemanager/dialog.php?type=1&field_id=</div>
<div style="display: none;" id='form-group'>
    
    <div class="form-group custom-cols">
        <div class="col-sm-12 control-label">
            <div class="col-sm-2">
                <div class="input-append">
                    <div class='col-sm-10 no-pad col-xs-offset-2'><img src="<?php echo no_image(); ?>" id='upload-img' class="width100" />                  	
                        <input name="image_url[]" id="upload" type="text" value="" style='display: none'>
                        <a class="col-sm-3 width24 thumbcheck" data-toggle="tooltip" data-placement="bottom" title="<?php echo __('Gallery Cover','galleries'); ?>" data-id=""><i class="fa fa-circle-o"></i></a> 
                        <a class="col-sm-8 no-pad boxGetFile"><h6><?php echo __('select image', 'galleries'); ?></h6></a>
                    </div>

                </div>
            </div>
            <div class="col-sm-7 padding-left-8">
                <input type="text" name='image_title[]' class="form-control" placeholder='<?php echo __('Image title','galleries'); ?>' />
                <input type="text" name='image_alt[]' class="form-control" placeholder='<?php echo __('Image alt','galleries'); ?>' />
            </div>
            <div class="col-sm-1">
                <a href="#" onclick="return false;" class="glyphicon glyphicon-remove btn-remove"></a>
            </div>
        </div>
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
	$("#main-content").mask("<?php echo __('Loading...','galleries'); ?>");
	try{
       tinymce.remove();
    }catch(e){}
	$('#main-content').load('<?php echo admin_url($this->action.'=index&token='); ?>',function(){
		$("#main-content").unmask();
		$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
	});
}
function Galleries_Edit(){
	gallery_name = $("#gallery_name").val();
	if(gallery_name == ''){
		$("#gallery_name").parent().parent().addClass("has-error");
		$("#show_error").html('<span class="help-block">The <span class="field"></span>field is required</span>');
		$.notification({type:"error",img:"",width:"400",content:"<i class='fa fa-times-circle fa-2x'></i> <?php echo __('There is an error, please check again!','galleries'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
		return false;
	}
	$("#main-content").mask("<?php echo __('Loading...','galleries'); ?>");
	try{
       tinymce.triggerSave(); 
        tinymce.remove();
    }catch(e){}
	$.post($('#frm_Galleries_Edit').attr('action'),$('#frm_Galleries_Edit').serialize(),function(obj){
		$("#main-content").unmask();
		if (obj.error == 1){	
			$('#main-content').html(obj.content);
			$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		    $('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
		}else if (obj.error == 0){
			$.notification({type:"success",img:"",width:"400",content:"<i class='fa fa-check fa-2x'></i> <?php echo __('Gallery is updated successfully','galleries'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
			$('#main-content').load(obj.url,function(){
				$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		        $('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
			});
		}
	},'json');
}

function Galleries_Apply(){
	gallery_name = $("#gallery_name").val();
	if(gallery_name == ''){
		$("#gallery_name").parent().parent().addClass("has-error");
		$("#show_error").html('<span class="help-block">The <span class="field"></span>field is required</span>');
		$.notification({type:"error",img:"",width:"400",content:"<i class='fa fa-times-circle fa-2x'></i> <?php echo __('There is an error, please check again!','galleries'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
		return false;
	}
	$("#main-content").mask("<?php echo __('Loading...','galleries'); ?>");
	try{
       tinymce.triggerSave(); 
        tinymce.remove();
    }catch(e){}
	$.post($('#frm_Galleries_Edit').attr('action'),$('#frm_Galleries_Edit').serialize(),function(obj){
		$("#main-content").unmask();
		$('#main-content').html(obj.content);
		if (obj.error == 0){
			$.notification({type:"success",img:"",width:"400",content:"<i class='fa fa-check fa-2x'></i> <?php echo __('Gallery is updated successfully','galleries'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
		}else{
	       
		}
		$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
	},'json');
}
</script>
<script type="text/javascript" src="<?php echo RELATIVE_PATH;?>/app/plugins/others/galleries/admin/assets/galleries.js"></script>