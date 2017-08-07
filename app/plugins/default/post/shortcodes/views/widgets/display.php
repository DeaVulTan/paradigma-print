<?php
    if(isset($this->atts['posts']) && $this->atts['posts'] != NULL){
        foreach($this->atts['posts'] as $item){
            if($this->atts['thumbnails'] == 'yes'){
                echo '<li><a href="'.public_url(get_page_url_by_id(get_configuration('page_detail', 'pf_post'))). '/' . (get_configuration('show_title_url', 'pf_post') == 1 ?  url_title(removesign(converter_post($item['post_title']))) . '-' . $item['id'] : $item['id']).'"><img style="float:left;margin: 5px;" src="'.get_thumbnails($item['post_thumbnail'],60).'" alt="'.$item['post_title'].'" title="'.$item['post_title'].'" /> '.cut($item['post_title'],$this->atts['number_string']).'</a></li>';
            }else{
                echo '<li><a href="'.public_url(get_page_url_by_id(get_configuration('page_detail', 'pf_post'))). '/' . (get_configuration('show_title_url', 'pf_post') == 1 ?  url_title(removesign(converter_post($item['post_title']))) . '-' . $item['id'] : $item['id']).'">'.cut($item['post_title'],$this->atts['number_string']).'</a></li>';
            }
        }
    }else{
        echo "There is no post";
    }