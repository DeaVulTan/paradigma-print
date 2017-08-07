<?php defined('PF_VERSION') OR header('Location:404.html');?>
<?php $script_name = "";?>
<?php if(isset($dashboards['approve'])&&count($dashboards['approve'])): ?>

<?php foreach($dashboards['approve'] as $key=>$approve):?>
<?php
$long_format =get_configuration('long_date');
$colmuns_token = $approve['token'];
$unpublished = $approve['count_unpublished'];
$script_name .= "#checkAll_".$key;
$padding = "padding:11px";
if($lay[P] =="left"){
    $padding = "padding-left:11px";
}else if($lay[P] =="right"){
    $padding = "padding-right:11px";
}
?>
<div class="box-header" style="cursor: move;<?php echo $padding;?>" id="list-sort-<?php echo $id;?>">
    <form method="post" role="form" action="<?php echo admin_url('&act=main'); ?>" id="listComments">
        <?php
            /* thay doi */
            $token_key =$permission_approve?Pf_Plugin_CSRF::token($key):"";
            /* end thay doi*/
        ?>
        <input type="hidden" value="<?php echo $token_key;?>" name="token_key" id="token_key_<?php echo $key;?>"/>
        <input type="hidden" value="<?php echo $approve['status'];?>" name="status"/>
        <div class="box box-primary">
            <div class="box-header" style="padding-bottom: 0px;">
                <i class="fa fa-bookmark" style="color:#3C8DBC;"></i>
                <h3 class="box-title" style="cursor: move;"><?php echo $approve['label'];?></h3>
                <div class="box-tools pull-right" data-toggle="tooltip" title="<?php echo __('View All','dashboard');?>">
                    <a href="<?php echo $approve['url'];?>" class="btn btn-default btn-sm">
                        <?php echo __('View All','dashboard');?>
                    </a>
                </div>
                <!-- thay doi -->
                <?php if($permission_approve): ?>
                <div style="padding-right: 0px;" class="box-tools pull-right" data-toggle="tooltip" title="<?php echo __('Public All','dashboard');?>">
                    <a data="<?php echo $key;?>" class="btn btn-default btn-sm approve_all">
                        <?php echo __('Public All','dashboard');?>
                    </a>
                </div>
                <?php endif; ?>
                <!-- end thay doi -->
            </div><!-- /.box-header -->
            <div class="box-body">
                <table class="bootstrap-table">
                    <tr class="table-header-container">
                        <th data-fixed="left">

                            <?php
                            /*thay doi*/
                            if($permission_approve): ?>
                            <input type="checkbox" id="checkAll_<?php echo $key;?>" class="check_dashboard"/>
                            <?php
                            /*end thay doi*/
                            endif;?>
                        </th>
                        <th><?php echo __('Content', 'dashboard'); ?></th>
                        <th style="width: 150px;"><?php echo __('Author', 'dashboard'); ?></th>
                        <th style="width: 140px;"><?php echo __('Date', 'dashboard'); ?></th>
                        <th style="width: 90px;"><?php echo __('View', 'dashboard'); ?></th>
                    </tr>
                    <?php
                    if (isset($approve['rows']) && count($approve['rows'])):
                        foreach($approve['rows'] as $row):
                            $token =  Pf_Plugin_CSRF::token($row[$colmuns_token]);
                            ?>
                            <tr data-id="<?php echo $row['id']?>" style="padding: 0;margin: 0">
                                <td class="text-center">
                                    <!-- thay doi -->
                                    <?php if($permission_approve): ?>
                                    <input type="checkbox" name="id[]" class="itemCheckBox_<?php echo $key;?>" value="<?php echo $row['id'];?>"/>
                                    <?php endif;?>
                                    <!-- end thay doi -->
                                    <input type="hidden" name="<?php echo 'token[' . $row['id'] . ']' ?>" value="<?php echo $token; ?>" />
                                    <input type="hidden" name="status_<?php echo $row['id']?>" value="<?php echo $row['status'];?>"/>
                                </td>
                                <td class="text-left">
                                    <?php echo e(the_excerpt($row['content'],200));?>
                                </td>
                                <td class="text-center">
                                    <?php echo $row['author'];?>
                                </td>
                                <td class="text-center">
                                    <?php echo date($long_format,strtotime($row['date_up']));?>
                                </td>
                                <td class="text-center">
                                    <a data="<?php echo $key;?>" class="view btn btn-info btn-xs"title="<?php echo __("View ".$key);?>">
                                        <i  class="fa fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php
                        endforeach;
                    endif;
                    ?>
                </table>
            </div><!-- /.box-body -->
            <div class="box-footer clearfix no-border">
                <p class="col-lg-7 unpublished_dashboard">
                    <?php echo __("There are ","dashboard");?>
                    <?php echo $unpublished;?>
                    <?php echo __("unpublished items","dashboard");?>
                </p>
                <p class="unpublished_dashboard">
                    <!-- thay doi -->
                    <?php if($permission_approve): ?>
                    <button class="btn btn-default pull-right"><?php echo __("Publish","dashboard");?></button>
                    <?php endif;?>
                    <!-- end thay doi -->
            </p>

            </div>
        </div>
    </form>
    <!-- /.box -->
<?php endforeach;?>
<script type="text/javascript">
    $("<?php echo $script_name;?>").on('ifChecked ifUnchecked', function(e) {
        var name = $(this).attr("id").replace("checkAll_","");
        changeStateCheckBox($('.itemCheckBox_'+name), e);
    });
</script>
</div>
<?php endif;?>