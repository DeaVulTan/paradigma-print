<?php defined('PF_VERSION') OR header('Location:404.html'); ?>
<div class="row">
     <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo __('Error log', 'configuration'); ?>
                </h3>
            </div>
            <div class="panel-body">
                <table class="bootstrap-table table-striped">
                    <tr>
                        <th style="width: 50px;"><label for="site_name" class="control-label"><?php echo __('#', 'configuration'); ?></label></th>
                        <th><label for="site_name" class="control-label"><?php echo __('File Name', 'configuration'); ?></label></th>
                        <th style="width:200px;"><label for="site_name" class="control-label"><?php echo __('Size', 'configuration'); ?></label></th>
                        <th style="width:80px;"><label for="site_name" class="control-label"><?php echo __('View', 'configuration'); ?></label></th>
                    </tr>
                    <?php foreach ($dh as $item){?>
                    <tr>
                       <td><?php echo $stt++; ?></td>
                       <td><?php echo $item;?></td>
                       <td><?php echo ceil(filesize($dir.$item)/1024).' kb';?> </td>
                       <td><a class="btn btn-primary btn-xs file" data-toggle="modal" href='#modal-id' id='<?php echo $item;?>'><i class="fa fa-search"></i></a>
                             <button type="button" onclick="log_Delete('<?php echo $item;?>');" id='<?php echo $item;?>' class="btn btn-danger btn-xs del">
                                        <i class="fa fa-minus-circle"></i>
                                    </button>
                       </td>
                    </tr>
                    <?php }?>
                </table>
               <!-- modal -->
               <div class="modal fade bs-example-modal-lg" id="modal-id" >
                   <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title">View Error Log</h4>
                            </div>
                            <div class="modal-body" style='height: 400px !important;overflow-y: auto;'>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                        </div><!-- /.modal-content -->
                   </div><!-- /.modal-dialog -->
               </div><!-- /.modal -->
             </div>
        </div>
     </div>
</div>
<script>
var baseURL = '<?php echo site_url().RELATIVE_PATH.'/'.ADMIN_FOLDER.get_full_url(); ?>';
function log_Delete(id){
	$.sModal({
    	image:'<?php echo RELATIVE_PATH; ?>/app/plugins/default/theme/admin/images/confirm.png',
    	content:'<?php echo __('Are you sure with this action?','configuration'); ?>',
    	animate:'fadeDown',
    	buttons:[
                 {
                     text:'<i class="fa fa-times-circle"></i> <?php echo __('Delete','configuration'); ?>',
                     addClass:'btn-danger',
                     click:function(m_id,data){
                    	 $.post(baseURL ,{id:id},function(obj){
                        	 //alert(obj);
                    		 $.sModal('close',m_id);
                    		 location.reload(true);
                    	 });
                     }
                 },
                 {
                     text:'Cancel',
                     click:function(id,data){
                         $.sModal('close',id);
                     }
                 },
             ]
    });
	
}
</script>