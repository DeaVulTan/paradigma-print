$(document).ready(function(){
	function nl2br (str, is_xhtml) {   
	    var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';    
	    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1'+ breakTag +'$2');
	}
   $('.file').click(function(){
    var baseURL = '../tmp/logs/';
	var text = $(this).attr("id");
	$.get(baseURL+text, function(data) {
	   $(".modal-body").html(nl2br(data));
	   });
	})
})
