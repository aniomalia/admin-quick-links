(function ($) {
    $(document).ready(function () {
        var aql = $('.aql-container');
        if (aql.length) {

            aql.prependTo('#wpbody');
            aql.css('margin-left', parseInt($('#wpcontent').css('padding-left')) * -1);
            aql.show();
            $(document).on('keypress', function (e) {
                var tag = e.target.tagName.toLowerCase();
                if (tag != 'input' && tag != 'textarea') {
                    if (e.key >= 0 && e.key < 11) {
                        var link = aql.find(`.aql-bar-item:nth-child(${e.key}) a`)[0];
                        link.click();
                        link.css('background', 'red');
                    }
                }
            });
            $(window).resize(function () {
                aql.css('margin-left', parseInt($('#wpcontent').css('padding-left')) * -1);
            });

        }
    });
})(jQuery);