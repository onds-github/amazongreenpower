/*! UIkit 2.27.4 | http://www.getuikit.com | (c) 2014 YOOtheme | MIT License */
(function ($) {
    $.fn.update = function (options) {

        var defaults = {
            urlSelect: null,
            url: null,
            table: null,
            id: null,
            content: null,
            data: null,
            validate: null,
            onDeny: null,
            onSuccess: null
        };
        
        var _id = null;

        var settings = $.extend({}, defaults, options);

        var onModalUpdate = new bootstrap.Modal(document.getElementById('onModalUpdate'));

        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.querySelectorAll('#onFormUpdate');

        // Loop over them and prevent submission
        Array.prototype.slice.call(forms)
                .forEach(function (form) {
                    form.addEventListener('submit', function (event) {
                        if (!form.checkValidity()) {
                            event.stopPropagation();
                        } else {
                            $.ajax({
                                type: 'POST',
                                url: settings.url,
                                data: {data: $('#onFormUpdate').serialize(), q: _id},
                                success: function (data) {
                                    switch (data.status) {
                                        case 'success':
                                            settings.table.bootstrapTable('refresh');
                                            onModalUpdate.hide();
                                            break;
                                    }
                                    if ($.isFunction(settings.onSuccess)) {
                                        settings.onSuccess.call(this, e);
                                    }
                                },
                                complete: function () {

                                }
                            });
                        }

                        form.classList.add('was-validated');
                        event.preventDefault();
                    }, false);
                });

        document.getElementById('onModalUpdate').addEventListener('shown.bs.modal', function (event) {

        });

        document.getElementById('onModalUpdate').addEventListener('hidden.bs.modal', function (event) {

        });

        settings.table.on('click-row.bs.table', function (e, row) {
            _id = row[settings.id];
            $.each(row, function(attr, value) {
                $('#' + attr).val(value);
            });
            
            onModalUpdate.show();
        });

    };
})(jQuery);