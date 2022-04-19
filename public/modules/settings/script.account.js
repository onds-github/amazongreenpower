$(document).ready(function () {

    var _url = '/settings/account/';
    var _id = 'id_account';

    var _fields = {
        name_account: {
            identifier: 'name_account',
            rules: [
                {
                    type: 'empty',
                    prompt: 'Informe'
                }
            ]
        },
        id_account_type: {
            identifier: 'id_account_type',
            rules: [
                {
                    type: 'empty',
                    prompt: 'Selecione'
                }
            ]
        }
    };

    var _table = $('#onTableAccount').DataTable({
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
                data: 'name_account'
            },
            {
                class: 'on-edit',
                data: 'debit_account'
            },
            {
                class: 'on-edit',
                data: 'id_account_type',
                render: function (value, row, index) {
                    switch (parseInt(value)) {
                        case 1:
                            return 'Conta corrente';
                            break;
                        case 2:
                            return 'Conta poupança';
                            break;
                        case 3:
                            return 'Caixa';
                            break;
                        case 4:
                            return 'Cartão de crédito';
                            break;
                    }
                }
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

});