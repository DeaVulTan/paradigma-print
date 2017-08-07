<?php defined('PF_VERSION') OR header('Location:404.html');?>
<div class="container">
    <div class="row">
    <div class="col-sm-6">
        <?php if(is_array($this->atts['items']) && count($this->atts['items'])>1){?>
            <div class="portfolio-slideshow">
              <!-- Image Carousel -->
              <div id="portfolio-slideshow" class="carousel slide" data-ride="carousel">
                <!-- Indicators -->
                <ol class="carousel-indicators">
                  <?php foreach($this->atts['items'] as $key => $val){ ?>
                  <?php if($key == 0){?>
                    <li data-target="#portfolio-slideshow" data-slide-to="<?php echo $key?>" class = "active"></li>
                  <?php }else{?>
                     <li data-target="#portfolio-slideshow" data-slide-to="<?php echo $key?>"></li>
                  <?php }?>
                  <?php }?>
                </ol>
                <!-- Wrapper for slides -->
                <div class="carousel-inner">
                <?php foreach($this->atts['items'] as $key => $val){ ?>
                   <?php if($key == 0){?>
                    <div class="item active">
                      <img src="<?php echo RELATIVE_PATH."/".$val?>" class="img-responsive" alt="...">
                    </div>
                   <?php }else{?>
                     <div class="item">
                        <img src="<?php echo RELATIVE_PATH."/".$val?>" class="img-responsive" alt="...">
                     </div>
                   <?php }?>
                <?php }?>
                </div>
                <!-- Controls -->
                <a class="carousel-arrow carousel-arrow-prev" href="#portfolio-slideshow" data-slide="prev">
                  <i class="fa fa-angle-left"></i>
                </a>
                <a class="carousel-arrow carousel-arrow-next" href="#portfolio-slideshow" data-slide="next">
                  <i class="fa fa-angle-right"></i>
                </a>
              </div>
            </div>
        <?php }else{?>
            <img src="<?php echo (!empty($this->atts['portfolio']['portfolio_avatar'])) ? RELATIVE_PATH.'/'.$this->atts['portfolio']['portfolio_avatar'] :  no_image(); ?>" class="img-responsive" style="width: 100%" alt="...">
        <?php }?>
    </div>
   <div class="col-sm-6">
    <h3 class="headline second-child"><span><?php echo e($this->atts['portfolio']['portfolio_name']);?></span></h3>
    <p><?php echo e($this->atts['portfolio']['portfolio_description']);?></p>
    <br />
    <h4 class="headline"><span><?php echo __('Portfolio Info','portfolio')?></span></h4>
    <table class="table">
      <tbody>
        <?php foreach ($this->atts['meta'] as $m){ if(!empty($m['meta_name']) && !empty($m['meta_value'])){?>
        <tr>
          <td><i class='fa fa-arrow-circle-o-right' > <?php echo e($m['meta_name']);?><td>
          <td><?php echo e($m['meta_value']);?></td>
        </tr>
        <?php }}?>
      </tbody>
    </table>
  </div>
 </div>
</div>