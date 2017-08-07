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
            <?php echo __('Testimonials','testimonials'); ?> <small><?php echo __('Copy','testimonials'); ?></small>
        </h3>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <form name="frm_Testimonials_Copy" id="frm_Testimonials_Copy" class="form-horizontal" role="form" action="<?php echo admin_url('token='.$this->token); ?>" method="post" onsubmit="return false;">
            <div class="row pad">
                <div class="col-md-12" id="col-md-12">
                    <div class="row mar0 pad10-0 form-group <?php $this->error_class("testimonial_name");?>">
                        <div class="col-sm-2 control-label ">
                                <label for="subject">
                                   <?php echo __("Customer name","testimonials"); ?>
                                     <span style="color: red;">*</span>
                                </label>
                        </div>
                        <div class="col-sm-10">
                            <?php echo form_input("testimonial_name");?>
                            <?php $this->error_message("testimonial_name")?>
                        </div>
                    </div>
                    <div class="row mar0 pad10-0 form-group <?php $this->error_class("testimonial_info");?>">
                        <div class="col-sm-2 control-label ">
                            <label for="subject">
                               <?php echo __("Customer info","testimonials"); ?>
                            </label>
                        </div>
                        <div class="col-sm-10">
                            <?php echo form_input("testimonial_info");?>
                            <?php $this->error_message("testimonial_info")?>
                        </div>
                    </div>
                    <div class="row mar0 pad10-0 form-group <?php $this->error_class("testimonial_avatar");?>">
                        <div class="col-sm-2 control-label ">
                            <label for="subject">
                               <?php echo __("Image","testimonials"); ?>
                            </label>
                        </div>
                        <div class="col-sm-10">
                            <div class="col-md-3 pad0" id="fix-image">
                                <img class="width100" src="<?php echo(!empty($avatar_url)&& is_file(urldecode(ABSPATH.'/'.$avatar_url))) ? '../'.$avatar_url : no_image(); ?>" id='img-avatar' width='100%' />
                                <?php echo form_media("testimonial_avatar");?>
                                <?php $this->error_message("testimonial_avatar")?>
                            </div>

                        </div>
                    </div>
                    <div class="row mar0 pad10-0 form-group <?php $this->error_class("testimonial_content");?>">
                        <div class="col-sm-2 control-label ">
                            <label for="subject" class="control-label">
                               <?php echo __("Content","testimonials"); ?>
                                 <span style="color: red;">*</span>
                            </label>
                        </div>
                        <div class="col-sm-10">
                            <?php echo form_textarea("testimonial_content");?>
                            <?php $this->error_message("testimonial_content")?>
                        </div>
                    </div>
                    <?php echo Pf::event()->trigger("filter","testimonials-form"); ?>
                    <?php echo Pf::event()->trigger("filter","testimonials-copy-form"); ?>
                </div>
            </div>
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

	avatar = $('#testimonial_avatar').val();
	if(avatar == ''){
		$('#img-avatar').attr('src','<?php echo no_image();?>');
	}else{
		$('#img-avatar').attr('src','<?php echo RELATIVE_PATH;?>/'+avatar);
	}
	$('.input-group-btn a').fancybox({
        'width': '75%',
        'height': '90%',
        'autoScale': false,
        'transitionIn': 'none',
        'transitionOut': 'none',
        'type': 'iframe',
        onClosed: function() {
            var imgurl  =   $('#testimonial_avatar').val();
            if (imgurl.length > 0) {
                $('#img-avatar').attr('src', '../'+imgurl);
            }
        }
    });
});
function back_to_list(){
	$("#main-content").mask("<?php echo __('Loading...','testimonials'); ?>");
	try{
       tinymce.remove();
    }catch(e){}
	$('#main-content').load('<?php echo admin_url($this->action.'=index&token='); ?>',function(){
		$("#main-content").unmask();
		$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
	});
}
function Testimonials_Copy(){
	$("#main-content").mask("<?php echo __('Loading...','testimonials'); ?>");
	try{
       tinymce.triggerSave(); 
        tinymce.remove();
    }catch(e){}
	$.post($('#frm_Testimonials_Copy').attr('action'),$('#frm_Testimonials_Copy').serialize(),function(obj){
		$("#main-content").unmask();
		if (obj.error == 1){
		    $.notification({type:"error",img:"",width:"400",content:"<i class='fa fa-times-circle fa-2x'></i> <?php echo __('There is an error, please check again!','testimonials'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
			$('#main-content').html(obj.content);
			$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		    $('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
		}else if (obj.error == 0){
			$.notification({type:"success",img:"",width:"400",content:"<i class='fa fa-check fa-2x'></i> <?php echo __('Testimonial is copied successfully','testimonials'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
			$('#main-content').load(obj.url,function(){
				$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		        $('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
			});
		}
	},'json');
}
$("#fix-image > div > span > a").text("Choose Image");
</script>