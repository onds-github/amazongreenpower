(function ($) {
    $.fn.onDelete = function (options) {

        var defaults = {
            className: null,
            url: null,
            table: null,
            button: null,
            modal: null,
            id: null
        };

        var settings = $.extend({}, defaults, options);
        
        
        var onModalDelete = $('<div />', {
            'html': '<div class="header">Confirmar Exclusão</div><div class="content"><p>Você tem certeza que deseja excluir o registro?</p></div><div class="actions"><button class="ui black deny button">Cancelar</button><button class="ui red ok button">Confirmar</button></div>',
            'class': 'ui mini modal',
            'id': 'onModalDelete'
        }).appendTo('body');

        var onModalNotify = $('<div />', {
            'html': '<div class="header">Atenção</div><div class="content"><p>Você precisa selecionar pelo menos 1 registro para continuar!</p></div><div class="actions"><button class="ui black deny button">Fechar</button>',
            'class': 'ui mini modal',
            'id': 'onModalNotify'
        }).appendTo('body');

        if (settings.table.rows({selected: true}).count() == 0) {
            onModalNotify.modal('show');
        } else {
            onModalDelete.modal({
                closable: false,
                observeChanges: true,
                autofocus: false,
                onApprove: function () {
                    var ids = $.map(settings.table.rows({selected: true}).data().toArray(), function (row) {
                        return row[settings.id];
                    });

                    $.ajax({
                        type: 'POST',
                        url: settings.url,
                        data: {data: JSON.stringify(ids)},
                        success: function (data) {
                            switch (data.status) {
                                case 'success':
                                    settings.table.ajax.reload();
                                    onModalDelete.modal('hide');
                                    break;
                            }
                        }
                    });
                    return false;
                }
            }).modal('show');
        }

    };

})(jQuery);