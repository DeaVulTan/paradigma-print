function initRating(id, readOnly) {
    var ratingWrap = $('#rating_' + id);
    var rating = ratingWrap.find('.ratingBox');
    var baseURL = ratingWrap.data('url');
    var isJSON = function(str) {
        try {
            JSON.parse(str);
        } catch (e) {
            return false;
        }
        return true;
    };
    rating.raty({
        half: true,
        starType: 'i',
        readOnly: readOnly,
        hints: ['', '', '', '', ''],
        score: function() {
            return $(this).attr('data-score');
        },
        click: function(score) {
            $.post(baseURL + 'pf_code:rating/rating-act:add', {rate: score, key: id, token: ratingWrap.data('token')}, function(result) {
                if (isJSON(result)) {
                    var data = JSON.parse(result);
                    if (data.code === 1) {
                        ratingWrap.data('token', data.token);
                        ratingWrap.find('.showResult').html(data.showRating);
                        ratingWrap.find('.messageRating').text(data.msg).removeClass('error').addClass('success').show();
                        rating.raty('score', data.rating.avg);
                        return;
                    }else{
                        ratingWrap.find('.messageRating').text(data.msg).removeClass('success').addClass('error').show();
                    }
                    if (typeof data.token !== 'undefined') {
                        ratingWrap.data('token', data.token);
                    }
                }
                if (result.length > 0) {
                    
                }
            });
        }
    });
}
setInterval(function() {
    $('.messageRating').fadeOut('fast');
}, 8000);