<?php defined('PF_VERSION') OR header('Location:404.html'); ?>
<?php 
$theme_options = get_option('theme_options'); 
$theme_options['color_scheme'] = (!empty($theme_options['color_scheme']))?$theme_options['color_scheme']:'';
$theme_options['bottom_content'] = (!empty($theme_options['bottom_content']))?$theme_options['bottom_content']:'';
$theme_options['google_fonts'] = (!empty($theme_options['google_fonts']))?$theme_options['google_fonts']:'';
$theme_options['logo_type'] = (!empty($theme_options['logo_type']))?$theme_options['logo_type']:'text';

$theme_options['text_logo'] = (!empty($theme_options['text_logo']))?$theme_options['text_logo']:'';
$theme_options['font_family_logo'] = (!empty($theme_options['font_family_logo']))?$theme_options['font_family_logo']:'';
$theme_options['font_size_logo'] = (!empty($theme_options['font_size_logo']))?$theme_options['font_size_logo']:'';
$theme_options['color_logo'] = (!empty($theme_options['color_logo']))?$theme_options['color_logo']:'';

$theme_options['image_logo'] = (!empty($theme_options['image_logo']))?$theme_options['image_logo']:'';
$theme_options['image_width_logo'] = (!empty($theme_options['image_width_logo']))?$theme_options['image_width_logo']:'';
$theme_options['image_height_logo'] = (!empty($theme_options['image_height_logo']))?$theme_options['image_height_logo']:'';

?>
<style>
.b-theme-options-box__color-list {
    list-style: outside none none;
    margin-bottom: 0;
    padding-left: 0;
}

.b-theme-options-box__color-list>li {
    background: none repeat scroll 0 0 gray;
    color: white;
    font-size: 13px;
    margin-bottom: 3px;
    padding: 3px;
    display: inline-block;
    width: 80px;
    text-align: center;
    cursor: pointer;
    border: solid 2px #FFFFFF;
}

.b-theme-options-box__color-list>li.checked {
    border: solid 2px #742E00;
}

.b-theme-options-box__color-list>li>a {
    color: white;
}

.b-theme-options-box__color-list>li>a:hover {
    color: white;
}

.b-theme-options-box__color-list>li.default {
    background: none repeat scroll 0 0 #ed3e49;
}

.b-theme-options-box__color-list>li.atlantis {
    background: none repeat scroll 0 0 #9bc949;
}

.b-theme-options-box__color-list>li.blue-chill {
    background: none repeat scroll 0 0 #0b8c8f;
}

.b-theme-options-box__color-list>li.cadillac {
    background: none repeat scroll 0 0 #ab526b;
}

.b-theme-options-box__color-list>li.cascade {
    background: none repeat scroll 0 0 #95a5a6;
}

.b-theme-options-box__color-list>li.cinnabar {
    background: none repeat scroll 0 0 #e6572b;
}

.b-theme-options-box__color-list>li.froly {
    background: none repeat scroll 0 0 #f56991;
}

.b-theme-options-box__color-list>li.go-ben {
    background: none repeat scroll 0 0 #7a6a53;
}

.b-theme-options-box__color-list>li.lochmara {
    background: none repeat scroll 0 0 #0088cc;
}

.b-theme-options-box__color-list>li.smart-blue {
    background: none repeat scroll 0 0 #5b7c8d;
}
</style>
<div class="form-group">
    <label class="control-label">Color Schemes</label>
    <div>
        <div class="form-control-static">
            <ul class="b-theme-options-box__color-list"
                id="theme_color_schemes">
                <li class="default checked" data-value="default"><?php echo __('Default','jupiter-theme')?></li>
                <li class="atlantis" data-value="atlantis"><?php echo __('Atlantis','jupiter-theme')?></li>
                <li class="blue-chill" data-value="blue-chill"><?php echo __('Blue Chill','jupiter-theme')?></li>
                <li class="cadillac" data-value="cadillac"><?php echo __('Cadillac','jupiter-theme')?></li>
                <li class="cascade" data-value="cascade"><?php echo __('Cascade','jupiter-theme')?></li>
                <li class="cinnabar" data-value="cinnabar"><?php echo __('Cinnabar','jupiter-theme')?></li>
                <li class="froly" data-value="froly"><?php echo __('Froly','jupiter-theme')?></li>
                <li class="go-ben" data-value="go-ben"><?php echo __('Go Ben','jupiter-theme')?></li>
                <li class="lochmara" data-value="lochmara"><?php echo __('Lochmara','jupiter-theme')?></li>
                <li class="smart-blue" data-value="smart-blue"><?php echo __('Smart Blue','jupiter-theme')?></li>
            </ul>
        </div>
    </div>
</div>

