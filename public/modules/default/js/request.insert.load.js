/*! UIkit 2.27.4 | http://www.getuikit.com | (c) 2014 YOOtheme | MIT License */
(function ($) {
    $.fn.insert_load = function (options) {

        var defaults = {
            form: null,
            url: null,
            data: null,
            validate: null
        };

        var settings = $.extend({}, defaults, options);

        var form = $(this);

        _form(settings.data);
        
                        $('<div />', {
                            class: 'ui error message'
                        }).appendTo(form);
                        
                        $('<button />', {
                            type: 'submit',
                            html: 'Enviar mensagem',
                            class: 'ui primary button'
                        }).appendTo(form);
        
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
                        form.form('reset');
                    }
                });

            }
        });

        var PhoneMaskBehavior = function (val) {
            return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
        },
                PhoneOptions = {
                    onKeyPress: function (val, e, field, options) {
                        field.mask(PhoneMaskBehavior.apply({}, arguments), options);
                    }
                };

        $('.phone').mask(PhoneMaskBehavior, PhoneOptions);

        function _form(data) {
            $.each(data, function (i, v) {
                if (v.multiple) {
                    var fields = $('<div />', {
                        class: 'two fields'
                    }).appendTo(form);
                    $.each(v.fields, function (x, z) {

                        var field = $('<div />', {
                            class: 'form-group'
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
                        class: 'form-group'
                    }).appendTo(form);

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
                            class: 'form-control' + v.class,
                            maxlength: v.maxlength,
                            placeholder: v.html
                        }).appendTo(field);
                    }
                }
            });
        }


    };
})(jQuery);