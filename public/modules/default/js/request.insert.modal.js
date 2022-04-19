/*! UIkit 2.27.4 | http://www.getuikit.com | (c) 2014 YOOtheme | MIT License */
(function ($) {
    $.fn.insert = function (options) {

        var defaults = {
            type: null,
            content: null,
            form: null,
            url: null,
            data: null,
            table: null,
            validate: null,
            button: null,
            onDeny: null,
            onSuccess: null
        };

        var settings = $.extend({}, defaults, options);
        
        $(document).on('click', settings.button, function () {
            
            var dContent = $('<div />', { 
                html: type(), 
                class: 'content',
                style: 'background-color: #f4f4f3; padding: 20px; border-radius: 10px 10px 0 0;'
            });


                var m = $('<div />', {
                    html: dContent,
                    class: 'ui small basic modal modal-uniq'
                }).appendTo('body').modal({
                    closable: false,
                    observeChanges: true,
                    onDeny: function () {
                        form.form('reset');
                    },
                    onApprove: function () {
                        form.submit();
                        return false;
                    },
                    onHidden: function () {
                        m.remove();
                    }
                }).modal('setting', 'transition', 'jiggle').modal('show');

                var form = $('.' + settings.form);
                
                _form(settings.data);
                        form.submit(function (event) {
                            return false;
                        }).form({
                            fields: settings.validate,
                            onSuccess: function () {
                                $.ajax({
                                    type: 'POST',
                                    url: settings.url,
                                    data: {data: form.serialize()},
                                    success: function (e) {
                                        $('body').notify({type: e.status, content: e.exception.message});
                                        settings.onSuccess.call(this, e);
                                    },
                                    complete: function () {
                                        m.modal('hide');
                                        form.form('reset');
                                        if (settings.table){
                                            settings.table.ajax.reload();
                                        } else {
                                            settings.table.ajax.reload();
                                        }
                                    }
                                });

                            }
                        });

                var dActions = $('<div />', { class: 'actions', style: 'background-color: #f4f4f3; padding: 20px; border-radius: 0 0 10px 10px;' }).appendTo(m);

                $('<button />', { html: 'CANCELAR', class: 'ui small secondary deny button' }).appendTo(dActions);
                $('<button />', { html: 'SALVAR', class: 'ui small primary ok button' }).appendTo(dActions);

                $('.cpf_cnpj').on('focusin', function () {
                    $(this).unmask();
                });

                $('.cpf_cnpj').blur(function () {
                    switch ($(this).val().replace(/[^0-9]/g, '').length) {
                        case 14:
                            $(this).mask('99.999.999/9999-99');
                            break;
                        default:
                            $(this).mask('999.999.999-99');
                            break;
                    }
                });

    $('.money').mask("#.##0,00", {reverse: true});

    var PhoneMaskBehavior = function (val) {
        return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
    },
            PhoneOptions = {
                onKeyPress: function (val, e, field, options) {
                    field.mask(PhoneMaskBehavior.apply({}, arguments), options);
                }
            };

    $('.phone').mask(PhoneMaskBehavior, PhoneOptions);

            function type() {
                return '<h4 class="ui header">FORMULÁRIO DE CADASTRO</h4>\n\
                        <div class="ui divider"></div>\n\
                        <form class="ui form form-uniq ' + settings.form + '"><div class="ui error message"></div></form>';
            }

            function _form(data){
            $.each(data, function(i, v) {
                if (v.multiple) {
                    var fields = $('<div />', {
                        class: 'two fields'
                    }).appendTo('.ui.form.form-uniq');
                    $.each(v.fields, function(x, z) {

                        var field = $('<div />', {
                            class: 'field'
                        }).appendTo(fields);
                        
                        $('<label />', {
                            html: z.html
                        }).appendTo(field);
                    
                        if (v.type === 'textarea') {
                            $('<textarea />', {
                                name: z.name,
                                rows: z.rows,
                                placeholder: z.html
                            }).appendTo(field);
                        } else {
                            $('<input />', {
                                type: z.type,
                                name: z.name,
                                class: z.class,
                                maxlength: z.maxlength,
                                placeholder: z.html
                            }).appendTo(field);
                        }
                    });
                } else {
                    var field = $('<div />', {
                        class: 'field'
                    }).appendTo('.ui.form.form-uniq');
                    
                    $('<label />', {
                        html: v.html
                    }).appendTo(field);
                    
                    if (v.type === 'textarea') {
                        $('<textarea />', {
                            name: v.name,
                            rows: v.rows,
                            placeholder: v.html
                        }).appendTo(field);
                    } else {
                        $('<input />', {
                            type: v.type,
                            name: v.name,
                            class: v.class,
                            maxlength: v.maxlength,
                            placeholder: v.html
                        }).appendTo(field);
                    }
                }
            });
            
        }
        
        });
        
    };
})(jQuery);