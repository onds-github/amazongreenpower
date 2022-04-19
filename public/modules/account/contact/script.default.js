$(document).ready(function () {

    var _table = $('.on-table-contact').DataTable({
        ajax: '/account/contact/ajax',
        language: {
            url: '/public/library/datatables/language.json'
        },
        columnDefs: [
            {
                searchable: false,
                orderable: false,
                targets: 0,
                visible: false
            },
            {
                className: 'collapsing',
                targets: 1
            },
            {
                targets: 2
            },
            {
                targets: 3
            },
            {
                targets: 4
            },
            {
                className: 'center aligned collapsing',
                orderable: false,
                render: function (data, type, row) {
                    var _html = '';
                    $.each(data, function(i, v) {
                       _html += (v.id_contact_type_link ? '<button class="circular ui icon primary button on-button-toggle-off" data-id-contact-type-link="' + v.id_contact_type_link + '" uk-tooltip="title: Remover atribuição de ' + v.name_contact_type + '">' + v.name_contact_type.substr(0, 1) + '</button>' : '<button class="circular ui icon grey button on-button-toggle-on" data-id-contact-type="' + v.id_contact_type + '" uk-tooltip="title: Adicionar atribuição de ' + v.name_contact_type + '">' + v.name_contact_type.substr(0, 1) + '</button>');
                    });
                    return _html;
                },
                targets: 5
            },
            {
                searchable: false,
                orderable: false,
                className: 'center aligned collapsing',
                render: function (data, type, row) {
                    return '<button class="circular ui icon button on-button-menu"><i class="bars icon"></i></button>';
                },
                targets: -1
            }
        ],
        order: [[0, 'asc']]
    });
    
    $('.on-form-search input').on( 'keyup', function () {
    _table.search( this.value ).draw();
} );


    $('.on-table-contact tbody').on('click', 'tr td .on-button-toggle-on', function() {
        var row = _table.row($(this).parents('tr')).data();
        var id_contact_type = $(this).data('id-contact-type');
        $.ajax({
            type: 'POST',
            url: '/account/contact-type-link/insert',
            data: {id_contact_type: id_contact_type, id_contact: row[0]},
            success: function (e) {
                UIkit.notification({message: e.message, status: e.status});
            },
            complete: function () {
                _table.ajax.reload();
            }
        });
    });

    $('.on-table-contact tbody').on('click', 'tr td .on-button-toggle-off', function() {
        var row = _table.row($(this).parents('tr')).data();
        var id_contact_type_link = $(this).data('id-contact-type-link');
        $.ajax({
            type: 'POST',
            url: '/account/contact-type-link/delete',
            data: {id_contact_type_link: id_contact_type_link},
            success: function (e) {
                UIkit.notification({message: e.message, status: e.status});
            },
            complete: function () {
                _table.ajax.reload();
            }
        });
    });

    $.fn.form.settings.rules.existsDocument = function (value) {
        var isExists;
        $.ajax({
            async: false,
            type: 'POST',
            url: '/account/contact/exists-document',
            data: {q: value},
            success: function (e) {
                isExists = e;
            }
        });
        return isExists;
    };

    var _modal_insert = $('.on-modal-insert').modal({
        closable: false,
        observeChanges: true,
        autofocus: false,
        onDeny: function () {
            _form_insert.form('reset');
        },
        onApprove: function () {
            _form_insert.submit();
            return false;
        }
    }).modal('attach events', '.on-button-insert');

    var _fields = {
        document_contact: {
            identifier: 'document_contact',
            rules: [
                {
                    type: 'empty',
                    prompt: 'Informe'
                },
//                {
//                    type: 'existsDocument',
//                    prompt: 'Este número de documento já existe como contato'
//                }
            ]
        },
        nickname_contact: {
            identifier: 'nickname_contact',
            rules: [
                {
                    type: 'empty',
                    prompt: 'Infome'
                }
            ]
        }
    };

    var _form_insert = $('.on-form-insert').submit(function (event) {
        return false;
    }).form({
        fields: _fields,
        inline: true,
        onSuccess: function () {
            $.ajax({
                type: 'POST',
                url: '/account/contact/insert',
                data: {data: _form_insert.serialize()},
                beforeSend: function (xhr) {
                    $('.on-modal-insert').find('.ui.button').addClass('disabled');
                },
                success: function (e) {
                    UIkit.notification(e.message, {status: e.status});
                    switch (e.status) {
                        case 'success':
                            window.location.href = '/account/contact/details?q=' + btoa(e.returning);
                            break;
                    }
                },
                complete: function () {
                    _form_insert.form('reset');
                    _modal_insert.modal('hide');
                    _table.ajax.reload();
                    
                    $('.on-modal-insert').find('.ui.button').removeClass('disabled');
                }
            });

        }
    });

    $.contextMenu({
        selector: '.on-table-contact tr td .on-button-menu',
        trigger: 'left',
        callback: function (key, options) {

            var row = _table.row($(this).parents('tr')).data();

            switch (key) {
                case 'update':
                    window.location.href = '/account/contact/details?q=' + btoa(row[0]);
                    break;

                case 'delete':
                    var _modal_delete = $('.on-modal-delete').modal({
                        closable: false,
                        observeChanges: true,
                        autofocus: false,
                        onDeny: function () {

                        },
                        onApprove: function () {
                            if (row[ 4 ] > 0) {
                                UIkit.notification('Não é possível excluir uma conta com lançamentos vinculados!', {status: 'danger'});
                            } else {
                                $.ajax({
                                    type: 'POST',
                                    url: '/account/contact/delete',
                                    data: {id_contact: row[ 0 ]},
                                    beforeSend: function (xhr) {
                                        $('.on-modal-delete').find('.ui.button').addClass('disabled');
                                    },
                                    success: function (e) {
                                        UIkit.notification(e.message, {status: e.status});
                                    },
                                    complete: function (jqXHR, textStatus) {
                                        _modal_delete.modal('hide');
                                        _table.ajax.reload();
                                        
                                        $('.on-modal-delete').find('.ui.button').removeClass('disabled');
                                    }
                                });
                            }
                            return false;
                        }
                    }).modal('show');
                    break;
            }


        },
        items: {
            'update': {name: '<i class="edit icon"></i> Atualizar', isHtmlName: true},
            'delete': {name: '<i class="trash icon"></i> Excluir', isHtmlName: true}
        }
    });

});