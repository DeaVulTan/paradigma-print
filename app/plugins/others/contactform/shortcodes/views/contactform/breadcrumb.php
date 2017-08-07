<div class="topic">
    <div class="container">
      <div class="row">
        <div class="col-sm-4">
          <h3><?php echo htmlspecialchars($this->breadcrumb_title); ?></h3>
        </div>
        <div class="col-sm-8">
          <ol class="breadcrumb pull-right hidden-xs">
            <li><a href="<?php echo public_url(); ?>"><?php echo __('Home','user');?></a></li>
            <li class="active"><?php echo htmlspecialchars($this->breadcrumb_title); ?></li>
          </ol>
        </div>
      </div>
    </div>
</div>