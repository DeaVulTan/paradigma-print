/*------------------------------------------------------------------
 Project:	Heaven
 Version:	1.1
 Created: 		27/11/2013
 Last change:	12/01/2014
 -------------------------------------------------------------------*/

// Recent works thumbnail image height resize
//===========================================
/*
 $('.recent-works .thumbnail > .image').on('resize', function() {
 $('.recent-works .thumbnail > .image').height($('.recent-works .thumbnail > .image').width() / 1.6);
 }).resize();
 
 // Sign In & Sign Out
 // ==================
 
 $('#sign-in').on('click', function() {
 $("#user-bar").toggleClass("show hidden");
 $("#user-bar").toggleClass("animated flipInX");
 $("#sign-in").toggleClass("hidden show");
 $("#sign-up").toggleClass("hidden show");
 $("#sign-in").removeClass("animated flipInX");
 $("#sign-up").removeClass("animated flipInX");
 return false;
 });
 
 $('#sign-out').on('click', function() {
 $("#user-bar").toggleClass("show hidden");
 $("#user-bar").toggleClass("animated flipInX");
 $("#sign-in").toggleClass("hidden show");
 $("#sign-in").addClass("animated flipInX");
 $("#sign-up").toggleClass("hidden show");
 $("#sign-up").addClass("animated flipInX");
 return false;
 });
 
 // Style Toggle
 // ============
 
 $('.style-toggle-btn').on('click', function() {
 $(".style-toggle-btn").toggleClass("show hidden");
 $(".style-toggle").toggleClass("hidden show");
 return false;
 });
 
 $('.style-toggle-close').on('click', function() {
 $(".style-toggle-btn").toggleClass("show hidden");
 $(".style-toggle").toggleClass("hidden show");
 return false;
 });
 
 // Body Style Change
 // =================
 
 $('.style-toggle ul > li.green').on('click', function() {
 $("body").addClass("body-green");
 $("body").removeClass("body-blue");
 $("body").removeClass("body-red");
 $("body").removeClass("body-amethyst");
 return false;
 });
 
 $('.style-toggle ul > li.blue').on('click', function() {
 $("body").addClass("body-blue");
 $("body").removeClass("body-green");
 $("body").removeClass("body-red");
 $("body").removeClass("body-amethyst");
 return false;
 });
 
 $('.style-toggle ul > li.red').on('click', function() {
 $("body").addClass("body-red");
 $("body").removeClass("body-green");
 $("body").removeClass("body-blue");
 $("body").removeClass("body-amethyst");
 return false;
 });
 
 $('.style-toggle ul > li.amethyst').on('click', function() {
 $("body").addClass("body-amethyst");
 $("body").removeClass("body-blue");
 $("body").removeClass("body-red");
 $("body").removeClass("body-green");
 return false;
 });
 
 // Lost password
 // =============
 
 $('#lost-btn').on('click', function() {
 $("#lost-form").toggleClass("show hidden");
 return false;
 });
 
 // Contact Us
 // ==========
 
 $('#signed-in').on('click', function() {
 $(".form-white > .contact-avatar > span").toggleClass("show hidden");
 $(".form-white > .contact-avatar > img").toggleClass("show hidden animated flipInX");
 return false;
 });
 
 $('#signed-in').on('click', function() {
 $("#email-contact").toggleClass("show hidden");
 $("#email-contact-disabled").toggleClass("show hidden");
 $("#name-1").toggleClass("show hidden");
 $("#name-1-disabled").toggleClass("show hidden");
 $("#name-2").toggleClass("show hidden");
 $("#name-2-disabled").toggleClass("show hidden");
 return false;
 });
 
 $('#signed-in').on('click', function() {
 $("#signed-in > i").toggleClass("fa-circle-o fa-dot-circle-o");
 return false;
 });
 
 // Search box toggle
 // =================
 $('#search-btn').on('click', function() {
 $("#search-icon").toggleClass('fa-search fa-times margin-2');
 $("#search-box").toggleClass('hidden show animated flipInX');
 return false;
 });
 
 // Error page
 // ==========
 
 var divs = $("i.random").get().sort(function() {
 return Math.round(Math.random()) - 0.5; //random so we get the right +/- combo
 }).slice(0, 1)
 $(divs).show();
 
 var divs = $("i.random2").get().sort(function() {
 return Math.round(Math.random()) - 0.5; //random so we get the right +/- combo
 }).slice(0, 1)
 $(divs).show();
 
 var divs = $("i.random3").get().sort(function() {
 return Math.round(Math.random()) - 0.5; //random so we get the right +/- combo
 }).slice(0, 1)
 $(divs).show();
 
 // Corporate Index Features
 // ========================
 
 $('.crp-ft').hover(function() {
 $(this).children("a").toggleClass("show hidden");
 $(this).children("a").toggleClass("animated flipInX");
 return false;
 });
 */


