<?php
defined ( 'PF_VERSION' ) or header ( 'Location:404.html' );
if ($this->atts ['type'] == 'vertical') {
?>
    <ul class="dropdown-menu col-md-12 menu-margin" role="menu" aria-labelledby="dropdownMenu">
        <?php echo $this->atts['content']; ?>
    </ul>
<?php } elseif ($this->atts ['type'] == 'main') { ?>
    <ul class="nav navbar-nav main-menu">
        <?php echo $this->atts['content']; ?>
    </ul>
<?php } elseif ($this->atts ['type'] == 'footer') { ?>
    <ul class='list-unstyled'>
        <?php echo $this->atts['content']; ?>
    </ul>
<?php } elseif ($this->atts ['type'] == 'accordion') { ?>
    <ul class='list-unstyled menu-accordion'>
        <?php echo $this->atts['content']; ?>
    </ul>
<?php } else { ?>
    <ul class="nav navbar-nav">
        <?php echo $this->atts['content']; ?>
    </ul>
<?php } ?>
