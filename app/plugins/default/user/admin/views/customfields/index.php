<?php defined ( 'PF_VERSION' ) or header ( 'Location:404.html' ); ?>
<!-- index -->
<!-- edit -->
<?php 
add_toolbar_button(form_button("<i class='fa fa-check'></i> " . __('Save changes', 'user'), array('onclick' => "save_custom()", 'class' => 'btn btn-primary')));
add_toolbar_button(form_button(" " . __('Cancel', 'user'), array('onclick' => "window.location='" . admin_url() . "'", 'class' => 'btn btn-default')));
?>
<div class="row" style="margin-bottom: 20px; margin-top: -15px;">
    <div class="col-sm-12">
        <h3>
            <?php if (!empty($this->menu_settings[$this->controller->get->{"admin-page"}])){ ?>
                <?php echo '<i class="' . $this->menu_settings[$this->controller->get->{"admin-page"}]['icon'] . '" style="color:'.$this->menu_settings[$this->controller->get->{"admin-page"}]['icon_color'].'"></i>'; ?>
            <?php }else{ ?>
                <?php global $_admin_menu; ?>
                <?php echo (!empty($_admin_menu[$this->controller->get->{"admin-page"}]['icon_class'])) ? '<i class="' . $_admin_menu[$this->controller->get->{"admin-page"}]['icon_class'] . '"></i>' : ''; ?>
            <?php } ?>
            <?php echo __('Custom Fields','user'); ?> <small><?php echo __('List','user'); ?></small>
        </h3>
    </div>
</div>
<!-- /edit -->
<div class="panel panel-default" style="border:none;">
    <div class="panel-body">
        <div class="row top-bar" style="background:#3c8dbc; color:white; border-radius:none;">
                <div class="col-sm-3 text-center"><strong><?php echo __("Label","user");?></strong></div>
                <div class="col-sm-3 text-center"><strong><?php echo __("Name","user");?></strong></div>
                <div class="col-sm-2 text-center"><strong><?php echo __("Required","user");?></strong></div>
                <div class="col-sm-2 text-center"><strong><?php echo __("Sign up","user");?></strong></div>
                <div class="col-sm-1 manage "><strong><?php echo __("Actions","user");?></strong></div>
        </div>
        <div class="list-bar" id="list_custom">
        <?php $list_decoded    = get_option('user_custom_fields');
        		$arr    =   array(__('No','user'),__('Yes','user'));
        ?>
            <?php if(!empty($list_decoded) && is_array($list_decoded)){ 
                foreach($list_decoded as $key=>$item){?>
                    <div class="row callout callout-info custom-field" id='<?php echo $item['id']?>'>
                        <div class="col-md-3 -label editable text-center" data-type="text" data-pk="1" data-title=""><?php echo $item['label']; ?></div>
                        <div class="col-md-3 -input editable text-center" data-type="text" data-pk="1" data-title="" id="<?php echo $item['name']; ?>"><?php echo $item['name']; ?></div>
                        <div class="col-md-2 validator text-center">
                            <span class="required_field editable-select" data-type="select" data-value="<?php echo $item['require']; ?>" data-id="<?php echo $item['require']; ?>"><?php echo $arr[$item['require']]; ?></span>
                        </div>
                        <div class="col-md-2 validator text-center">
                            <span class="register_field editable-select" data-type="select" data-value="<?php echo $item['register']; ?>" data-id="<?php echo $item['register']; ?>"><?php echo $arr[$item['register']]; ?></span>
                        </div>
                        <div class="col-md-1 manage text-center"  style="margin-left:15px;">
                            <button type="button" class="btn btn-info btn-xs edit-btn" title="<?php echo __('Edit', 'user'); ?>"><i class="fa fa-edit"></i></button>
                            <a class="btn btn-danger btn-xs delete-btn" title="<?php echo __('Delete', 'user'); ?>"><i class="fa fa-trash-o"></i></a>
                        </div>
                    </div>
                <?php }
                } ?>
        </div>
        <a class="btn btn-primary btn-xs" id="btnAdd"><?php echo __('Add Custom Field','user'); ?></a>
    </div>
</div>
<div id='hide-custom' style="display:none">
    <div class="row callout callout-info custom-field">
        <div class="col-md-3 -label editable text-center" data-type="text" data-pk="1" data-title="" id=''></div>
        <div class="col-md-3 -input editable text-center" data-type="text" data-pk="1" data-title=""></div>
        <div class="col-md-2 validator text-center">
           <span class="required_field editable-select" data-type="select" data-value="1" data-id="1"></span>
        </div>
        <div class="col-md-2 validator text-center">
           <span class="register_field editable-select" data-type="select" data-value="1" data-id="1"></span>
        </div>
        <div class="col-md-1 manage text-center"  style="margin-left:15px;">
            <button type="button" class="btn btn-info btn-xs edit-btn" title="<?php echo __('Edit', 'user'); ?>"><i class="fa fa-edit"></i></button>
            <a class="btn btn-danger btn-xs delete-btn" title="<?php echo __('Delete', 'user'); ?>"><i class="fa fa-trash-o"></i></a>
        </div>
    </div>
    
