$(document).ready(function () {
    
    var _base_url = base_url + 'account/service/';

    var _table_contact = $('.on-table-service').DataTable({
        ajax: _base_url + 'ajax',
        language: {
            url: '/public/library/datatables/language.json'
        },
        columnDefs: [
            {
                searchable: false,
                orderable: false,
                targets: 0,
                visible: false
            },
            {
                searchable: false,
                orderable: false,
                targets: 1
            },
            {
                className: 'uk-table-shrink uk-text-nowrap',
                searchable: false,
                orderable: false,
                targets: 2
            },
            {
                className: 'uk-table-shrink uk-text-nowrap',
                targets: 3
            }
        ],
        createdRow: function( row, data, dataIndex ) {
            $(row).attr('tabindex', '-1');
            $(row).addClass('uk-visible-toggle');
        },
        order: [[0, 'asc']]
    });
    
    
    
    //fields
    var _fields_pf = {
        2: {
            fields: [
                {
                    field: 'eight wide field',
                    type: 'text',
                    name: 'description_service',
                    html: 'Descrição'
                },
                {
                    field: 'eight wide field',
                    type: 'text',
                    name: 'price_service',
                    html: 'Preço'
                }
            ]
        }
    };

    //validate
    var _validate_pf = {
        nome_fantasia: {
            identifier: 'nome_fantasia',
            rules: [
                {
                    type: 'empty',
                    prompt: 'Infome o nome fantasia'
                }
            ]
        }
    };

    $(this).insert({
        url: _base_url,
        table: _table_contact,
        data: _fields_pf,
        validate: _validate_pf,
        className: '.on-button-service'
    });


});