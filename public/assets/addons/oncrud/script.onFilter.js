
(function ($) {
    $.fn.onFilter = function (options) {

        var defaults = {
            url: null,
            table: null,
            modal: null,
            fields: null,
            id: null,
            content: null,
            data: null,
            validate: null,
            onSuccess: null
        };

        var settings = $.extend({}, defaults, options);

        var onFormFilter = $('#onFormFilter').submit(function (event) {
            return false;
        }).form({
            fields: {
                filter_id_type_order: {
                    identifier: 'filter_id_type_order',
                    rules: [
                        {
                            type: 'empty',
                            prompt: 'Selecione'
                        }
                    ]
                },
                filter_id_type_date: {
                    identifier: 'filter_id_type_date',
                    rules: [
                        {
                            type: 'empty',
                            prompt: 'Selecione'
                        }
                    ]
                },
                filter_id_type_period: {
                    identifier: 'filter_id_type_period',
                    rules: [
                        {
                            type: 'empty',
                            prompt: 'Selecione'
                        }
                    ]
                },
                filter_period_min: {
                    identifier: 'filter_period_min',
                    rules: [
                        {
                            type: 'empty',
                            prompt: 'Infome'
                        }
                    ]
                },
                filter_period_max: {
                    identifier: 'filter_period_max',
                    rules: [
                        {
                            type: 'empty',
                            prompt: 'Infome'
                        }
                    ]
                }
            },
            onSuccess: function () {
                $.ajax({
                    type: 'POST',
                    url: '/account/filter/update',
                    data: {data: onFormFilter.serialize()},
                    success: function (response) {
                        switch (response.status) {
                            case 'success':
                                settings.table.ajax.reload();
                                break;
                        }
                        if ($.isFunction(settings.onSuccess)) {
                            settings.onSuccess.call(this, response);
                        }
                    }
                });
            }
        });


        $(document).on('click', '#onButtonFilter', function () {
            $.ajax({
                type: 'POST',
                url: '/account/filter/update',
                data: {data: 'filter_toggle=true'},
                success: function (response) {
                    switch (parseInt(response.returning)) {
                        case 1:
                            $('#onCardFilter .uk-card-body').show();
                            $('#onButtonFilter').removeClass('black').html('<i class="eye slash icon"></i> Ocultar');
                            break;
                        case 0:
                            $('#onCardFilter .uk-card-body').hide();
                            $('#onButtonFilter').addClass('black').html('<i class="eye icon"></i> Mostrar');
                            break;
                    }
                }
            });
        });

    };
})(jQuery);