<?php defined('PF_VERSION') OR header('Location:404.html');?>
<div class="row">
    <div class="col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo __('Email templates','configuration');?>
                </h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-3 col-sm-4">
                        <ul class="nav nav-pills nav-stacked nav-email-template-left">
                            <li class="header"><?php echo __('Templates', 'configuration'); ?></li>
                            <?php if (!empty($email_template->properties)){?>
                            <?php foreach ($email_template->properties as $k =>$v){?>
                            <li class="email-template-item <?php if ($type == strtolower($k)){?> active<?php }?>"><a href="<?php echo admin_url('type='.$k);?>"><?php echo htmlspecialchars($v['title']); ?></a></li>
                            <?php } ?>
                            <?php } ?>
                        </ul>
                    </div>
                    <div class="col-md-9 col-sm-8">
                        <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                        <?php if (!empty($email_template->properties) && !empty($email_template->properties[$type])){?>
                            <?php $i = 0;?>
                            <?php  foreach ($email_template->properties[$type]['elements'] as $k => $v){?>
                            <li <?php if ($i==0){?> class="active" <?php } ?>><a href="<?php echo '#'.$k.'_tab';?>" data-toggle="tab"><?php echo htmlspecialchars($v['label']);?></a></li>
                            <?php $i++; ?>
                            <?php } ?>
                        <?php } ?>
                        </ul>
                        <!-- Tab panes -->
                        <form role="form">
                        <div class="tab-content" style="padding-top:10px;">
                          <?php if (!empty($email_template->properties) && !empty($email_template->properties[$type])){?>
                            <?php $i = 0;?>
                            <?php  foreach ($email_template->properties[$type]['elements'] as $k => $v){?>  
                              <div class="tab-pane<?php if ($i==0){?> active<?php } ?>" id="<?php echo $k.'_tab';?>">
                                <div class="form-group">
                                  <label for="<?php echo $k.'_subject';?>"><?php echo __('Email subject', 'configuration'); ?></label>
                                  <input type="text" id="<?php echo $k.'_subject';?>" name="<?php echo $k.'_subject';?>" class="form-control"">
                                </div>
                                <div class="form-group">
                                  <label for="<?php echo $k.'_body';?>"><?php echo __('Email content', 'configuration'); ?></label>
                                    <i id="<?php echo $k.'_help';?>" class="fa fa-question-circle" style="color: #F0AD4E; cursor:pointer;"></i>
                                    <div id="<?php echo $k.'_help_block';?>" style="display:none;">
                                        <?php echo $v['helper']; ?>
                                    </div>
                                  <textarea class="form-control" id="<?php echo $k.'_body';?>" name="<?php echo $k.'_body';?>" style="height: 20em;"></textarea>
                                </div>
                              </div>
                              <?php $i++; ?>
                            <?php } ?>
                        <?php } ?>
                        </div>
                        </form>
                        <div class="row pad">
                            <div class="col-md-12">
                                <?php add_toolbar_button(form_button("<i class='fa fa-check'></i> ". __('Save changes', 'configuration') ,array('onclick'=>'pf_email_template_save_change();','class' => 'btn btn-primary')));?>
                            </div>
                        </div>
                        </div>
                    </div>
                </div><!-- /.row -->
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div><!-- /.col (MAIN) -->
</div>
<script>
function pf_email_template_save_change(){
	var obj = {};
    $('form').each(function(){
        var o = {};
		 var a = $(this).serializeArray();
		 $.each(a, function() {
			 if (o[this.name] !== undefined) {
				 if (!o[this.name].push) {
					 o[this.name] = [o[this.name]];
				 }
				 o[this.name].push(this.value || '');
			 } else {
				 o[this.name] = this.value || '';
			 }
		 });
		 obj = o;
    });
    $.post('<?php echo admin_url('action=save&type='.$type)?>',obj,function(json){
    	$.notification({type:"success",width:"400",content:"<i class='fa fa-check fa-2x'></i><?php echo __("Email template is updated successfully", 'configuration'); ?>",html:true,autoClose:true,timeOut:"2000",delay:"0",position:"topRight",effect:"fade",animate:"fadeDown",easing:"easeInOutQuad",duration:"300"});
    },'json');
}
<?php if (!empty($data)){?>
$(document).ready(function(){
	json_data = <?php echo $data; ?>;
	for(var k in json_data){
    	$('#'+k).val(json_data[k]);
	}
});
<?php } ?>
$(document).ready(function(){
	 <?php if (!empty($email_template->properties) && !empty($email_template->properties[$type])){?>
        <?php  foreach ($email_template->properties[$type]['elements'] as $k => $v){?>
        $('#<?php echo $k.'_help';?>').popover({content:function(){return $('#<?php echo $k.'_help_block';?>').html();},html:true});
        <?php } ?>
    <?php } ?>
    
});
</script>