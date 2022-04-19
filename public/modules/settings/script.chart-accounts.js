$(document).ready(function () {

    var _url = '/settings/chart-accounts/';
    var _id = 'id_chart_accounts';

    var _fields = {
        name_chart_accounts: {
            identifier: 'name_chart_accounts',
            rules: [
                {
                    type: 'empty',
                    prompt: 'Informe'
                }
            ]
        }
    };

    var _table = $('#onTableChartAccounts').DataTable({
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
                data: 'name_chart_accounts'
            },
            {
                class: 'on-edit',
                data: 'credit_chart_accounts'
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