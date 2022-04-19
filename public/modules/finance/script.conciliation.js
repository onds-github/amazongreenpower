$(document).ready(function () {

    var onModalNotifyEmpty = $('<div />', {
        'html': '<div class="header">Atenção</div><div class="content"><p>Você precisa selecionar pelo menos 1 registro para continuar!</p></div><div class="actions"><button class="ui black deny button">Fechar</button>',
        'class': 'ui mini modal',
        'id': 'onModalNotifyEmpty'
    }).appendTo('body');

    var onModalNotifyDifferent = $('<div />', {
        'html': '<div class="header">Atenção</div><div class="content"><p>A soma dos valores a serem conciliados são diferentes!</p></div><div class="actions"><button class="ui black deny button">Fechar</button>',
        'class': 'ui mini modal',
        'id': 'onModalNotifyDifferent'
    }).appendTo('body');

    var _url = '/finance/conciliation/';
    var _id = 'id_order_conciliation';

    var _tableOrderConciliation = $('#onTableOrderConciliation').DataTable({
        ajax: _url + 'ajax-conciliation',
        language: {
            url: '/public/library/datatables/language.json'
        },
        searching: false,
        paging: false,
        dom: 'Bfrtip',
        buttons: [
            {
                className: 'ui blue button',
                text: 'Importar .OFX',
                action: function () {

                    var index = $(this).parents('tr').index();
                    $('.on-form-file input').click();
                    var fu = $('.on-form-file').fileupload({
                        url: '/finance/conciliation/insert',
                        start: function (e) {
                            
                        },
                        add: function (e, v) {
                            
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
                            
                        },
                        done: function (e, v) {
                            _tableOrderConciliation.ajax.reload();
                        }
                    });

                }
            },
            {
                className: 'ui green button onButtonConciliation',
                text: 'Conciliar',
                action: function () {
                    var countOrderConciliation = _tableOrderConciliation.rows({selected: true}).count();
                    var dataOrderConciliation = _tableOrderConciliation.rows({selected: true}).data().toArray();

                    var countOrder = _tableOrder.rows({selected: true}).count();
                    var dataOrder = _tableOrder.rows({selected: true}).data().toArray();

                    if (countOrderConciliation == 0) {
                        onModalNotifyEmpty.modal('show');
                    } else if (countOrder == 0) {
                        onModalNotifyEmpty.modal('show');
                    } else if ($('#sumInOrder').val() != $('#sumInConciliation').val()) {
                        onModalNotifyDifferent.modal('show');

                    } else if ($('#sumOutOrder').val() != $('#sumOutConciliation').val()) {
                        onModalNotifyDifferent.modal('show');
                    } else {
                        
                        
                        if ((countOrderConciliation == 1 && countOrder > 1) || (countOrderConciliation > 1 && countOrder == 1)) {
                            $.ajax({
                                type: 'POST',
                                url: '/finance/conciliation/conciliation-order-single',
                                data: {data_order: JSON.stringify(dataOrder), data_conciliation: JSON.stringify(dataOrderConciliation)},
                                success: function (data) {

                                },
                                complete: function (jqXHR, textStatus) {
                                    _tableOrderConciliation.ajax.reload();
                                    _tableOrder.ajax.reload();
                                }
                            });
                        } else {
                            $.ajax({
                                type: 'POST',
                                url: '/finance/conciliation/conciliation-order',
                                data: {data_order: JSON.stringify(dataOrder), data_conciliation: JSON.stringify(dataOrderConciliation)},
                                success: function (data) {

                                },
                                complete: function (jqXHR, textStatus) {
                                    _tableOrderConciliation.ajax.reload();
                                    _tableOrder.ajax.reload();
                                }
                            });
                        }

                    }

                }
            },
            {
                className: 'ui red button',
                text: 'Excluir',
                action: function () {

                    $(this).onDelete({
                        url: _url + 'delete',
                        table: _tableOrderConciliation,
                        modal: 'onModalDelete',
                        id: _id
                    });

                }
            }
        ],
        stateSave: true,
        paging: false,
        columns: [
            {
                orderable: false,
                data: null,
                defaultContent: '',
                className: 'select-checkbox noVis'
            },
            {
                className: 'on-edit',
                type: 'date-br',
                data: 'date_order',
                render: function (value, type, row) {
                    return moment(value).format('DD/MM/YYYY')
                }
            },
            {
                className: 'on-edit',
                data: 'description_order',
            },
            {
                className: 'collapsing on-edit',
                type: 'currency',
                data: 'price_order',
                render: function (value, type, row) {
                    return numeral(value).format('0,0.00');
                }
            }
        ],
        order: [[1, 'asc']],
        select: {
            style: 'multi'
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

    _tableOrderConciliation
            .on('select', function (e, dt, type, indexes) {
                var rowData = _tableOrderConciliation.rows(indexes).data().toArray();

                var _sum_in = $.map(rowData, function (row) {
                    if (row.id_type_order == 1) {
                        return row.price_order;
                    }
                });

                var _total_in = _sum_in.reduce(function (a, b) {
                    return parseFloat(a) + parseFloat(b);
                }, 0);
                $('#sumInConciliation').val(_total_in);

                // Soma Valores de Saída
                var _sum_out = $.map(rowData, function (row) {
                    if (row.id_type_order == 2) {
                        return row.price_order;
                    }
                });
                var _total_out = _sum_out.reduce(function (a, b) {
                    return parseFloat(a) + parseFloat(b);
                }, 0);
                $('#sumOutConciliation').val(_total_out);
            })
            .on('deselect', function (e, dt, type, indexes) {
                var rowData = _tableOrderConciliation.rows(indexes).data().toArray();

                var _sum_in = $.map(rowData, function (row) {
                    if (row.id_type_order == 1) {
                        return row.price_order;
                    }
                });

                var _total_in = _sum_in.reduce(function (a, b) {
                    return parseFloat(a) + parseFloat(b);
                }, 0);
                $('#sumInConciliation').val(_total_in);

                // Soma Valores de Saída
                var _sum_out = $.map(rowData, function (row) {
                    if (row.id_type_order == 2) {
                        return row.price_order;
                    }
                });
                var _total_out = _sum_out.reduce(function (a, b) {
                    return parseFloat(a) + parseFloat(b);
                }, 0);
                $('#sumOutConciliation').val(_total_out);
            });


    var _tableOrder = $('#onTableOrder').DataTable({
        ajax: _url + 'ajax-order',
        language: {
            url: '/public/library/datatables/language.json'
        },
        searching: false,
        paging: false,
        dom: 'Bfrtip',
        buttons: [
            {
                className: 'ui black button',
                text: 'Desconciliar',
                action: function () {
                    var countOrder = _tableOrder.rows({selected: true}).count();
                    var dataOrder = _tableOrder.rows({selected: true}).data().toArray();

                    if (countOrder == 0) {
                        onModalNotifyEmpty.modal('show');
                    } else {

                        $.ajax({
                            type: 'POST',
                            url: '/finance/conciliation/remove-order',
                            data: {data: JSON.stringify(dataOrder)},
                            success: function (data) {
                                
                            },
                            complete: function (jqXHR, textStatus) {
                                _tableOrder.ajax.reload();
                                _tableOrderConciliation.ajax.reload();
                            }
                        });

                    }
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
                className: 'select-checkbox noVis'
            },
            {
                className: 'on-edit',
                type: 'date-br',
                data: 'date_order',
                render: function (value, type, row) {
                    return moment(value).format('DD/MM/YYYY')
                }
            },
            {
                className: 'on-edit',
                data: 'description_order',
            },
            {
                className: 'on-edit',
                type: 'currency',
                data: 'price_down',
                render: function (value, type, row) {
                    return numeral(value).format('0,0.00') + (row.token_conciliation ? ' <i class="check green circle icon" uk-tooltip="title: Lançamento conciliado"></i>' : '');
                }
            }
        ],
        order: [[1, 'asc']],
        select: {
            style: 'multi'
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

    _tableOrder
            .on('select', function (e, dt, type, indexes) {
                var rowData = _tableOrder.rows({selected: true}).data().toArray();

                function isConciliation(conciliation) {
                    return conciliation.token_conciliation !== null;
                }

                if (rowData.find(isConciliation) === undefined || rowData.find(isConciliation) === null) {
                    $('.onButtonConciliation').removeAttr('disabled');

                    var _sum_in = $.map(rowData, function (row) {
                        if (row.id_type_order == 1) {
                            return row.price_down;
                        }
                    });

                    var _total_in = _sum_in.reduce(function (a, b) {
                        return parseFloat(a) + parseFloat(b);
                    }, 0);
                    $('#sumInOrder').val(_total_in);

                    // Soma Valores de Saída
                    var _sum_out = $.map(rowData, function (row) {
                        if (row.id_type_order == 2) {
                            return row.price_down;
                        }
                    });
                    var _total_out = _sum_out.reduce(function (a, b) {
                        return parseFloat(a) + parseFloat(b);
                    }, 0);
                    $('#sumOutOrder').val(_total_out);
                    
                } else {
//                    UIkit.notification('Não é possível conciliar um registro conciliado!', {status: 'warning'});
                    $('.onButtonConciliation').attr('disabled', 'disabled');
                }
            })
            .on('deselect', function (e, dt, type, indexes) {
                var rowData = _tableOrder.rows({selected: true}).data().toArray();

                var _sum_in = $.map(rowData, function (row) {
                    if (row.id_type_order == 1) {
                        return row.price_down;
                    }
                });

                var _total_in = _sum_in.reduce(function (a, b) {
                    return parseFloat(a) + parseFloat(b);
                }, 0);
                $('#sumInOrder').val(_total_in);

                // Soma Valores de Saída
                var _sum_out = $.map(rowData, function (row) {
                    if (row.id_type_order == 2) {
                        return row.price_down;
                    }
                });
                var _total_out = _sum_out.reduce(function (a, b) {
                    return parseFloat(a) + parseFloat(b);
                }, 0);
                $('#sumOutOrder').val(_total_out);
                
            });


    $('.on-dropdown-account').dropdown({
        apiSettings: {
            url: _url + 'dropdown-account?q={query}',
            cache: false
        },
        filterRemoteData: true,
        clearable: true
    }).dropdown('queryRemote', '', function () {});


    $(this).onFilter({
        table: _tableOrderConciliation,
        onSuccess: function (response) {
            switch (response.status) {
                case 'success':
                    _tableOrder.ajax.reload();
                    break;
            }
        }
    });

});