</div>
    <div class="modal fade" id="edit-custom" tabindex="-1" role="dialog" aria-labelledby="Custom Fields" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="myModalLabel"><?php echo __('Edit Custom Field','user'); ?></h4>
                    <input type="hidden" value="" id="input-id"/>
                </div>
                <div class="modal-body">
                    <div class="form-horizontal" role="form">
                        <div class="form-group">
                            <label class="col-sm-3 control-label"><?php echo __("Label", "user"); ?><span class="requiredfield">*</span></label>
                            <div class="col-sm-8 ">
                                <input type="text" id="input-label" class="form-control ">
                                <div id="err-label" class="help-block"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label"><?php echo __("Field Name", "user"); ?><span class="requiredfield">*</span></label>
                            <div class="col-sm-8 ">
                                <input type="text" id="input-name" class="form-control " readonly="readonly">
                                <div id="err-name" class="help-block"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label"><?php echo __("Required?", "user"); ?></label>
                            <div class="col-sm-8 ">
                                <div class="col-sm-10">
                                    <label class="checkbox-inline">
                                        <input type="radio" value="0" name="required" checked="checked" id="require-no"> <?php echo __('No', 'user'); ?>
                                    </label>
                                    <label class="checkbox-inline">
                                        <input type="radio" value="1" name="required" id="require-yes"> <?php echo __('Yes', 'user'); ?>    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label"><?php echo __("Display in Sign Up", "user"); ?></label>
                            <div class="col-sm-8 ">
                                <div class="col-sm-10">
                                    <label class="checkbox-inline">
                                        <input type="radio" value="0" name="register" checked="checked" id="regis-no"> <?php echo __('No', 'user'); ?>
                                    </label>
                                    <label class="checkbox-inline">
                                        <input type="radio" value="1" name="register" id="regis-yes"> <?php echo __('Yes', 'user'); ?>    </label>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="require" value="0" />
                        <input type="hidden" name="register-display" value="0" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('Close','user'); ?></button>
                    <button type="button" class="btn btn-primary" id="btn-edit"><?php echo __('Save changes','user'); ?></button>
                </div>
            </div>
        </div>
    </div>
<div class="modal fade" id="new-custom" tabindex="-1" role="dialog" aria-labelledby="Custom Fields" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php echo __('Add Custom Field','user'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="form-horizontal" role="form">
                    <div class="form-group">
                        <label class="col-sm-3 control-label"><?php echo __("Label", "user"); ?><span class="requiredfield">*</span></label>
                        <div class="col-sm-8 ">
                            <input type="text" id="input-label" class="form-control ">
                            <div id="err-label" class="help-block"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label"><?php echo __("Field Name", "user"); ?><span class="requiredfield">*</span></label>
                        <div class="col-sm-8 ">
                            <input type="text" id="input-name" class="form-control ">
                            <div id="err-name" class="help-block"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label"><?php echo __("Required?", "user"); ?></label>
                        <div class="col-sm-8 ">
                            <div class="col-sm-10">
                                <label class="checkbox-inline">
                                    <input type="radio" value="0" name="required" checked="checked" id="require-no"> <?php echo __('No', 'user'); ?>
                                </label>
                                <label class="checkbox-inline">
                                    <input type="radio" value="1" name="required" id="require-yes"> <?php echo __('Yes', 'user'); ?>    </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label"><?php echo __("Display in Sign Up", "user"); ?></label>
                        <div class="col-sm-8 ">
                            <div class="col-sm-10">
                                <label class="checkbox-inline">
                                    <input type="radio" value="0" name="register" checked="checked" id="regis-no"> <?php echo __('No', 'user'); ?>
                                </label>
                                <label class="checkbox-inline">
                                    <input type="radio" value="1" name="register" id="regis-yes"> <?php echo __('Yes', 'user'); ?>    </label>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="require" value="0" />
                    <input type="hidden" name="register-display" value="0" />
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('Close','user'); ?></button>
                <button type="button" class="btn btn-primary" id="btn-save"><?php echo __('Save changes','user'); ?></button>
            </div>
        </div>
    </div>
</div>
<div class="">
    <form id="data-custom-fields" method="post">
        <textarea style="display: none;" id="data" name="data-json"><?php echo !empty($list_decoded)?json_encode($list_decoded):'[]'; ?></textarea>
    </form>
</div>
<div id="language" class="hidden">
    <div id="yes-lang"><?php echo __('Yes','user'); ?></div>
    <div id="no-lang"><?php echo __('No','user'); ?></div>
    <div id='require'><?php echo __('This field is required','user'); ?></div>
    <div id='existed'><?php echo __('This field is already exist!','user'); ?></div>
</div>
<script type="text/javascript" src="<?php echo site_url().RELATIVE_PATH."/app/plugins/default/user/assets/custom-fields.js"?>"></script>
<script type="text/javascript">
function save_custom(){
    var sort    =   $( "#list_custom" ).sortable( "toArray");
    var result  =   new Array();
    $.each(sort,function(key, value){
        var k   =   key;
        var v   =   value;
        $.each(JSON.parse($('#data').val()),function(key,value){
            if(value.id==v){
                result[k]=value;
            }
        });
    });
    var data = JSON.stringify(result);
    $("#main-content").mask("<?php echo __('Loading...','user'); ?>");
    $.post("<?php echo admin_url(); ?>", {action: "view-status",data: data},
        	function (html) {
        	$("#main-content").unmask();
        	$.notification({type:"success",img:"",width:"400",content:"<i class='fa fa-check fa-2x'></i> <?php echo __('Custom Fields are updated successfully','user'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
        	$('#main-content').html(html);
         	$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
    });
}
</script>