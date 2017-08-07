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
    $.fn.exists = function() {
        return this.length > 0;
    };
    var base = function() {
        var bulkActionCheckBox = $('#bulkAction');
        var checkAll = $('#checkAll');
        var renderHTML = function(key, data) {
            var source = $(key).html();
            var template = Handlebars.compile(source);
            return $.parseHTML(template(data));
        };
        //Base
        var getMessage = function(key) {
            var messages = $('#messageErrorJS');
            var message = messages.find('li.' + key).text();
            if (message.length > 0) {
                return message;
            }
            return '';
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
            messageWrap.addClass('alert-' + type);
            messageWrap.addClass('alert-modal');
            obj.html(messageWrap);
        };

        var moveCursorToEnd = function(input) {
            var originalValue = input.val();
            input.val('');
            input.blur().focus().val(originalValue);
        };

        var confirmDelete = function(obj, e) {
            e.preventDefault();
            var link = $(obj).attr('href');
            bootbox.confirm(getMessage('confirmDelete'), function(result) {
                if (result === true) {
                    redirectToURL(link);
                }
            });
        };

        //State and change state checkbox
        var checkStateCheckBox = function() {
            var count = $('.itemCheckBox:checked').length;
            var length = $('.itemCheckBox').length;
            if (count > 0) {
                bulkActionCheckBox.fadeIn(100);
            } else {
                checkAll.iCheck('uncheck');
                bulkActionCheckBox.fadeOut(100);
            }
            if (length === count) {
                checkAll.iCheck('check');
            } else {
                checkAll.iCheck('uncheck');
            }
        };

        var stateCheckBox = function() {
            $('body').on('ifUnchecked', '.itemCheckBox', function() {
                checkStateCheckBox();
            }).on('ifChecked', '.itemCheckBox', function() {
                checkStateCheckBox();
            }).on('ifClicked', '#checkAll', function() {
                var state = 'check';
                if (this.checked) {
                    state = 'uncheck';
                }
                $('.itemCheckBox').iCheck(state);
            });
        };

        //Bulk Action
        var bulkAction = function(obj) {
            var checked = $('.itemCheckBox:checked');
            console.log(checked);
            if (checked.length === 0) {
                base.notification('error', getMessage('notChecked'));
                return false;
            }
            bootbox.confirm(getMessage('confirmBulkAction'), function(result) {
                if (result) {
                    var action = +obj.data('action');
                    $('form').find('input[name=action]').val(action);
                    $('form').submit();
                }
            });
        };

        //URL and search
        var getUrl = function() {
            return window.location.href;
        };
        var existParameterURL = function(url, field) {
            if (url.indexOf('?' + field + '=') !== -1)
                return true;
            else if (url.indexOf('&' + field + '=') !== -1)
                return true;
            return false;
        };
        var changeParameterURL = function(href, paramName, newVal) {
            var tmpRegex = new RegExp("(" + paramName + "=)[a-zA-Z0-9]+", 'ig');
            return href.replace(tmpRegex, '$1' + newVal);
        };

        var redirectToURL = function(href) {
            window.location.href = href;
        };
        var resetPagination = function(url) {
            if (existParameterURL(url, 'current')) {
                url = changeParameterURL(url, 'current', '1');
            }
            return url;
        };
        var search = function(kw) {
            if (kw.length === 0) {
                base.notification('error', getMessage('errorSearch'));
            } else {
                var url = getUrl();
                if (existParameterURL(url, 'kw')) {
                    url = changeParameterURL(url, 'kw', kw);
                } else {
                    url += "&kw=" + kw;
                }
                redirectToURL(resetPagination(url));
            }
        };

        var notification = function(type, content) {
            $.notification({
                type: type,
                width: "400",
                content: content,
                html: true,
                autoClose: true,
                timeOut: "4000",
                delay: "0",
                position: "topRight",
                effect: "fade",
                animate: "fadeDown",
                easing: "easeInOutQuad",
                duration: "300"
            });
        };
        return {
            renderHTML: renderHTML,
            getMessage: getMessage,
            alertMessageBootstrap: alertMessageBootstrap,
            moveCursorToEnd: moveCursorToEnd,
            confirmDelete: confirmDelete,
            stateCheckBox: stateCheckBox,
            bulkAction: bulkAction,
            search: search,
            notification: notification,
            checkStateCheckBox: checkStateCheckBox
        };
    }();
    window.base = base;
    base.stateCheckBox();

    $(".confirmationDelete").on('click', function(e) {
        base.confirmDelete(this, e);
    });

    /* Bulk Action */
    $('#bulkAction').find('li').on('click', function() {
        base.bulkAction($(this));
    });

    /* Mini search */
    function search(e, obj) {
        e.preventDefault();
        var kw = obj.closest('.miniSearch').find('input[name=kw]').val();
        base.search(kw);
    }

    $('.miniSearch').find('input[type=text]').bind('keypress', function(e) {
        if (e.keyCode === 13) {
            search(e, $(this));
        }
    });

    $('#btnMiniSearch').on('click', function(e) {
        search(e, $(this));
    });

    //notification
    if ($('.notification').exists()) {
        var notification = $('.notification');
        var type = notification.data('type');
        var content = notification.find('p.content').html();
        base.notification(type, content);
    }
});