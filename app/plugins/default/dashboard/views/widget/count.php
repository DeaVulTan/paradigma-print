<?php defined('PF_VERSION') OR header('Location:404.html');?>
<?php if(isset($dashboards['count'])&&count($dashboards['count'])): ?>
<?php foreach($dashboards['count'] as $dashboard):?>
    <div class="col-md-3  <?php echo $sortable;?>">
        <!-- small box -->
        <div class="small-box <?php echo $dashboard['backgorund_box']?>">
            <div class="inner">
                <h3>

                    <?php echo $dashboard['count']?$dashboard['count']:0;?>
                </h3>
                <p>
                    <?php echo __($dashboard['label'],'dashboard');?>
                </p>
            </div>
            <div class="icon">
                <i class="<?php echo $dashboard['icon'];?>"></i>
            </div>
            <a href="<?php echo ($view_url)?$dashboard['url']:"#";?>" class="small-box-footer">
                <?php echo __("Details",'dashboard');?> <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div><!-- ./col -->
<?php endforeach;?>
<?php endif;?>