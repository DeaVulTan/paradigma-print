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
var getStateCheckBox = function(e) {
    if (e.type === 'ifUnchecked') {
        status = 'uncheck';
    } else {
        status = 'check';
    }
    return status;
};
var changeStateCheckBox = function(objEvent, e) {
    objEvent.iCheck(getStateCheckBox(e));
};
$(function() {
    var data = {};
    baseURL = "?admin-page=dashboard";

    $(".view").click(function(){
        var id = $(this).parent().parent().attr('data-id');
        var key = $(this).attr('data');
        var tonken = $("#token_key_"+key).val();
        var render_view = function(obj){
            $("#editComment #myModalLabel").html(name);
            $("#editComment #content").html(obj.content);
        }
        var reset_view=function(){
            $("#myModalLabel").html("");
            $("#content").html("");
        }
        $.post(baseURL + '&act=ajax', {
            id: id,
            token_key: tonken,
            action:'get_info'
        }, function(result) {
            try {
                var obj = JSON.parse(result);
                console.log(obj);
                render_view(obj);
            } catch (e) {
            }
        });
        $("#editComment").modal('show');
    });

    function convertDayMonthYear(str) {
        var date = new Date(str),
            mnth = ("0" + (date.getMonth()+1)).slice(-2),
            day  = ("0" + date.getDate()).slice(-2);
        if(date.getFullYear()>1970)
            return [ date.getFullYear(), mnth, day ].join("-");
        return "";
    }
    function action_ajax_json(url,object,callback,type){
        var _array_type = ['GET','POST'];
        callback = (typeof(callback)==='undefined')?function(){}:callback;
        type = (typeof(callback)==='undefined' && _array_type.in_array(type.toUpperCase()))?type.toUpperCase():"POST";
        $.ajax({
            type: "POST",
            url: url,
            data: object,
            success:function(result){
                try {
                    var obj = JSON.parse(result);
                    callback.call(null,obj);
                } catch (e) {

                }
            }
        });
    }
    $('#date_start,#date_end').datetimepicker();
    var resetCotentView =function(obj){
        if(obj.hasOwnProperty('id')){
            $("#ModelCalendarView #id_view").val(obj.id);
        }
        $("#ModelCalendarView #title").html(obj.title);
        if(obj.url.length!=0){
            $("#ModelCalendarView #url").find('a').show();
            $("#ModelCalendarView #url").find('a').attr('href',obj.url);
        }
        else
            $("#ModelCalendarView #url").find('a').hide();

        $("#ModelCalendarView #time_start").html(obj.start);
        $("#ModelCalendarView #time_end").html(obj.end);
        $("#ModelCalendarView #c_content").html(obj.content);
    }
   var fullCalendar = $('#calendar').fullCalendar({
        editable: true,
        timeFormat:'H(:mm)',
        events: baseURL+'&act=ajax&action=calendar_get_data',
        eventDrop: function(event, dayDelta,minuteDelta,revertFunc) {
                var start = convertDayMonthYear(event.start);
                var end = convertDayMonthYear(event.end);
                action_ajax_json(baseURL + '&act=ajax',{
                    id:event.id,
                    start:start,
                    end:end,
                    dayDelta:dayDelta,
                    action:'calendar_save'
                },function(obj){

                });
        },
        eventResize: function(event,dayDelta,minuteDelta,revertFunc) {
            if (!confirm($("#messageErrorJs").find(".confirmResize").html())) {
                revertFunc();
            }else{
                var start = convertDayMonthYear(event.start);
                var end = convertDayMonthYear(event.end);
                action_ajax_json(baseURL + '&act=ajax',{
                    id:event.id,
                    start:start,
                    end:end,
                    dayDelta:dayDelta,
                    action:'calendar_save'
                });
            }
        },
        eventClick: function (calEvent, jsEvent, view, eventid) {
            jsEvent.preventDefault();
            var eventid = calEvent.id;
            var start = convertDayMonthYear(calEvent.start);
            var end = convertDayMonthYear(calEvent.end);
            var title = calEvent.title;
            var url = calEvent.url;
            var content = calEvent.content;
            end = (end.length ==0)?start:end;
            data = {id:eventid,title:title,url:url,start:start,end:end,content:content};
            resetCotentView(data);
            $("#ModelCalendarView").modal('show');
        },
        loading: function(bool) {
            if (bool) $('#loading').show();
            else $('#loading').hide();
        }
    });
   var Calendar = function() {
        var baseURL = "?admin-page=dashboard";
        var form = $('#form_calendar');
       var initEdit = function(obj) {
           form[0].reset();

           form.find('textarea[name=content]').text($.trim(obj.content));
           form.find('input[name=title]').val($.trim(obj.title));
           form.find('input[id=id]').val($.trim(obj.id));
           form.find("input[name=url]").val($.trim(obj.url));
           form.find("input[name=start]").val($.trim(obj.start));
           form.find("input[name=end]").val($.trim(obj.end));
       };

        var action = function(){
            var type = form.find("input[id=id]").val();
            var data={
                    content:{
                         object:form.find("textarea[name=content]"),
                         vali:'empty|length(5)'
                    },
                    title:{
                        object:form.find("input[name=title]"),
                        vali:'empty|length(5)'
                    },
                    url:{
                        object:form.find("input[name=url]"),
                        vali:'?-!empty.is_url'
                    },
                    start:{
                        object:form.find("input[name=start]"),
                        vali:'date_time'
                    },
                    end:{
                        object:form.find("input[name=end]"),
                        vali:'date_time'
                    }
                };


            if(Validate(data) == 0){

                  var data_ajax = {
                      title:data.title.object.val(),
                      url:data.url.object.val(),
                      content:data.content.object.val(),
                      start:data.start.object.val(),
                      end:data.end.object.val(),
                      action:'calendar_save'
                  }
                  if(type != 0){
                      data_ajax.id = form.find("input[id=id]").val();
                  }

                  action_ajax_json(baseURL + '&act=ajax',data_ajax,function(obj){
                       $("#ModelCalendar").modal('hide');
                       fullCalendar.fullCalendar('refetchEvents');
                       resetCotentView(obj);

                  });
            }
        };
        var resets = function() {
            form[0].reset();
            form.find("input[id=id]").val(0);
            form.find("textarea[name=content]").text("");

        };
        return {
            resetsForm: resets,
            actionForm:action,
            initEdit:initEdit
        };
    }();
    $("#add_new_event").click(function(e){
        e.preventDefault();
        Calendar.resetsForm();
        $("#ModelCalendar").modal('show');
    });
    $("#clear_event").click(function(e){
        e.preventDefault();

        action_ajax_json(baseURL + '&act=ajax',{action:"clear_events",type:"time"},function(obj){
            fullCalendar.fullCalendar('refetchEvents');

        });
    });
    $("#clear_add_event").click(function(e){
        e.preventDefault();
        bootbox.confirm($("#messageErrorJS .confirmDeleteAllEvent").html(), function(result) {
            if (result === true) {
                action_ajax_json(baseURL + '&act=ajax',{action:"clear_events",type:"all"},function(obj){
                    fullCalendar.fullCalendar('refetchEvents');
                });
            }
        });
    });
    $("#btnDelete").click(function(e){
        e.preventDefault();
        var id = $("#ModelCalendarView #id_view").val();
        action_ajax_json(baseURL + '&act=ajax',{action:"delete_events",id:id},function(obj){
            fullCalendar.fullCalendar('refetchEvents');
            console.log(obj);
            $("#ModelCalendarView").modal('hide');
        });
    });
    $("#btnEdit").click(function(e){
        e.preventDefault();
        Calendar.initEdit(data);
        $("#ModelCalendar").modal('show');
    });
    $("#btnSaveEdit").click(function(e){
        e.preventDefault();
        Calendar.actionForm();
    });
    $(".approve_all").click(function(){
       var key = $(this).attr('data');
       var tonken = $("#token_key_"+key).val();
        action_ajax_json(baseURL + '&act=ajax',{action:"update_approve_all",token_key:tonken},function(obj){
            window.location.reload();
        });
    });
});