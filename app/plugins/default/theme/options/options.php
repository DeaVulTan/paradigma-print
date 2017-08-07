<?php
defined('PF_VERSION') OR header('Location:404.html');
?>
<div class="row pull-right btnTop">
    <div class="col-md-12">
        <?php add_toolbar_button(form_button("<i class='fa fa-check'></i> " . __('Save changes', 'theme'), array('class' => 'btn btn-primary btnSave', 'id'=>'theme-save-option'))); ?>
    </div>
</div><!--end button-->
<div class="row">
    <div class="col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <iclass="fa fa-list"></i> <?php echo ucfirst(get_option('active_theme')).' '.__('Theme Options', 'theme'); ?>
                </h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-xs-12">
                        <?php
                        require ABSPATH . '/app/themes/' . get_option('active_theme') . '/options/options.php';
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function(){
	$('#theme-save-option').click(function(){
		var post = {};
		post.color_scheme = $('#theme_color_schemes .checked').data('value');
		post.google_fonts = $('#theme_google_fonts').val();
		post.bottom_content = $('#theme_bottom_content').val();

		post.logo_type = $('input[name="logo_type"]:checked').val();
		post.text_logo = $('#text-logo').val();
		post.font_family_logo = $('#font-family-logo').val();
		post.font_size_logo = $('#font-size-logo').val();
		post.color_logo = $('#color-logo').val();
		
		post.image_logo = $('#image_logo').val();
		post.image_width_logo = $('#image-width-logo').val();
		post.image_height_logo = $('#image-height-logo').val();

		
		$.post('',post,function(obj){
			$.notification({type:obj.type,
                width:"400",
                content:"<i class='fa fa-check fa-2x'></i>"+obj.message,
                html:true,autoClose:true,timeOut:"2000",delay:"0",position:"topRight",effect:"fade",animate:"fadeDown",easing:"easeInOutQuad",duration:"300"});
		},'json');
	});
});
</script>