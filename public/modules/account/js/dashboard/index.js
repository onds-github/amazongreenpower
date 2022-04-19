$(document).ready(function () {

    $.ajax({
        type: 'GET',
        url: '/account/dashboard/select',
        success: function (e) {
            $('.on-total-accounts').html(e.total_accounts);

        }
    });

    $.ajax({
        type: 'GET',
        url: '/account/dashboard/ajax-account',
        success: function (e) {
            $('.on-slider-accounts ul').html('');
            $.each(e.data, function (i, v) {
                $('.on-slider-accounts ul').append('<li><div class="uk-card uk-card-body uk-card-default uk-card-small">\n\
                    <h3 class="uk-h3 uk-margin-remove">' + v[5] + '</h3>\n\
                    <p class="uk-text-meta uk-margin-remove">' + v[1] + '</p>\n\
                </div></li>');
            });
        }
    });


    $('.on-table-cost-center').DataTable({
        ajax: '/account/dashboard/ajax-cost-center',
        language: {
            url: '/public/library/datatables/language.json'
        },
        searching: false,
        paging: false,
        info: false,
        columnDefs: [
            {
                searchable: false,
                orderable: false,
                targets: 0,
                visible: false
            },
            {
                className: 'center aligned collapsing',
                orderable: false,
                targets: 1,
                render: function (data, type, row) {
                    return '<button class="circular ui icon inverted button" style="background-color: ' + data.color_cost_center + '"><i class="' + data.icon_cost_center + ' icon"></i></button>';
                }
            },
            {
                orderable: false,
                targets: 2,
                render: function (data, type, row) {
                    return '<h6 class="uk-h6 uk-margin-remove">' + data + '</h6><p class="uk-text-meta uk-margin-remove">' + row[3] + '</p>';
                }
            },
            {
                type: 'currency',
                targets: 3,
                visible: false
            }
        ],
        order: [[3, 'desc']]
    });


    $.ajax({
        type: 'GET',
        url: '/account/dashboard/select-cost-center',
        success: function (data) {
            // Build the chart
            Highcharts.chart('on-container-cost-center', {
                navigation: {
                    buttonOptions: {
                        enabled: false
                    }
                },
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    type: 'pie'
                },
                title: {
                    text: 'Centro de Custos'
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                },
                accessibility: {
                    point: {
                        valueSuffix: '%'
                    }
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: false
                        },
                        showInLegend: false
                    }
                },
                series: [{
                        name: 'Porcentagem',
                        colorByPoint: true,
                        data: data
                    }]
            });
        }
    });


    var _table_order_in = $('.on-table-order-in').DataTable({
        ajax: '/account/dashboard/ajax-order-in',
        language: {
            url: '/public/library/datatables/language.json'
        },
        searching: false,
        paging: false,
        info: false,
        columnDefs: [
            {
                searchable: false,
                orderable: false,
                targets: 0,
                visible: false
            },
            {
                orderable: false,
                targets: 1
            },
            {
                type: 'date-br',
                className: 'single line collapsing',
                orderable: false,
                targets: 2,
                render: function (data, type, row) {
                    return moment(data).format('DD/MM/YYYY');
                }
            },
            {
                className: 'single line collapsing',
                orderable: false,
                targets: 3
            }
        ],
        order: [[2, 'asc']]
    });

    var _table_order_out = $('.on-table-order-out').DataTable({
        ajax: '/account/dashboard/ajax-order-out',
        language: {
            url: '/public/library/datatables/language.json'
        },
        searching: false,
        paging: false,
        info: false,
        columnDefs: [
            {
                searchable: false,
                orderable: false,
                targets: 0,
                visible: false
            },
            {
                orderable: false,
                targets: 1
            },
            {
                type: 'date-br',
                className: 'single line collapsing',
                targets: 2,
                render: function (data, type, row) {
                    return moment(data).format('DD/MM/YYYY');
                }
            },
            {
                className: 'single line collapsing',
                orderable: false,
                targets: 3
            }
        ],
        order: [[2, 'asc']]
    });

    $('.on-table-order-out tbody').on('click', 'tr td .on-button-not-okay', function () {
        var row = _table_order_out.row($(this).parents('tr')).data();
        $.ajax({
            type: 'POST',
            url: '/finance/order-out/update',
            data: {data: 'situation_order_out=false', id_order_out: row[ 0 ]},
            success: function (e) {
                UIkit.notification({message: e.message, status: e.status});
            },
            complete: function () {
                _table_order_out.ajax.reload();
            }
        });
    });

    $('.on-table-order-out tbody').on('click', 'tr td .on-button-okay', function () {
        var row = _table_order_out.row($(this).parents('tr')).data();
        $.ajax({
            type: 'POST',
            url: '/finance/order-out/update',
            data: {data: 'situation_order_out=true', id_order_out: row[ 0 ]},
            success: function (e) {
                UIkit.notification({message: e.message, status: e.status});
            },
            complete: function () {
                _table_order_out.ajax.reload();
            }
        });
    });

    $.ajax({
        type: 'GET',
        url: '/account/dashboard/select-in-out',
        success: function (data) {
            Highcharts.chart('on-container-in-out', {
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    type: 'column'
                },
                title: {
                    text: 'Resumo'
                },
                subtitle: {
                    text: 'Últimos 12 meses'
                },
                yAxis: {
                    title: {
                        text: 'Valor'
                    }
                },
                xAxis: {
                    categories: data.months
                },
                tooltip: {
                    headerFormat: '<b>{series.name}</b><br />',
                    pointFormat: 'R$ {point.y}'
                },
                plotOptions: {
                    line: {
                        dataLabels: {
                            enabled: true
                        }
                    }
                },
                series: data.data,
                responsive: {
                    rules: [
                        {
                            condition: {
                                maxWidth: 500
                            },
                            chartOptions: {
                                legend: {
                                    layout: 'horizontal',
                                    align: 'center',
                                    verticalAlign: 'bottom'
                                }
                            }
                        }
                    ]
                }
            });
        }
    });

});