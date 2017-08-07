$(function() {
    $("#checkall").on('ifChecked', function(event) {
        //Check all checkboxes
        $("input[type='checkbox']", ".table-striped").iCheck("check");
        $('#action-box').show();
        console.log($("input[type='checkbox']", ".table-striped"));
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


});

function edit_item(id, name, type, call, options, icontype, icon, desc) {
    dismiss_edit();
    $('.item-id').val(id);
    $('.act').val('edit');
    $('.item-name').val(name);
    if(icontype=='css'){
        console.log(type);
        $('#new-'+type+' #css-input input[name=item-icon]').val(icon);
        $('#new-'+type+' .-css').iCheck('check');
    }
    else {
        $('#css-input').hide();
        $('#img-input').show();
        $('#new-'+type+' #img-input input[name=item-icon]').val(icon);
        $('#new-'+type+' .-img').iCheck('check');
    }
    $('.item-desc').val(desc);
    if (type === 'url') {
        $('#item-link').val(call);
    }
    else {
        $('.item-options').val(options);
        $('#pagepicker option').each(function() {
            t = $(this).val();
            if (t == call) {
                $(this).attr('selected', 'selected');
            }
        });
    }
}
function dismiss_edit() {
    $('#page-name').html('');
    $('#url-name').html('');
    $('#url-call').html('');
    $('#url-call').parent().removeClass('has-error');
    $('.itemname').removeClass('has-error');
    $('#page-name').html('');
    $('#url-name').html('');
    $('#url-call').html('');
    $('.item-id').val('');
    $('.act').val('creat');
    $('.item-name').val('');
    $('#item-link').val('');
    $('.item-options').val('');
    $('.item-icon').val('');
    $('.item-desc').val('');
    $('.-css').iCheck('check');
    $('.-img').iCheck('uncheck');
    $('#css-input').show();
    $('#img-input').hide();
}
function add_name() {
    var t = $('#title').val();
    $('.new_name').val(t);
}

        
function rand(){
    var text = "";
    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

    for( var i=0; i < 8; i++ )
        text += possible.charAt(Math.floor(Math.random() * possible.length));
    return text;
}
function notif(note,type){
   if(type=='success') icon='fa-check'; else if(type=='warning') icon='fa-warning';
            $.notification({
                type:type,
                width:"400",
                content:"<strong><i class='fa "+icon+" fa-2x'></i></strong>"+note,
                html:true,
                autoClose:true,
                timeOut:"2000",delay:"0",
                position:"topRight",
                effect:"fade",
                animate:"fadeDown",
                easing:"easeInOutQuad",});
}
