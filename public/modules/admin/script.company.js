$(document).ready(function () {

    var _url = '/admin/company/';
    var _id = 'id_company';

    var _fields = {
        document_company: {
            identifier: 'document_company',
            rules: [
                {
                    type: 'empty',
                    prompt: 'Informe'
                }
            ]
        },
        name_company: {
            identifier: 'name_company',
            rules: [
                {
                    type: 'empty',
                    prompt: 'Informe'
                }
            ]
        },
        nickname_company: {
            identifier: 'nickname_company',
            rules: [
                {
                    type: 'empty',
                    prompt: 'Informe'
                }
            ]
        },
        phone_primary_company: {
            identifier: 'phone_primary_company',
            rules: [
                {
                    type: 'empty',
                    prompt: 'Informe'
                }
            ]
        }
    };

    var _table = $('#onTableCompany').DataTable({
        ajax: _url + 'ajax',
        language: {
            url: '/public/library/datatables/language.json'
        },
        searching: false,
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
                data: 'nickname_company'
            },
            {
                class: 'on-edit',
                data: 'document_company'
            },
            {
                class: 'on-edit',
                data: 'phone_primary_company'
            },
            {
                class: 'on-edit',
                data: 'status_company',
                render: function (value, type, row) {
                    return (value == 1 ? 'Ativo' : 'Bloqueado');
                }
            },
            {
                className: 'collapsing noVis',
                orderable: false,
                data: null,
                render: function (value, type, row) {
                    return '<button class="ui black button onButtonSwitcher">Acessar conta</button>';
                }
            }
        ],
        order: [[1, 'asc']],
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
    
    $(document).on('click', '.onButtonSwitcher', function() {
        var row = _table.row($(this).parents('tr')).data();
        
        $.ajax({
            type: 'POST',
            url: '/account/user/update',
            data: {data: 'id_company_session=' + row['id_company']},
            success: function (response) {
                switch (response.status) {
                    case 'success':
                        window.location.reload();
                        break;
                }
            }
        });
    });

});