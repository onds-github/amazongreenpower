(function ($) {
    $.fn.delete = function (options) {

        var defaults = {
            className: null,
            url: null,
            table: null,
            id: null
        };

        var settings = $.extend({}, defaults, options);


        var _modal = $('<div />', {
            'html': '<div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">Formulário de cadastro</h5></div><div class="modal-body">Tem certeza?</div><div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button><button type="button" class="btn btn-primary">Salvar</button></div></div></div>',
            'class': 'modal fade',
            'data-bs-backdrop': 'static',
            'data-bs-keyboard': 'false',
            'aria-labelledby': 'staticBackdropLabel',
            'aria-hidden': 'true',
            'tabindex': '-1',
            'id': 'onModalDelete'
        }).appendTo('body');

        var onModalDelete = new bootstrap.Modal(document.getElementById('onModalDelete'));

        $(document).on('click', settings.className, function () {
            if (!settings.table.bootstrapTable('getSelections').length) {
                var toastLiveExample = document.getElementById('onToastAlert');
                var toast = new bootstrap.Toast(toastLiveExample);
                $('.toast-body').html('É necessário selecione ao menos um lançamento para continuar.');

                toast.show();
            } else {
                onModalDelete.show();
            }
        });

        $(document).on('click', '#onModalDelete .btn-primary', function () {

            var ids = $.map(settings.table.bootstrapTable('getSelections'), function (row) {
                return row[settings.id];
            });

            $.ajax({
                type: 'POST',
                url: settings.url,
                data: {data: JSON.stringify(settings.table.bootstrapTable('getSelections'))},
                success: function (data) {
                    console.log(data);
                    switch (data.status) {
                        case 'success':
                            settings.table.bootstrapTable('remove', {field: settings.id, values: ids});
                            onModalDelete.hide();
                            break;
                    }
                },
                complete: function (jqXHR, textStatus) {

                }
            });

        });

    };

})(jQuery);