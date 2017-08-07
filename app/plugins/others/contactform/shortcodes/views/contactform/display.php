<?php defined('PF_VERSION') OR header('Location:404.html');?>
<div class="row pf-contactform-shortcode">
    <div class="col-md-12">
        <?php echo $this->atts['form']; ?>
    </div>
</div>
<?php public_css(RELATIVE_PATH . '/media/assets/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css',true); ?>
<?php public_css(RELATIVE_PATH . '/app/plugins/others/contactform/shortcodes/assets/contactform.css',true) ?>
<?php public_js(RELATIVE_PATH . '/media/assets/moment/js/moment.min.js',true) ?>
<?php public_js(RELATIVE_PATH . '/media/assets/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js',true) ?>
<?php public_css(RELATIVE_PATH . '/app/plugins/others/contactform/shortcodes/assets/minimal/grey.css',true) ?>
<?php public_js(RELATIVE_PATH . '/app/plugins/others/contactform/shortcodes/assets/icheck.min.js',true) ?>
<script type="text/javascript">
$(document).ready(function() {
    if ($('.pf-contactform-shortcode').length > 0) {
    	 $('.pf-contactform-shortcode input[type=radio],input[type=checkbox]').iCheck({
             checkboxClass: 'icheckbox_minimal-grey',
             radioClass: 'iradio_minimal-grey',
             increaseArea: '20%' // optional
         });
    	  $(function () {
    		  $('.pf-contactform-shortcode').find('input[data-type=date]').datetimepicker();
          });
    }
})
</script>



