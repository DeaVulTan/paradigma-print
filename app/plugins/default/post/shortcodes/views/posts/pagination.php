<?php

return array(
    'timeline' => ' <div class="text-center load-more pagination-posts" data-page="1">
                        <button class="btn btn-theme-primary  btn-lg" data-theme="timeline" data-position="{theme_position}"><i class="fa fa-spinner loading-timeline"></i><span class="text">'. __('Read more', 'post').'</span></button>
                    </div>
                   ',
    'multiple-columns' => ' <div class="load-more pagination-posts" data-theme="multiple_columns" data-page="1">
                                <div class="ajaxLoader">
                                    <img src="' . get_path_file('app/plugins/default/post/public/assets/loading.gif') . '" alt="Loading" />
                                </div>
                            </div>'
);

