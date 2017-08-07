var pf_layout_setting = {
		relative_path:'',
        admin_folder:'',
		delete_widget_message:'Are you sure to delete widget?',
		ok_button_message:'Ok',
		delete_button_message:'Delete',
		cancel_button_message:'Cancel'
}

function pattern_active(index) {
	$('#pattern-img-' + index).addClass('pattern-active');
	pattern_inactive(index);
	$('#pattern').val(index);
}

function pattern_inactive(index) {
	for (var i = 1; i <= 4; i++) {
		if (i != index)
			$('#pattern-img-' + i).removeClass('pattern-active');
	}
}

$(document).ready(function() {
	pattern_active($('#pattern').val());
	$('#pattern-label-1').click(function() {
		pattern_active(1);
	});
	$('#pattern-label-2').click(function() {
		pattern_active(2);
	});
	$('#pattern-label-3').click(function() {
		pattern_active(3);
	});
	$('#pattern-label-4').click(function() {
		pattern_active(4);
	});

	$('#btn-add').click(function() {
		$('#add-pattern-form').submit();
	});

	$('#btn-add-2').click(function() {
		$('#json_data').val(pf_build_layout_json_data());
		$('#frm_layout_New').submit();
	});
});

function pf_build_layout_json_data() {
	var json_data = {};

	if ($('#panel_1 > li').length > 0) {
		var panel_1 = [];
		$('#panel_1 > li').each(function() {
			var widget = {};
			widget['id'] = $(this).attr('id');
			widget['name'] = $(this).attr('name');
			var text = '';
			text = $(this).text();
			text = text.replace(/\r?\n|\r/g, "");
			widget['value'] = $.trim(text);
			panel_1[panel_1.length] = widget;
		});
		json_data['panel_1'] = panel_1;
	}

	if ($('#panel_2 > li').length > 0) {
		var panel_2 = [];
		$('#panel_2 > li').each(function() {
			var widget = {};
			widget['id'] = $(this).attr('id');
			widget['name'] = $(this).attr('name');
			var text = '';
			text = $(this).text();
			text = text.replace(/\r?\n|\r/g, "");
			widget['value'] = $.trim(text);
			panel_2[panel_2.length] = widget;
		});
		json_data['panel_2'] = panel_2;
	}

	if ($('#panel_3 > li').length > 0) {
		var panel_3 = [];
		$('#panel_3 > li').each(function() {
			var widget = {};
			widget['id'] = $(this).attr('id');
			widget['name'] = $(this).attr('name');
			var text = '';
			text = $(this).text();
			text = text.replace(/\r?\n|\r/g, "");
			widget['value'] = $.trim(text);
			panel_3[panel_3.length] = widget;
		});
		json_data['panel_3'] = panel_3;
	}

	if ($('#panel_4 > li').length > 0) {
		var panel_4 = [];
		$('#panel_4 > li').each(function() {
			var widget = {};
			widget['id'] = $(this).attr('id');
			widget['name'] = $(this).attr('name');
			var text = '';
			text = $(this).text();
			text = text.replace(/\r?\n|\r/g, "");
			widget['value'] = $.trim(text);
			panel_4[panel_4.length] = widget;
		});
		json_data['panel_4'] = panel_4;
	}
	
	

	return JSON.stringify(json_data);
}

function pf_rebuild_layout() {
	var json_data = {};
	json_data = jQuery.parseJSON($('#json_data').val());
	for ( var i in json_data) {
		var widgets = [];
		widgets = json_data[i];
		for ( var j in widgets) {
			var item = $('<li class="sortable-item"></li>') ;
			$('#' + i).append(
					item
					.attr('id',widgets[j].id)
					.attr('name',widgets[j].name)
					.text(widgets[j].value)
			);
			var button_toolbar = $('<div id="button_toolbar" class="pull-right" style="padding:0 5px;"></div>');
			var setting_button = $('<a class="btn btn-default btn-xs"><i class="fa fa-cog"></i></a>');
			var delete_button = $('<a class="btn btn-danger btn-xs"><i class="fa fa-times"></i></a>');
			item.prepend(button_toolbar);
			button_toolbar.append(setting_button).append(' ').append(delete_button);
			setting_button.click(function(){
				var widget = {};
				widget['id'] = $(this).parent().parent().attr('id');
				widget['name'] = $(this).parent().parent().attr('name');
				widget['value'] = $.trim($(this).parent().parent().text());
				pf_layout_setting_form(widget);
			});
			delete_button.click(function(){
				var _object = $(this).parent().parent();
				var name = $(this).parent().parent().attr('name');
				
				$.sModal({
					image:pf_layout_setting.relative_path+ '/app/plugins/default/theme/admin/images/confirm.png',
			    	content:pf_layout_setting.delete_widget_message,
			    	animate:'fadeDown',
			    	buttons:[
			                 {
			                     text:'<i class="fa fa-times-circle"></i> '+ pf_layout_setting.delete_button_message,
			                     addClass:'btn-danger',
			                     click:function(id,data){
			                    	var setting_data = jQuery.parseJSON($('#setting_data').val());
			         				if (setting_data != null && setting_data[name] != null && setting_data[name] != undefined){
			         					delete setting_data[name];
			         				}
			         				$('#setting_data').val(JSON.stringify(setting_data));
			         				_object.remove();
			                         
			                         $.sModal('close',id);
			                     }
			                 },
			                 {
			                     text:pf_layout_setting.cancel_button_message,
			                     click:function(id,data){
			                         $.sModal('close',id);
			                     }
			                 },
			             ]
			    });
				
			});
		}
		
	}
	
}

