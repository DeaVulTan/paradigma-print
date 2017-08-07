<?php defined('PF_VERSION') OR header('Location:404.html');?>
<?php
/*
 * * 
 * @package  Vitubo
 * @author  Vitubo Team 
 * @copyright Vitubo Team
 * @link  http://www.vitubo.com
 * @since  Version 1.0
 * @filesource
 *
 */
 if(is_dir($bak_folder)==0)
        $error  =  __("<p>The backup folder does not exist. Please check folder <b>%s</b></p>",'configuration');
    elseif(is_writable($bak_folder)==0)
        $error  =  __("<p>The backup folder is not writable! Please check folder <b>%s</b></p>",'configuration');
    elseif(is_readable($bak_folder)==0)
        $error  =   __("<p>The backup folder is not readable! Please check folder <b>%s</b></p>",'configuration');
    $this->js ('media/assets/bootstrap-notification/js/bootstrap.notification.js' );
if(!empty($_SESSION['success'])){
    echo "<script>notif('".$_SESSION['success']."')</script>";
}
    ?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo __('Backup - Restore','configuration'); ?></h3>
    </div>
    <div class="panel-body box" style="border-top:none">
                <div class="overlay" style="display:none"></div><div class="loading-img" style="display: none"></div>
                    <div class="row">          
                        <div class="col-md-12">
                            <?php if (!empty($error)) { 
                                $btncl  =   "disabled";?>
                                <div class="callout callout-danger">
                                    <h4>Error detected!</h4>
                                    <p><?php printf( $error,$bak_folder); ?></p>
                                </div>
                            <?php } ?>
                            <?php add_toolbar_button(form_button('<span class="glyphicon glyphicon-plus"></span> '. __('Backup Now', 'configuration') ,array('onclick'=>"ajax_action('backup','');",'class' => 'btn btn-primary '.((!empty($btncl))?$btncl:''))));?>
							<?php add_toolbar_button(form_button('<span class="glyphicon glyphicon-upload"></span> '. __('Upload and Restore', 'configuration') ,array('id'=>'upload','class' => 'btn btn-primary '.((!empty($btncl))?$btncl:''))));?>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive" id="table-data">
                                <table class="bootstrap-table">
                                    <thead>
                                         <tr>
                                            <th data-fixed='left' style="width:300px"><?php echo __('File Name', 'configuration'); ?></th>
                                            <th><?php echo __('Created Date', 'configuration'); ?></th>
                                            <th><?php echo __('File Size (kB)', 'configuration'); ?></th>
                                            <th data-fixed='right' style="width: 24%; text-align: center;"><?php echo __('Actions', 'configuration'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody id="list-backup">
                                        <?php
                                        if (is_dir($bak_folder)==1 && is_writable($bak_folder)== 1) {
                                            $bkrs->check_htaccess();
                                            echo $bkrs->list_all();
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
            </div>
            
        </div>
<div class="modal fade" id="uploadres" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title" id="myModalLabel"><?php echo __('Upload','configuration'); ?></h4>
                    </div>
                    <div class="modal-body">
                        <div class="row pad">
                            <form class="form pad" role='form' action="?admin-page=configuration&sub_page=backup&action=upload" method='post' enctype='multipart/form-data' id='form-upload'>
                                <div class='form-group'><input type="file" name="file" id="select-file"/></div>
                                <div class='form-group'><input type="checkbox" name="delete-after" />  <?php echo __('Delete uploaded file after restore?', 'configuration'); ?></div>
                                <div class='form-group'><button class='btn btn-primary btn-xs' type="submit" value="Upload" id="submit-upload"><?php echo __('Upload and Restore', 'configuration'); ?></button></div>
                            </form>
                            <div id="result" class='has-error'>
                                <div id='errorcode1' class='help-block' style='display:none'><?php echo __('Upload failed! Please try again', 'configuration'); ?></div>
                                <div id='errorcode2' class='help-block' style='display:none'><?php echo __('Please upload ZIP file only', 'configuration'); ?></div>
                                <div id='errorcode3' class='help-block' style='display:none'><?php echo __('Your file is not in Vitubo backup format.', 'configuration'); ?></div>
                            </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
<div style="display: none" id="notification"><?php echo __('Action is performed successfully','configuration'); ?></div>
<div style="display: none" id="confirm"><?php echo __('Are you sure with this action?','configuration'); ?></div>
<div style="display: none" id="sure"><?php echo __('Confirm','configuration'); ?></div>
<div style="display: none" id="btn-backup"><?php echo __('Backup Now','configuration'); ?></div>
<div style="display: none" id="cf-backup"><?php echo __('Do you want to backup current database before uploading new one?','configuration'); ?></div>
<div style="display: none" id="ok-backup"><?php echo __('Backup successfully','configuration'); ?></div>
<div style="display: none" id="no-backup"><?php echo __('Restore only','configuration'); ?></div>
<div style="display: none" id="cancel"><?php echo __('Cancel','configuration'); ?></div>
<div style="display: none" id="delete-after">0</div>