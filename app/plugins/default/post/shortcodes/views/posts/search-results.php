<!-- Search Input -->
<?php $post_list = get_page_url_by_id(get_configuration('page_lists', 'pf_post')); ?>
<form role="form" action="<?php echo site_url() . RELATIVE_PATH . '/' . $post_list ?>" class="b-search-results__search-input">
  <div class="input-group">
    <input type="text" class="form-control" name="title" placeholder="Enter your search query here..." />
    <span class="input-group-btn">
      <button class="btn btn-default" type="submit">Search</button>
    </span>
  </div>
</form>

<div class="row b-search-results__info-panel">
  <div class="col-sm-6">
    <!-- Results found -->
    <p class="text-muted">
      Website search (<?php echo $this->atts['total_records'];?> matches)
    </p>
  </div>
  <div class="col-sm-6">
    <!-- Sort by -->
    <ul class="b-search-results-info__sort-by">
      <li>Sort by:</li> 
      <li class="active"><a href="<?php echo public_url($_GET['pf_page_url'].'?title='.htmlspecialchars_decode($_GET['title'])) . '&sort_by=relevance';?>">relevance</a></li>
      <li><a href="<?php echo public_url($_GET['pf_page_url'].'?title='.htmlspecialchars_decode($_GET['title'])) . '&sort_by=date';?>">date</a></li>
    </ul>
  </div>
</div>
<hr>

<!-- Search results links -->
<ul class="b-search-results__links">
<?php 
    if(isset($this->atts['search_results']) && $this->atts['search_results'] != NULL){
        foreach($this->atts['search_results'] as $item){
?>
  <li>
    <div class="title">
      <a href="<?php echo public_url(get_page_url_by_id(get_configuration('page_detail', 'pf_post'))).'/' . (get_configuration('show_title_url', 'pf_post') == 1 ?  url_title(removesign($item['post_title'])) . '-' . $item['id'] : $item['id']);?>"><?php echo $item['post_title'];?></a>
    </div>
    <div class="snippet">
      <?php echo cut($item['post_content'],230);?>
    </div>
    <div class="url">
      <a href="<?php echo public_url(get_page_url_by_id(get_configuration('page_detail', 'pf_post'))). '/' . (get_configuration('show_title_url', 'pf_post') == 1 ?  url_title(removesign($item['post_title'])) . '-' . $item['id'] : $item['id']);?>"><?php echo public_url(get_page_url_by_id(get_configuration('page_detail', 'pf_post'))).url_title(removesign($item['post_title'])) . '/' . (get_configuration('show_title_url', 'pf_post') == 1 ?  url_title(removesign($item['post_title'])) . '-' . $item['id'] : $item['id']);?></a>
    </div>
  </li>
<?php } }else{echo "No result";}?>
</ul>

<!-- Pagination -->
<?php echo $this->atts['pagination']->page_links(public_url($_GET['pf_page_url'].'?title='.$_GET['title']) . '&'); ?>
<link href="<?php echo public_url('app/plugins/default/post/shortcodes/assets/');?>post.css" rel="stylesheet"> 
<script src="<?php echo public_url('app/themes/jupiter/js/');?>search-results.js"></script>
<script>
    
</script>
