<?php $id_faq = uniqid();?>
<div  id="<?php echo $id_faq; ?>" data-url="<?php echo public_url('', true); ?>" class="row margin-top-20">
    <?php
    $faqs = $this->atts['faqss'];
    $i = 0;
    if (!empty($faq)):
        ?>
        <div class="col-sm-12">
            <h4><?php echo $faq['title'] ?></h4>
            <hr>
            <div class="panel-group" id="accordion">
                <?php
                if (count($faq['list'])):
                    foreach ($faq['list'] as $key => $value):
                        ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $key ?>">
                                    <?php echo e($value['question']); ?>
                                </a>
                            </div>
                            <div id="collapse<?php echo $key ?>" class="panel-collapse collapse <?php echo $i == 0 ? 'in' : ''; ?>">
                                <div class="panel-body">
                                    <?php echo e($value['answer']); ?>
                                </div>
                            </div>
                        </div>
                        <?php
                        $i = 1;
                    endforeach;
                endif;
                ?>
            </div>
        </div>
    <?php elseif (!empty($faqs)): ?>
        <div class="col-sm-6 pfFAQCategories">
            <h4><?php echo __('FAQ Lists', 'faq') ?></h4>
            <hr>
            <div class="row faq-cats">
                <div class="col-xs-12 list-faq">
                    <ul class="clearfix">
                        <?php
                        foreach ($faqs as $id => $faq):
                            $i++;
                            ?>
                            <li>
                                <span class="fa-stack fa-2x">
                                    <i class="fa fa-circle fa-stack-2x text-color"></i>
                                    <i class="fa fa-question-circle fa-stack-1x text-white"></i>
                                </span>
                                <a href="#" data-id="<?php echo $id; ?>"><?php echo e($faq['title']); ?></a>
                            </li>
                            <?php
                        endforeach;
                        ?>
                    </ul>
                </div>
            </div>
            <div class="row pagination-faq">
                <div class="col-xs-12">
                    <?php echo $this->atts['pagination'];?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php
public_js(get_path_file('/media/assets/handlebars/js/handlebars.min.js'),true);
public_js(get_path_file('/app/plugins/others/faq/shortcodes/assets/faq.js'),TRUE);
public_css(get_path_file('/app/plugins/others/faq/shortcodes/assets/faq-accordion.css'),TRUE);
require_once abs_plugin_path(__FILE__) . '/faq/shortcodes/views/faq/template.php';
?>
<script type="text/javascript">
    $(function() {
        initFAQ('<?php echo $id_faq; ?>');
    });
</script>