function pf_layout_s4() {
	return Math.floor((1 + Math.random()) * 0x10000).toString(16).substring(1);
};

function pf_layout_guid() {
	return pf_layout_s4() + pf_layout_s4() + '-' + pf_layout_s4() + '-' + pf_layout_s4() + '-' + pf_layout_s4() + '-' + pf_layout_s4()
			+ pf_layout_s4() + pf_layout_s4();
}

function pf_layout_setting_form(widget){
	$.sModal({
		header:'<i class="fa fa-wrench"></i> '+ widget['value'] + ' Setting',
		content:'<div id="pf_widget_setting"><div style="text-align:center;"><i class="fa fa-spinner fa-spin fa-3x"></i></div></div>',
		animate:'fadeDown',
		onShow:function(id){
			var setting_data = jQuery.parseJSON($('#setting_data').val());
			var name = widget['name'];
			var form_data = {};
			if (setting_data != null && setting_data[name] != undefined && setting_data[name] != null){
				form_data = setting_data[name];
			}
			$.post($('#setting_form_url').val(),{'data':form_data,'widget':widget},function(html){
				$('#'+ id +' #pf_widget_setting').html(html);
			},'html');
		},
		buttons:[
		         {
		        	 text:' &nbsp; '+ pf_layout_setting.ok_button_message +' &nbsp; ',
		        	 addClass:'btn-primary',
		        	 click:function(id,data){
		        		 var setting_data = jQuery.parseJSON($('#setting_data').val());
		        		 if (setting_data == null || setting_data == undefined || (setting_data.length != undefined && setting_data.length <= 0)){
		        			 setting_data = {};
		        		 }
		        		 var o = {};
		        		 var a = $('#'+ id +' form').serializeArray();
		        		 $.each(a, function() {
		        			 if (o[this.name] !== undefined) {
		        				 if (!o[this.name].push) {
		        					 o[this.name] = [o[this.name]];
		        				 }
		        				 o[this.name].push(this.value || '');
		        			 } else {
		        				 o[this.name] = this.value || '';
		        			 }
		        		 });
		        		 var name = widget['name'];
		        		 setting_data[name] = o;
		        		 
		        		 $('#setting_data').val(JSON.stringify(setting_data));

		        		 $.sModal('close',id);
		        	 }
		         },
		         {
		        	 text:pf_layout_setting.cancel_button_message,
		        	 click:function(id,data){
		        		 $.sModal('close',id);
		        	 }
		         }
		         ]
	});
}

$(document).ready(function() {
	$('#plugins .sortable-item').draggable({
		connectToSortable : '#layout_buider_container .sortable-list',
		helper : function(event) {
			var new_helper = $(this).clone();
			new_helper.attr('name',pf_layout_guid());
			new_helper.css({
				'z-index' : 1000
			});
			$(new_helper).width(300);
			return new_helper;
		}
	});

	$('#layout_buider_container .sortable-list').sortable({
		connectWith : '#layout_buider_container .sortable-list',
		placeholder : 'placeholder',
		cursorAt: { top: 15, left: 150 },
		helper: function (event, ui) {
			console.log(event);
			$(ui).width(300);
		    return $(ui);
		},
		start : function(event, ui) {
			var id = $(ui.helper).attr('id');
			if (id != null && id != '') {
				$(ui.item).attr('id', id);
			}
			if ($(ui.helper).attr('name') != null && $(ui.helper).attr('name') != ''){
				$(ui.item).attr('name',$(ui.helper).attr('name'));
			}
			
			if ($(ui.item).children('#button_toolbar').length <= 0){
				var button_toolbar = $('<div id="button_toolbar" class="pull-right" style="padding:0 5px;"></div>');
				var setting_button = $('<a class="btn btn-default btn-xs"><i class="fa fa-cog"></i></a>');
				var delete_button = $('<a class="btn btn-danger btn-xs"><i class="fa fa-times"></i></a>');
				$(ui.item).prepend(button_toolbar);
				button_toolbar.append(setting_button).append(' ').append(delete_button);
				setting_button.click(function(){
					var widget = {};
					widget['id'] = $(this).parent().parent().attr('id');
					widget['name'] = $(this).parent().parent().attr('name');
					widget['value'] = $.trim($(this).parent().parent().text());
					pf_layout_setting_form(widget);
				});
				delete_button.click(function(){
					var _object = $(this).parent().parent();
					var name = $(this).parent().parent().attr('name');
					
					$.sModal({
				    	image:pf_layout_setting.relative_path+ '/'+pf_layout_setting.admin_folder+'/plugins/theme/layouts/images/confirm.png',
				    	content:pf_layout_setting.delete_widget_message,
				    	animate:'fadeDown',
				    	buttons:[
				                 {
				                     text:'<i class="fa fa-times-circle"></i> '+ pf_layout_setting.delete_button_message,
				                     addClass:'btn-danger',
				                     click:function(id,data){
				                    	var setting_data = jQuery.parseJSON($('#setting_data').val());
				         				if (setting_data != null && setting_data[name] != null && setting_data[name] != undefined){
				         					delete setting_data[name];
				         				}
				         				$('#setting_data').val(JSON.stringify(setting_data));
				         				_object.remove();
				                         
				                         $.sModal('close',id);
				                     }
				                 },
				                 {
				                     text:pf_layout_setting.cancel_button_message,
				                     click:function(id,data){
				                         $.sModal('close',id);
				                     }
				                 },
				             ]
				    });
				});
			}
		},
		stop: function (event, ui) {
            ui.item.css('width', '');
        }
	});

});