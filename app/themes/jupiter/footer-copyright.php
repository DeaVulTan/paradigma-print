<?php defined('PF_VERSION') OR header('Location:404.html'); ?>
<?php
if (isset($rs['page_type']) && $rs['page_type'] == 0){
    $widgets_footer_uses = array();
    foreach ($widgets_footer as $k => $item) {
        if (!empty($item) && has_active_widget($item, $active_widgets)) {
            $widgets_footer_uses[$k] = $item;
            unset($widgets_footer[$k]);
        }
    }
?>
<!-- Footer -->
<footer>
<?php if (!empty($widgets_footer_uses)){ ?>
  <div class="container">
    <div class="row">
<?php 
        $col_width = 12 / count($widgets_footer_uses);
        foreach ($widgets_footer_uses as $item){
?>
      <!-- Contact Us -->
      <div class="col-sm-<?php echo $col_width; ?>">
        <?php load_widgets((is_array($item)) ? $item : array(), $setting_footer, $active_widgets); ?>
      </div>
<?php } ?>
    </div>
  </div>
<?php } ?>
</footer>

<?php 
$theme_options = get_option('theme_options');
$theme_options['bottom_content'] = (!empty($theme_options['bottom_content']))?$theme_options['bottom_content']:'';
if ($theme_options['bottom_content'] != ''){ ?>
<!-- Copyright -->
<div class="container">
  <div class="row">
    <div class="col-sm-12">
      <div class="copyright">
        <?php echo Pf::shortcode()->exec($theme_options['bottom_content']); ?>
      </div>
    </div>
  </div>  <!-- / .row -->
</div> <!-- / .container -->
<?php } ?>
<?php } ?>
<?php echo get_configuration('google_analytics'); ?>
<!-- include javascript (do not remove this line) -->