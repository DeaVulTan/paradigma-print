$(function() {
    $('.thumbcheck').tooltip();
    $('#btn-sub').click(function() {
        $('#name').removeClass('has-error');
        $('#err_name').hide();
        name=   $('#gallery_name').val();
        if(name.length==0){
            $('#name').addClass("has-error");
            $('#err_name').show();
        }
        else
         $("#form-gallery").submit();
    });
    $('#append').on('click', '.btn-remove', function() {
        var parent = $(this).closest('.form-group');
        var input   =   parent.find('.width100').next('input').val();
        if($('input[name=cover]').val()==input ){
            $('input[name=cover]').val('');
        }
        parent.remove();
    });
    $("#checkall").on('ifChecked', function(event) {
        //Check all checkboxes
        $("input[type='checkbox']", ".table-striped").iCheck("check");
        $('#action-box').show();
    });
    $("#checkall").on('ifUnchecked', function(event) {
        //Check all checkboxes
        $("input[type='checkbox']", ".table-striped").iCheck("uncheck");
        $('#action-box').hide();
    });
    $(".checkbox").on('ifChecked', function(event) {
        $('#action-box').show();
    });
    $(".checkbox").on('ifUnchecked', function(event) {
        var length = $(".table-striped input[type='checkbox']:checked").length;
        if ($(".table-striped input[type='checkbox']:checked").length === 0) {
            $('#action-box').hide();
            $("#selectAll").iCheck("uncheck");
        }
    });
    $('.img-thumb').next().change(function() {
        $('.img-thumb').attr('src', $('.img-thumb').next().val())
    });
    $('#btnAdd').click(function() {
        var form_group = $('#form-group').html();
        var new_id = rand();
        var baseurl = $('#baseurl').html();
        $('#upload').clone().attr('id', new_id);
        $('#upload-img').clone().attr('id', 'img-' + new_id);
        $('.thumbcheck').clone().attr('data-id',new_id);
        $('#append').append(form_group);
        $('#upload').attr('id', new_id);
        $('#upload-img').attr('id', 'img-' + new_id);
        $('.thumbcheck').eq(-2).attr('data-id',new_id);
        $('#' + new_id).next('a').next('a').attr('href', baseurl + new_id);
        $('#' + new_id).next('a').next('a').fancybox({
            'width': '75%',
            'height': '90%',
            'autoScale': false,
            'transitionIn': 'none',
            'transitionOut': 'none',
            'type': 'iframe',
            onClosed: function() {
                var imgurl = $('#'+new_id).val();
                var check   =   $('#append').find('.thumbcheck[data-id='+new_id+']').find('i');
                console.log($('#append').find('.thumbcheck[data-id='+new_id+']'));
                console.log(check);
                console.log(check.attr('class'));
                if(check.attr('class')=='fa fa-dot-circle-o'){
                    console.log('sd');
                    $('#thumb').val(imgurl);
                }
                if (imgurl.length > 0) {
                    $('#img-' + new_id).attr('src', '../'+imgurl);
                }
            }
        });
        $('.thumbcheck').tooltip();
    });
    $(document).on('click','.thumbcheck',function(){
        var value   =   $('#'+$(this).data('id')).val();
        if($(this).find('i').attr('class')=='fa fa-circle-o'){
            $(document).find('.thumbcheck i').attr('class','fa fa-circle-o');
            $(this).find('i').attr('class','fa fa-dot-circle-o');
            $('input[name=cover]').val(value);
        }
        else {
            return false;
        }
    });
    $('.boxGetFile').fancybox({
        'width': '75%',
        'height': '90%',
        'autoScale': false,
        'transitionIn': 'none',
        'transitionOut': 'none',
        'type': 'iframe',
        onClosed: function(link, index) {
                var id      =   $(link).data('id');
                var imgurl  =   $('#'+id).val();
                var check   =   $(document).find('.thumbcheck[data-id='+id+']').find('i');
                if(check.attr('class')=='fa fa-dot-circle-o'){
                    $('input[name=cover]').val(imgurl);
                }
                if (imgurl.length > 0) {
                    $('#img-' + id).attr('src', '../'+imgurl);
                }
            }
    });
});
function rand() {
    var text = "";
    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

    for (var i = 0; i < 8; i++)
        text += possible.charAt(Math.floor(Math.random() * possible.length));
    return text;
}
function notif(note) {
    $.notification({
        type: "success",
        width: "400",
        content: "<strong><i class='fa fa-check fa-2x'></i></strong>" + note,
        html: true,
        autoClose: true,
        timeOut: "2000", delay: "0",
        position: "topRight",
        effect: "fade",
        animate: "fadeDown",
        easing: "easeInOutQuad", });
}