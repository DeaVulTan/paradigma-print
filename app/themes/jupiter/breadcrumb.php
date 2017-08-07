<?php
if(class_exists('Menu_Shortcode_Model')){
    $menu_shortcode_model = new Menu_Shortcode_Model();
    $page_links = $menu_shortcode_model->all_page_links();
    $navigation = $menu_shortcode_model->navigation();
    
    $page_id = '';
    foreach ($page_links as $id => $link){
        if ($_GET['pf_page_url'] == $link){
            $page_id = $id;
            break;
        }
    }
    $childrens = array();
    all_children_menu($childrens,$navigation[0]);
    $item = array();
    if(is_array($navigation[1])){
        foreach ($navigation[1] as $k => $detail){
            if ($detail['call'] == $page_id && in_array($detail['id'], $childrens)){
                $item = $detail;
                break;
            }
        }
    }
    
?>
<?php if (!empty($item)){ ?>
<div class="topic">
    <div class="container">
      <div class="row">
        <div class="col-sm-4">
          <h3><?php echo htmlspecialchars($item['name']); ?></h3>
        </div>
        <div class="col-sm-8">
          <ol class="breadcrumb pull-right hidden-xs">
            <li><a href="<?php echo public_url(); ?>">Home</a></li>
            <li class="active"><?php echo htmlspecialchars($item['name']); ?></li>
          </ol>
        </div>
      </div>
    </div>
</div>
<?php } ?>
<?php } ?>