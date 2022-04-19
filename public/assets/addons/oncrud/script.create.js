(function ($) {
    $.fn.insert = function (options) {

        var defaults = {
            className: null,
            url: null,
            table: null,
            content: null,
            fields: null,
            onDeny: null,
            onSuccess: null
        };

        var settings = $.extend({}, defaults, options);

//        var $modal = $('<div />', {
//            'html': '<div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">Formulário de cadastro</h5></div><div class="modal-body"><form class="row g-2 needs-validation" id="onFormCreate" novalidate></form></div></div></div>',
//            'class': 'modal fade',
//            'data-bs-backdrop': 'static',
//            'data-bs-keyboard': 'false',
//            'aria-labelledby': 'staticBackdropLabel',
//            'aria-hidden': 'true',
//            'tabindex': '-1',
//            'id': 'onModalCreate'
//        }).appendTo('body');
//
//        $.each(settings.fields, function (count, attr) {
//
//            if (attr.type === 'hidden') {
//                $('<input />', {
//                    type: attr.type,
//                    name: attr.name,
//                    value: attr.value
//                }).prependTo('#onFormCreate');
//            } else {
//
//                var _col = $('<div />', {
//                    class: attr.col
//                }).appendTo('#onFormCreate');
//
//                var _form_floating = $('<div />', {
//                    class: 'form-floating'
//                }).appendTo(_col);
//
//                if (attr.type === 'textarea') {
//                    $('<textarea />', {
//                        name: attr.name,
//                        rows: attr.rows
//                    }).appendTo(_form_floating);
//                } else if (attr.type === 'select') {
//
//                    var select = $('<select />', {
//                        class: 'form-select',
//                        name: attr.name
//                    }).appendTo(_form_floating);
//
//                    $.each(attr.itens, function (itensCount, itensAttr) {
//                        $('<option />', {
//                            value: itensAttr.value,
//                            html: itensAttr.label
//                        }).appendTo(select);
//                    });
//
//                } else {
//                    $('<input />', {
//                        type: attr.type,
//                        name: attr.name,
//                        class: 'form-control ' + attr.class,
//                        maxlength: attr.maxlength,
//                        value: attr.value,
//                        required: attr.required
//                    }).appendTo(_form_floating);
//                }
//
//                $('<label />', {
//                    html: attr.label
//                }).appendTo(_form_floating);
//
//            }
//
//        });

//        var _col_btn = $('<div />', {
//            class: 'col-12'
//        }).appendTo('#onFormCreate');
//
//        $('<button />', {
//            'type': 'submit',
//            'html': 'Salvar',
//            'class': 'btn btn-primary float-end'
//        }).appendTo(_col_btn);
//
//
//        $('<div />', {
//            'data-bs-dismiss': 'modal',
//            'type': 'button',
//            'html': 'Cancelar',
//            'class': 'btn btn-secondary me-3 float-end'
//        }).appendTo(_col_btn);

        var onModalCreate = new bootstrap.Modal(document.getElementById('onModalCreate'));

        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.querySelectorAll('#onFormCreate');


        var _form = $('.on-form-insert').submit(function (event) {
            return false;
        }).form({
            inline: true,
            fields: _fields,
            onSuccess: function () {
                $.ajax({
                    type: 'POST',
                    url: settings.url,
                    data: {data: $('#onFormCreate').serialize(), q: row[ 0 ]},
                    beforeSend: function (xhr) {
                        $('.on-modal-update').find('.ui.button').addClass('disabled');
                    },
                    success: function (e) {
                        switch (data.status) {
                            case 'success':
                                settings.table.bootstrapTable('refresh');
                                onModalCreate.hide();
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
        });

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
                                data: {data: $('#onFormCreate').serialize()},
                                success: function (data) {
                                    switch (data.status) {
                                        case 'success':
                                            settings.table.bootstrapTable('refresh');
                                            onModalCreate.hide();
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

        document.getElementById('onModalCreate').addEventListener('shown.bs.modal', function (event) {

        });

        document.getElementById('onModalCreate').addEventListener('hidden.bs.modal', function (event) {

        });

        $(document).on('click', settings.className, function () {
            onModalCreate.show();
        });

    };
})(jQuery);