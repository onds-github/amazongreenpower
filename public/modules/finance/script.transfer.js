$(document).ready(function () {

    var _url = '/finance/transfer/';
    var _id = 'id_order';

    var _fields = {
        description_order: {
            identifier: 'description_order',
            rules: [
                {
                    type: 'empty',
                    prompt: 'Informe'
                }
            ]
        },
        id_account_out: {
            identifier: 'id_account_out',
            rules: [
                {
                    type: 'empty',
                    prompt: 'Selecione'
                }
            ]
        },
        id_account_in: {
            identifier: 'id_account_in',
            rules: [
                {
                    type: 'empty',
                    prompt: 'Selecione'
                }
            ]
        },
        different: {
          identifier  : 'id_account_in',
          rules: [
            {
              type   : 'different[id_account_out]',
              prompt : 'Transfira para uma conta diferente'
            }
          ]
        },
        date_due_order: {
            identifier: 'date_due_order',
            rules: [
                {
                    type: 'empty',
                    prompt: 'Infome'
                }
            ]
        },
        price_order: {
            identifier: 'price_order',
            rules: [
                {
                    type: 'empty',
                    prompt: 'Infome'
                }
            ]
        }
    };
    
    
    $('.on-dropdown-account-in').dropdown({
        apiSettings: {
            url: '/finance/transfer/dropdown-account?q={query}',
            cache: false
        },
        filterRemoteData: true
    }).dropdown('queryRemote', '', function () {});

    
    $('.on-dropdown-account-out').dropdown({
        apiSettings: {
            url: '/finance/transfer/dropdown-account?q={query}',
            cache: false
        },
        filterRemoteData: true
    }).dropdown('queryRemote', '', function () {});


    var _table = $('#onTableTransfer').DataTable({
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
                data: 'description_order'
            },
            {
                class: 'on-edit',
                data: 'name_account_out'
            },
            {
                class: 'on-edit',
                data: 'name_account_in'
            },
            {
                class: 'on-edit',
                type: 'date-br',
                data: 'date_due_order',
                render: function (value, type, row) {
                    return moment(value).format('DD/MM/YYYY');
                }
            },
            {
                class: 'on-edit',
                type: 'currency',
                data: 'price_order',
                render: function (value, type, row) {
                    return numeral(value).format('$ 0,0');
                }
            }
        ],
        order: [[4, 'desc']],
        select: {
            style: 'os',
            selector: 'td:first-child'
        }
    });

    $(this).onUpdate({
        url: _url + 'update',
        fields: _fields,
        table: _table,
        modal: 'onModalUpdate',
        id: _id
    });

});
