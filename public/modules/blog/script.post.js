
$(document).ready(function () {

    const editor = new EditorJS({
        /**
         * Id of Element that should contain Editor instance
         */
        holder: 'editor'
    });
    
    

    var $url = '/blog/post/';
    var _id = 'id_post';

    var _fields = {
        name_post: {
            identifier: 'name_post',
            rules: [
                {
                    type: 'empty',
                    prompt: 'Informe'
                }
            ]
        }
    };

    var _table = $('#onTablePost').DataTable({
        ajax: $url + 'ajax',
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
                        url: $url + 'insert',
                        table: _table,
                        fields: _fields,
                        modal: 'onModalCreate',
                        onSuccess: function (response) {

                            editor.save().then((outputData) => {
                                $.ajax({
                                    type: 'POST',
                                    url: $url + 'update',
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
                        url: $url + 'delete',
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
                data: 'title_post',
                render: function (value, index, row) {
                    console.log(index);
                    console.log(row);
                    return value + '<br /><a target="_blank" href="/post/p/?q="' + row['slug_post'] + '">Ver publicação</a>';
                }
            },
            {
                className: 'on-edit',
                data: 'date_post',
                render: function (value, type, row) {
                    return moment(value).format('DD/MM/YYYY')
                }
            },
            {
                class: 'collapsing on-edit',
                data: 'status_post',
                render: function (value, type, row) {
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
                data: 'image_post',
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
        url: $url + 'update',
        fields: _fields,
        table: _table,
        modal: 'onModalUpdate',
        id: _id
    });
    
    
    $(document).on('click', 'tr td .on-button-files', function () {

        var row = _table.row($(this).parents('tr')).data();

        $('body').onUploadFile({
            idTableParent: row[_id],
            nameTableParent: 'on_post',
            singleFile: true,
            onSuccess: function(response) {
                $.ajax({
                    type: 'POST',
                    url: $url + 'update',
                    data: {q: row[_id], data: 'image_post=' + response.result.link_file},
                    success: function (response) {
                        _table.ajax.reload();
                    }
                });
                
            }
        });

    });


});