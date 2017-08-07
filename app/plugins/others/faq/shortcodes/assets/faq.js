function initFAQ(id_faq) {
    $.fn.exists = function() {
        return this.length > 0;
    };
    var isJSON = function(str) {
        try {
            JSON.parse(str);
        } catch (e) {
            return false;
        }
        return true;
    };

    var pfFAQ = $('#' + id_faq);
    var baseURL = pfFAQ.data('url');
    //alert(baseURL);

    var faq = function() {
        var renderHTML = function(key, data) {
            var source = $(key).html();
            var template = Handlebars.compile(source);
            data = typeof data !== 'undefined' ? data : {};
            return template(data);
        };

        var loadFAQ = function(id, title) {
            $.post(baseURL + 'faq_code:get_faq/ajax:1', {id: id}, function(result) {
                if (result.length > 0) {
                    var data = JSON.parse(result);
                    var html = renderHTML('#listFAQ', {id: id, title: title, faqs: data, id_faq: id_faq});
                    var list = html.replace(/idfaq/g, id_faq);
                    if (pfFAQ.find('.faqs').exists()) {
                        pfFAQ.find('.faqs').replaceWith(list);
                    } else {
                        pfFAQ.prepend(list);
                    }
                }
            });
        };

        var paginationFAQ = function(page) {
            $.post(baseURL + 'faq_code:faq_load_list_faqs/ajax:1', {page: page}, function(result) {
            	//alert(result);
                var data = JSON.parse(result);
                pfFAQ.find('.list-faq').html(renderHTML('#category', data));
                pfFAQ.find('.pagination-faq').html(data.pagination);
            });
        };

        var showFAQ = function(item) {
            var a = item.find('a');
            var id = a.data('id');
            var title = a.text();
            if (pfFAQ.find('.faqs').data('id') !== id) {
                loadFAQ(id, title);
            }
        };

        return {
            showFAQ: showFAQ,
            paginationFAQ: paginationFAQ
        };
    }();

    if (pfFAQ.find('.pfFAQCategories').exists()) {
        var li = pfFAQ.find('.pfFAQCategories').find('li').first();
        faq.showFAQ(li);
    }

    pfFAQ.find('.faq-cats').on('click', 'a', function(e) {
        e.preventDefault();
        var li = $(this).closest('li');
        faq.showFAQ(li);
    });

    pfFAQ.children('.pfFAQCategories').find('.pagination-faq').on('click', 'a', function(e) {
        e.preventDefault();
        var href = $(this).attr('href');
        var result = href.split('page-faq:');
        var page = !isNaN(result[1]) ? result[1] : 1;
        faq.paginationFAQ(page);
    });
}
