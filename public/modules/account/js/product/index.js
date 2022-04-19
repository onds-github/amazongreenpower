$(document).ready(function () {
    
    var _base_url = base_url + 'account/product/';

    var _table_contact = $('.on-table-product').DataTable({
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
        order: [[0, 'asc']]
    });
    
    
    
    //fields
    var _fields = {
        2: {
            fields: [
                {
                    field: 'ten wide field',
                    type: 'text',
                    name: 'description_product_service',
                    html: 'Descrição'
                },
                {
                    field: 'six wide field',
                    type: 'text',
                    name: 'price_product_service',
                    html: 'Preço'
                }
            ]
        }
    };

    //validate
    var _validate = {
        description_product_service: {
            identifier: 'description_product_service',
            rules: [
                {
                    type: 'empty',
                    prompt: 'Infome'
                }
            ]
        }
    };

    $(this).insert({
        url: _base_url,
        table: _table_contact,
        data: _fields,
        validate: _validate,
        className: '.on-button-product'
    });


});