$(document).ready(function () {
    
    var _url = '/finance/order/';
    var _id = 'id_order';

    var _table = $('#onTableOrder').DataTable({
        ajax: _url + 'ajax',
        language: {
            url: '/public/library/datatables/language.json'
        },
        searching: false,
        paging: false,
        dom: 'Bfrtip',
        buttons: [
            {
                className: 'ui green button',
                text: 'Nova entrada',
                action: function () {
                    
                    $('#onModalCreateIn form').form('set value', 'date_issue_order', moment().format('YYYY-MM-DD'));
                    $('#onModalCreateIn form').form('set value', 'date_competence_order', moment().format('YYYY-MM'));

                    $(this).onCreate({
                        url: _url + 'insert',
                        table: _table,
                        fields: _fields,
                        modal: 'onModalCreateIn'
                    });

                }
            },
            {
                className: 'ui red button',
                text: 'Nova saída',
                action: function () {

                    $('#onModalCreateOut form').form('set value', 'date_issue_order', moment().format('YYYY-MM-DD'));
                    $('#onModalCreateOut form').form('set value', 'date_competence_order', moment().format('YYYY-MM'));

                    $(this).onCreate({
                        url: _url + 'insert',
                        table: _table,
                        fields: _fields,
                        modal: 'onModalCreateOut'
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
            {
                extend: 'csvHtml5',
                text: 'CSV',
                title: 'CSV - Lançamentos',
                className: 'ui secondary button',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'pdfHtml5',
                download: 'open',
                text: 'PDF',
                title: 'PDF - Lançamentos',
                className: 'ui secondary button',
                orientation: 'landscape',
                pageSize: 'LEGAL',
                exportOptions: {
                    columns: ':not(.noVis)'
                },
                footer: true
            },
            {
                extend: 'colvis',
                text: 'Colunas',
                className: 'ui secondary button',
                columns: ':not(.noVis)'
            }
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
                data: 'name_account'
            },
            {
                className: 'on-edit',
                data: 'name_cost_center',
                visible: false
            },
            {
                className: 'on-edit',
                data: 'name_chart_accounts',
                visible: false
            },
            {
                className: 'on-edit',
                data: 'date_competence_order',
                render: function (value, type, row) {
                    return moment(value).format('MM/YYYY')
                },
                visible: false
            },
            {
                className: 'on-edit',
                type: 'date-br',
                data: 'date_issue_order',
                render: function (value, type, row) {
                    return moment(value).format('DD/MM/YYYY')
                },
                visible: false
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
                type: 'date-br',
                data: 'date_payment_order',
                render: function (value, type, row) {
                    return moment(value).format('DD/MM/YYYY')
                },
                visible: false
            },
            {
                className: 'collapsing on-edit',
                type: 'currency',
                data: 'price_order'
            },
            {
                className: 'collapsing on-edit',
                type: 'currency',
                data: 'price_discount_order',
                render: function (value, type, row) {
                    return numeral(value).format('0,0.00');
                },
                visible: false
            },
            {
                className: 'collapsing on-edit',
                type: 'currency',
                data: 'price_fees_order',
                render: function (value, type, row) {
                    return numeral(value).format('0,0.00');
                },
                visible: false
            },
            {
                className: 'collapsing on-edit',
                type: 'currency',
                data: 'price_addition_order',
                render: function (value, type, row) {
                    return numeral(value).format('0,0.00');
                },
                visible: false
            },
            {
                className: 'collapsing on-edit',
                type: 'currency',
                data: 'price_down',
                render: function (value, type, row) {
                    return numeral(value).format('0,0.00');
                },
                visible: false
            },
            {
                className: 'collapsing noVis',
                orderable: false,
                data: 'files_order',
                render: function (value, type, row) {
                    return '<div class="mini ui black label on-button-files"><i class="paperclip icon"></i> ' + value + '</div>';
                }
            },
            {
                className: 'collapsing noVis',
                orderable: false,
                data: 'situation_order',
                render: function (value, type, row) {
                    return (value == 1 ? '<div class="ui mini fluid center aligned green label">Baixado</div>' : '<div class="ui mini fluid center aligned yellow label onButtonDown">Em aberto</div>');
                }
            }
        ],
        order: [[9, 'desc']],
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

    $('.on-dropdown-order').dropdown();

    $('.on-dropdown-repeat-period').dropdown();

    $('.on-dropdown-type-order-filter').dropdown();

    $('.on-dropdown-type-date').dropdown();

    $('.on-dropdown-type-period').dropdown({
        onChange: function (value, text) {
            switch (parseInt(value)) {
                case 9:
                    $('#onColFilterPeriod').css('display', 'flex');
                    break;

                default:
                    $('#onColFilterPeriod').css('display', 'none');
                    break;
            }
        }
    });

    $('.on-dropdown-contact').dropdown({
        apiSettings: {
            url: '/finance/order/dropdown-contact?q={query}',
            cache: false
        },
        filterRemoteData: true,
        clearable: true
    }).dropdown('queryRemote', '', function () {});

    $('.on-dropdown-account').dropdown({
        apiSettings: {
            url: '/finance/order/dropdown-account?q={query}',
            cache: false
        },
        filterRemoteData: true,
        clearable: true
    }).dropdown('queryRemote', '', function () {});

    $('.on-dropdown-chart-accounts').dropdown({
        apiSettings: {
            url: '/finance/order/dropdown-chart-accounts?q={query}',
            cache: false
        },
        filterRemoteData: true,
        clearable: true
    }).dropdown('queryRemote', '', function () {});

    $('.on-dropdown-cost-center').dropdown({
        apiSettings: {
            url: '/finance/order/dropdown-cost-center?q={query}',
            cache: false
        },
        filterRemoteData: true,
        clearable: true
    }).dropdown('queryRemote', '', function () {});

    $('.on-dropdown-situation-order').dropdown({
        onChange: function (value, text) {
            switch (parseInt(value)) {
                case 1:
                    $('.onSituationOrderFields').css('display', 'flex');
                    break;

                case 0:
                    $('.onSituationOrderFields').css('display', 'none');
                    break;
            }
        }
    });

    $('.on-dropdown-repeat-order').dropdown({
        onChange: function (value, text) {
            switch (parseInt(value)) {
                case 1:
                    $('.onRepeatOrderFields').css('display', 'flex');
                    break;

                case 0:
                    $('.onRepeatOrderFields').css('display', 'none');
                    break;
            }
        }
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
                    prompt: 'Informe'
                }
            ]
        },
        date_due_order: {
            identifier: 'date_due_order',
            rules: [
                {
                    type: 'empty',
                    prompt: 'Informe'
                }
            ]
        },
        price_order: {
            identifier: 'price_order',
            rules: [
                {
                    type: 'empty',
                    prompt: 'Informe'
                }
            ]
        },
        situation_order: {
            identifier: 'situation_order',
            rules: [
                {
                    type: 'empty',
                    prompt: 'Selecione'
                }
            ]
        },
        repeat_order: {
            identifier: 'repeat_order',
            rules: [
                {
                    type: 'empty',
                    prompt: 'Selecione'
                }
            ]
        }
    };

    $(this).onFilter({
        table: _table
    });

    $(this).onUpdate({
        url: _url + 'update',
        fields: _fields,
        table: _table,
        modal: 'onModalUpdate',
        id: 'id_order'
    });

    $(document).on('click', 'tr td .on-button-files', function () {

        var row = _table.row($(this).parents('tr')).data();

        $('body').onUploadFile({
            idTableParent: row['id_order'],
            nameTableParent: 'on_order'
        });

    });

    $(document).on('click', '#onButtonReceipt', function () {

        $.ajax({
            type: 'GET',
            url: '/finance/order-in/select',
            data: {q: row[ 1 ]},
            success: function (e) {

                $.each(e, function (i, v) {
                    $.each(v, function (x, z) {
                        switch (x) {
                            case 'date_issue_order_in':
                                if (z) {
                                    $('.' + x).html(moment(z).format('DD/MM/YYYY'));
                                }
                                break;
                            case 'date_competence_order_in':
                                if (z) {
                                    $('.' + x).html(moment(z).format('DD/MM/YYYY'));
                                }
                                break;
                            case 'date_due_order_in':
                                if (z) {
                                    $('.' + x).html(moment(z).format('DD/MM/YYYY'));
                                }
                                break;
                            default:
                                $('.' + x).html(z);
                                break;
                        }
                    });
                });
            },
            complete: function () {

                $('.on-modal-receipt').modal({
                    closable: false,
                    observeChanges: true,
                    autofocus: false,
                    onApprove: function () {
                        var element = document.getElementById('on-print-receipt');
                        var opt = {
                            margin: .5,
                            filename: 'recibo.pdf',
                            image: {type: 'jpeg', quality: 0.98},
                            html2canvas: {scale: 2},
                            jsPDF: {unit: 'in', format: 'letter', orientation: 'portrait'}
                        };
                        // New Promise-based usage:
                        html2pdf().set(opt).from(element).toContainer().toCanvas().toImg().toPdf().save();
                        UIkit.notification('Seu recibo foi gerado...', {status: 'success'});
                    }
                }).modal('show');
            }
        });

    });

});