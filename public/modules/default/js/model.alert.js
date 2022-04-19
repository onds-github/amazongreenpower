/*! UIkit 2.27.4 | http://www.getuikit.com | (c) 2014 YOOtheme | MIT License */
(function ($) {
    $.fn.alert = function (options) {

        var defaults = {
            type: null,
            content: null,
            onDeny: null,
            onApprove: null
        };

        var settings = $.extend({}, defaults, options);
        
        var dContent = $('<div />', { 
            html: type(settings.type, settings.content), 
            class: 'content',
            style: 'background-color: #f4f4f3; padding: 20px; border-radius: 10px 10px 0 0;'
        });
        
        return this.each(function () {
            var m = $('<div />', {
                html: dContent,
                class: 'ui small basic modal'
            }).appendTo('body').modal({
                closable: false,
                observeChanges: true,
                onDeny: function () {
                    if ($.isFunction(settings.onDeny)) {
                        settings.onDeny.call(this);
                    }
                },
                onApprove: function () {
                    if ($.isFunction(settings.onApprove)) {
                        settings.onApprove.call(this);
                    }
                },
                onHidden: function () {
                    m.remove();
                }
            }).modal('setting', 'transition', 'jiggle').modal('show');
            
            var dActions = $('<div />', { class: 'actions', style: 'background-color: #f4f4f3; padding: 20px; border-radius: 0 0 10px 10px;' }).appendTo(m);

            
            if (settings.type === 'confirm') {
                $('<button />', { html: 'NÃO', class: 'ui small deny red button' }).appendTo(dActions);
                $('<button />', { html: 'SIM', class: 'ui small approve green button' }).appendTo(dActions);
            } else {
                $('<button />', { html: 'OK', class: 'ui small approve primary button' }).appendTo(dActions);
            }

        });
        
        function type(type, content) {
            switch (type) {
                case 'alert':
                    return '<img class="ui centered medium image" src="' + base_url + 'public/modules/default/img/assistant/assistant_softcuca.gif" />\n\
                            <h4 class="ui center aligned header">MENSAGEM DE AVISO</h4>\n\
                            <p style="text-align: center; color: #000">' + content + '</p>';
                break;
                
                case 'confirm':
                    return '<div style="background-color: #f4f4f3; padding: 20px; border-radius: 10px;">\n\
                                <img class="ui centered medium image" src="' + base_url + 'public/modules/default/img/assistant/assistant_softcuca.gif" />\n\
                                <h4 class="ui center aligned header">MENSAGEM DE CONFIRMAÇÃO</h4>\n\
                                <p style="text-align: center; color: #000">' + content + '</p>\n\
                            </div>';
                break;
                
                case 'refresh':
                    return '<div style="background-color: #f4f4f3; padding: 20px; border-radius: 10px;">\n\
                                <img class="ui centered medium image" src="' + base_url + 'public/modules/default/img/assistant/assistant_softcuca.gif" />\n\
                                <h4 class="ui center aligned header">MENSAGEM DE ESCLARECIMENTO</h4>\n\
                                <p style="text-align: center; color: #000">' + content + '</p>\n\
                            </div>';
                break;
            }
        }
        
    };
})(jQuery);