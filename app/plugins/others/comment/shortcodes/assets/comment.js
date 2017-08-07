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
function initComment(id) {
    $.fn.exists = function() {
        return this.length > 0;
    };

    $.fn.serializeObject = function()
    {
        var o = {};
        var a = this.serializeArray();
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
        return o;
    };

    var escapeHtml = function(text) {
        if (typeof text !== "undefined" && typeof text === 'string') {
            return text.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
        }
        return text;
    };

    Handlebars.registerHelper('breaklines', function(text) {
        text = Handlebars.Utils.escapeExpression(text);
        text = text.replace(/(\r\n|\n|\r)/gm, '<br>');
        return new Handlebars.SafeString(text);
    });

    var commentWrap = $('#id_' + id);
    var formEditWrap = '';
    var key = commentWrap.data('key');
    var listComment = commentWrap.children('.listComment');
    var pagination = commentWrap.children('.pagination-comment');
    var approve = +commentWrap.data('approve');
    var order = +commentWrap.data('order');
    var comment = function() {
        var baseURL = commentWrap.data('url');
        var renderHTML = function(key, data) {
            var source = $(key).html();
            var template = Handlebars.compile(source);
            return $.parseHTML(template(data));
        };
        var isJSON = function(str) {
            try {
                JSON.parse(str);
            } catch (e) {
                return false;
            }
            return true;
        };
        var showTotal = function(total) {

        };
        var loadComment = function(page) {
            listComment.find('.ajaxLoader').show();
            $.post(baseURL + 'comment_code:comment_load_comment/ajax:1', {key: key, page: page}, function(result) {
                if (isJSON(result)) {
                    var data = JSON.parse(result);
                    listComment.find('.content').html(data.comments);
                    pagination.html(data.pagination);
                    if (data.total > 0) {
                        commentWrap.find('em.totalComment').text(data.total);
                    }
                } else {
                    showTotal(0);
                }
            }).done(function() {
                listComment.find('.ajaxLoader').hide();
            });
        };
        var post = function(item, token, id) {
            var form;
            if (id !== '') {
                form = item.closest('form[data-id="' + id + '"]');
            } else {
                form = item.closest('form');
            }
            var data_submit = form.serializeObject();
            if (token !== '') {
                data_submit.token = token;
            }
            data_submit.key = key;
            var type = form.data('type');
            listComment.find('.ajaxLoader').show();

            $.post(baseURL + 'comment_code:comment_post/ajax:1', data_submit, function(result) {
                var message = alertMessage(result);
                if (message.length > 0) {
                    var error = '';
                    var data = JSON.parse(result);
                    if (data.code === 'error_validate') {
                        $.each(data.errors, function(i, item) {
                            error += item + '<br/>';
                        });
                        commentWrap.data('token', data.token);
                        if($('.formComment.edit').exists()){
                            $('.formComment.edit').find('input[name=token]').val(data.token);
                        }
                    }
                    bootbox.alert(message[0] + error);
                    return false;
                }
                // Data Display
                var data = JSON.parse(result);
                var id = data.id, token = data.token_id, message = form.find('textarea').val();
                var avatar = form.find('.avatar').html(), author = commentWrap.find('.author').text();
                var item = {
                    id: id,
                    token: token,
                    message: message,
                    avatar: avatar,
                    author: author
                };
                // Data Custom Field
                $.each(data.meta, function(i, value) {
                    if (data_submit.hasOwnProperty(value)) {
                        item[value] = data_submit[value];
                    }
                });
                switch (type) {
                    case 'reply':
                        var formReply = form.closest('.formComment');
                        if (approve) {
                            bootbox.alert(getMessage('approve'));
                        } else {
                            if (order) {
                                $(renderHTML('#itemComment', item)).insertAfter(formReply);
                            } else {
                                $(renderHTML('#itemComment', item)).insertBefore(formReply);
                            }
                        }
                        formReply.remove();
                        break;
                    case 'edit':
                        var message = form.find('textarea').val();
                        var parent = form.parent().parent('.media-body');
                        parent.find('.message[id=comment_id_'+item.id+']').find('p').text(item.message);
                        parent.data('token', data.token_id);
                        resetEdit(parent);
                        break;
                    default:
                        if (approve === 1) {
                            bootbox.alert(getMessage('approve'));
                        } else {
                            if (order) {
                                listComment.find('.content').append($(renderHTML('#itemComment', item)));
                            } else {
                                listComment.find('.content').prepend($(renderHTML('#itemComment', item)));
                            }
                            position(false);
                        }
                        break;
                }
                form.find('textarea[name=message]').attr('style', '');
                commentWrap.data('token', data.token);
                form[0].reset();
            }).done(function() {
                listComment.find('.ajaxLoader').hide();
            });
        };
        var reply = function(obj) {
            var toolBar = obj.closest('.toolBar');
            var parent = obj.closest('.media-body');
            if (!$(toolBar).next('.formComment').exists()) {
                var html = renderHTML('#formReplyComment', {parent: parent.data('id')});
                $(html).insertAfter(toolBar);
            }
        };
        var remove = function(obj) {
            bootbox.confirm(getMessage('confirmDelete'), function(result) {
                if (result) {
                    var media = obj.closest('.media-body');
                    var token = media.data('token');
                    var id = media.data('id');
                    $.post(baseURL + 'comment_code:comment_delete/ajax:1', {id: id, token: token, key: commentWrap.data('key')}, function(result) {
                        var message = alertMessage(result);
                        if (message.length > 0) {
                            bootbox.alert(message[0]);
                        } else {
                            media.closest('.media').animate({opacity: 0}, 1000, function() {
                                $(this).remove();
                            });
                        }
                    });
                }
            });
        };

        /*
         * Edit comment
         */
        var resetEdit = function(wrap) {
            wrap.children('.message').show();
            wrap.children('.custom-field-message').show();
            wrap.children().children().children('.btnEdit').removeClass('active');
            wrap.children('.formComment.edit').remove();
        };

        var edit = function(item) {
            var wrap = item.parent().parent().parent('.media-body');
            var data = {
                id: wrap.data('id'),
                token: wrap.data('token'),
                key: key
            };
            var html = '';
            $.post(baseURL + 'comment_code:comment_edit/ajax:1', data, function(result) {
                if (!isJSON(result)) {
                    return;
                }
                result = JSON.parse(result);
                data.token = result.token;
                wrap.data('token', data.token);
                if (result.hasOwnProperty('code')) {
                    bootbox.alert(getMessage(result.code));
                    return;
                }
                data.message = $.trim(wrap.children('.message').text());
                data.form = result.form;
                html = renderHTML('#formEditComment', data);
                $(html).insertBefore(wrap.children('.toolBar'));
                wrap.children('.message').hide();
            });
        };

        /*
         * Validator and Alert Message
         */
        var getMessage = function(key) {
            var message = $('li.' + key).text();
            if (message.length > 0) {
                return message;
            }
            return ' ';
        };

        var alertMessage = function(data) {
            var message = [];
            if (isJSON(data)) {
                $.map(JSON.parse(data), function(item, key) {
                    if (key === 'code') {
                        message.push(getMessage(item));
                    }
                });
            }
            return message;
        };
        /*
         * Scroll position
         */
        var position = function(select) {
            var position = listComment.offset().top - 60;
            if (order) {
                if (!select) {
                    position = position + listComment.height() - listComment.find('.media').last().height();
                }
            }
            $('body,html').animate({scrollTop: position}, 1000);
        };

        return{
            loadComment: loadComment,
            post: post,
            remove: remove,
            reply: reply,
            edit: edit,
            resetEdit: resetEdit,
            position: position
        };
    }();
    comment.loadComment(1);
    commentWrap.find('.btnPost').click(function() {
        comment.post($(this), commentWrap.data('token'), '');
    });
    listComment.on('click', '.btnRemove', function(e) {
        e.preventDefault();
        comment.remove($(this));
    });
    /*
     * Edit
     */
    listComment.on('click', '.btnEdit', function(e) {
        e.preventDefault();
        if ($(this).hasClass('active')) {
            var wrap = $(this).parent().parent().parent('.media-body');
            comment.resetEdit(wrap);
            $(this).removeClass('active');
        } else {
            $(this).addClass('active');
            comment.edit($(this));
        }
    });
    listComment.on('click', '.btnCancelComment', function(e) {
        e.preventDefault();
        var wrap = $(this).parent().parent().parent().parent().parent().parent('.media-body');
        comment.resetEdit(wrap);
    });
    listComment.on('click', '.btnEditComment', function(e) {
        e.preventDefault();
        comment.post($(this), '', $(this).closest('form').data('id'));

    });
    /*
     * Reply
     */
    listComment.on('click', '.btnReply', function(e) {
        e.preventDefault();
        comment.reply($(this));
    });
    listComment.on('click', '.btnPostReply', function(e) {
        e.preventDefault();
        comment.post($(this), commentWrap.data('token'), '');
    });

    /*
     * Pagination
     */
    pagination.on('click', 'a', function(e) {
        e.preventDefault();
        var href = $(this).attr('href');
        var result = href.split('page-comment:');
        var page = !isNaN(result[1]) ? result[1] : 1;
        comment.loadComment(parseInt(page));
        comment.position(true);
    });

    commentWrap.on('click', 'textarea', function() {
        $(this).autosize();
    });

    commentWrap.on('keypress', 'textarea', function(e) {
        if (e.keyCode === 13) {
            //$(this).val($(this).val() + "\n\r");
        }
    });
}