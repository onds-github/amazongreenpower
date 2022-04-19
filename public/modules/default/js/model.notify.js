
(function ($) {
    $.fn.notify = function (options) {

        var defaults = {
            type: null,
            content: null
        };

        var settings = $.extend({}, defaults, options);

        return this.each(function () {
            var m = $('<div />', {
                html: type(settings.type, settings.content),
                class: 'ui ' + settings.type + ' icon message transition hidden',
                style: 'position: fixed; z-index: 9999; max-width: 350px; bottom: 15px; right: 15px;'
            }).appendTo('body').transition({
                animation: 'fade',
                onComplete: function () {
                    setTimeout(function () {
                        m.transition({
                            animation: 'fade',
                            duration: '2s',
                            onComplete: function () {
                                m.remove();
                            }
                        });
                    }, 3000);
                }
            });
        });
        
        function type(type, content) {
            switch (type) {
                case 'success':
                    return '<i class="checkmark icon"></i><div class="content"><div class="header">Sucesso</div><p>' + content + '</p></div>';
                    break;
                case 'error':
                    return '<i class="checkmark icon"></i><div class="content"><div class="header">Error</div><p>' + content + '</p></div>';
                    break;
            }
        }

    };
})(jQuery);