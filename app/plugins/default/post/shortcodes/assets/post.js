$(function() {
    $.fn.exists = function() {
        return this.length > 0;
    };
    $('.post-lists .full-width').find('.post-item').last().find('hr').remove();

    Handlebars.registerHelper("everyOther", function(index, mod, scope) {
        if (index % mod === 0)
            return scope.fn(this);
        else
            return scope.inverse(this);
    });

    Handlebars.registerHelper("math", function(lvalue, operator, rvalue, options) {
        lvalue = parseFloat(lvalue);
        rvalue = parseFloat(rvalue);

        return {
            "+": lvalue + rvalue,
            "-": lvalue - rvalue,
            "*": lvalue * rvalue,
            "/": lvalue / rvalue,
            "%": lvalue % rvalue
        }[operator];
    });

    var posts = function() {
        var busy = false;
        var isJSON = function(str) {
            try {
                JSON.parse(str);
            } catch (e) {
                return false;
            }
            return true;
        };

        var postLists = $('.post-lists');
        var pagination = $('.pagination-posts');
        var baseURL = postLists.data('url');

        var renderHTML = function(key, data) {
            var source = $(key).html();
            var template = Handlebars.compile(source);
            return $.parseHTML(template(data));
        };
        var loading = $('.load-more.pagination-posts').find('.ajaxLoader');
        var loadPage = function(theme, position, obj) {
            if (busy) {
                return;
            }
            $.ajax({
                url: baseURL + 'posts_code:load_page/ajax:1',
                data: {page: pagination.data('page') + 1, attrs: postLists.data('attrs')},
                type: 'POST',
                beforeSend: function() {
                    loading.show();
                    busy = true;
                }
            }).success(function(result) {
            	//alert(result);
                busy = false;
                loading.hide();
                if (!isJSON(result)) {
                    return;
                }
                var data = JSON.parse(result);
                var posts = $(buildTemplate(data.posts, theme, position));
                if (theme === 'timeline') {
                    obj.find('.text').show();
                    obj.find('.i').hide();
                    $('.loading-timeline').hide();
                    postLists.find('.timeline').append(posts);
                } else {
                    var multiple_columns = postLists.find('.multiple-columns');
                    multiple_columns.append(posts).masonry('appended', posts).fadeIn();
                    multiple_columns.masonry().masonry('reloadItems');
                }
                if (data.pagination === false) {
                    pagination.remove();
                } else {
                    var page = parseInt(pagination.data('page')) + 1;
                    pagination.data('page', page);
                }
                $.each(data.ratings, function(index, value) {
                    initRating('post_' + value, 1);
                });
            });
        };

        var buildTemplate = function(data, theme, position) {
            if (typeof position === 'undefined' && theme === 'multiple_columns') {
                theme = '#template_' + theme;
                return renderHTML(theme, {theme_column_size: postLists.data('column'), data: data});
            }
            var i = 0;
            if (position === 'mid') {
                var total = $('.post-lists').find('.post-item').length;
                total % 2 === 0 ? i = 0 : i = 1;
                theme += '_mid';
            }
            return renderHTML('#template_' + theme, {i: i, data: data});
        };

        return {
            loadPage: loadPage,
            buildTemplate: buildTemplate
        };
    }();

    $('.load-more').on('click', 'button', function() {
        var position = $(this).data('position');
        var theme = $(this).data('theme');
        $(this).find('i').show();
        $(this).find('.text').hide();
        posts.loadPage(theme, position, $(this));
    });

    $(window).scroll(function() {
        if ($(document).height() - $(window).height() - $(window).scrollTop() < 100) {
            if ($('.load-more').exists() && $('.load-more').data('theme') === 'multiple_columns') {
                posts.loadPage($('.load-more').data('theme'));
            }
        }
    });
});
$(window).load(function(){
	$('.multiple-columns').masonry({
        itemSelector: '.item'
    });
});