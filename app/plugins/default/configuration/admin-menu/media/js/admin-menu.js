var Admin_Menu_Settings = {
	data:{},
	current_item_id:'',
	updated_successfully_message:'',
	
	init: function(){
		var data = Admin_Menu_Settings.data;
		for(var item_id in data){
			if (data[item_id].visibility == 'show'){
				var item_setting = data[item_id];
				$('#admin-menu-design > .list-group-show > #'+ item_id + ' i')
				.attr('class',item_setting.icon)
				.css({color:item_setting.icon_color});
				$('#admin-menu-design > .list-group-show > #'+ item_id).appendTo($('#admin-menu-design > .list-group-show'));
			}else{
				var item_setting = data[item_id];
				$('#admin-menu-design > .list-group-hide > #'+ item_id + ' i')
				.attr('class',item_setting.icon)
				.css({color:item_setting.icon_color});
				$('#admin-menu-design > .list-group-hide > #'+ item_id).appendTo($('#admin-menu-design > .list-group-hide'));
			}
		}
		
		$('#menu-icon-picker a').click(function(){
			var current_item_id = Admin_Menu_Settings.current_item_id;
			$('#menu-icon-picker-modal').modal('hide');
			data[current_item_id].icon = $(this).children('i').attr('class');
			$('#menu-item-icon > i').attr('class',data[current_item_id].icon);
			$("#admin-menu-design #"+current_item_id+" > i").attr('class',data[current_item_id].icon);
		});
	},
	
	load: function(item_id,item){
		var data = Admin_Menu_Settings.data;
		
		if (data[item_id] == undefined){
			data[item_id] = {};
			data[item_id].visibility = 'show';
			data[item_id].icon = item.children('i').attr('class');
			data[item_id].icon_color = '#555555';
		}
	},
	
	load_item_setting: function (item){
		var current_item_id = Admin_Menu_Settings.current_item_id;
		var item_setting = {};
		var data = Admin_Menu_Settings.data;
		
		if (data[current_item_id] == undefined){
			data[current_item_id] = {};
			data[current_item_id].visibility = 'show';
			data[current_item_id].icon = item.children('i').attr('class');
			data[current_item_id].icon_color = '#555555';
		}
		
		item_setting = data[current_item_id];
		
		$('#menu-item-name').text(item.text());
		
		switch(data[current_item_id].visibility){
			case 'show':
				$('#menu-item-visibility > #hide').removeClass('active');
				$('#menu-item-visibility > #show').addClass('active');
				break;
			case 'hide':
				$('#menu-item-visibility > #show').removeClass('active');
				$('#menu-item-visibility > #hide').addClass('active');
				break;
		}
		$('#menu-item-visibility > a').click(function(){
			if (!$(this).hasClass('active')){
				var visibility = $(this).attr('id');
				switch(visibility){
					case 'show':
						$('#menu-item-visibility > #hide').removeClass('active');
						$('#menu-item-visibility > #show').addClass('active');
						data[current_item_id].visibility = 'show';
						$('#admin-menu-design  #'+ current_item_id).appendTo($('#admin-menu-design > .list-group-show'));
						break;
					case 'hide':
						$('#menu-item-visibility > #show').removeClass('active');
						$('#menu-item-visibility > #hide').addClass('active');
						data[current_item_id].visibility = 'hide';
						$('#admin-menu-design  #'+ current_item_id).appendTo($('#admin-menu-design > .list-group-hide'));
						break;
				}
			}
		});
		var item_icon = $('<i></i>');
		$('#menu-item-icon').append(
			item_icon
			.css({color:data[current_item_id].icon_color})
			.addClass(item_setting.icon)
		);
		
		item_icon.click(function(){
			$('#menu-icon-picker-modal').modal('show');
		});
		

		
		$('#menu-item-icon-color')
		.css({backgroundColor:data[current_item_id].icon_color})
		.css({borderColor:data[current_item_id].icon_color});
		
		$('#menu-item-icon-color-border')
		.css({borderColor:data[current_item_id].icon_color})
		.val(data[current_item_id].icon_color);
		
		$('#menu-item-icon-color-border').keyup(function(){
			$('#menu-item-icon-color').colorpicker('setValue', $(this).val());
		});
		
		
		$('#menu-item-icon-color').colorpicker({
            color: data[current_item_id].icon_color
        }).on('changeColor', function(ev) {
        	data[current_item_id].icon_color = ev.color.toHex();
        	$('#menu-item-icon-color')
        	.css({backgroundColor:data[current_item_id].icon_color})
        	.css({borderColor:data[current_item_id].icon_color});
        	
        	$('#menu-item-icon-color-border')
    		.css({borderColor:data[current_item_id].icon_color})
    		.val(data[current_item_id].icon_color);
        	
        	$('#menu-item-icon > i').css({color:data[current_item_id].icon_color});
        	$("#admin-menu-design #"+current_item_id+" > i").css({color:data[current_item_id].icon_color});
        });
		
	},
	save : function(){
		$.post($('#save-change-url').val(),{admin_menu_setting:Admin_Menu_Settings.data},function(html){
			$.notification({type: "success", width: "400", content: "<i class='fa fa-check fa-2x'></i> "+Admin_Menu_Settings.updated_successfully_message, html: true, autoClose: true, timeOut: "2000", delay: "0", position: "topRight", effect: "fade", animate: "fadeDown", easing: "easeInOutQuad", duration: "300"});
			$('#sidebar-menu').load($('#load-sidebar-menu-url').val(),function(){
				$(".sidebar .treeview").tree();
			});
		},'html');
	}
};

