$(document).ready(function () {
    $('.theme-option-color').click(function () {
        $('.theme-option-color').removeClass('active');
        $(this).addClass('active');
        var element = $(this).data('class');
        $('#body-class').val(element);
    });
    $('.boxGetFile').fancybox({
        'width': '75%',
        'height': '90%',
        'autoScale': false,
        'transitionIn': 'none',
        'transitionOut': 'none',
        'type': 'iframe'
    });
    var saving = false;
    $('#theme-save-option').click(function () {
        if(saving !== false){
            saving.abort();
        }
        saving = $.ajax({
            type: 'post',
            url: '',
            data: $('#options-theme-theme').serialize(),
            beforeSend: function(data){

            },
            success: function(result){
                var data = $.parseJSON(result);
                if(data.type == 'success'){
                    $.notification({type:data.type,
                        width:"400",
                        content:"<i class='fa fa-check fa-2x'></i>"+data.message,
                        html:true,autoClose:true,timeOut:"2000",delay:"0",position:"topRight",effect:"fade",animate:"fadeDown",easing:"easeInOutQuad",duration:"300"});
                    $('.save-error').slideUp();
                }else{
                    $('#error-message').append('<div class="col-xs-12">'+
                        '<div class="alert alert-danger">'+
                        data.message+
                        '</div>'+
                        '</div>');
                    $('.save-error').slideDown();
                }
                saving = false;
            }
        });
        return false;
    });
    $('#theme-reset-option').click(function () {
        $('#myModal11').modal('show');return false;
    });

    $('.color-picker').each(function(){
        var object = $(this);
        var tr = object.closest('tr');
        object.ColorPicker({
            color: object.data('color'),
            onShow: function (colpkr) {
                $(colpkr).fadeIn(500);
                return false;
            },
            onHide: function (colpkr) {
                $(colpkr).fadeOut(500);
                return false;
            },
            element: function () {
                console($(this).html())
            },
            onChange: function (hsb, hex, rgb) {
                tr.find('span.color-picker').css('backgroundColor', '#' + hex);
                tr.find('input.color-picker').val('#' + hex);
            }
        });
    });
    show_hidden_logo();
    show_hidden_background();
    show_hidden_color();
    $('input[name="logo"]').on('ifToggled', function () {
        show_hidden_logo();
    });
    $('input.theme-bg-option').on('ifToggled', function () {
        show_hidden_background();
    });
    $('input[name="color"]').on('ifToggled', function () {
        show_hidden_color();
    });
    var tr_padding;
    function show_hidden_background(){
        var input = $('input.theme-bg-option:checked');
        input.each(function(){
            var block = $(this).closest('table');
            var background = $(this).val();
            tr_padding = $(input).closest('td').css('padding');
            if (background == 'image') {
                block.find('.tr-background-color').hide(0, function(){
                    block.find('.tr-background-image').finish().fadeIn(200);
                });
            } else {
                if (background == 'color') {
                    block.find('.tr-background-image').hide(0, function(){
                        block.find('.tr-background-color').finish().fadeIn(200);
                    });
                }else{
                    block.find('.tr-background-image, .tr-background-color').finish().fadeIn(200);
                }
            }
        });
    }
    function show_hidden_logo() {
        var logo = $('input[name="logo"]:checked').val();
        if (logo == 'image') {
            $('.logo-text').hide(0, function(){
                $('.logo-image').finish().fadeIn(200);
            });
        } else {
            $('.logo-image').hide(0, function(){
                $('.logo-text').finish().fadeIn(200);
            });
        }
    }
    function show_hidden_color() {
        var logo = $('input[name="color"]:checked').val();
        if (logo == 'theme') {
            $('.tr-color-custom').hide(0, function(){
                $('.tr-color-theme').finish().fadeIn(200);
            });
        } else {
            $('.tr-color-theme').hide(0, function(){
                $('.tr-color-custom').finish().fadeIn(200);
            });
        }
    }
});