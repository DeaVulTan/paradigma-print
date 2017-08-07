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
$(function() {
    var form = $('form');
    var ContactForm = function() {
        var showItem = function(key, data) {
            var source = $('#controlGroup' + key).html();
            var template = Handlebars.compile(source);
            $('.showItem').html(template(data));
        };

        var getAllValue = function(data) {
            var result = $.map(data, function(item) {
                var name = item['name'];
                var value = item['value'];
                if (name === 'type') {
                    return;
                } else if (name === 'items') {
                    value = value.split('\r\n');
                    if (value.length > 0) {
                        return "items='" + value.join('|') + "'";
                    }
                } else {
                    return name + "='" + value + "'";
                }
            });
            return result;
        };

        var addItemToForm = function(obj) {
            if ($('.showItem').find('input[name=name]').length > 0) {
                var name = $('.showItem').find('input[name=name]').val().length;
                if (name > 32 || name < 1) {
                    base.notification('error', base.getMessage('errorLengthName'));
                    return;
                }
            }
            var controlItem = obj.closest('.modal-body').find('.controlItem');
            var type = controlItem.data('type');
            if (type === 'button') {
                type = controlItem.find('select').val();
            }

            var data = $('.showItem').serializeArray();
            var value = ContactForm.getAllValue(data);
            value.unshift("type='" + type + "'");
            var shortCode = '{ct:'+type+' ' + value.join(' ') + '}';
            var form = $('#form');

            form.selection('insert', {
                text: shortCode,
                mode: 'before'
            });
            $('.modal').modal('hide');
            form.focus();
        };
        return {
            getAllValue: getAllValue,
            showItem: showItem,
            addItemToForm: addItemToForm
        };
    }();

    $('.btnAddLists').find('button').on('click', function() {
        var key = $(this).data('group');
        var type = $(this).data('type');
        ContactForm.showItem(key, {
            type: type
        });
    });

    $('#btnAddItem').on('click', function() {
        ContactForm.addItemToForm($(this));
    });
    $('.btnSave').on('click', function() {
        form[0].submit();
    });
});