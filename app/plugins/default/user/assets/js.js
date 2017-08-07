 $(function (){
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
        
    });
function pf_user_save(user_length,pass_length){
    var username    =   $('#username').val();
    var email       =   $('#email').val();
    var password    =   $('#password').val();
    var repass      =   $('#repassword').val();
    var has_err     =   0;
    var type        =   $('#type').val();
    $('#eusername').removeClass('has-error');
    $('#err-name').hide();
    $('#eemail').removeClass('has-error');
    $('#err-email').hide();
    $('#epassword').removeClass('has-error');
    $('#err-password').hide();
    $('#erepassword').removeClass('has-error');
    $('#err-repassword').hide();
    $('.err').hide();
    $('.has-error').removeClass('has-error');
    if(username.length<user_length){
        $('#eusername').addClass('has-error');
        $('#err-name').show();
        has_err =   has_err+1;
    }
    if(email.length<6){
        $('#eemail').addClass('has-error');
        $('#err-email').show();
        has_err =   has_err+1;
    }
    if((password.length<pass_length && type=='create') || (password.length<pass_length && password.length>0 && type=='edit')){
        $('#epassword').addClass('has-error');
        $('#err-password').show();
        has_err =   has_err+1;
    }
    if(password != repass){
        $('#erepassword').addClass('has-error');
        $('#err-repassword').show();
        has_err =   has_err+1;
    }
    $.each($('label span').closest('tr').find('input'), function() {
        if ($(this).val().length == 0 && type=='create') {
            $(this).parent().addClass('has-error');
            $(this).next('p').show();
        }
    });
    $(".field_require").each(function(i,val){
        if ($(this).val().length == 0) {
            var lable = $(this).parent().parent().find('label').text().replace("*","");
            $(this).parent().addClass('has-error');
            $(this).next('p').html(lable+" "+$("#messageErrorJS .error_required").text());
        }
    });
    if(has_err==0)
        $('#formuser').submit();
}
function notif(note){
            $.notification({
                type:"success",
                width:"400",
                content:"<strong><i class='fa fa-check fa-2x'></i></strong>"+note,
                html:true,
                autoClose:true,
                timeOut:"2000",delay:"0",
                position:"topRight",
                effect:"fade",
                animate:"fadeDown",
                easing:"easeInOutQuad",});
}