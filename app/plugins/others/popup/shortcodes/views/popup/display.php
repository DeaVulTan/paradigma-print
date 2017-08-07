<div id="modalPopup">
    <div class="modal fade" id="detailPopup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form class="form-horizontal" role="form" method="post" accept-charset="utf-8">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="custom-field-message" id="show-popup-detail">
                            <?php echo $this->atts['records'][0]['popup_description'];?>
                        </div>
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('Close', 'popup'); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<a class="<?php if($this->atts['records'][0]['popup_type'] == 1){echo 'btn btn-theme-secondary btn-color';}?>" href="javascript:view_popup();"><?php echo $this->atts['records'][0]['popup_url'];?></a>
<script type="text/javascript">
    function view_popup(){
        $('#detailPopup').modal('show');
    }
</script>
<style type="text/css">
.modal-dialog{width:<?php echo $this->atts['records'][0]['popup_width']."px";?>;height:<?php echo $this->atts['records'][0]['popup_height']."px";?>;}
#show-popup-detail img{max-width: 100%}
</style>