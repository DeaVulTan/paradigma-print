<?php defined ( 'PF_VERSION' ) or header ( 'Location:404.html' ); ?>
<?php 
    $portfolio_category = $this->controller->post->portfolio_category;
    $images = isset($images) && is_array($images) ? $images : array();
    $images = isset($this->controller->post->portfolio_avatar) && is_array($this->controller->post->portfolio_avatar) ? $this->controller->post->portfolio_avatar:$images;
?>
<div class="row" style="margin-bottom: 20px;margin-top:-15px;">
    <div class="col-sm-12">
        <h3>
            <?php if (!empty($this->menu_settings[$this->controller->get->{"admin-page"}])){ ?>
                <?php echo '<i class="' . $this->menu_settings[$this->controller->get->{"admin-page"}]['icon'] . '" style="color:'.$this->menu_settings[$this->controller->get->{"admin-page"}]['icon_color'].'"></i>'; ?>
            <?php }else{ ?>
                <?php global $_admin_menu; ?>
                <?php echo (!empty($_admin_menu[$this->controller->get->{"admin-page"}]['icon_class'])) ? '<i class="' . $_admin_menu[$this->controller->get->{"admin-page"}]['icon_class'] . '"></i>' : ''; ?>
            <?php } ?>
            <?php echo __('Portfolios','portfolio'); ?>  <small><?php echo __('Edit','portfolio'); ?></small>
        </h3>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <form name="frm_Portfolio_Edit" id="frm_Portfolio_Edit" class="form-horizontal" role="form" action="<?php echo admin_url('token='.$this->token); ?>" method="post" onsubmit="return false;">
            
            <div class="form-group <?php $this->error_class("portfolio_name");?>">
                <label for="subject" class="col-sm-2 control-label">
                   <?php echo __("Portfolio name","portfolio"); ?>
                     <span style="color: red;">*</span>
                </label>
                <div class="col-sm-10">
                    <?php echo form_input("portfolio_name");?>
                    <?php $this->error_message("portfolio_name")?>
                </div>
            </div>

            <div class="form-group <?php $this->error_class("portfolio_category");?>">
                <label for="subject" class="col-sm-2 control-label">
                   <?php echo __("Category","portfolio"); ?>
                     <span style="color: red;">*</span>
                </label>
                <div class="col-sm-10">
                    <select class="form-control" name="portfolio_category">
                        <option value="">--------</option>
                       <?php 
                            foreach ($this->controller->category_model->list_cate() as $cate){
                                   if($portfolio_category == $cate['id']){
                                       echo '<option value='.$cate['id'].' selected="selected">'.$cate['category_name'].'</option>';
                                   }else{
                                       echo '<option value='.$cate['id'].'>'.$cate['category_name'].'</option>';
                                   }
                            }
                       ?>
                    </select>
                    <?php $this->error_message("portfolio_category")?>
                </div>
            </div>

            
            <div class="form-group <?php $this->error_class("portfolio_avatar");?>">
                <label for="subject" class="col-sm-2 control-label">
                   <?php echo __("Portfolio image","portfolio"); ?>
                     <span style="color: red;">*</span>
                </label>
                <div class="col-sm-10">
                   <div class="col-sm-12" style="padding-right:0px;">
                        <a href="<?php echo RELATIVE_PATH . "/app/plugins/default/media/filemanager/dialog.php?type=1&field_id=image_select"; ?>" class="btn btn-primary btn-xs pull-right" id="btnAdd">Add Image</a>
                        <input type="hidden" id="image_select"/>
                        <?php
                               $portfolio_avatar = isset($portfolio_avatar) ? $portfolio_avatar : null;
                                $portfolio_avatar = isset($this->controller->post->portfolio_avatar) ? $this->controller->post->portfolio_avatar : $portfolio_avatar;
                        ?>
                        <input type="hidden" id="set_thumb" name="avatar" value="">
                   </div>
                   <div class="col-sm-12">
                            <div class="image_append ">
                            <div id="maindata">
                               <?php if(count($images)):?>
                                    <?php foreach($images as $key=>$rows):?>
                                        <div class="thumbview-gal thumbview">
                                            <div class="inner">
                                                   <span class="control">
                                                      <a href="<?php echo RELATIVE_PATH . "/app/plugins/default/media/filemanager/dialog.php?type=1&field_id=tmp_".$key; ?>"
                                                         data-id="<?php echo $key;?>" rel="#" class="open_media btn btn-success btn-xs">
                                                          <i class="fa fa-file-image-o"></i>
                                                          <input type="hidden" id="tmp_<?php echo $key;?>"/>
                                                      </a>
                                                      <a href="javascript:void(0);" rel="#" class="delete btn btn-danger btn-xs">
                                                          <i class="fa fa-trash-o"></i>
                                                      </a>
                                                      <a href="javascript:void(0);" class="set_thumb fa-stack">
                                                          <?php if($this->controller->post->avatar != $rows):?>
                                                              <i class="fa fa-star i-thumb" data-id="<?php echo $key;?>"></i>
                                                          <?php else:?>
                                                              <i class="fa fa-star i-thumb active" data-id="<?php echo $key;?>"></i>
                                                          <?php endif;?>
                                                      </a>
                                                   </span>
                                                <img src="../<?php echo $rows ?>" alt="" style="width: 193px;height: 108px" class="img-responsive" id="img-<?php echo $key;?>">
                                                <input name="portfolio_avatar[]" id="img-<?php echo $key;?>-hidden" type="hidden" value="<?php echo $rows ?>">
                                            </div>
                                        </div>
                                    <?php endforeach;?>
                                <?php endif;?>
                            </div>
                        </div>
                   </div>
                    <?php $this->error_message("portfolio_avatar")?>
                </div>
            </div>
            
            <div class="form-group <?php $this->error_class("portfolio_description");?>">
                <label for="subject" class="col-sm-2 control-label">
                   <?php echo __("Portfolio description","portfolio"); ?>
                     
                </label>
                <div class="col-sm-10">
                    <?php echo form_textarea("portfolio_description");?>
                    <?php $this->error_message("portfolio_description")?>
                </div>
            </div>
            <div class="form-group <?php $this->error_class("portfolio_status");?>">
                <label for="subject" class="col-sm-2 control-label">
                   <?php echo __("Status","portfolio"); ?>
                     
                </label>
                <div class="col-sm-10">
                   <label class="checkbox-inline" style="padding-left:0px;">
                        <?php echo form_checkbox('portfolio_status','1'); ?>
                        <?php echo __('Published','portfolio'); ?>
                    </label>
                    <?php $this->error_message("portfolio_status")?>
                </div>
            </div>
             <div id="append" >
               <?php if (!empty($this->controller->post->custom) && is_array($this->controller->post->custom)) {
                    foreach ($this->controller->post->custom as $field) {
                        if(!(empty($field['meta_name']) && empty($field['meta_value']))) {
                            ?>
                            <div class="form-group custom-cols">
                                <div class="col-sm-2">
                                    <input type="text" name="field_name[]" class="form-control" value="<?php echo $field['meta_name'] ?>">
                                </div>
                                <div class="col-sm-10" style="padding:0px">
                                    <div class="col-sm-10">
                                        <input type="text" name="field_value[]" value="<?php echo $field['meta_value'] ?>" class="form-control">
                                    </div>
                                    <div class="col-sm-1"><a href="#" onclick="return false;" class="glyphicon glyphicon-remove btn-remove"></a></div>
                                </div>
                            </div>
                        <?php
                        }
                    }
                }
                ?>
            </div>
            <div id="custom-portfolio">
              <?php
                $field_name = isset($field_name) ? $field_name : null;
                $field_name = isset($this->controller->post->field_name) ? $this->controller->post->field_name : $field_name;
                $field_value = isset($field_value) ? $field_value : null;
                $field_value = isset($this->controller->post->field_value) ? $this->controller->post->field_value : $field_value;
                if(is_array($field_value) && is_array($field_name)):
                    foreach($field_name as $key => $val):
                ?>
                <div class="form-group custom-cols">
                    <div class="col-sm-2"><input type="text" value="<?php echo $val ?>" name='field_name[]' class="form-control" placeholder="<?php echo __('Custom field\'s Name', 'portfolio'); ?>" /></div>
                    <div class="col-sm-10" style="padding:0px">
                        <div class="col-sm-10"><input type="text" name='field_value[]' value="<?php echo isset($field_value[$key]) ? $field_value[$key] : null; ?>" class="form-control" placeholder="<?php echo __('Custom field\'s Value', 'portfolio'); ?>" /></div>
                        <div class="col-sm-1"><a href="#" class="glyphicon glyphicon-remove btn-remove"></a></div>
                    </div>
                </div>
                <?php
                    endforeach;
                endif;
                ?>
            </div>
             <div class="form-group">
                <label class="col-sm-2 control-label"></label>
                <div class="col-sm-10">
                    <a class='btn btn-primary btn-xs' id='btnAddField'><?php echo __('Add custom field', 'portfolio'); ?></a>
                </div>
            </div>
            <?php echo Pf::event()->trigger("filter","portfolio-form"); ?>
            <?php echo Pf::event()->trigger("filter","portfolio-editing-form"); ?>
        </form>
    </div>