$('#search-btn').on('click', function() {
    $("#search-icon").toggleClass('fa-search fa-times margin-2');
    $("#search-box").toggleClass('hidden show animated flipInX');
    $('#usermenu').attr('class','hidden');
    return false;
});

$('#btn-search,#btn-search-xs').on('click', function() {
    search($(this));
});
$('#user-dropdown').on('click',function(){
    if($('#search-icon').attr('class')=='fa fa-color fa-times margin-2'){
        $("#search-icon").attr('class','fa fa-color fa-search');
        $("#search-box").toggleClass('hidden animated flipInX');
    }
    if($('#usermenu').attr('class')=='hidden')
        $('#usermenu').attr('class','show');
    else
        $('#usermenu').attr('class','hidden');
    return false;
});
$('#search-box,#search-box-xs').find('input[type=text]').on('keypress', function(e) {
    if (e.keyCode === 13) {
        e.preventDefault();
        search($(this));
    }
});

function search(obj) {
    var form = obj.closest('form');
    var title = form.find('input[type=text]').val();
    if (title.length > 0) {
        var action = form.attr('action');
        window.location = action + '/title:' + title;
    }
}
// Corporate Index Features
$('.crp-ft').hover(function() {
    $(this).children("a").toggleClass("show hidden");
    $(this).children("a").toggleClass("animated flipInX");
    return false;
});

$.fn.exists = function() {
    return this.length > 0;
};
// JS of shortcode
$(document).ready(function() {
    
    $('.footer').on('click', '.style-toggle-btn', function() {
        $(".style-toggle-btn").toggleClass("show hidden");
        $(".style-toggle").toggleClass("hidden show");
        return false;
    });
    $('.footer').on('click', '.style-toggle-close', function() {
        $(".style-toggle-btn").toggleClass("show hidden");
        $(".style-toggle").toggleClass("hidden show");
        return false;
    });
    $('#navbar-collapse a').click(function() {
        var li_parent = $(this).parent();
        var liclass = li_parent.attr('class');
        if (liclass == 'dropdown open') {
            window.location = $(this).attr('href');
        }
    });
    $('.hover-dropdown').dropdownHover().dropdown();
    $("#user-bar a, #sign-in a, #sign-up a").tooltip();
    var self = $("#navbar-collapse .dropdown .sub").find(".active-color");
    $("#navbar-collapse>ul>li").each(function() {
        var obj = $(this).find(".active-color");
        if (typeof obj.html() != 'undefined') {
            $(this).attr("class", "dropdown active-color");
            obj.attr("class", "");
            return false;
        }
    });
    if ($('.pf-contactform-shortcode').exists()) {
        $('.pf-contactform-shortcode input[type=radio],input[type=checkbox]').iCheck({
            checkboxClass: 'icheckbox_minimal-grey',
            radioClass: 'iradio_minimal-grey',
            increaseArea: '20%' // optional
        });
        $('.pf-contactform-shortcode').find('input[data-type=date]').datetimepicker();
    }
    
    $('.sub').find('.caret').addClass('fa fa-angle-right').removeClass('caret').attr('style','float:right;margin-top:2px;');
    
    $('#post-lists').find('hr').last().remove();
    if($('.main-menu >li >a >p').hasClass('menu-desc')==true) $('.main-menu >li').height('78px');
});