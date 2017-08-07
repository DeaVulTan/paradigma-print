<?php
return array(
    'lists' => array(
        'default' => '<div class="row">
                        <div class="post-lists col-md-12">
                            <div class="default">
                            {have_post}
                                <div class="row post-item margin-bottom-40 blg-summary">
                                    <div class="col-md-12">
                                        <h3><a href="{link_detail}">{title}</a></h3>
                                        <ul class="text-muted list-inline blg-header metadata">
                                                <li><i class="fa fa-user"></i> <a href="{author_link}">{author}</a></li>
                                                <li><i class="fa fa-calendar"></i> {date}</li>
                                                <li><i class="fa fa-comments-o"></i> <a href="{link_comment}">{comment} '.__('Comments', 'jupiter-theme').'</a></li>
                                                <li><i class="fa fa-eye"></i> {views}</li>
                                                <li>{rating}</li>
                                        </ul>
                                        <hr />
                                        <div class="description">
                                                <img class="br thumbnails img-responsive pull-left right" src="{thumbnail}" alt="{title}" />
                                        {description}
                                        </div>
                                        <div class="blog-tags">
                                                {tags}
                                        </div>
                                    </div>
                                </div>
                            {/have_post}
                            {no_post}'.__('There is no post', 'jupiter-theme').'{/no_post}
                            </div>
                        </div>
                    </div>',
        'full-width' => '<div class="row">
                            <div class="post-lists col-md-12" data-attrs="{attrs}">
                                <div class="full-width">
                                {have_post}
                                    <div class="post-item">
                                        <h2><a href="{link_detail}">{title}</a></h2>
                                        <ul class="text-muted list-inline blg-header metadata">
                                                <li><i class="fa fa-user"></i> <a href="{author_link}">{author}</a></li>
                                                <li><i class="fa fa-calendar"></i> {date}</li>
                                                <li><i class="fa fa-comments-o"></i> <a href="{link_comment}">{comment} '.__('Comments', 'jupiter-theme').'</a></li>
                                                <li><i class="fa fa-eye"></i> {views}</li>
                                                <li>{rating}</li>
                                        </ul>
                                        <div class="description">
                                                {description}
                                        </div>
                                        <a href="{link_detail}" class="btn btn-theme-primary btn-xs">
                                                <i class="fa fa-sign-out"></i>
                                                '.__('Read more', 'jupiter-theme').'
                                        </a>
                                        <hr/>
                                    </div>
                                {/have_post}
                                {no_post}'.__('There is no post', 'jupiter-theme').'{/no_post}
                                </div>
                            </div>
                        </div>',
        'multiple-columns' => '
                                <div class="post-lists" data-attrs="{attrs}" data-url="'. public_url('', true).'" data-column="{theme_column_size}">
                                        <div class="multiple-columns">
                                            {have_post}
                                                <div class="col-sm-6 {theme_column_size} item">
                                                    <div class="post-item">
                                                        <h2><a href="{link_detail}">{title}</a></h2>
                                                        <ul class="text-muted list-inline blg-header metadata">
                                                                <li><i class="fa fa-user"></i> <a href="{author_link}">{author}</a></li>
                                                                <li><i class="fa fa-calendar"></i> {short_date}</li>
                                                                <li><i class="fa fa-comments-o"></i> <a href="{link_comment}">{comment} '.__('Comments', 'jupiter-theme').'</a></li>
                                                        </ul>
                                                        <img class="br img-responsive" src="{thumbnail}" alt="{title}" />
                                                        <div class="description">
                                                                {description}
                                                        </div>
                                                        <a href="{link_detail}" class="btn btn-theme-primary btn-xs">
                                                                <i class="fa fa-sign-out"></i>
                                                                '.__('Read more', 'jupiter-theme').'
                                                        </a>
                                                    </div>
                                                </div>
                                            {/have_post}
                                            {no_post}'.__('There is no post', 'jupiter-theme').'{/no_post}
                                        </div>
                                </div>
                            ',
        'timeline' => array(
            'left' => '<h2 class="headline-lg first-child headline-lg_left">'.__('Timeline', 'jupiter-theme').'</h2>
                        <h4>
                          '.__('Timeline is a great way to showcase your company history, share news or events', 'jupiter-theme').'
                        </h4>
                        <div class="post-lists" data-attrs="{attrs}" data-url="'. public_url('', true).'">
                            <ul class="timeline left timeline_left">
                                <li class="year">
                                <span>2014</span>
                                </li>
                                <li></li>
                                {have_post}
                                    <li class="event">
                                        <div class="event__title">
                                            <h3><a href="{link_detail}">{title}</a></h3>
                                            <time datetime="{short_date}">{short_date}</time>
                                        </div>
                                        <div class="event__content">
                                            <p>
                                                <img class="img-responsive" src="{thumbnail}" alt="{title}" />
                                                {description}
                                            </p>
                                        </div>
                                    </li>
                                {/have_post}
                                {no_post}'.__('There is no post', 'jupiter-theme').'{/no_post}
                            </ul>
                    </div>',
            'mid' => '<div class="row">
                        <div class="col-sm-12">
                            <h2 class="headline-lg first-child">
                              Timeline
                            </h2>
                            <h4>
                              '.__('Timeline is a great way to showcase your company history, share news or events', 'jupiter-theme').'
                            </h4>
                            <div class="post-lists col-md-12" data-attrs="{attrs}" data-url="'. public_url('', true).'">
                                <div class="timeline1 mid">
                                    <ul class="timeline">
                                      <li class="year">
                                        <span>2014</span>
                                      </li>
                                      <li></li>
                                    {have_post}
                                      <li class="event">
                                        <div class="event__title">
                                          <h3><a href="{link_detail}">{title}</a></h3>
                                          <time datetime="{short_date}">{short_date}</time>
                                        </div>
                                        <div class="event__content">
                                          <p>
                                            <img class="img-responsive" src="{thumbnail}" alt="{title}" />
                                            {description}
                                          </p>
                                        </div>
                                      </li>
                                      {/have_post}
                                    {no_post}'.__('There is no post', 'jupiter-theme').'{/no_post}
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                    </div>',
            'right' => '<h2 class="headline-lg first-child headline-lg_right">Timeline</h2>
                        <h4>'.__('There is no post', 'jupiter-theme').'
                          '.__('Timeline is a great way to showcase your company history, share news or events', 'jupiter-theme').'
                        </h4>
                        <div class="post-lists" data-attrs="{attrs}" data-url="'. public_url('', true).'">
                            <ul class="timeline left timeline_right">
                                <li class="year">
                                <span>2014</span>
                                </li>
                                <li></li>
                                {have_post}
                                    <li class="event">
                                        <div class="event__title">
                                            <h3><a href="{link_detail}">{title}</a></h3>
                                            <time datetime="{short_date}">{short_date}</time>
                                        </div>
                                        <div class="event__content">
                                            <p>
                                                <img class="img-responsive" src="{thumbnail}" alt="{title}" />
                                                {description}
                                            </p>
                                        </div>
                                    </li>
                                {/have_post}
                                {no_post}'.__('There is no post', 'jupiter-theme').'{/no_post}
                            </ul>
                    </div>',
        )
    ),
    'detail' => array(
        'default' => '<div class="post-detail blg-summary">
                            <h3 style="margin-top:0px"><a href="javascript:void(0)">{title}</a></h3>
                            <ul class="text-muted list-inline blg-header">
                                    <li><i class="fa fa-user"></i> <a href="{author_link}">{author}</a></li>
                                    <li><i class="fa fa-calendar"></i> {date}</li>
                                    <li><i class="fa fa-comment-o"></i><a href="{link_comment}"> {comment} '.__('comments', 'jupiter-theme').'</a></li>
                            </ul>
                            <hr />
                            <div class="content">{content}</div>
                            <div class="blog-tags">{tags}</div>
                    </div>
                    {no_post}
                    No post
                    {/no_post}'
    )
); 