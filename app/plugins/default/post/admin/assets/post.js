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
    var table = $('table.table-tags');
    var tagInput = $('#tags');

    var Post = function() {
        var baseURL = "?admin-page=post&sub_page=post";
        var pagination = $('#paginationTag');

        var loadTag = function(page) {
            $.post(baseURL + "&action=ajax_load_tag&page="+page, {page: page},function (result) {
                $(document).ajaxComplete(function() {
                    if (result.length > 0) {
                        var data = JSON.parse(result);
                        table.find('tbody').html(data.table);
                        pagination.html(data.pagination);
                    }
                });
            });
        };

        //Check tag
        var checkExistsTag = function(tagName) {
            $.post(baseURL + "&action=ajax_check_tag_exists", {
                name: tagName
            }, function(result) {
                if (parseInt(result)) {
                }
            });
        };

        var deleteTag = function(id, tagName) {
            bootbox.confirm(base.getMessage('confirmDeleteTag'), function(result) {
                if (result === true) {
                    $.post(baseURL + '&action=ajax_delete_tag', {
                        id: id
                    }, function(result) {
                        tagInput.tagsinput('remove', tagName);
                        loadTag(1);
                    });
                }
            });
        };

        var addTag = function(value) {
            tagInput.tagsinput('add', value);
        };

        var getValueCheckbox = function(table) {
            var tags = [];
            $.each(table.find('input[type=checkbox]'), function() {
                if ($(this).is(":checked")) {
                    tags.push($(this).val());
                }
            });
            return tags.join();
        };

        var choiceTag = function() {
            var checked = getValueCheckbox(table);
            if (checked.length > 0) {
                tagInput.tagsinput('add', checked);
                $('#tagsManager').modal('hide');
            } else {
                base.notification('error', base.getMessage('errorChoiceTag'));
            }
        };

        return {
            loadTag: loadTag,
            deleteTag: deleteTag,
            choiceTag: choiceTag,
            checkExistsTag: checkExistsTag,
            addTag: addTag
        };
    }();

    //Tag
    $('.bootstrap-tagsinput').find('input').on('focusout', function() {
        tagInput.tagsinput('add', $(this).val());
        $(this).val('');
    });

    $('#btnTagsManager').on('click', function(e) {
        e.preventDefault();
        $('#tagsManager').modal('show');
        Post.loadTag(1);
    });

    $('#tagsManager').on('click', 'a', function(e) {
        e.preventDefault();
        var href = $(this).attr('href');
        var result = href.split('=');
        var page = !isNaN(result[1]) ? result[1] : 1;
        Post.loadTag(parseInt(page));
    });

    table.on('click', '.confirmDeleteTag', function() {
        var id = $(this).data('id');
        var tagName = $(this).data('name');
        Post.deleteTag(id, tagName);
        
    });

    $('#btnChoiceTags').on('click', function() {
        Post.choiceTag();
    });

    //Add more
    $('.btnSave').on('click', function(e) {
        e.preventDefault();
        var form = $('form');
        form[0].submit();
    });

    $('.bootstrap-tagsinput').find('input').attr('tabindex', 10);
    $('#publishedDate,#unpublishedDate').datetimepicker();

    $('.boxGetFile').fancybox({
        'width': '75%',
        'height': '90%',
        'autoScale': false,
        'transitionIn': 'none',
        'transitionOut': 'none',
        'type': 'iframe'
    });
    
    /**/
    var filterByCategory = $('.filterByCategory');
    var categoryName = filterByCategory.find('li[data-id="'+filterByCategory.data('id')+'"]').text();
    filterByCategory.find('.categoryName').text(categoryName);
});