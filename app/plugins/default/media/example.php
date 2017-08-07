
<?php
//Include fancybox
$this->js('media/assets/fancybox/jquery.fancybox-1.3.6.pack.js');
$this->css('media/assets/fancybox/jquery.fancybox-1.3.6.css');
?>

Javascript
$('.boxGetFile').fancybox({
    'width': '75%',
    'height': '90%',
    'autoScale': false,
    'transitionIn': 'none',
    'transitionOut': 'none',
    'type': 'iframe'
});


URL
<?php echo site_url(false) . RELATIVE_PATH . '/app/plugins/others/media/filemanager/dialog.php?type=1&field_id=getImage'; ?>
Type = 0(Select only video)
Type = 1 (Select only image)
Type = 2 or Emplty (Select all files)

subfolder = folder select (Ex: subfolder=testimonial)
editor= edit (Ex: editor=mce_0)

field_id (ID of input container URL callback, Ex: field_id=getImage)


<div class="input-append">
<input id="getImage" type="text" value="">
<a class="btn boxGetFile" type="button" href="URL open dialog media">Select</a>
</div>