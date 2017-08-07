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
        try{
            return this.length > 0;
        }catch(e){
            return false;
        }
    };
    var baseURL = "?admin-page=page";
    var form = $('form');
    var isJSON = function(str) {
        try {
            JSON.parse(str);
        } catch (e) {
            return false;
        }
        return true;
    };
    var Page = function() {
        var friendlyLink = function(s) {
            if (typeof s === "undefined") {
                return;
            }
            var i = 0, uni1, arr1;
            var newclean = s;
            uni1 = 'à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ|À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ|A';
            arr1 = uni1.split('|');
            for (i = 0; i < uni1.length; i++)
                newclean = newclean.replace(uni1[i], 'a');
            uni1 = 'è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ|È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ|E';
            arr1 = uni1.split('|');
            for (i = 0; i < uni1.length; i++)
                newclean = newclean.replace(uni1[i], 'e');
            uni1 = 'ì|í|ị|ỉ|ĩ|Ì|Í|Ị|Ỉ|Ĩ|I';
            arr1 = uni1.split('|');
            for (i = 0; i < uni1.length; i++)
                newclean = newclean.replace(uni1[i], 'i');
            uni1 = 'ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ|Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ|O';
            arr1 = uni1.split('|');
            for (i = 0; i < uni1.length; i++)
                newclean = newclean.replace(uni1[i], 'o');
            uni1 = 'ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ|Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ|U';
            arr1 = uni1.split('|');
            for (i = 0; i < uni1.length; i++)
                newclean = newclean.replace(uni1[i], 'u');

            uni1 = 'ỳ|ý|ỵ|ỷ|ỹ|Ỳ|Ý|Ỵ|Ỷ|Ỹ|Y';
            arr1 = uni1.split('|');
            for (i = 0; i < uni1.length; i++)
                newclean = newclean.replace(uni1[i], 'y');
            uni1 = 'd|Đ|D';
            arr1 = uni1.split('|');
            for (i = 0; i < uni1.length; i++)
                newclean = newclean.replace(uni1[i], 'd');
            newclean = newclean.toLowerCase();
            ret = newclean.replace(/[\&]/g, '-').replace(/[^a-zA-Z0-9.-\/]/g, '-').replace(/[-]+/g, '-').replace(/-$/, '');
            return ret;
        };
        /* Slug*/
        var defaultSlug = function() {
            var permalink = $('.permalinkWrap');
            var slug = permalink.find('#slug');
            if (permalink.data('method') === 'edit' || (slug.length > 0 && slug.val().length > 0)) {
                setStateButton(true);
                permalink.find('.customSlug').show();
                permalink.find('.editPageName').html($('<span id="span_slug">' + slug.val() + '</span>'));
                
                link = $("#span_slug").text();
                link_default = $("#url_default").text()
                full_link = link_default + link;
                $('#a_target').attr('href',full_link);
            }
        };
        var getGroupButton = function() {
            var parent = $('.groupAction');
            var edit = parent.find('button[name=edit]');
            var save = parent.find('button[name=save]');
            var cancel = parent.find('button[name=cancel]');
            return [edit, save, cancel];
        };
        var setStateButton = function(state) {
            var button = getGroupButton();
            if (state) {
                button[0].show();
                button[1].hide();
                button[2].hide();
            } else {
                button[0].hide();
                button[1].show();
                button[2].show();
            }
        };
        var renderSlug = function(param, obj) {
            var slug = friendlyLink(param.title);
            if (slug.length > 0) {
                if (obj.is('input')) {
                    obj.val(slug);
                } else if (obj.is('span')) {
                    obj.text(slug);
                }
            }
        };
        var buildSlug = function(param, obj) {
            var permalinkWrap = obj.closest('.permalinkWrap');
            var slug = permalinkWrap.find('#slug');
            renderSlug(param, slug);
            var append = $('<span id="span_slug">' + slug.val() + '</span>');
            setStateButton(true);
            permalinkWrap.find('.customSlug').show();
            permalinkWrap.find('.editPageName').html(append);
            
            link = $("#span_slug").text();
            link_default = $("#url_default").text()
            full_link = link_default + link;
            $('#a_target').attr('href',full_link);
        };

        /* Custom Slug */
        var customSlug = function(parent, type) {
            var button = getGroupButton();
            var edit = function() {
                var tempInput = $('.controlClone').find('input#tempInput');
                tempInput.val(parent.find('#slug').val());
                parent.find('.editPageName').html(tempInput.clone().attr('id', 'newSlug'));
                parent.on('click', button[0], function() {
                    base.moveCursorToEnd(parent.find('#newSlug'));
                });
                setStateButton(false);
            };
            var save = function() {
                var newSlug = $('#newSlug').val();
                var slug = $('#slug');
                if (newSlug.length === 0) {
                    base.notification('error', base.getMessage('errorLengthNewURL'));
                } else {
                    if (newSlug !== slug.val()) {
                        var method = parent.data('method');
                        var param;
                        if (method === 'edit') {
                            var id = $('.titleWrap').find('input[name=page_id]').val();
                            param = {title: newSlug, id: id};
                        } else {
                            param = {title: newSlug};
                        }
                        renderSlug(param, slug);
                    }
                    parent.find('.editPageName').html($('<span>' + slug.val() + '</span>'));
                    setStateButton(true);
                }
            };
            var cancel = function() {
                var slug = parent.find('#slug').val();
                parent.find('.editPageName').html('<span>' + slug + '</span>');
                setStateButton(true);

            };
            switch (type) {
                case 'cancel':
                    cancel();
                    break;
                case 'save':
                    save();
                    break;
                default:
                    edit();
                    break;
            }
        };

        var checkExistsSlug = function() {
            if ($('#page_title').val().length === 0) {
                form.submit();
                return;
            }

            var permalink = $('.permalinkWrap');
            var slug = permalink.find('#slug').val();
            if (slug.length > 0) {
                var param;
                if (permalink.data('method').toString() === 'edit') {
                    var id = $('.titleWrap').find('input[name=page_id]').val();
                    param = {url: slug, page_id: id};
                } else {
                    param = {url: slug};
                }
                $.post(baseURL + '&act=add', param, function(result) {
                	//alert(baseURL + '&act=ajax_check_exists_slug');return false;
                	//alert(result); return false;
                    if (parseInt(result) > 0) {
                    	//alert("false"); return false;
                        base.notification('error', base.getMessage('existsURL'));
                    } else {
                    	//alert("false"); return false;
                        form.submit();
                    }
                });
            } else {
                base.notification('error', base.getMessage('urlNotEmpty'));
            }
        };
        return{
            setStateButton: setStateButton,
            defaultSlug: defaultSlug,
            checkExistsSlug: checkExistsSlug,
            buildSlug: buildSlug,
            customSlug: customSlug
        };
    }();

    /* Render Slug*/
    Page.defaultSlug();
    var permalink = $('.permalinkWrap');
    $('#page_title').focusout(function() {
        var title = $(this).val();
        var method = permalink.data('method');
        if (title.length > 0 && method !== 'edit') {
            var title = $(this).val();
            var param = {title: title};
            var slug = $('#slug');
            Page.buildSlug(param, slug);
        }
        if (method !== 'edit') {
            $(this).closest('form').find('input[name=page_meta_title]').val(title);
        }
    });

    $('.groupAction').find('button').click(function() {
        var type = $(this).attr('name');
        Page.customSlug(permalink, type);
    });

    permalink.on('keypress', '#newSlug', function(e) {
        if (e.keyCode === 13) {
            e.preventDefault();
            Page.customSlug(permalink, 'save');
        }
    });

    $('.btnSave').on('click', function(e) {
    	//alert("HI"); return false;
        e.preventDefault();
        Page.checkExistsSlug();
    });


    var baseURL = "?admin-page=page";

    if ($('#visible_users_data').exists()) {
        var ms = $('#visibleUsers').magicSuggest({
            noSuggestionText: 'No result matching your search',
            data: baseURL + '&act=ajax_get_users',
            allowFreeEntries: false,
            dataUrlParams: {act: 'visible_users'}
        });
        $(ms).on('selectionchange', function() {
            $('#visible_users_data').val(JSON.stringify(this.getValue()));
        });
        var visibleUsers = $('#visible_users_data').val();
        if (visibleUsers !== '') {
            visibleUsers = visibleUsers.replace(/\\/g, '');
            if (isJSON(visibleUsers)) {
                ms.setValue($.parseJSON(visibleUsers));
            }
        }
    }

    $("#visible_group").on('ifChecked', function() {
        if ($(this).attr('class') === 'all') {
            $("input[type='checkbox'].one").iCheck("disable").iCheck('uncheck');
        } else {
            $("input[type='checkbox'].all").iCheck("disable");
        }
    }).on('ifUnchecked', function() {
        $("input[type='checkbox'].one").iCheck("enable");
    });
});