(function ($) {
    $.fn.onCreate = function (options) {

        var defaults = {
            url: null,
            fields: null,
            table: null,
            modal: null,
            button: null,
            onDeny: null,
            onBeforeSend: null,
            onSuccess: null
        };

        var settings = $.extend({}, defaults, options);

        var onFormCreate = $('#' + settings.modal + ' form').submit(function (event) {
            return false;
        }).form({
            fields: settings.fields,
            onSuccess: function () {
                $.ajax({
                    type: 'POST',
                    url: settings.url,
                    data: {data: onFormCreate.serialize()},
                    beforeSend: function (xhr) {
                        if ($.isFunction(settings.onBeforeSend)) {
                            settings.onBeforeSend.call(this, xhr);
                        }
                    },
                    success: function (response) {
                        switch (response.status) {
                            case 'success':
                                settings.table.ajax.reload();
                                onModalCreate.modal('hide');
                                break;
                        }
                        if ($.isFunction(settings.onSuccess)) {
                            settings.onSuccess.call(this, response);
                        }
                    },
                    complete: function () {

                    }
                });
            }
        });

        var onModalCreate = $('#' + settings.modal).modal({
            closable: false,
            observeChanges: true,
            autofocus: false,
            onHidden: function () {
                onFormCreate.form('reset');
            },
            onApprove: function () {
                onFormCreate.submit();
                return false;
            }
        }).modal('show');

    };
})(jQuery);