</div>
<script id="entry-template" type="text/x-handlebars-template">
        <div class="thumbview-gal thumbview">
            <div class="inner">
               <span class="control">
                  <a href="<?php echo RELATIVE_PATH . "/app/plugins/default/media/filemanager/dialog.php?type=1&field_id=tmp_{{id}}"; ?>"
                     data-id="{{id}}"
                     data-toggle="tooltip"
                     data-placement="left"
                     title="Tooltip on left"
                     rel="#" class="open_media ajax_media btn btn-success btn-xs"
                     id="open_media_{{id}}">
                      <i class="fa fa-file-image-o"></i>
                      <input type="hidden" id="tmp_{{id}}"/>
                  </a>
                  <a href="javascript:void(0);"  rel="#"
                     data-toggle="tooltip"
                     data-placement="left"
                     title="Tooltip on left"
                     class="delete btn btn-danger btn-xs">
                      <i class="fa fa-trash-o"></i>
                  </a>
                    <a href="javascript:void(0);"
                       data-toggle="tooltip"
                       data-placement="left"
                       class="set_thumb fa-stack">
                        <i class="fa fa-star i-thumb" data-id="{{id}}"></i>
                    </a>
               </span>
                <img src="../{{src}}"
                     alt=""
                     id="img-{{id}}"
                     style="width: 193px;height: 108px"
                     class="img-responsive">
                <input name="portfolio_avatar[]" type="hidden" id="img-{{id}}-hidden" value="{{src}}">
            </div>
        </div>
