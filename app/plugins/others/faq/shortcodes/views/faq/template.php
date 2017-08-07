<script id="listFAQ" type="text/x-handlebars-template">
<div class="col-sm-6 faqs" data-id="{{id}}">
    <h4>{{title}}</h4>
    <hr>
    <div class="panel-group" id="accordion_idfaq">
        {{#each faqs}}
        <div class="panel panel-default">
            <div class="panel-heading">
                    <a data-toggle="collapse" data-parent="#accordion_idfaq" href="#collapse_idfaq_{{@key}}" class="collapsed">
                        {{question}}
                    </a>
            </div>
            <div id="collapse_idfaq_{{@key}}" class="panel-collapse collapse {{#if @first}} in {{/if}}">
              <div class="panel-body">
                   {{answer}} 
              </div>
            </div>
        </div>
       {{/each}}
    </div>
</div>
</script>

<script id="category" type="text/x-handlebars-template">
<ul class="clearfix">
    {{#each faqs}}
    <li>
        <span class="fa-stack fa-2x">
            <i class="fa fa-circle fa-stack-2x text-color"></i>
            <i class="fa fa-question-circle fa-stack-1x text-white"></i>
        </span>
        <a href="#" data-id="{{@key}}">{{title}}</a>
    </li>
    {{/each}}
</ul>
</script>