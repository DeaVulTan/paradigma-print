$(function() {
    baseURL = "?admin-page=dashboard";
$(".connectedSortable").sortable({
    placeholder: "sort-highlight",
    connectWith: ".connectedSortable",
    handle: ".box-header, .nav-tabs",
    forcePlaceholderSize: true,
    zIndex: 999999,
    update: savePosition
}).disableSelection();
function savePosition(event, ui ){
    var place="";
    var count = 0;
    $("[id^=list-sort-]").each(function() {
        count++;
        if(this.parentNode.id != 'undefined')
            place += (this.parentNode.id + "[]" + "="+ this.id + "&");
    });
    place+='action=layout';
    $.ajax({
        type: "post",
        url: baseURL + '&act=ajax',
        data: place,
        success: function (res) {
             console.log(res);
        }
    });
}
$("#left,#right,#content").on("sortstart", function(event, ui) {
    console.log("sortstart");
    $(".position-sort-temp").show();
});

$("#left,#right,#content").on("sortchange", function(event, ui) {
        console.log("sortchange");
});

$("#left,#right,#content").on("sortstop", function(event, ui) {
    $(".position-sort-temp").hide();
});

});
