
$(document).ready(function () {

    var _url = '/website/service/';
    var _id = 'id_service';

    var _fields = {
        name_service: {
            identifier: 'name_service',
            rules: [
                {
                    type: 'empty',
                    prompt: 'Informe'
                }
            ]
        }
    };

    var _table = $('#onTableService').DataTable({
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
                        modal: 'onModalCreate',
                        onSuccess: function (response) {

                            editor.save().then((outputData) => {
                                $.ajax({
                                    type: 'POST',
                                    url: _url + 'update',
                                    data: {q: response.returning, json: JSON.stringify(outputData)},
                                    success: function (response) {
                                        editor.clear();
                                    }
                                });
                            }).catch((error) => {
                                console.log('Saving failed: ', error)
                            });

                        }
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
                className: 'on-edit',
                data: 'name_service'
            },
            {
                className: 'on-edit',
                data: 'title_service'
            },
            {
                class: 'on-edit',
                data: 'status_service',
                render: function (value, row, index) {
                    switch (parseInt(value)) {
                        case 1:
                            return 'Ativo';
                            break;
                        case 2:
                            return 'Inativo';
                            break;
                    }
                }
            },
            {
                className: 'collapsing noVis',
                orderable: false,
                data: 'image_service',
                render: function (value, type, row) {
                    return '<div class="mini ui black label on-button-files"><i class="paperclip icon"></i> <img src="' + value + '" width="64" /></div>';
                }
            }
        ],
        order: [[2, 'desc']],
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
    
    
    $(document).on('click', 'tr td .on-button-files', function () {

        var row = _table.row($(this).parents('tr')).data();

        $('body').onUploadFile({
            idTableParent: row[_id],
            nameTableParent: 'on_service',
            singleFile: true,
            onSuccess: function(response) {
                $.ajax({
                    type: 'POST',
                    url: _url + 'update',
                    data: {q: row[_id], data: 'image_service=' + response.result.link_file},
                    success: function (response) {
                        _table.ajax.reload();
                    }
                });
                
            }
        });

    });


});