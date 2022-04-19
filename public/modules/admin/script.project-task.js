$(document).ready(function () {

    var _url = '/admin/project-task/';
    var _id = 'id_project_task';

    var _fields = {
        name_project_task: {
            identifier: 'name_project_task',
            rules: [
                {
                    type: 'empty',
                    prompt: 'Informe'
                }
            ]
        },
        id_project_task_status: {
            identifier: 'id_project_task_status',
            rules: [
                {
                    type: 'empty',
                    prompt: 'Selecione'
                }
            ]
        },
        date_start_project_task: {
            identifier: 'date_start_project_task',
            rules: [
                {
                    type: 'empty',
                    prompt: 'Informe'
                }
            ]
        },
        date_finish_project_task: {
            identifier: 'date_finish_project_task',
            rules: [
                {
                    type: 'empty',
                    prompt: 'Informe'
                }
            ]
        },
    };

    var _table = $('#onTableProjectTask').DataTable({
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
            }
        ],
        columns: [
            {
                orderable: false,
                data: null,
                defaultContent: '',
                className: 'select-checkbox noVis'
            },
            {
                class: 'on-edit',
                data: 'name_project_task'
            },
            {
                class: 'on-edit',
                data: 'id_project_task_status',
                render: function (value, row, index) {
                    switch (parseInt(value)) {
                        case 1:
                            return 'Requisitos';
                            break;
                        case 2:
                            return 'Desenvolvimento';
                            break;
                        case 3:
                            return 'Teste';
                            break;
                        case 4:
                            return 'Concluído';
                            break;
                    }
                }
            },
            {
                class: 'on-edit',
                data: 'date_start_project_task',
                type: 'date-br',
                render: function (value, type, row) {
                    return moment(value).format('DD/MM/YYYY')
                }
            },
            {
                class: 'on-edit',
                data: 'date_finish_project_task',
                type: 'date-br',
                render: function (value, type, row) {
                    return moment(value).format('DD/MM/YYYY')
                }
            }
        ],
        select: {
            style: 'os',
            selector: 'td:first-child'
        }
    });

    $('.ui.dropdown').dropdown();

    $(this).onUpdate({
        url: _url + 'update',
        fields: _fields,
        table: _table,
        modal: 'onModalUpdate',
        id: _id
    });

});