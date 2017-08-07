/* 
 ** 
 * @package  Vitubo
 * @author  Vitubo Team (technical@vinamost.net)
 * @copyright Vitubo Team
 * @link  http://www.vitubo.com
 * @since  Version 1.0
 * @filesource
 *
 */


$(function() {
    $('#btnAdd').click(function() {
        var form_group = $('#form-group').html();
        $('#append').append(form_group);
    });

    $('#append').on('click', '.btn-remove', function() {
        var parent = $(this).closest('.form-group');
        parent.remove();
    });
    $('.btn-remove').click(function() {
        var parent = $(this).closest('.form-group');
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
        console.log(length);
        if ($(".table-striped input[type='checkbox']:checked").length === 0) {
            $('#action-box').hide();
            $("#selectAll").iCheck("uncheck");
        }
    });
    
    $('.input-group-btn a').fancybox({
        'width': '75%',
        'height': '90%',
        'autoScale': false,
        'transitionIn': 'none',
        'transitionOut': 'none',
        'type': 'iframe',
        onClosed: function() {
            var imgurl  =   $('#testimonial_avatar').val();
            if (imgurl.length > 0) {
                $('#img-avatar').attr('src', '../'+imgurl);
            }
        }
    });
});
function save_changes() {
    $("#name").removeClass('has-error');
    $("#content").removeClass('has-error');
    $("#err_name").hide();
    $("#err_content").hide();
    var name    =   $("#customer_name").val();
    var content =   $("#testimonial_content").val();
    if(name.length<1 || content.length<2){
        if (name.length < 1) {
            $("#name").addClass('has-error');
            $("#err_name").show();
        }
        if (content.length < 2) {
            $("#content").addClass('has-error');
            $("#err_content").show();
        }
    }
    else
        $('#formtest').submit();
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