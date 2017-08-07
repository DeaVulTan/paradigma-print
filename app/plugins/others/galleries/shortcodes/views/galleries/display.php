<?php
defined('PF_VERSION') OR header('Location:404.html');
    public_js( public_base_url() . 'media/assets/color-box/js/jquery.colorbox-min.js',true );
    public_css( public_base_url() . 'media/assets/color-box/css/colorbox.css',true );
    
    public_js(public_base_url().('media/assets/masonry/masonry.min.js'),true);
   	public_js(public_base_url().('media/assets/imagesloaded/js/imagesloaded.min.js'),true);
   	public_js(public_base_url().('media/assets/waterfall/waterfall.min.js'),true);

  
   	public_css(public_base_url().('app/plugins/others/galleries/shortcodes/assets/font-awesome.css'),true);
   	public_css(public_base_url().('app/plugins/others/galleries/shortcodes/assets/style.css'),true);
?>

<div id="container">
	 
</div>
<div class="content">
        
</div>
<script>
	jQuery(document).ready(function($) {
	    //loadArticle(count);
               //alert(total); //return false;
               //count++;
        	   $('#container').waterfall({
        	    	itemCls: 'pin-item',
        	    	colWidth: 212,  
        	    	gutterWidth: 20,
        	    	gutterHeight: 10,
        	    	maxPage: 1, 
        	    	checkImagesLoaded: true,
        	    	dataType: 'html',
        	    	path: function(page) {
        	    		return '<?php echo public_url('',true).$_GET['pf_page_url']."/galleries_code:data/ajax:1"; ?>?page=' +page;
        	    	}
        		});                   
	});	
</script>