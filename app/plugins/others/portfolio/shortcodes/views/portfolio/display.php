<?php defined('PF_VERSION') OR header('Location:404.html'); ?>
<?php public_css(site_url().RELATIVE_PATH.'/app/themes/jupiter/css/lightbox.css',true);?>
<div class="row">
    <div class="col-sm-3">
        <!-- Categories -->
        <div class="panel panel-default">
          <div class="panel-heading">
            Portfolio
          </div>
          <div class="panel-body">
            <ul id="filter">
              <li  class="filter" data-filter="all"><a href="#" >All</a></li>
              <?php if(!empty($this->atts['list_cat'])) {foreach ($this->atts['list_cat'] as $cat){?>
                <li  class="filter" data-filter=".<?php echo e($this->controller->category_model->convert_catname($cat['category_name']));?>"><a href="#"><?php echo e($cat['category_name']);?></a></li>
              <?php }} ?>
            </ul>
          </div>
        </div>
    </div>
     <div class="col-sm-9" >
        <div class="row" id="Container">
           <?php if(!empty($this->atts['list_port'])){ foreach ($this->atts['list_port'] as $item) {?>
            <div class="col-sm-6 col-md-6 col-lg-4 mix <?php echo e($this->controller->category_model->convert_catname($item['category_name']));?>">
                <div class="portfolio-item">
                  <div class="portfolio-thumbnail">
                    <img class="img-responsive" src="<?php echo (!empty($item['portfolio_avatar']))? RELATIVE_PATH."/".$item['portfolio_avatar']: no_image();?>" alt="...">
                    <div class="mask">
                      <p>
                        <a href="<?php echo (!empty($item['portfolio_avatar']))? RELATIVE_PATH."/".$item['portfolio_avatar']: no_image();?>" data-lightbox="template_showcase<?php echo $item['id']?>"><i class="fa fa-search-plus fa-2x"></i></a>
                        <a href="<?php echo  $this->atts['url'].url_title($item['portfolio_name'])."/"."portfolio-id:".$item['id']; ?>"><i class="fa fa-info-circle fa-2x"></i></a>
                        <?php foreach(unserialize($item['portfolio_items']) as $key=> $lie){?>
                            <?php if($lie === $item['portfolio_avatar']){?>
                                <?php unset($key);?>
                            <?php }else{?>
                                <a href="<?php echo public_url(). $lie;?>" data-lightbox="template_showcase<?php echo $item['id']?>"></a>
                            <?php }?>
                        <?php }?>
                      </p>
                    </div>
                  </div>
                </div> <!-- / .portfolio-item -->
             </div>
            <?php }}?>
        </div>
    </div>
</div>
<?php public_js(site_url().RELATIVE_PATH.'/app/themes/jupiter/js/lightbox-2.6.min.js',true);?>
<?php public_js(site_url().RELATIVE_PATH.'/media/assets/jquery-mixitup/jquery.mixitup.js',true);?>
<script>
    $(function(){
        $('#Container').mixItUp({
            load: {
                filter: '<?php 
                    if(!empty($this->atts['get_cat'])){
                        echo '.'.$this->controller->category_model->convert_catname($this->atts['get_cat']['category_name']);
                    }else{
                        echo 'all';
                    }
                ?>',
            }
        });
    });
</script>
