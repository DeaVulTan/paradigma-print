<script>
    $(document).ready(function()
{
    $('.boxGetFile').fancybox({
        'width': '75%',
        'height': '90%',
        'autoScale': false,
        'transitionIn': 'none',
        'transitionOut': 'none',
        'type': 'iframe',
        onClosed: function(link, index) {
            var img = $('#icon-image').val();
            if(img.length >0 ){
                var txtfield   =   $(link).parent().parent().find('.item-icon');
                var checkbox   =   $(link).parent().parent().find('.-img');
                txtfield.val(img);
                checkbox.iCheck('check');
            }
        }
    });
    $('#pagepicker').addClass('item-call');
    var updateOutput = function(e)
    {
        var list = e.length ? e : $(e.target),
              output = list.data('output');
        if (window.JSON) {
            output.val(window.JSON.stringify(list.nestable('serialize')));//, null, 2));
        } else {
            output.val('JSON browser support required for this demo.');
        }
    };

    // activate Nestable for list 1
    $('#nestable').nestable({
        group: 1
    })
            .on('change', updateOutput);

    // activate Nestable for list 2
    $('#nestable2').nestable({
        group: 1
    })
            .on('change', updateOutput);

    // output initial serialised data
    updateOutput($('#nestable').data('output', $('#nestable-output')));

    $('#nestable-menu').on('click', function(e)
    {
        var target = $(e.target),
                action = target.data('action');
        if (action === 'expand-all') {
            $('.dd').nestable('expandAll');
        }
        if (action === 'collapse-all') {
            $('.dd').nestable('collapseAll');
        }
    });

    $('#nestable3').nestable();

});

        function delete_item(url) {
        $.sModal({
            image: '<?php echo RELATIVE_PATH; ?>/app/plugins/default/theme/admin/images/confirm.png',
            content: '<?php echo __('Are you sure to delete this item?', 'menu'); ?>',
            animate: 'fadeDown',
            buttons: [
                {
                    text: '<i class="fa fa-times-circle"></i> <?php echo __('Delete', 'menu'); ?> ',
                    addClass: 'btn-danger',
                    click: function(id, data) {
                        window.location = '<?php echo admin_url('admin-page=menu&act=once&del=1&item-id=',false);?>'+url;
                        $.sModal('close', id);
                    }
                },
                {
                    text: ' <?php echo __('Cancel', 'menu'); ?> ',
                    click: function(id, data) {
                        $.sModal('close', id);
                    }
                },
            ]
        });
    }
    function remove(obj, id){
    	for(i=0;i<obj.length;i++){
            if(obj[i].id== id){
                obj.splice(i,1);
                return obj;
            }
            if($.isArray(obj[i].children)) {
               obj[i].children   =   remove(obj[i].children,id);
            }
        }
        return obj;
    }
    function add_item(type){ 
        $('#page-name').html('');
        $('#url-name').html('');
        $('#url-call').html('');
        $('#url-call').parent().removeClass('has-error');
        $('.itemname').removeClass('has-error');
        var name    =   $('#new-'+type+' .item-name').val();
        var call    =   $('#new-'+type+' .item-call').val();
        var id      =   $('#new-'+type+' .item-id').val();
        var desc    =   $('#new-'+type+' .item-desc').val();
        var color   =	$('#new-'+type+' .item-color').val(); 
        if(type=='page') var options = $('#new-'+type+' .item-options').val(); else options = '';
        if($('#new-'+type+' .-img').parent().attr('aria-checked') == 'true'){var icontype='img'; var icon =   $('#new-'+type+' #img-input input[name=item-icon]').val();}
        else if($('#new-'+type+' .-css').parent().attr('aria-checked') == 'true') { var icontype='css'; var icon   =   $('#new-'+type+' #css-input input[name=item-icon]').val();}
        var output    =   jQuery.parseJSON($('#nestable-output').val());
        var data    =   jQuery.parseJSON($('#nestable-output-data').val());
        if(name=='' || name.length>75 || (type == 'url' && ValidURL(call)==false)){
                if(name ==  '') {$('#'+type+'-name').html('<?php echo __('Please enter item name', 'menu'); ?>'); $('.itemname').addClass('has-error');}
                if(name.length>75) {$('#'+type+ '-name').html('<?php echo __('Item name can not more than 75 characters', 'menu'); ?>'); $('.itemname').addClass('has-error');
                    }
                if (type == 'url' && ValidURL(call) == false) {
                        $('#url-call').parent().addClass('has-error');
                        $('#url-call').html('<?php echo __('Please enter valid URL', 'menu'); ?>');
                    }
                }
        else{
            if(id ==    ""){
            var id  =   rand();
            t   =   "<li class='dd-item' id='"+id+"' data-id='"+id+"'><div class='manage-item'> <a class='btn btn-success btn-xs public_btn' onclick=\"public('"+id+"'); return;\"><i id='icon-"+id+"' class='fa fa-check-square'></i></a><a class='btn btn-info btn-xs' id='edit-"+id+"' data-toggle='modal' data-target='#new-"+type+"' onclick=\"edit_item('"+id+"','"+name+"','"+type+"','"+call+"','"+options+"', '"+icontype+"','"+icon+"','"+desc+"'); \"><i class='fa fa-pencil-square-o'></i></a><a class='btn btn-danger btn-xs' onclick=\"deleteid('"+id+"'); return;\"><i class='fa fa-times-circle'></i></a> </div><div class='dd-handle'>"+name+"</div></li>";
            $('#nestable #parent').append(t);
            output.push({"id":id});
            var object ={"id":id,"name":name,"type":type,"call":call,"status":1,"options":options,"icontype":icontype, "icon":icon,"desc":desc,"color":color};
            data.push(object);
            console.log(icontype);
            console.log(icon);
            console.log(object);
            }
            else    {
                    for(i=0;i<data.length;i++){
                    if(data[i].id   ==  id){
                        data[i].name    =   name;
                        data[i].call    =   call;
                        data[i].options =   options;
                        data[i].icontype=   icontype;
                        data[i].icon    =   icon;
                        data[i].desc    =   desc;
                        data[i].color   =   color;
                        $('#'+id+' > .dd-handle').html(name);
                    }
               }
               $('#edit-'+id).attr('onclick',"edit_item('"+id+"','"+name+"','"+type+"','"+call+"','"+options+"', '"+icontype+"','"+icon+"','"+desc+"'),'"+color+"'); ");
            }
            $('.modal').modal('hide')
            }
        $('#nestable-output').val(JSON.stringify(output));
        $('#nestable-output-data').val(JSON.stringify(data));
    }
    function deleteid(data){
        $.sModal({
             image: '<?php echo RELATIVE_PATH; ?>/app/plugins/default/theme/admin/images/confirm.png',
             content: '<?php echo __('Are you sure to delete this item? \n (If this item has any children, it will be deleted also)','menu');?>',
             animate: 'fadeDown',
             buttons: [
                 {
                     text: '<i class="fa fa-times-circle"></i> <?php echo __('Confirm', 'menu'); ?> ',
                     addClass: 'btn-danger',
                     click: function(id) {
                         console.log(data);
                         var ind =   jQuery.parseJSON($('#nestable-output').val());
                         var data_ind=   jQuery.parseJSON($('#nestable-output-data').val());
                         console.log(data);
                         $('#'+data).remove();
                         data_ind =    remove(data_ind,data);
                         $('#nestable-output').val(JSON.stringify(ind));
                         $('#nestable-output-data').val(JSON.stringify(data_ind));
                         $.sModal('close', id);
                     }
                 },
                 {
                     text: ' <?php echo __('Cancel', 'menu'); ?> ',
                     click: function(id, data) {
                         $.sModal('close', id);
                     }
                 },
             ]
         });
    }
    function public(id){
        ind =   jQuery.parseJSON($('#nestable-output').val());
        data=   jQuery.parseJSON($('#nestable-output-data').val());
        $(data).each(function(i,item){
            if(item.id == id){
                if(item.status==1){
                    item.status=2;
                    $('#icon-'+id).attr('class','fa fa-square');
                }
                else if(item.status==2){
                    item.status=1;
                    $('#icon-'+id).attr('class','fa fa-check-square');
                }
            }
        });
        $('#nestable-output-data').val(JSON.stringify(data));
    }
    function leave_changes(url){
    	alert('<?php echo __('Do you really want to cancel?', 'menu'); ?>',url);
    }
    function ValidURL(s) {    
      var regexp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;
      return regexp.test(s);    
 }
    function save_changes(){
        $('#err_title').html('');
        $('#ename').removeClass('has-error');
       var length  =   $('#menu_title').val().length;
       if(length>255 || length==0){
           $('#ename').addClass('has-error');
            if(length>255)
                $('#err_title').html('<?php echo __('Menu name can not more than 255 characters', 'menu'); ?>');
            else if(length==0)
                $('#err_title').html('<?php echo __('Please enter menu name', 'menu'); ?>');
        }
       else
           $('#form').submit();
   }    
   function alert(content,url){
    $.sModal({
             image: '<?php echo RELATIVE_PATH; ?>/app/plugins/default/theme/admin/images/confirm.png',
             content: content,
             animate: 'fadeDown',
             buttons: [
                 {
                     text: '<i class="fa fa-times-circle"></i> <?php echo __('Confirm', 'menu'); ?> ',
                     addClass: 'btn-danger',
                     click: function(id, data) {
                         window.location = url;
                         $.sModal('close', id);
                     }
                 },
                 {
                     text: ' <?php echo __('Cancel', 'menu'); ?> ',
                     click: function(id, data) {
                         $.sModal('close', id);
                     }
                 },
             ]
         });
   }
</script>