<div class="form-group">
    <label class="control-label"><?php echo __('Logo','jupiter-theme')?></label>
    <div>
        <label><input type="radio" name="logo_type" value="text"<?php if ($theme_options['logo_type'] == 'text'){?> checked="checked" <?php }?>> Text </label> 
        <label class="radio-inline"> <input type="radio" name="logo_type" value="image"<?php if ($theme_options['logo_type'] == 'image'){?> checked="checked" <?php }?>> Image </label>
        <div class="row" style="display:none;" id="text-logo-content">
            <div class="col-xs-8 col-sm-5">
                <div class="form-group">
                    <div>Text</div>
                    <div><input type="text" class="form-control" id="text-logo" placeholder="Text" value="<?php echo $theme_options['text_logo'];?>"></div>
                </div>
                <div class="form-group">
                    <div>Font-family</div>
                    <div><input type="text" class="form-control"id="font-family-logo" placeholder="Font-family" value="<?php echo $theme_options['font_family_logo'];?>"></div>
                </div>
                <div class="form-group">
                    <div>Font-size</div>
                    <div><input type="text" class="form-control"id="font-size-logo" placeholder="Font-size" value="<?php echo $theme_options['font_size_logo'];?>"></div>
                </div>
                <div class="form-group">
                    <div>Color</div>
                    <div>
                    <div class="input-group color-logo">
                        <input type="text" class="form-control"id="color-logo" value="<?php echo $theme_options['color_logo'];?>">
                        <span class="input-group-addon"><i></i></span>
                    </div>
                    <script>
                        $(function(){
                            $('.color-logo').colorpicker();
                        });
                    </script>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row" style="display:none;" id="image-logo-content">
            <div class="col-xs-8 col-sm-5">
                <div class="form-group">
                    <div>Image</div>
                    <div>
                        <div class="input-group">
                            <input type="text" class="form-control" id="image_logo" value="<?php echo $theme_options['image_logo'];?>">
                            <span class="input-group-btn">
                                <a href="<?php echo RELATIVE_PATH;?>/app/plugins/default/media/filemanager/dialog.php??type=1&field_id=image_logo" type="button" class="btn btn-default boxGetFile">Select image</a>
                            </span>
                        </div>
                        <script>
                        $(function(){
                        	$('.boxGetFile').fancybox({
                                'width': '75%',
                                'height': '90%',
                                'autoScale': false,
                                'transitionIn': 'none',
                                'transitionOut': 'none',
                                'type': 'iframe'
                            });
                        });
                        </script>
                    </div>
                    <p class="help-block">Example: Image size (Width: 180px, Height: 65px)</p>
                </div>
                <div class="form-group">
                    <div>Image width</div>
                    <div><input type="text" class="form-control"id="image-width-logo" placeholder="Image width" value="<?php echo $theme_options['image_width_logo'];?>"></div>
                </div>
                <div class="form-group">
                    <div>Image height</div>
                    <div><input type="text" class="form-control"id="image-height-logo" placeholder="Image height" value="<?php echo $theme_options['image_height_logo'];?>"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="form-group">
    <label class="control-label"><?php echo __('Google fonts','jupiter-theme')?></label>
    <div>
        <textarea class="form-control" style="height: 10em;"
            id="theme_google_fonts"><?php echo $theme_options['google_fonts']; ?></textarea>
        <p class="help-block">Default font of Jupiter theme is: </p>
            <p class="help-block">&lt;link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700' rel='stylesheet' type='text/css'&gt;</p>
            <p class="help-block">&lt;link href='http://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700' rel='stylesheet' type='text/css'&gt;</p>
    </div>
</div>

<div class="form-group">
    <label class="control-label"><?php echo __('Bottom content','jupiter-theme')?></label>
    <div>
        <textarea class="form-control" style="height: 10em;"
            id="theme_bottom_content"><?php echo $theme_options['bottom_content']; ?></textarea>
    </div>
</div>
<script>
$(document).ready(function(){
	$('#<?php echo $theme_options['logo_type'];?>-logo-content').show();
	$('input[name="logo_type"]').on('ifChecked', function(event){
		$('#text-logo-content').hide();
		$('#image-logo-content').hide();
		$('#'+$(this).val()+'-logo-content').show();
	});
	$('#theme_color_schemes').children().removeClass('checked');
<?php if ($theme_options['color_scheme'] != ''){ ?>	
    $('#theme_color_schemes').children('.<?php echo $theme_options['color_scheme']; ?>').addClass('checked');
<?php } ?>
	$('#theme_color_schemes').children().each(function(){
	    $(this).click(function(){
	    	$('#theme_color_schemes').children().removeClass('checked');
	    	$(this).addClass('checked');    	   
	    });
	});
});
</script>
