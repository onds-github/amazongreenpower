$(document).ready(function () {

    var _url = '/finance/report/';
    
    var _table = $('.on-table-report-extract').DataTable({
        ajax: '/finance/report/ajax-extract',
        language: {
            url: '/public/library/datatables/language.json'
        },
        paging: false,
        columnDefs: [
            {
                searchable: false,
                orderable: false,
                targets: 0,
                visible: false
            },
            {
                className: 'collapsing',
                type: 'date-br',
                orderable: false,
                targets: 1,
                render: function (data, type, row) {
                    return moment(data).format('DD/MM/YYYY');
                }
            },
            {
                orderable: false,
                targets: 2
            },
            {
                orderable: false,
                targets: 3
            },
            {
                orderable: false,
                targets: 4,
                visible: false
            },
            {
                className: 'right aligned collapsing',
                orderable: false,
                targets: 5
            },
            {
                className: 'collapsing',
                orderable: false,
                targets: 6,
                render: function (data, type, row) {
                    return '';
                }
            }
        ],
        order: [[1, 'asc']],
        rowGroup: {
            startRender: function (rows, group) {
                return '<p class="uk-text-bold">Dia: ' + moment(group).format('DD/MM/YYYY') + '</p>';
            },
            endRender: function (rows, group) {
                var total = rows
                        .data()
                        .pluck(5)
                        .reduce(function (a, b) {
                            return a + b.replace(/[^\d]/g, '') * 1;
                        }, 0);

                $.ajax({
                    type: 'GET',
                    url: '/finance/report/select-previous-balance',
                    data: {q: group},
                    async: true,
                    success: function (e) {
                        $('.on-' + group).html(e);
                    }
                });
                return '<p class="right aligned on-' + group + '">...</p>';
            },
            dataSrc: 1
        },
        createdRow: function (row, data, dataIndex) {
            switch (parseInt(data[6])) {
                case 1:
                    $(row).addClass('positive');
                    break;

                case 2:
                    $(row).addClass('negative');
                    break;
            }
        }
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
        table: _table,
        onSuccess: function (response) {
            switch (response.status) {
                case 'success':
                    _table.ajax.reload();
                    break;
            }
        }
    });

});