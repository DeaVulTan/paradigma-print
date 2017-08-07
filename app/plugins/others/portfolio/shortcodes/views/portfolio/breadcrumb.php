<div class="topic">
    <div class="container">
      <div class="row">
        <div class="col-sm-4">
          <h3><?php echo htmlspecialchars($this->page_title); ?></h3>
        </div>
        <div class="col-sm-8">
          <ol class="breadcrumb pull-right hidden-xs">
            <li><a href="<?php echo public_url(); ?>"><?php echo __('Home','Portfolio');?></a></li>
            <?php echo $this->breadcrumb_title; ?>
          </ol>
        </div>
      </div>
    </div>
</div>