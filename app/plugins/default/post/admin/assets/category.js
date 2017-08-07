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
$(document).ready(function() {
    //config nestable
    var baseURL = "?admin-page=post&sub_page=category";
    var nestable = $('#nestable');
    var alertMessage = $('#alertMessage');
    var form = $('form');
    var modal = $("#categoryModal");

    var Category = function() {
        //Base
        var getMessage = function(key) {
            var messages = $('#messageErrorJS');
            var message = messages.find('li.' + key).text();
            if (message.length > 0) {
                return message;
            }
            return '';
        };

        var statusButtonCategory = function() {
            var length = +nestable.find('li').length;
            var sortCategory = $('.btnSortWrap');
            if (length) {
                sortCategory.removeClass('hidden');
            } else {
                sortCategory.addClass('hidden');
            }
        };

        var alertMessageBootstrap = function(title, type, messages, obj) {
            var messageWrap = $('#control-clone').find('.alert').clone();
            var html;
            html = '<h4>' + title + '</h4>';

            if (messages instanceof Array) {
                html += '<ul>';
                $.each(messages, function(i, message) {
                    html += '<li>' + message + '</li>';
                });
                html += '</ul>';
            } else {
                html += '<p>' + messages + '</p>';
            }


            messageWrap.find('.bodyMessage').append(html);
            console.log(messageWrap);
            messageWrap.addClass('alert-' + type);
            messageWrap.addClass('alert-modal');


            obj.html(messageWrap);
        };

        var init = function(type) {
            form.get(0).reset();
            if (nestable.data('action') === 'edit' || type === true) {
                $.post(baseURL + "&act=ajax_get_dropdown", function(result) {
                    if (result.length > 0) {
                        var dropdown = JSON.parse(result);
                        updateParentSelect(form.find('select'), dropdown);
                    }
                });
            }
        };

        //Load data from database
        var loadCategory = function() {
            $.post(baseURL + "&act=ajax_get_category", function(category) {
                nestable.html($(category));
                statusButtonCategory();
            });
        };
        /*
         * Create and update category
         */
        var validatorCategory = function(obj) {
            var errors = new Array();
            var name = obj.find("input[name=name]").val();
            var parent = +obj.find("select").val();
            if (name.length < 2 || name.length > 255) {
                errors.push(getMessage('validatorName'));
            }

            if (parent < 0) {
                errors.push(getMessage('validatorParent'));
            }
            return errors;
        };

        var buildCategoryItem = function(obj, id) {
            var name = obj.find('input[name=name]').val();
            var parent = +obj.find('select').val();
            var append = '.categorySort';

            //Check exists parent category
            if (parent !== 0) {
                append = '#list_' + parent;
                if ($(append).find('ol').length === 0) {
                    $(append).append('<ol></ol>');
                }
            }
            //Clone and bind data to item
            var control = $('#control-clone').find('li');
            control.find('.dd-handle').text(name);
            control.attr('id', 'list_' + id);
            control.attr('data-id', id);

            //Add and show nestable
            if ($(append).find('ol').first().length === 0) {
                append = $(append).html($('<ol class="sortable list-unstyled ui-sortable"></ol>'));
            }
            control.clone().appendTo($(append).find('ol').first());
        };

        var updateParentSelect = function(obj, data) {
            var select = '';
            $.each(data, function(i, value) {
                select += '<option value="' + value.id + '">' + value.name + '</option>';
            });
            $(document).ajaxComplete(function() {
                obj.html($(select));
            });
        };

        var createCategory = function(obj) {
            var form = obj.closest('form');
            var validator = validatorCategory(form);
            if (validator.length === 0) {
                var data = form.serialize();
                nestable.slideUp(function() {
                    $.post(baseURL + '&act=ajax_add_category', data, function(result) {
                        if (result.length > 0) {
                            var data = JSON.parse(result);
                            buildCategoryItem(form, data.id);
                            updateParentSelect(form.find('select'), data.parent);
                            form.get(0).reset();
                            form.find('textarea').text('');
                            alertMessageBootstrap('Success!', 'success', 'You have successfully added categories', alertMessage);
                        }
                        nestable.slideDown();
                        statusButtonCategory();
                    });
                });
            } else {
                alertMessageBootstrap('You see the following error', 'danger', validator, alertMessage);
            }
        };

        //Update category
        var editCategory = function(obj) {
            var form = $('form');
            var id = +obj.closest('li').data('id');
            form.data('action', 'edit');
            $.post(baseURL + '&act=ajax_get_category_edit', {id: id}, function(result) {
                if (result.length > 0) {
                    result = JSON.parse(result);
                    var category = result.category;
                    form.find('input[name=id]').val(category.id);
                    form.find('input[name=name]').val(category.category_name);
                    form.find('textarea').text(category.category_description);
                    updateParentSelect(form.find('select'), result.categories);
                    $(document).ajaxComplete(function() {
                        form.find('option').each(function() {
                            if (+$(this).val() === parseInt(category.category_parent)) {
                                $(this).attr("selected", "selected");
                                $(this).show();
                            }
                        });
                    });
                } else {

                }
            });
        };

        var updateCategory = function(obj) {
            var form = obj.closest('form');
            var validator = validatorCategory(form);
            if (validator.length === 0) {
                var data = form.serialize();
                nestable.slideUp(function() {
                    $.post(baseURL + '&act=ajax_update_category', data, function(result) {
                        if (result.length > 0) {
                            var data = JSON.parse(result);
                            updateParentSelect(form.find('select'), data);
                            form.get(0).reset();
                            form.find('textarea').text('');
                            modal.modal('hide');
                            //alertMessageBootstrap('Success!', 'success', 'You have successfully updated categories', alertMessage);
                            loadCategory();
                        }
                        nestable.slideDown();
                        statusButtonCategory();
                    });
                });
            } else {
                alertMessageBootstrap('You see the following error', 'danger', validator, alertMessage);
            }
        };

        //Delete category
        var deleteCategory = function(id) {
            bootbox.confirm(getMessage('confirmDelete'), function(result) {
                if (result) {
                    nestable.slideUp(function() {
                        $.post(baseURL + "&act=ajax_delete_category", {id: id}, function(result) {
                            if (result.length > 0) {
                                var parse = JSON.parse(result);
                                updateParentSelect($('select'), parse);
                                loadCategory();
                                base.notification('success', getMessage('deleteSuccess'));
                            } else {
                                base.notification('error', getMessage('deleteError'));
                            }
                            statusButtonCategory();
                        });
                        nestable.slideDown();
                    });
                }
            });
        };

        //Sort category
        var sortCategory = function() {
            var oSortable = $('.sortable').nestedSortable('toArray');
            $('.sortable').slideUp(function() {
                $.post(baseURL + '&act=ajax_sort_category', {category: oSortable}, function(result) {
                    if (result.length > 0) {
                        var parse = JSON.parse(result);
                        updateParentSelect($('select'), parse);
                    }
                    $('.sortable').slideDown();
                });
            });
        };

        return{
            init: init,
            loadCategory: loadCategory,
            createCategory: createCategory,
            deleteCategory: deleteCategory,
            sortCategory: sortCategory,
            editCategory: editCategory,
            updateCategory: updateCategory
        };
    }();

    $(document).ajaxComplete(function() {
        $('.sortable').nestedSortable({
            handle: 'div',
            items: 'li',
            toleranceElement: '> div',
            maxLevels: 5
        });
    });
    Category.loadCategory();
    Category.init(true);

    $('#categoryModal').on('show.bs.modal', function() {
        form.get(0).reset();
        Category.init();
    });

    //Save Category
    $('#btnSaveCategory').on('click', function(e) {
        e.preventDefault();
        if (+$(this).closest('form').find('input[name=id]').val() > 0) {
            Category.updateCategory($(this));
        } else {
            Category.createCategory($(this));
        }
    });

    //Delete category
    nestable.on('click', 'button[name=delete]', function() {
        var id = $(this).closest('li').data('id');
        Category.deleteCategory(id);
    });

    nestable.on('click', 'button[name=edit]', function() {
        modal.modal('show');
        nestable.data('action', 'edit');
        Category.editCategory($(this));
    });

    //Sort
    $('#sortCategory').on('click', function(e) {
        e.preventDefault();
        Category.sortCategory();
    });

    //Restart
    $('#categoryModal').on('hidden.bs.modal', function() {
        $(this).find('.alert-modal').remove();
        Category.init();
        nestable.data('action', 'add');
    });

    window.setInterval(function() {
        $(".alert-modal").fadeTo(500, 0).slideUp(500, function() {
            $(this).remove();
        });
    }, 10000);
});
