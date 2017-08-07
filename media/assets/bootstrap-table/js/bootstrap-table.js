(function ( $ ) {
	var _bTable = {
		obj:null,
		data:{},
		table_container:null,
		init:function(obj){
			_bTable.data = obj.data();
			_bTable.obj = obj;
			obj.hide();
			_bTable.table_container = $('<div></div>').addClass('table-container');
			obj.after(_bTable.table_container);
		},
		build:function(){
			if (_bTable.obj == null) return;
			
			var fixed_left = false;
			var first_row = _bTable.obj.find('tr:first');
			var rows = _bTable.obj.find('tr:not(:first)');
			
			var table_fix_left = $('<div></div>').addClass('table-fix-left');
			var table_fix_left_header = $('<div></div>').addClass('table-fix-left-header');
			table_fix_left_header.appendTo(table_fix_left);
			var flh_table = $('<table></table>').addClass('table');
			flh_table.appendTo(table_fix_left_header);
			var flh_table_tr = $('<tr></tr>');
			flh_table_tr.appendTo(flh_table);
			
			var table_fix_left_body = $('<div></div>').addClass('table-fix-left-body');
			if (_bTable.data.height != undefined){
				table_fix_left_body.height(_bTable.data.height);
			}
			table_fix_left_body.appendTo(table_fix_left);
			var flb_table = $('<table></table>').addClass('table table-striped');
			flb_table.appendTo(table_fix_left_body);
			
			var fixed_right = false;
			var table_fix_right = $('<div></div>').addClass('table-fix-right');
			var table_fix_right_header = $('<div></div>').addClass('table-fix-right-header');
			table_fix_right_header.appendTo(table_fix_right);
			var frh_table = $('<table></table>').addClass('table');
			frh_table.appendTo(table_fix_right_header);
			var frh_table_tr = $('<tr></tr>');
			frh_table_tr.appendTo(frh_table);
			
			var table_fix_right_body = $('<div></div>').addClass('table-fix-right-body');
			if (_bTable.data.height != undefined){
				table_fix_right_body.height(_bTable.data.height);
			}
			table_fix_right_body.appendTo(table_fix_right);
			var frb_table = $('<table></table>').addClass('table table-striped');
			frb_table.appendTo(table_fix_right_body);
			
			first_row.children().each(function(index){
				var th_div = $('<div></div>').css({'overflow':'hidden','white-space':'nowrap'});
				$(this).contents().wrap(th_div);
			});
			
			var tfl_index = [];
			var tfr_index = [];
			first_row.children().each(function(index){
				var th_data = $(this).data();
				if (th_data.fixed != undefined && th_data.fixed == 'left'){
					tfl_index[tfl_index.length] = index;
					$(this).appendTo(flh_table_tr);
					fixed_left = true;
				}
			});
			
			
			var table_scroll = $('<div></div>').addClass('table-scroll');
			if (fixed_left == true){
				rows.each(function(){
					var flb_table_tr = $('<tr></tr>');
					flb_table_tr.appendTo(flb_table);
					for(var i =0; i < tfl_index.length; i++){
						var index = tfl_index[i];
						$($(this).children()[index]).appendTo(flb_table_tr);
					}
				});
				
				table_fix_left.appendTo(_bTable.table_container);
				
				
				flh_table_tr.children().each(function(index){
					$(flb_table.find('tr:first').children()[index]).width($(this).width());
				});
				
				flb_table.find('tr:first').children().each(function(index){
					$(flh_table_tr.children()[index]).width($(this).width());
				});
				
				$(window).on('resize', function(){
					flh_table_tr.children().each(function(index){
						$(flb_table.find('tr:first').children()[index]).width($(this).width());
					});
					
					flb_table.find('tr:first').children().each(function(index){
						$(flh_table_tr.children()[index]).width($(this).width());
					});
				});
				
				table_scroll.css('margin-left',table_fix_left.width()+'px');
			}
			
			first_row.children().each(function(index){
				var th_data = $(this).data();
				if (th_data.fixed != undefined && th_data.fixed == 'right'){
					tfr_index[tfr_index.length] = index;
					$(this).appendTo(frh_table_tr);
					fixed_right = true;
				}
			});
			
			
			if (fixed_right == true){
				rows.each(function(){
					var frb_table_tr = $('<tr></tr>');
					frb_table_tr.appendTo(frb_table);
					for(var i =0; i < tfr_index.length; i++){
						var index = tfr_index[i];
						$($(this).children()[index]).appendTo(frb_table_tr);
					}
				});

				table_fix_right.appendTo(_bTable.table_container);
				$(window).on('resize', function(){
					frh_table_tr.children().each(function(index){
						$(frb_table.find('tr:first').children()[index]).width($(this).width());
					});
					frb_table.find('tr:first').children().each(function(index){
						$(frh_table_tr.children()[index]).width($(this).width());
					});
				});
				
			}
			
			table_scroll.appendTo(_bTable.table_container);
			
			var table_header_container = $('<div></div>').addClass('table-header-container').css({'padding-right':'17px'});
			table_header_container.appendTo(table_scroll);
			var table_header = $('<div></div>').addClass('table-header');
			table_header.appendTo(table_header_container);
			var table_header_body = $('<div></div>');
			table_header_body.appendTo(table_header);
			var htable = $('<table></table>').addClass('table');
			
			htable.appendTo(table_header_body);
			first_row.appendTo(htable);
			
			var table_body = $('<div></div>').addClass('table-body').height(_bTable.data.height);
			if (_bTable.data.height != undefined){
				table_body.height(_bTable.data.height);
			}
			table_body.appendTo(table_scroll);
			var table_body_container = $('<div></div>').addClass('table-body-container');
			table_body_container.appendTo(table_body);
			var btable = $('<table></table>').addClass('table table-striped');
			btable.appendTo(table_body_container);
			
			rows.appendTo(btable);
			
			if (fixed_right == true){
				table_body_container.css('padding-right',table_fix_right.width()+'px');
				table_header_body.css('padding-right',table_fix_right.width()+'px');
			}
			
			
			table_header_container.css({'padding-right':(table_body[0].offsetWidth - table_body[0].clientWidth)+'px'});
			table_fix_right.css({'margin-right':(table_body[0].offsetWidth - table_body[0].clientWidth)+'px'});
			
			
			first_row.children().each(function(index){
				$(this).width($(this).width());
				$($(rows[0]).children()[index]).width($(this).width());
			});
			$(rows[0]).children().each(function(index){
				$(first_row.children()[index]).width($(this).width());
				var _brw = $(first_row.children()[index]).css('border-right-width');
				_brw = _brw.replace("px","");
				var th_div_width = $(this).width() + (parseInt(_brw) - 1);
				$(first_row.children()[index]).children().width(th_div_width);
			});
			
			$(window).on('resize', function(){
				if (table_body_container.width() < (table_header.width() - table_fix_right.width())){
					table_body_container.width(table_header.width() - table_fix_right.width());
					table_header_body.width(table_header.width() - table_fix_right.width());
					
					first_row.children().each(function(index){
						$(this).width($(this).width());
						$($(rows[0]).children()[index]).width($(this).width());
					});
					$(rows[0]).children().each(function(index){
						$(first_row.children()[index]).width($(this).width());
						var _brw = $(first_row.children()[index]).css('border-right-width');
						_brw = _brw.replace("px","");
						var th_div_width = $(this).width() + (parseInt(_brw) - 1);
						$(first_row.children()[index]).children().width(th_div_width);
					});
				}
				
				btable.find('tr').each(function(index){
					$(this).height(0);
			        $(flb_table.find('tr')[index]).height(0);
			        $(frb_table.find('tr')[index]).height(0);
					
			        var tr_body_height = $(this).height();
			        var tr_body_fix_left_height = $(flb_table.find('tr')[index]).height();
			        var tr_body_fix_right_height = $(frb_table.find('tr')[index]).height();
			        
			        var new_body_height = tr_body_height;
			        new_body_height = (new_body_height > tr_body_fix_left_height)?new_body_height:tr_body_fix_left_height;
			        new_body_height = (new_body_height > tr_body_fix_right_height)?new_body_height:tr_body_fix_right_height;
			        if (new_body_height < 39){
			            new_body_height = 39;
			        }
			        
			        $(this).height(new_body_height);
			        $(flb_table.find('tr')[index]).height(new_body_height);
			        $(frb_table.find('tr')[index]).height(new_body_height);
			    });
				if (table_body[0].offsetHeight - table_body[0].clientHeight > 0){
					table_fix_left_body.height(table_body.height() - (table_body[0].offsetHeight - table_body[0].clientHeight));
					table_fix_right_body.height(table_body.height() - (table_body[0].offsetHeight - table_body[0].clientHeight));
			    }
			});
			
			
			if (_bTable.data.width != undefined){
				table_body_container.width(_bTable.data.width);
				table_header_body.width(_bTable.data.width);
			}else{
				table_body_container.width(table_header.width() - table_fix_right.width());
				table_header_body.width(table_header.width() - table_fix_right.width());
			}
			
			btable.find('tr').each(function(index){
		        var tr_body_height = $(this).height();
		        var tr_body_fix_left_height = $(flb_table.find('tr')[index]).height();
		        var tr_body_fix_right_height = $(frb_table.find('tr')[index]).height();
		        
		        var new_body_height = tr_body_height;
		        new_body_height = (new_body_height > tr_body_fix_left_height)?new_body_height:tr_body_fix_left_height;
		        new_body_height = (new_body_height > tr_body_fix_right_height)?new_body_height:tr_body_fix_right_height;
		        if (new_body_height < 39){
		            new_body_height = 39;
		        }
		        
		        $(this).height(new_body_height);
		        $(flb_table.find('tr')[index]).height(new_body_height);
		        $(frb_table.find('tr')[index]).height(new_body_height);
		    });
			
			var tr_header_height = htable.find('th:first').height();
		    var tr_header_fix_left_height = flh_table.find('th:first').height();
		    var tr_header_fix_right_height = frh_table.find('th:first').height();
		    
		    var new_header_height = tr_header_height;
		    new_header_height = (new_header_height > tr_header_fix_left_height)?new_header_height:tr_header_fix_left_height;
		    new_header_height = (new_header_height > tr_header_fix_right_height)?new_header_height:tr_header_fix_right_height;
		    
		    htable.find('th:first').height(new_header_height);
		    flh_table.find('th:first').height(new_header_height);
		    frh_table.find('th:first').height(new_header_height);
			
			if (table_body[0].offsetHeight - table_body[0].clientHeight > 0){
				table_fix_left_body.height(table_body.height() - (table_body[0].offsetHeight - table_body[0].clientHeight));
				table_fix_right_body.height(table_body.height() - (table_body[0].offsetHeight - table_body[0].clientHeight));
		    }
			
			table_body.scroll(function() {
				table_header.scrollLeft($(this).scrollLeft());
				table_fix_left_body.scrollTop($(this).scrollTop());
				table_fix_right_body.scrollTop($(this).scrollTop());
		    });
			
		}
		
	};
	
    $.fn.bTable = function( ) {
        this.each(function() {
        	_bTable.init($(this));
        	_bTable.build();
        });
    };
 
}( jQuery ));
$(document).ready(function(){
	$('.bootstrap-table').bTable();
});