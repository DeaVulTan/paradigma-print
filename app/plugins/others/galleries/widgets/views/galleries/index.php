<?php defined('PF_VERSION') OR header('Location:404.html'); ?>
<div class="widgets row galleries-menu">
    <div class="col-xs-12" style="background: <?php echo $this->controller->setting['background_color']; ?>">
        <?php echo !empty($this->controller->setting['widget-title']) ? "<h3 class=' text-color widget-title'>{$this->controller->setting['widget-title']}</h3>" : ''; ?>
        <div>
            <ul class="list-inline glr-list-small widget-content">
                <?php
                    if(isset($this->controller->setting['data'])):
                        foreach ($this->controller->galleries_model->pf_get_img($this->controller->setting['data']) as $img):
                            ?>
                            <li class="col-md-3">
                                <a href="<?php echo public_url(get_page_url_by_id($this->controller->setting['gallerypage']) . "/" . "gallery:" . $img['gallery']); ?>">
                                    <img class="img-responsive" src="<?php echo get_thumbnails($img['img']); ?>" alt="">
                                </a>
                            </li>
                        <?php
                        endforeach;
                    endif;
                ?>
            </ul>
        </div>
    </div>
</div>