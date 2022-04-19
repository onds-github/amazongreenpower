/*! UIkit 2.27.4 | http://www.getuikit.com | (c) 2014 YOOtheme | MIT License */
(function ($) {
    $.fn.delete = function (options) {

        var defaults = {
            url: null,
            table: null,
            button: null
        };

        var settings = $.extend({}, defaults, options);

        $(document).on('click', settings.button, function () {

            var data = settings.table.rows('.selected').data();

            switch (data.length) {
                case 0:
                    $(this).alert({
                        type: 'alert',
                        content: 'Você precisa selecionado ao menos 1 registro para continuar.'
                    });
                    break;
                default:
                    var d_t = 0;
                    var d_f = 0;
                    var arr = new Array();
                    $.each(data, function (i, v) {
                        $.each(v[0]['delete'], function (x, z) {
                            
                            if (z.status) {
                                d_t++;
                                arr.push(v[0]['id']);
                            } else {
                                d_f++;
                            }
                        
                        });
                        
                                
                        if (data.length === (i + 1)) {
                            $('body').alert({
                                type: 'confirm',
                                content:    
                                    '<div class="ui message">\n\
                                        <div class="header">RESUMO DA EXCLUSÃO</div>\n\
                                        <ul class="list">\n\
                                            <li>Arquivos que serão excluídos: ' + d_t + '</li>\n\
                                            <li>Arquivos que não serão excluídos: ' + d_f + '</li>\n\
                                        </ul>\n\
                                    </div>\n\
                                    <h4 class="ui blue center aligned header">Antes de continuar a exclusão precisamos da sua autorização, deseja continuar?</h4>'
                                ,
                                onApprove: function () {
                                    $.ajax({
                                        type: 'POST',
                                        url: settings.url,
                                        data: {q: arr},
                                        success: function (e) {
                                            $('body').notify({type: 'success', content: data.length + ' registros(s) excluído(s) com sucesso.'});
                                            settings.table.ajax.reload();
                                        }
                                    });
                                }
                            });
                        }
                    });

                    break;
            }

        });

    };
})(jQuery);