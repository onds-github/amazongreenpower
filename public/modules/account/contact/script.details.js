$(document).ready(function () {
    // Turn input element into a pond
    $('.on-filepond').filepond();

    // Set allowMultiple property to true
    $('.on-filepond').filepond('allowMultiple', false);
    
    $('.on-filepond').on('FilePond:addfile', function(e) {
        $.ajax({
            type: 'GET',
            url: '/account/contact/select',
            data: {q: atob(_url()['q'])},
            success: function (e) {
                $.each(e, function (i, v) {
                    $.each(v, function (x, z) {
                        switch (x) {
                            case 'image_contact':
                                if (z) {
                                    $('.' + x).attr('src', z);
                                }
                                break;
                        }
                    });
                });
            }
        });
    });
    
    FilePond.setOptions({
        labelIdle: 'Arraste & solte sua imagem ou <span class="filepond--label-action"> Procure </span>',
        allowReplace: false,
        server: {
            url: '/account/contact/',
            process: 'upload?q=' + atob(_url()['q']),
            revert: 'remove-image?q=' + atob(_url()['q'])
        }
    });
//
//    var _table = $('.on-table-contact-type').DataTable({
//        ajax: '/account/contact-type-link/ajax?q=' + atob(_url()['q']),
//        language: {
//            url: '/public/library/datatables/language.json'
//        },
//        searching: false,
//        paging: false,
//        info: false,
//        columnDefs: [
//            {
//                className: 'collapsing',
//                searchable: false,
//                orderable: false,
//                targets: 0,
//                render: function (data, type, row) {
//                    return   (row[2] ? '<button class="circular ui icon primary button on-button-toggle-off" uk-tooltip="title: Remover atribuição de ' + row[1] + '">' + row[1].substr(0, 1) + '</button>' : '<button class="circular ui icon grey button on-button-toggle-on" uk-tooltip="title: Adicionar atribuição de ' + row[1] + '">' + row[1].substr(0, 1) + '</button>');
//                }
//            },
//            {
//                searchable: false,
//                orderable: false,
//                targets: 1
//            },
//            {
//                searchable: false,
//                orderable: false,
//                targets: 2,
//                visible: false
//            }
//        ],
//        order: [[0, 'asc']]
//    });

    $('.on-table-contact-type tbody').on('click', 'tr td .on-button-toggle-off', function () {
        var row = _table.row($(this).parents('tr')).data();

        $.ajax({
            type: 'POST',
            url: '/account/contact-type-link/delete',
            data: {id_contact_type_link: row[2]},
            success: function (e) {
                UIkit.notification({message: e.message, status: e.status});
            },
            complete: function () {
                _table.ajax.reload();
            }
        });
    });

    $('.on-table-contact-type tbody').on('click', 'tr td .on-button-toggle-on', function () {
        var row = _table.row($(this).parents('tr')).data();

        $.ajax({
            type: 'POST',
            url: '/account/contact-type-link/insert',
            data: {id_contact_type: row[0], id_contact: atob(_url()['q'])},
            success: function (e) {
                UIkit.notification({message: e.message, status: e.status});
            },
            complete: function () {
                _table.ajax.reload();
            }
        });
    });

    $.ajax({
        type: 'GET',
        url: '/account/contact/select',
        data: {q: atob(_url()['q'])},
        success: function (e) {
            $.each(e, function (i, v) {
                $.each(v, function (x, z) {
                    switch (x) {
                        case 'image_contact':
                            if (z) {
                                $('.' + x).attr('src', z);
                            }
                            break;

                        default:
                            $('.on-form-contact').form('set value', x, z);
                            break;
                    }
                });
            });
        }
    });

    var _form = $('form').submit(function (event) {
        return false;
    }).form({
        inline: true,
        fields: {
            nickname_contact: {
                identifier: 'nickname_contact',
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
                url: '/account/contact/update',
                data: {data: _form.serialize(), q: atob(_url()['q'])},
                success: function (e) {
                    UIkit.notification({message: e.message, status: e.status});
                },
                complete: function () {
                    _form.addClass('disabled');
                }
            });
        }
    });

});