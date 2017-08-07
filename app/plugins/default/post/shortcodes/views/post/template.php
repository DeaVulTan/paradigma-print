<script id="template_multiple_columns" type="text/x-handlebars-template">
{{#each data}}
<div class="col-sm-6 item {{../theme_column_size}}">
    <div class="post-item">
        <h2><a href="{{link_detail}}">{{title}}</a></h2>
        <ul class="text-muted list-inline blg-header metadata">
            <li><i class="fa fa-user"></i> <a href="{{author_link}}">{{author}}</a></li>
            <li><i class="fa fa-calendar"></i> {{short_date}}</li>
            <li><i class="fa fa-comments-o"></i> <a href="{{link_comment}}">{{comment}} Comments</a></li>
        </ul>
        <img class="br img-responsive" src="{{thumbnail}}" alt="{{title}}" />
        <div class="description">
            {{description}}
        </div>
        <a href="{{link_detail}}" class="btn btn-theme-primary  btn-xs">
            <i class="fa fa-sign-out"></i>
            <?php echo __('Read more', 'post'); ?>
        </a>
    </div>
</div>
{{/each}}
</script>

<script id="template_timeline" type="text/x-handlebars-template">
{{#each data}}
<div class="blg-summary post-item">
    <h2><a href="{{link_detail}}">{{title}}</a></h2>
        <div class="item-date hidden-xs background-color">
            <strong>{{day}}</strong>
            <span>{{month}}</span>
        </div>
        <ul class="text-muted list-inline blg-header metadata">
            <li><i class="fa fa-user"></i> <a href="{{author_link}}">{{author}}</a></li>
            <li><i class="fa fa-calendar"></i> <span class="visible-xs-custom">{{short_date}}</span> <span class="hidden-xs">{{date}}</span></li>
            <li><i class="fa fa-comments-o"></i> <a href="{{link_comment}}">{{comment}} Comments</a></li>
            <li class="hidden-xs"><i class="fa fa-eye"></i> {{views}}</li>
            <li class="hidden-xs hidden-sm">{{{rating}}}</li>
        </ul>
        <hr class="hidden-xs"/>
        <div class="description">
            <img alt="{{title}}" src="{{thumbnail}}" class="br thumbnails img-responsive pull-left right">
            {{description}}
        </div>
        <div class="blog-tags">
            {{{tags}}}
        </div>
</div>
{{/each}}
</script>

<script id="template_timeline_mid" type="text/x-handlebars-template">
{{#each data}}
<div class="post-item {{#everyOther (math @index "+" ../i) 2 }}pull-left{{else}}pull-right{{/everyOther}}">
    <div class="item-date hidden-xs background-color">
        <strong>{{day}}</strong>
        <span>{{month}}</span>
    </div>
    <h2><a href="{{link_detail}}">{{title}}</a></h2>
        <ul class="text-muted list-inline blg-header metadata">
            <li><i class="fa fa-user"></i> <a href="{{author_link}}">{{author}}</a></li>
            <li><i class="fa fa-calendar"></i> {{short_date}}</li>
            <li><i class="fa fa-comments-o"></i> <a href="{{link_comment}}">{{comment}} Comments</a></li>
        </ul>
        <hr class="hidden-xs hidden-sm" />
        <div class="description">
            <img class="br thumbnails img-responsive pull-left right" src="{{thumbnail}}" alt="{{title}}" />
            {{description}}
        </div>
        <div class="blog-tags">{{{tags}}}</div>
</div>
{{/each}}
</script>