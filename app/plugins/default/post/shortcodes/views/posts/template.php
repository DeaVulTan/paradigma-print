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
        <a href="{{link_detail}}" class="btn btn-theme-primary btn-xs">
            <i class="fa fa-sign-out"></i>
            <?php echo __('Read more', 'post'); ?>
        </a>
    </div>
</div>
{{/each}}
</script>

<script id="template_timeline" type="text/x-handlebars-template">
{{#each data}}
<li class="event">
    <div class="event__title">
        <h3><a href="{{link_detail}}">{{title}}</a></h3>
        <time datetime="{{short_date}}">{{short_date}}</time>
    </div>
    <div class="event__content">
        <p>
            <img class="img-responsive" src="{{thumbnail}}" alt="{{title}}" />
            {{description}}
        </p>
    </div>
</li>
{{/each}}
</script>

<script id="template_timeline_mid" type="text/x-handlebars-template">
{{#each data}}
<li class="event">
    <div class="event__title">
        <h3><a href="{{link_detail}}">{{title}}</a></h3>
        <time datetime="{{short_date}}">{{short_date}}</time>
    </div>
    <div class="event__content">
        <p><img class="img-responsive" src="{{thumbnail}}" alt="{{title}}" />{{description}}</p>
    </div>
</li>
{{/each}}
</script>