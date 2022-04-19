$(document).ready(function () {

    var _base_url = base_url + 'account/loggin-session/';

    var _table = $('.on-table-loggin-session').DataTable({
        ajax: _base_url + 'ajax',
        language: {
            url: base_url + 'public/library/datatables/language.json'
        },
        ordering: false,
        columnDefs: [
            {
                targets: 0,
                visible: false
            },
            {
                render: function (data, type, row) {
                    return data.name_user;
                },
                targets: 1
            },
            {
                className: 'collapsing',
                render: function (data, type, row) {
                    return '<ul class="uk-iconnav">\n\
                                <li class="uk-width-auto"><button class="uk-icon-button" uk-tooltip="title: ' + data.city + ' - ' + data.state + ' (' + data.country + ')"><img src="https://img.icons8.com/material-outlined/24/ffffff/marker.png"/></button></li>\n\
                                <li class="uk-width-auto">' + (data.modile ? '<button class="uk-icon-button" uk-tooltip="title: Acesso através de dispositivo móvel"><img src="https://img.icons8.com/material-outlined/24/ffffff/two-smartphones.png"/></button>' : '<button class="uk-icon-button" uk-tooltip="title: Acesso através de computador"><img src="https://img.icons8.com/material-outlined/24/ffffff/laptop.png"/></button>') + '</li>\n\
                            </ul>';
                },
                targets: 2
            },
            {
                render: function (data, type, row) {
                    return moment(data).fromNow();
                },
                className: 'collapsing uk-text-right@s',
                targets: 3
            }
        ],
        order: [[0, 'desc']]
    });
    
});