/**
 *
 * @package		Vitubo
 * @author		Vitubo Team 
 * @copyright           Vitubo Team
 * @link		http://www.vitubo.com
 * @since		Version 1.0
 * @filesource
 *
 */
$(function() {
    //Add more
    function convertToSlug(str) {
        str = str.toLowerCase();
        str = str.replace(/^\s+\s+$/g, ''); // trim
        var from = "àáạảãâầấậẩẫăằắặẳẵäèéẹẻẽêềếệểễëìíịỉĩïîòóọỏõôồốộổỗơờớợởỡöùúụủũưừứựửữüûñçỳýỵỷỹđ·/_,:;";
        var to = "aaaaaaaaaaaaaaaaaaeeeeeeeeeeeeiiiiiiioooooooooooooooooouuuuuuuuuuuuuncyyyyyd------";
        for (var i = 0, l = from.length; i < l; i++) {
            str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
        }
        return str.replace(/[^a-z0-9 -]/g, '-').replace(/\s+/g, '-').replace(/-+/g, '-').replace(/^\-+|\-+$/g, "");
    }
    $('.btnSave').on('click', function(e) {
        var form = $('form');
        form[0].submit();
    });

    $(document).on('click','.i-thumb',function(){
        var thumb = $(this);
        $('.i-thumb').removeClass('active');
        thumb.addClass('active');
        var input = '#img-'+thumb.data('id').toString()+'-hidden';
        $('#set_thumb').val($(input).val());

    });
    $(document).on('click','.delete',function(){
        var self = $(this).closest(".thumbview");
        self.remove();
    });
    $(document).on('click','.delete-field',function(){
        var self = $(this).closest(".custom-cols");
        self.remove();
    });
    $("#btnAddField").click(function(e){
        var source   = $("#field-template").html();
        var template = Handlebars.compile(source);
        $('#custom-portfolio').append(template);
    });
    $('#custom-portfolio').on('click', '.btn-remove', function(){
        $(this).closest('.custom-cols').remove();
        return false;
    });
    function rand() {
        var text = "";
        var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        for (var i = 0; i < 8; i++)
            text += possible.charAt(Math.floor(Math.random() * possible.length));
        return text;
    }
    $('#btnAdd').fancybox({
        'width': '75%',
        'height': '90%',
        'autoScale': false,
        'transitionIn': 'none',
        'transitionOut': 'none',
        'type': 'iframe',
        onClosed: function(link, index) {
            var id      =   $(link).data('id');
            var imgurl  =   $('#image_select').val();
            if (imgurl.length > 0) {
                var source   = $("#entry-template").html();
                var template = Handlebars.compile(source);
                id = rand();
                var context = {id:id,src: imgurl, label: "This is my first post!"}
                var html    = template(context);
                $("#maindata").append(html);

                $('#open_media_' + id).fancybox({
                    'width': '75%',
                    'height': '90%',
                    'autoScale': false,
                    'transitionIn': 'none',
                    'transitionOut': 'none',
                    'type': 'iframe',
                    onClosed: function() {
                        var imgurl =$("#tmp_"+id).val();

                        if (imgurl.length > 0) {
                            $('#img-' + id).attr('src', '../'+imgurl);
                            $('#img-'+id+'-hidden').val(imgurl);
                        }
                    }
                });
            }
        }
    });
    $('.open_media').fancybox({
        'width': '75%',
        'height': '90%',
        'autoScale': false,
        'transitionIn': 'none',
        'transitionOut': 'none',
        'type': 'iframe',
        onClosed: function(link, index) {
            var id      =   $(link).attr('data-id');
            var imgurl  =   $('#tmp_'+id).val();
            if (imgurl.length > 0) {
                $('#img-' + id).attr('src', '../'+imgurl);
                $('#img-'+id+'-hidden').val(imgurl);
            }
        }
    });
    $("#checkall").on('ifChecked', function (event) {
        //Check all checkboxes
        $("input[type='checkbox']", ".table-striped").iCheck("check");
        $('#action-box').show();
    }).on('ifUnchecked', function (event) {
        //Check all checkboxes
        $("input[type='checkbox']", ".table-striped").iCheck("uncheck");
        $('#action-box').hide();
    });
    $(".checkbox").on('ifChecked', function (event) {
        $('#action-box').show();
    });
});