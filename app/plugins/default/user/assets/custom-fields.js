$(document).ready(function(){

    function check_field(field,id){
        var current_data    =   JSON.parse($('#data').val());
        var array_error = [];
        $.each(current_data,function(i,val){
            if(val.name == field.name && val.id!=id){
                array_error['name'] = 1;
            }
            if(val.label == field.label && val.id!=id){
                array_error['label'] = 1;
            }
        });
        return array_error;
    }
    function rand() {
        var text = "";
        var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        for (var i = 0; i < 8; i++)
            text += possible.charAt(Math.floor(Math.random() * possible.length));
        return text;
    }
    var array   = {0:$('#no-lang').html(),1:$('#yes-lang').html()};
    $('#btnAdd').click(function(){
        var label = $('#new-custom #input-label').val("");
        var name = $('#new-custom #input-name').val("");
        $('#new-custom').modal('show');
    });
    $('#list_custom').on('click', '.edit-btn', function() {

        var info = $(this).closest('.custom-field');
        var label = info.find('.-label').html();
        var name = info.find('.-input').html();
        var id   = info.attr('id');
        var require = info.find('.required_field').attr('data-id');
        var register = info.find('.register_field').attr('data-id');
        new_arr = {"id": name, "type": "text", "label": label, "name": name, "require": require, "register": register};
        info.find('.-input').attr('id',name);
        $('#edit-custom').find("#input-label").val(label);
        $('#edit-custom').find("#input-name").val(name);
        $('#edit-custom').find("#input-id").val(id);
        if(register==1){
            $("#regis-yes").iCheck('check');
        }else{
            $("#regis-no").iCheck('check');
        }
        if(require==1){
            $("#require-yes").iCheck('check');
        }else{
            $('#require-no').iCheck('check');
        }
        $('#edit-custom').modal('show');
    });
    $("#btn-edit").click(function(){
        var id = $('#edit-custom').find("#input-id").val();
        var label = $('#edit-custom #input-label').val();
        var name = $('#edit-custom #input-name').val();
        var require = $('#edit-custom input[name=required]:checked').val();
        var register = $('#edit-custom input[name=register]:checked').val();
        $('#edit-custom #input-name').parent().removeClass('has-error');
        $('#edit-custom #err-name').html('');
        $('#edit-custom #input-label').parent().removeClass('has-error');
        $('#edit-custom #err-label').html('');
        if (label.length == 0) {
            $('#edit-custom #input-label').parent().addClass('has-error');
        }
        else if (name.length == 0) {
            $('#edit-custom #input-name').parent().addClass('has-error');
        }
        else {

            var new_arr = {"id": id, "type": "text", "label": label, "name": name, "require": require, "register": register};
            var arr = check_field(new_arr,id);
            var error = 1;
            if(typeof arr['name']=='number'){
                error =0;
                $('#new-custom #input-name').parent().addClass('has-error');
                $('#new-custom #err-name').html($('#existed').html());
            }
            if(typeof arr['label'] =='number'){
                error =0;
                $('#new-custom #input-label').parent().addClass('has-error');
                $('#new-custom #err-label').html($('#existed').html());
            }
            if(error){
                var html = $('#hide-custom .custom-field').clone();
                new_arr = {"id": id, "type": "text", "label": label, "name": name, "require": require, "register": register};
                update_row($("#"+id),new_arr);
                edit_data(new_arr);
                selectable();
                $('#edit-custom')
                $('#edit-custom').modal('hide');
            }
        }
    });
    $('#btn-save').click(function() {
        var label = $('#new-custom #input-label').val();
        var name = $('#new-custom #input-name').val();
        var require = $('#new-custom input[name=required]:checked').val();
        var register = $('#new-custom input[name=register]:checked').val();
        $('#new-custom #input-name').parent().removeClass('has-error');
        $('#new-custom #err-name').html('');
        $('#new-custom #input-label').parent().removeClass('has-error');
        $('#new-custom #err-label').html('');
        if (label.length == 0) {
            $('#new-custom #input-label').parent().addClass('has-error');
        }
        else if (name.length == 0) {
            $('#new-custom #input-name').parent().addClass('has-error');
        }
        else {
            var id = rand();
            var new_arr = {"id": id, "type": "text", "label": label, "name": name, "require": require, "register": register};
            var arr = check_field(new_arr,0);
            var error = 1;
            if(typeof arr['name']=='number'){
                error =0;
                $('#new-custom #input-name').parent().addClass('has-error');
                $('#new-custom #err-name').html($('#existed').html());
            }
             if(typeof arr['label'] =='number'){
                 error =0;
                $('#new-custom #input-label').parent().addClass('has-error');
                $('#new-custom #err-label').html($('#existed').html());
            }
            if(error){
                var html = $('#hide-custom .custom-field').clone();

                html.attr('id', id);
                html.find('.-label').html(label);
                html.find('.-input').attr('id', name).html(name);
                html.find('.required_field').attr('data-id', require).attr('data-value', require).html(array[require]);
                html.find('.register_field').attr('data-id', register).attr('data-value', register).html(array[register]);
                html.appendTo('#list_custom');
                add_data(new_arr);
                selectable();
                $('#new-custom').modal('hide');
            }
        }
    });
    function update_row(html,value){

        html.find('.-label').html(value.label);
        html.find('.-input').attr('id', value.name).html(value.name);
        html.find('.required_field').attr('data-id',value.require).html(array[value.require]);
        html.find('.register_field').attr('data-id',value.register).html(array[value.register]);
    }
    function add_data(new_arr){
        var current_data    =   JSON.parse($('#data').val());
        current_data.push(new_arr);
        $('#data').val(JSON.stringify(current_data));
    }
    function edit_data(edit_arr){
        var current_data    =   JSON.parse($('#data').val());
        $.each(current_data,function(i,val){
            if(val.id == edit_arr.id){
                current_data[i] = edit_arr;
            }
        });
        $('#data').val(JSON.stringify(current_data));
    }
    $('#list_custom').on('click','.delete-btn',function(){
        var row_div =   $(this).parent().parent();
        var name    =   row_div.find('.-input').attr('id');
        current_data    =   JSON.parse($('#data').val());
        row_div.remove();
        $.each(current_data, function(key, value) {
            console.log(value.name);
            console.log(name);
            if(value.name===name){
                current_data.splice(key,1);
                $('#data').val(JSON.stringify(current_data));
            }
          });
    });
    selectable();
    $( "#list_custom" ).sortable( );
    $( "#list_custom" ).sortable( {
        stop: function(){
            console.log($( "#list_custom" ).sortable( "toArray"));
        }
    });
    
});
var array   = {0:$('#no-lang').html(),1:$('#yes-lang').html()};
function selectable(){
    $( "#list_custom" ).sortable();
}