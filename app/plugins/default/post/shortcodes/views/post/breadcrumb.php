<div class="topic">
    <div class="container">
      <div class="row">
        <div class="col-sm-4">
          <h3><?php echo cut($this->breadcrumb_title,40); ?></h3>
        </div>
        <div class="col-sm-8">
          <ol class="breadcrumb pull-right hidden-xs">
            <li><a href="<?php echo public_url(); ?>"><?php echo __('Homepage','post');?></a></li>
            <?php echo $this->breadcrumb_breadcrumb; ?>
          </ol>
        </div>
      </div>
    </div>
</div>