$(document).ready(function () {

    var _url = '/account/contact/';
    var _id = 'id_contact';

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

    var _table = $('#onTableContact').DataTable({
        ajax: _url + 'ajax',
        language: {
            url: '/public/library/datatables/language.json'
        },
        paging: false,
        dom: 'Bfrtip',
        buttons: [
            {
                className: 'ui primary button',
                text: 'Novo registro',
                action: function () {

                    $(this).onCreate({
                        url: _url + 'insert',
                        table: _table,
                        fields: _fields,
                        modal: 'onModalCreate'
                    });

                }
            },
            {
                className: 'ui red button',
                text: 'Excluir',
                action: function () {

                    $(this).onDelete({
                        url: _url + 'delete',
                        table: _table,
                        modal: 'onModalDelete',
                        id: _id
                    });

                }
            },{
                className: 'ui black button',
                text: 'Importar .CSV',
                action: function () {

                    var index = $(this).parents('tr').index();
                    $('.on-form-file input').click();
                    var fu = $('.on-form-file').fileupload({
                        url: '/account/contact/import',
                        start: function (e) {
//                            UIkit.notification('Iniciando...', {status: 'success'});
                        },
                        add: function (e, v) {
//                            UIkit.notification('Importando...', {status: 'success'});
                            var jqXHR = v.submit();
                        },
                        progress: function (e, v) {
                            var progress = parseInt(v.loaded / v.total * 100, 10);

                            if (progress === 100) {

                            }
                        },
                        progressall: function (e, data) {
                            var progress = parseInt(data.loaded / data.total * 100, 10);

                            if (progress === 100) {

                            }
//                            UIkit.notification('Finalizando...', {status: 'success'});
                        },
                        done: function (e, v) {
                            _table.ajax.reload();
                        }
                    });

                }
            }
        ],
        columns: [
            {
                orderable: false,
                data: null,
                defaultContent: '',
                className: 'collapsing select-checkbox noVis'
            },
            {
                class: 'on-edit',
                data: 'document_contact',
                visible: false
            },
            {
                class: 'on-edit',
                data: 'nickname_contact',
                render: function (value, type, row) {
                    return (value ? value : row['name_contact']);
                }
            },
            {
                class: 'on-edit',
                data: 'email_contact'
            },
            {
                class: 'on-edit',
                data: 'phone_primary_contact'
            },
            {
                orderable: false,
                class: 'collapsing',
                data: 'client_contact',
                render: function (value, type, row) {
                    return (value == '1' ? '<button class="circular ui icon primary button on-button-client-toggle-off" uk-tooltip="title: Remover atribuição de cliente">C</button>' : '<button class="circular ui icon grey button on-button-client-toggle-on" uk-tooltip="title: Adicionar atribuição de cliente">C</button>');
                },
                visible: false
            },
            {
                orderable: false,
                class: 'collapsing',
                data: 'provider_contact',
                render: function (value, type, row) {
                    return (value == '1' ? '<button class="circular ui icon primary button on-button-provider-toggle-off" uk-tooltip="title: Remover atribuição de fornecedor">F</button>' : '<button class="circular ui icon grey button on-button-provider-toggle-on" uk-tooltip="title: Adicionar atribuição de fornecedor">F</button>');
                },
                visible: false
            }
        ],
        order: [[1, 'desc']],
        select: {
            style: 'os',
            selector: 'td:first-child'
        }
    });
    
    $('.ui.dropdown').dropdown();

    $(this).onUpdate({
        url: _url + 'update',
        fields: _fields,
        table: _table,
        modal: 'onModalUpdate',
        id: _id
    });


    _table.on('click', 'tr td .on-button-client-toggle-on', function () {
        var row = _table.row($(this).parents('tr')).data();
        $.ajax({
            type: 'POST',
            url: '/account/contact/update',
            data: {data: 'client_contact=1', q: row[_id]},
            success: function (e) {
            },
            complete: function () {
                _table.ajax.reload();
            }
        });
    });

    _table.on('click', 'tr td .on-button-client-toggle-off', function () {
        var row = _table.row($(this).parents('tr')).data();
        $.ajax({
            type: 'POST',
            url: '/account/contact/update',
            data: {data: 'client_contact=0', q: row[_id]},
            success: function (e) {
            },
            complete: function () {
                _table.ajax.reload();
            }
        });
    });

    _table.on('click', 'tr td .on-button-provider-toggle-on', function () {
        var row = _table.row($(this).parents('tr')).data();
        $.ajax({
            type: 'POST',
            url: '/account/contact/update',
            data: {data: 'provider_contact=1', q: row[_id]},
            success: function (e) {
            },
            complete: function () {
                _table.ajax.reload();
            }
        });
    });

    _table.on('click', 'tr td .on-button-provider-toggle-off', function () {
        var row = _table.row($(this).parents('tr')).data();
        $.ajax({
            type: 'POST',
            url: '/account/contact/update',
            data: {data: 'provider_contact=0', q: row[_id]},
            success: function (e) {
            },
            complete: function () {
                _table.ajax.reload();
            }
        });
    });

});