$(document).ready(function(){
	$('.sidebar-menu:not(#sidebar-hidden-menu) > li').each(function(){
		var item = $('<div class="list-group-item"></div>')
					.attr('id',$(this).attr('id'))
					.append($(this).find('a > span').html());
		$('#admin-menu-design > .list-group-show').append(item);
		
		Admin_Menu_Settings.load($(this).attr('id'),item);
	});
	
	$('#sidebar-hidden-menu > li').each(function(){
		var item = $('<div class="list-group-item"></div>')
					.attr('id',$(this).attr('id'))
					.append($(this).find('a > span').html());
		$('#admin-menu-design > .list-group-hide').append(item);
		
		Admin_Menu_Settings.load($(this).attr('id'),item);
	});
	
	Admin_Menu_Settings.init();
	
	$("#admin-menu-design > .list-group-show").sortable({
		stop: function( event, ui ) {
			var data = Admin_Menu_Settings.data;
			var new_data = {};
			$("#admin-menu-design > .list-group > div").each(function(){
				var item_id = $(this).attr('id');
				new_data[item_id] = data[item_id];

			});
			Admin_Menu_Settings.data = new_data;
		}
	});
	
	$("#admin-menu-design > .list-group-hide").sortable({
		stop: function( event, ui ) {
			var data = Admin_Menu_Settings.data;
			var new_data = {};
			$("#admin-menu-design > .list-group > div").each(function(){
				var item_id = $(this).attr('id');
				new_data[item_id] = data[item_id];

			});
			Admin_Menu_Settings.data = new_data;
		}
	});
	
	$("#admin-menu-design > .list-group > div").click(function(){
		var _this = $(this);
		Admin_Menu_Settings.current_item_id = _this.attr('id');
		$('#admin-menu-settings').load($('#load-template-url').val(),function(){
			Admin_Menu_Settings.load_item_setting(_this);
		});
		$("#admin-menu-design > .list-group-show").children('div').removeClass('active');
		$("#admin-menu-design > .list-group-hide").children('div').removeClass('active');
		$(this).addClass('active');
	});
	
	$("#admin-menu-design > .list-group > div:first").trigger('click');
});