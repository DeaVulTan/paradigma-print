/* 
 ** 
 * @package  Vitubo
 * @author  Vitubo Team 
 * @copyright Vitubo Team
 * @link  http://www.vitubo.com
 * @since  Version 1.0
 * @filesource
 *
 */
function ajax_action(act,file,after){
	if(typeof after=='undefined'){ after='';}
    if(act==='delete' | act==='restore'){
        if (after == 'delete') {
            var urlcall = '?admin-page=configuration&sub_page=backup&action=' + act + '&file=' + file + '&after=delete';
        }
        else {
            var urlcall = '?admin-page=configuration&sub_page=backup&action=' + act + '&file=' + file;
        }
        var content   =   $('#confirm').html();
        $.sModal({
             image: 'plugins/theme/layouts/images/confirm.png',
             content: content,
             animate: 'fadeDown',
             buttons: [
                 {
                     text: "<i class='fa fa-times-circle'></i> "+$('#sure').html(),
                     addClass: 'btn-danger',
                     click: function(id, data) {
                         $("#"+file).hide();
                         $('.overlay').show();
                         $('.loading-img').show();
                         $.get(urlcall,function(data){
                                return_data(data);
                         });
                         $.sModal('close', id);
                     }
                 },
                 {
                     text: $('#cancel').html(),
                     click: function(id, data) {
                         $.sModal('close', id);
                     }
                 },
             ]
         });
        }   
        else
        {
            $('.overlay').show();
            $('.loading-img').show();
        $.get('?admin-page=configuration&sub_page=backup&action='+act+'&file='+file,function(data){
            return_data(data);
        });
        }
}
function return_data(data,note){
            $('.overlay').hide();
            $('.loading-img').hide();
            if(note!='nonotif')
                notif(data);
            $.get('?admin-page=configuration&sub_page=backup&action=list',function(data){
                $('#table-data').html(data);
                $('.bootstrap-table').bTable();
            });
            /*if(note!='noload')
                    location.reload('3000');*/
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
            $.get('?admin-page=configuration&sub_page=backup&action=unset',function(data){});
}

$(document).ready(function(){
	$('#upload').click(function(){
		var content   =   $('#cf-backup').html();
		$.sModal({
                image: 'plugins/theme/layouts/images/confirm.png',
                content: content,
                animate: 'fadeDown',
                duration: 0,
                buttons: [
                    {
                        text: "<i class='glyphicon glyphicon-plus'></i> "+$('#btn-backup').html(),
                        addClass: 'btn-success btn-backup',
                        click: function(id, data) {
                           $('.btn-backup').addClass('disabled');
                           $('.btn-backup').html('........');
                           $.get('?admin-page=configuration&sub_page=backup&action=backup&after=nonotif',function(data){
                                   $('.btn-backup').removeClass('disabled');
                                   $('.btn-backup').html($('#ok-backup').html());
                                   $.sModal('close', id);
                                   return_data(data,'nonotif');
                                   $('#select-file').val('');
                                   $('#errorcode1').hide();
                                   $('#errorcode2').hide();
                                   $('#errorcode3').hide();
                                   setTimeout(function() {$('#uploadres').modal('show');}, 300);
                           });

                        }
                    },
                    {
                        text: $('#no-backup').html(),
                        addClass: 'btn-primary',
                        click: function(id, data) {
                           $.sModal('close', id);
                           $('#select-file').val('');
                           $('#errorcode1').hide();
                           $('#errorcode2').hide();
                           $('#errorcode3').hide();
                           setTimeout(function() {$('#uploadres').modal('show');}, 500);
                        }
                    },
                    {
                        text: $('#cancel').html(),
                        click: function(id, data) {
                           $.sModal('close', id);
                           return false;
                        }
                    }
             ]
         });
	});
    var bar = $('#bar');
    var percent = $('#percent');
    var result = $('#result');
    var percentValue = "0%";
    var checkbox =  $('input[name=delete-after]');
    checkbox.on('ifChecked', function(event) {
        $('#delete-after').html('1');
    });
    checkbox.on('ifUnchecked', function(event) {
        $('#delete-after').html('0');
    });
    $('#form-upload').ajaxForm({
    beforeUpload: function() {
        $('#submit-upload').addClass('disabled');
        $('#errorcode1').hide();
        $('#errorcode2').hide();
        $('#errorcode3').hide();
        result.empty();
        percentValue = "0%";
        bar.width = percentValue;
        percent.html(percentValue);
      },
 
      // Do somthing while uploading
      uploadProgress: function(event, position, total, percentComplete) {
        var percentValue = percentComplete + '%';
        bar.width(percentValue)
        percent.html(percentValue);
      },
 
      // Do something while uploading file finish
      success: function() {
		$('#submit-upload').removeClass('disabled');
		$('#errorcode1').hide();
		$('#errorcode2').hide();
		$('#errorcode3').hide();
        var percentValue = '100%';
        bar.width(percentValue)
        percent.html(percentValue);
      },
 
      // Add response text to div #result when uploading complete
        complete: function(xhr) {
            if (xhr.responseText != 'errorcode1' && xhr.responseText != 'errorcode2' && xhr.responseText != 'errorcode3') {
                $('#uploadres').modal('hide');
                if($('#delete-after').html()=='1')
                    ajax_action('restore', xhr.responseText, 'delete');
                else
                    ajax_action('restore', xhr.responseText,'');
            }
            else
                $('#' + xhr.responseText).show();
        }
  });
});