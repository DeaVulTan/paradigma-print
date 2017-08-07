<script>
$(document).ready(function(){
    var width   =   $( document ).width();
    if(width<=900)
        wbox    =   width;
    else
        wbox    =   900;
    function resize_box(){
        var width   =   $('#cboxContent .item.active img').data('width');
        $.colorbox.resize({width: width+42+'px'});
    }
    $('.zoom').click(function(){
        //alert("he");
        $(this).attr('style','background-image:url("<?php echo public_base_url(); ?>themes/<?php echo get_option('active_theme'); ?>/img/loading.gif");');
        var id  =   $(this).attr('href');
        var title   =   $(this).parent().find('a span').html();
                $('.zoom').colorbox({title:title,closeButton:true,scrolling:false,transition:"none",
                    onComplete:function(){ 
                $('.carousel').carousel({interval:false});
                $('.carousel').on('click','.carousel-arrow',function(){
                    $(this).parent().append('<div class="pending-carousel" style="background-image:url(\'<?php echo public_base_url().'themes/'.  get_option('active_theme'); ?>/img/loading.gif\');"></div>');
                    });
                $('.carousel').on('slide.bs.carousel', function () {
                    resize_box()
                    $.colorbox.resize();
                  });
                $('.carousel').on('slid.bs.carousel', function () {
                  $('.pending-carousel').remove();
                  resize_box()
                  $.colorbox.resize();
                });
                $('.zoom').attr('style','background-image:url("<?php echo public_base_url(); ?>themes/<?php echo get_option('active_theme'); ?>/img/overlay-icon.png");');
        },});
    });

});   	     
</script>
<?php

if (isset ( $this->atts ['records'] ) && $this->atts ['records'] != NULL) {
	foreach ( $this->atts ['records'] as $item ) {
		$galledata = unserialize ( $item ['gallery_data'] );
		
		if (! empty ( $galledata [0] [0] )) {
			if (! empty ( $item ['gallery_cover'] )) {
				$cover = $item ['gallery_cover'];
			} else
				$cover = $galledata [0] [0];
			?>
			<div id="main" role="main">
			<div class="overlay"></div>
			<ul id="tiles" class="unstyled">
			<li>
        			<div class="post-home">
        			<div class="pin-item view view-first">
        				<a href="" title="<?php $item['gallery_name']?>"> <img class="" src="<?php $galledata = unserialize($item['gallery_data']); echo(!empty($cover) && is_file(urldecode(ABSPATH.'/'.$cover))) ? get_thumbnails(e($cover), 500) : no_image(); ?>" /></a>
        				<div class="mask">
        					<h3><?php echo $item['gallery_name']; ?></h3>
        					<p class="link-btn">
        						<a href="<?php echo $this->atts['url'] .url_title($item['gallery_name'])."/". "gallery:" . $item['id']; ?>" rel="bookmark">
        							<i class="icon-link"></i>
        						</a> 
        						<a class="zoom" href="<?php echo public_url('',true).$this->atts['url_get']; ?>/gallery:<?php echo $item['id']; ?>/ajax:1" title="<?php echo $item['gallery_name']; ?>" rel="colorbox">
        							<i class=" icon-zoom-in"></i>
        						</a>
        					</p>
        				</div>
        			</div>
        			</div>
        </li>
        <?php
        		
        }
        	}
        } else {
        	echo __ ( 'No Data', 'galleries' );
        }
        ?>
        </ul>
        </div>