</script>
<script id="field-template" type="text/x-handlebars-template">
        <div class="form-group custom-cols">
            <div class="col-sm-2"><input type="text" name='field_name[]' class="form-control" placeholder="<?php echo __('Custom field\'s Name', 'portfolio'); ?>" /></div>
            <div class="col-sm-10" style="padding:0px">
                <div class="col-sm-10"><input type="text" name='field_value[]' class="form-control" placeholder="<?php echo __('Custom field\'s Value', 'portfolio'); ?>" /></div>
                <div class="col-sm-1"><a href="#" class="glyphicon glyphicon-remove btn-remove"></a></div>
            </div>
        </div>
</script>
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
function Portfolio_Edit(){
	$("#main-content").mask("<?php echo __('Loading...','portfolio'); ?>");
	try{
       tinymce.triggerSave(); 
        tinymce.remove();
    }catch(e){}
	$.post($('#frm_Portfolio_Edit').attr('action'),$('#frm_Portfolio_Edit').serialize(),function(obj){
		$("#main-content").unmask();
		if (obj.error == 1){
		    $.notification({type:"error",img:"",width:"400",content:"<i class='fa fa-times-circle fa-2x'></i> <?php echo __('There was an error. Please try again!','portfolio'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
			$('#main-content').html(obj.content);
			$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		    $('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
		}else if (obj.error == 0){
			$.notification({type:"success",img:"",width:"400",content:"<i class='fa fa-check fa-2x'></i> <?php echo __('Portfolio is updated successfully','portfolio'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
			$('#main-content').load(obj.url,function(){
				$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		        $('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
			});
		}
	},'json');
}
$('#append').on('click', '.btn-remove', function(){
    $(this).closest('.custom-cols').remove();
    return false;
});

function Portfolio_Apply(){
	$("#main-content").mask("<?php echo __('Loading...','portfolio'); ?>");
	try{
       tinymce.triggerSave(); 
        tinymce.remove();
    }catch(e){}
	$.post($('#frm_Portfolio_Edit').attr('action'),$('#frm_Portfolio_Edit').serialize(),function(obj){
		$("#main-content").unmask();
		$('#main-content').html(obj.content);
		if (obj.error == 0){
			$.notification({type:"success",img:"",width:"400",content:"<i class='fa fa-check fa-2x'></i> <?php echo __('Portfolio is updated successfully','portfolio'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
		}else{
	        $.notification({type:"error",img:"",width:"400",content:"<i class='fa fa-times-circle fa-2x'></i> <?php echo __('There was an error. Please try again!','portfolio'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
		}
		$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
	},'json');
}
</script>
<script type="text/javascript" src="<?php echo RELATIVE_PATH;?>/app/plugins/others/portfolio/assets/portfolio.js"></script>