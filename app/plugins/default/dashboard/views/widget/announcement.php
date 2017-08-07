<?php defined('PF_VERSION') OR header('Location:404.html');?>
<div class="col-lg-12" style="padding-right: 15px;">
    <?php
    $type= array(
        1=>'danger',
        2=>'info',
        3=>'warning',
        4=>'success'
    );
    require_once ABSPATH . "/app/plugins/default/dashboard/include/announcement-class.php";
    $announcement = new Pf_Announcement();
    $show_announcement = $announcement->show();
    
    function get_icon($typenum, $typearr) {
        switch ($typenum) {
            case 1:
                $icon = 'ban';
                break;
            case 4:
                $icon = 'check';
                break;
            default:
                $icon = $typearr[$typenum];
                break;
        }
        return $icon;
    }
?>
<div>
<?php if (!empty($show_announcement)) {
    foreach ($show_announcement as $item) { ?>
            <div class="alerts alert-<?php echo e($type[$item['type']]); ?> alert-dismissable">
                <i class="fa fa-<?php echo get_icon($item['type'], $type); ?>"></i>
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <div class='container'><?php echo e($item['content']); ?></div>
            </div>
    <?php }
} ?>
</div>
</div>