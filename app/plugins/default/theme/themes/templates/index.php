<?php
defined('PF_VERSION') OR header('Location:404.html');
?>
<div class="row">
    <div class="col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo __('Themes','theme');?>
                    <span class="small"><?php echo __('Please choose the theme','theme');?></span>
                </h3>
            </div>
            <div class="panel-body">
            <div class="row pad">
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="thumbnail" style="position: relative;">
                        <?php if (is_file(ABSPATH.'/app/themes/'.$active_theme.'/screenshot.png')){ ?>
                        <img class="img-thumbnail" src="<?php echo RELATIVE_PATH.'/app/themes/'.$active_theme.'/screenshot.png'; ?>">
                        <?php }else{?>
                        <img  class="img-thumbnail" src="<?php echo RELATIVE_PATH; ?>/app/plugins/default/theme/themes/images/screenshot.png"/>
                        <?php }?>
                        <div class="caption">
                            <div class="row">
                                <div class="col-md-12" style="white-space: nowrap;">
                                    <h4><?php echo htmlspecialchars($theme_info['name']);?></h4>
                                </div>
                                <div style="position: absolute; right:5px; bottom:12px;">
                                    <button role="button" class="btn btn-danger" disabled="disabled"><i class="fa fa-check"></i> <?php echo __('Activated','theme');?></button>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
                <?php if (!empty($themes)) {?>
                <?php foreach ($themes as $file => $theme){?>
                <?php $tmp = explode('/', str_replace('.php', '', $file)); ?>
                <?php if (trim($tmp[0]) == trim($active_theme)) continue;?>
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="thumbnail" style="position: relative;">
                        <?php if (count($tmp) == 2 && is_file(ABSPATH.'/app/themes/'.$tmp[0].'/screenshot.png')){ ?>
                        <img class="img-thumbnail" src="<?php echo RELATIVE_PATH.'/app/themes/'.$tmp[0].'/screenshot.png'; ?>">
                        <?php }else{?>
                        <img  class="img-thumbnail" src="<?php echo RELATIVE_PATH; ?>/app/plugins/default/theme/themes/images/screenshot.png"/>
                        <?php }?>
                        <div class="caption">
                            <div class="row">
                                <div class="col-md-12" style="white-space: nowrap;">
                                    <h4><?php echo htmlspecialchars($theme['name']);?></h4>
                                </div>
                                <div style="position: absolute; right:5px; bottom:12px;">
                                    <a role="button" class="btn btn-primary" href="<?php echo admin_url('action=activate&theme='.$tmp[0])?>"><?php echo __('Activate','theme');?></a>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
                <?php } ?>
            </div>
        
        </div>
        </div>
        
    </div>
</div>