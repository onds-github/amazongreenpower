$(document).ready(function () {

    function _format(data) {
        var _html = '<p class="uk-text-meta uk-text-center">Lançamentos de saída</p>\
            <table class="ui celled table" id="onTableOrder' + data['id_order'] + '">\n\
                <thead class="transition hidden">\n\
                    <tr>\n\
                        <th></th>\n\
                        <th>Ref.</th>\n\
                        <th>Descrição</th>\n\
                        <th>Fornecedor</th>\n\
                        <th>Data</th>\n\
                        <th>Valor</th>\n\
                    </tr>\n\
                </thead>\n\
            </table>';
        return _html;

    }

    var _url = '/finance/cashier/';
    var _id = 'id_order';

    var _table = $('#onTableCashier').DataTable({
        ajax: _url + 'ajax',
        language: {
            url: '/public/library/datatables/language.json'
        },
        paging: false,
        dom: 'Bfrtip',
        buttons: [
            {
                className: 'ui red button',
                text: 'Nova retirada',
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
            },
        ],
        stateSave: true,
        paging: false,
        columns: [
            {
                orderable: false,
                data: null,
                defaultContent: '',
                className: 'collapsing select-checkbox noVis'
            },
            {
                className: 'on-edit',
                data: 'description_order',
            },
            {
                className: 'on-edit',
                data: 'name_account',
            },
            {
                className: 'on-edit',
                type: 'date-br',
                data: 'date_due_order',
                render: function (value, type, row) {
                    return moment(value).format('DD/MM/YYYY')
                }
            },
            {
                className: 'on-edit',
                type: 'currency',
                data: 'price_order',
                render: function (value, type, row) {
                    return numeral(value).format('$ 0,0.00');
                }
            },
            {
                className: 'on-edit',
                type: 'currency',
                data: 'sum_order',
                render: function (value, type, row) {
                    return numeral(value).format('$ 0,0.00');
                }
            },
            {
                className: 'on-edit',
                type: 'currency',
                data: 'troco',
                render: function (value, type, row) {
                    return numeral(value).format('$ 0,0.00');
                }
            },
            {
                className: 'collapsing noVis',
                orderable: false,
                data: 'cashier_order_check',
                render: function (value, type, row) {
                    return parseInt(value) == 1 ? '<div class="ui mini yellow label on-button-not-okay">Em aberto</div>' : '<div class="ui mini green label on-button-okay">Baixado</div>';
                }
            },
            {
                className: 'collapsing noVis',
                orderable: false,
                data: null,
                render: function (value, type, row) {
                    return '<div class="ui aligned centered mini label green label on-button-details"><i class="plus icon"></i></div>';
                }
            }
        ],
        order: [[3, 'desc']],
        select: {
            style: 'os',
            selector: 'td:first-child'
        },
        createdRow: function (row, data, dataIndex) {
            switch (parseInt(data['id_type_order'])) {
                case 1:
                    $(row).addClass('positive');
                    break;
                case 2:
                    $(row).addClass('negative');
                    break;
            }
        },
//        footerCallback: function (row, data, start, end, display) {
//            var api = this.api(), data;
//            // Remove the formatting to get integer data for summation
//            var intVal = function (i) {
//                return typeof i === 'string' ?
//                        i.replace(/[\.]/g, '').replace(/[\,]/g, '.').replace(/[\R$]/g, '') * 1 :
//                        typeof i === 'number' ?
//                        i : 0;
//            };
//            var _total = api
//                    .column(7, {search: 'applied'})
//                    .data()
//                    .reduce(function (a, b) {
//                        return intVal(a) + intVal(b);
//                    }, 0);
//            // Update footer
//            $(api.column(7).footer()).html('R$ ' + numeral(_total).format('0,0.00'));
//        }
    });


    // Array to track the ids of the details displayed rows
    var detailRows = [];

    $('#onTableCashier tbody').on('click', 'tr td .on-button-details', function () {
        var tr = $(this).closest('tr');
        var row = _table.row(tr);
        var idx = $.inArray(tr.attr('id'), detailRows);
        var _idOrder = row.data()['id_order'];
        var _priceOrder = row.data()['price_order'];

        if (row.child.isShown()) {
            tr.removeClass('details uk-background-muted');
            $(this).html('<i class="plus icon"></i>').addClass('green').removeClass('red');
            row.child.hide();

            // Remove from the 'open' array
            detailRows.splice(idx, 1);
        } else {
            tr.addClass('details uk-background-muted');
            $(this).html('<i class="minus icon"></i>').addClass('red').removeClass('green');
            row.child(_format(row.data())).show();

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
                id_account: {
                    identifier: 'id_account',
                    rules: [
                        {
                            type: 'empty',
                            prompt: 'Selecione'
                        }
                    ]
                },
                id_contact: {
                    identifier: 'id_contact',
                    rules: [
                        {
                            type: 'empty',
                            prompt: 'Selecione'
                        }
                    ]
                },
                date_issue_order: {
                    identifier: 'date_issue_order',
                    rules: [
                        {
                            type: 'empty',
                            prompt: 'Infome'
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
                date_payment_order: {
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

            var _tableOrder = $('#onTableOrder' + _idOrder).DataTable({
                ajax: '/finance/cashier/ajax-order?q=' + _idOrder,
                language: {
                    url: '/public/library/datatables/language.json'
                },
                searching: false,
                paging: false,
                info: false,
                dom: 'Bfrtip',

                buttons: [
                    {
                        className: 'ui red button ' + (parseInt(row.data()['cashier_order_check']) == 0 ? 'transition hidden' : ''),
                        text: 'Nova saída',
                        action: function () {

                            $(this).onCreate({
                                url: _url + 'insert-order?q=' + _idOrder + '&id_account=' + row.data()['id_account'],
                                table: _tableOrder,
                                fields: _fields,
                                modal: 'onModalCreateOut'
                            });

                        }
                    },
                    {
                        className: 'ui red button ' + (parseInt(row.data()['cashier_order_check']) == 0 ? 'transition hidden' : ''),
                        text: 'Excluir',
                        action: function () {

                            $(this).onDelete({
                                url: _url + 'delete',
                                table: _tableOrder,
                                modal: 'onModalDelete',
                                id: _id
                            });

                        }
                    },
                ],
                //        stateSave: true,
                paging: false,
                columns: [
                    {
                        orderable: false,
                        data: null,
                        defaultContent: '',
                        className: 'collapsing select-checkbox noVis'
                    },
                    {
                        className: 'on-edit',
                        data: 'reference_order',
                    },
                    {
                        className: 'on-edit',
                        data: 'description_order',
                    },
                    {
                        className: 'on-edit',
                        data: 'nickname_contact'
                    },
                    {
                        className: 'on-edit',
                        type: 'date-br',
                        data: 'date_issue_order',
                        render: function (value, type, row) {
                            return moment(value).format('DD/MM/YYYY')
                        }
                    },
                    {
                        className: 'on-edit',
                        type: 'currency',
                        data: 'price_order',
                        render: function (value, type, row) {
                            return numeral(value).format('$ 0,0.00');
                        }
                    }
                ],
                order: [[4, 'desc']],
                select: {
                    style: 'os',
                    selector: 'td:first-child'
                },
                createdRow: function (row, data, dataIndex) {
                    switch (parseInt(data['id_type_order'])) {
                        case 1:
                            $(row).addClass('positive');
                            break;
                        case 2:
                            $(row).addClass('negative');
                            break;
                    }
                }
            });

            // Add to the 'open' array
            if (idx === -1) {
                detailRows.push(tr.attr('id'));
            }
        }
    });


    $('.on-dropdown-contact').dropdown({
        apiSettings: {
            url: '/finance/order/select-dropdown-contact?q={query}',
            cache: false
        },
        filterRemoteData: true,
        clearable: true
    }).dropdown('queryRemote', '', function () {});

    $('.on-dropdown-account').dropdown({
        apiSettings: {
            url: '/finance/order/select-dropdown-account?q={query}',
            cache: false
        },
        filterRemoteData: true,
        clearable: true
    }).dropdown('queryRemote', '', function () {});

    $('.on-dropdown-chart-accounts').dropdown({
        apiSettings: {
            url: '/finance/order/select-dropdown-chart-accounts?q={query}',
            cache: false
        },
        filterRemoteData: true,
        clearable: true
    }).dropdown('queryRemote', '', function () {});

    $('.on-dropdown-cost-center').dropdown({
        apiSettings: {
            url: '/finance/order/select-dropdown-cost-center?q={query}',
            cache: false
        },
        filterRemoteData: true,
        clearable: true
    }).dropdown('queryRemote', '', function () {});


    // On each draw, loop over the `detailRows` array and show any child rows
    _table.on('draw', function () {
        $.each(detailRows, function (i, id) {
            $('#' + id + ' td .on-button-details').trigger('click');
        });
    });

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
        id_account: {
            identifier: 'id_account',
            rules: [
                {
                    type: 'empty',
                    prompt: 'Selecione'
                }
            ]
        },
        date_issue_order: {
            identifier: 'date_issue_order',
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

    $('.on-account-dropdown').dropdown({
        apiSettings: {
            url: '/finance/cashier/select-account-dropdown?q={query}',
            cache: false
        },
        filterRemoteData: true
    }).dropdown('queryRemote', '', function () {});

    $('.on-chart-accounts-dropdown').dropdown({
        apiSettings: {
            url: '/finance/cashier/select-chart-accounts-dropdown?q={query}',
            cache: false
        },
        filterRemoteData: true
    }).dropdown('queryRemote', '', function () {});

    $('.on-cost-center-dropdown').dropdown({
        apiSettings: {
            url: '/finance/cashier/select-cost-center-dropdown?q={query}',
            cache: false
        },
        filterRemoteData: true
    }).dropdown('queryRemote', '', function () {});

    $('#onTableCashier tbody').on('click', 'tr td .on-button-not-okay', function () {
        var row = _table.row($(this).parents('tr')).data();

        $.ajax({
            type: 'POST',
            url: '/finance/cashier/update-check',
            data: {data: 'cashier_order_check=0', q: row['id_order']},
            success: function (e) {

            },
            complete: function () {
                _table.ajax.reload();
            }
        });
    });

    $('#onTableCashier tbody').on('click', 'tr td .on-button-okay', function () {
        var row = _table.row($(this).parents('tr')).data();

        $.ajax({
            type: 'POST',
            url: '/finance/cashier/update-check',
            data: {data: 'cashier_order_check=1', q: row['id_order']},
            success: function (e) {

            },
            complete: function () {
                _table.ajax.reload();
            }
        });
    });

});