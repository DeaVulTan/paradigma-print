<?php defined('PF_VERSION') OR header('Location:404.html');?>
<div class="row">
    <div class="col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo __('IP Blacklist','configuration');?>
                </h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="nav-tabs-custom">
                        <!-- Tab panes -->
                        <form role="form">
                        <div class="tab-content" style="padding-top:10px;"> 
                              <div class="tab-pane active">
                                <div class="form-group">
                                  <small><?php echo __('Enter IP addresses here, one IP per line.', 'configuration'); ?></small>
                                  <textarea class="form-control" id="ip_address" name="" style="height: 20em;"><?php echo $data;?></textarea>
                                </div>
                              </div>
                        </div>
                        </form>
                        <div class="row pad">
                            <div class="col-md-12">
                                <?php add_toolbar_button(form_button("<i class='fa fa-check'></i> ". __('Save changes', 'configuration') ,array('onclick'=>'blacklist_save_change();','class' => 'btn btn-primary')));?>
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
function blacklist_save_change(){
	list_ip = $("#ip_address").val();
	$.post("<?php echo admin_url('action=save')?>",{
		list_ip:list_ip, 
	},
		function(data){
		  if(data == "false"){
			  $.notification({type:"error",width:"400",content:"<i class='fa fa-times fa-2x'></i><?php echo __("Error update failed", 'configuration'); ?>",html:true,autoClose:true,timeOut:"2000",delay:"0",position:"topRight",effect:"fade",animate:"fadeDown",easing:"easeInOutQuad",duration:"300"});
		  }else{
			  $.notification({type:"success",width:"400",content:"<i class='fa fa-check fa-2x'></i><?php echo __("Blacklist is updated successfully", 'configuration'); ?>",html:true,autoClose:true,timeOut:"2000",delay:"0",position:"topRight",effect:"fade",animate:"fadeDown",easing:"easeInOutQuad",duration:"300"});
		  }
	});
}
</script>