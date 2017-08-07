<?php defined('PF_VERSION') OR header('Location:404.html');
    public_css(public_base_url().('app/plugins/others/galleries/shortcodes/assets/custom.css'),true);
?>
	<div class="row">
	<div class="col-sm-8 col-md-9">
            <?php
            if(!empty($id)){
            	$catid = $id;
            }else{
            	$catid = $this->atts['catid'];
            }
			 if($this->atts['total_records'] !=0);
            {
            	$list = $this->atts['records'];       
            	$list_img = unserialize($list['gallery_data']);
            
            	$count  =   count($list_img[0]);
            	for($i=0; $i<$count; $i++){
            		$list_show[]  =   array($list_img[0][$i],$list_img[1][$i],$list_img[2][$i]);
            	}
            	$views = $list['gallery_views'];
            	
            $list_shortcode = '';
            $dot = '';
            foreach ($list_show as $item) {
                if (empty($_GET['ajax'])){
                    $list_shortcode .=(!empty($item[0]) && is_file(urldecode(ABSPATH . '/' . $item[0]))) ? $dot . "{slider:img src='".$item[0]."' text='$item[1]' desc='$item[2]'}" : $dot . "{slider:img src='" . no_image() . "' text='$item[1]' desc='$item[2]'}";
                }
                else {                                  
                    $size = $this->atts['size'];
                	$list_shortcode .=(!empty($item[0]) && is_file(urldecode(ABSPATH . '/' . $item[0]))) ? $dot . "{slider:img src='".$item[0]."' text='$item[1]' desc='$item[2]' data='data-width=".$size['width']."' style='width:".$size['width']."px;height:".$size['height']."px;'} " : $dot . "{slider:img src='" . no_image() . "' data='data-width=189' text='$item[1]' desc='$item[2]'}";
                }
                $dot = ',';
            }
            $shortcode = "{slider:carousel id='$catid' class='img-gallery'}$list_shortcode{/slider:carousel} ";
            if (empty($_GET['ajax']))
                echo Pf::shortcode()->exec($shortcode);
            else {
                ob_clean();
                die(Pf::shortcode()->exec($shortcode));
            }
            ?>
        </div>
        <!-- Right Column -->
        <div class="col-sm-4 col-md-3">
            <h3 class="text-color"><?php echo $list['gallery_name']; ?></h3>
            <p><?php echo e($list['gallery_description']); ?></p>
            <hr class="inverse">
            <ul class="list-inline row glr-stats">
                <li class="col-xs-6">
                    <span class="title"><?php echo __('Views', 'galleries') ?></span>
                    <span class="icon">
                        <i class="fa fa-eye"></i>
                    </span>
                    <span class="number"><?php echo ($views); ?></span>
                </li>
                <li class="col-xs-6">
                    <span class="title"><?php echo __('Total', 'galleries') ?></span>
                    <span class="icon">
                        <i class="fa fa-thumb-tack"></i>
                    </span>
                    <span class="number"><?php echo $count; ?></span>
                </li>
            </ul>
            <ul class="list-inline glr-list-small">
                <?php $i = 0;
                foreach ($list_img[0] as $item) { ?>
                    <li class="col-xs-6 col-md-4" data-target="#<?php echo $catid; ?>" data-slide-to="<?php echo $i; ?>">
                        <img class="img-thumb" src="<?php echo get_thumbnails($item);?>" alt="">
                    </li>
        <?php $i++;
    } ?>
            </ul>
        </div>
    </div>
<?php
    $comment = 'Pf_Gallery_' . $catid;
}?>