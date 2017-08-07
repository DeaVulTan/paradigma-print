$(document).ready(function() {
    $('.btn-theme-primary').click(function(event){
        var multiple = $('.polls-multiple').attr('value');
        var len =  $( "input:checked" ).length;
        if(len > multiple){
           window.confirm("The maximum number of votes is "+multiple+" !");
           return false;
        }
    });
    
    $('input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
    $('input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
});