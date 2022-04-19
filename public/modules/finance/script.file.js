(function ($) {

    var defaults = {
        idTableParent: null,
        nameTableParent: null,
        onSuccess: null,
        singleFile: null
    };

    $.fn.onUploadFile = function (options) {

        var _table;

        var settings = $.extend({}, defaults, options);

        if (settings.singleFile) {
            
            $('.on-form-file input').click();
            var fu = $('.on-form-file').fileupload({
                url: '/finance/file/insert?id_table_parent=' + settings.idTableParent + '&name_table_parent=' + settings.nameTableParent,
                start: function (e) {
                    console.log(e);
                },
                add: function (e, v) {

                    var jqXHR = v.submit();
                },
                progress: function (e, v) {
                    var progress = parseInt(v.loaded / v.total * 100, 10);

                    if (progress === 100) {
                        
                    }
                },
                progressall: function (e, data) {

                },
                done: function (e, response) {
                    switch (response.result) {
                        default:
//                            _table.ajax.reload();

                            if ($.isFunction(settings.onSuccess)) {
                                settings.onSuccess.call(this, response);
                            }
                            break;
                    }
                }
            });
        } else {

            var _modal = $('<div />', {
                html: _modal(),
                class: 'ui small modal on-modal-file'
            }).appendTo('body').modal({
                closable: false,
                observeChanges: true,
                autofocus: false,
                onShow: function () {

                    _table = $('.on-table-file').DataTable({
                        ajax: '/finance/file/ajax?id_table_parent=' + settings.idTableParent + '&name_table_parent=' + settings.nameTableParent,
                        paging: false,
                        searching: false,
                        dom: 'Bfrtip',
                        language: {
                            url: '/public/library/datatables/language.json'
                        },
                        buttons: [
                            {
                                text: 'Enviar arquivo',
                                className: 'ui primary button on-button-file-insert',
                                action: function (e, dt, node, config) {
                                    var index = $(this).parents('tr').index();
                                    $('.on-form-file input').click();
                                    var fu = $('.on-form-file').fileupload({
                                        url: '/finance/file/insert?id_table_parent=' + settings.idTableParent + '&name_table_parent=' + settings.nameTableParent,
                                        start: function (e) {
                                            console.log(e);
                                        },
                                        add: function (e, v) {
                                            v.context = _table.row.add([
                                                '',
                                                'https://img.icons8.com/material-sharp/50/000000/upload--v2.png',
                                                v.files[0].name,
                                                v.files[0].size,
                                                '--',
                                                '--']).draw().node();

                                            $(v.context).addClass('recent');

                                            var jqXHR = v.submit();
                                        },
                                        progress: function (e, v) {
                                            var progress = parseInt(v.loaded / v.total * 100, 10);
                                            _table.cell(index, 4).data(progress).draw();

                                            if (progress === 100) {
                                                _table.cell(_table.row(v.context).index(), 4).data('Finalizando processo...').draw();
                                            }
                                        },
                                        progressall: function (e, data) {
                                            //            var progress = parseInt(data.loaded / data.total * 100, 10);
                                            //
                                            //            if (progress === 100) {
                                            //                setTimeout(function () {
                                            //                    $('.upload-progress').empty();
                                            //                }, 1000);
                                            //            }
                                        },
                                        done: function (e, v) {
                                            switch (v.result) {
                                                case 'BLOCK':
                                                    //                    _table.cell(_table.row(v.context).index(), 0).data('<img class="ui image" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACgAAAAoCAYAAACM/rhtAAAC/klEQVRYR+3YT0iTYRwH8O+717EKQ3J/LKhp5jpIB0dSiQYLhhR4nBJIkNShQyKLguqQhw6GdooRBAV18BB6Ci+OHTzIjErUyLHROxsi+WfbYeWfrfd9t3imm3vnnK/vfN952I7P3j+ffZ/n93seRuGQf6hD7sOuwMcDbxMHhVfTdOD5g66zUp6XF9j38I6UZwruefLyHXSVFYhEViUhFQE+676F14OfJCEVAZKZ2Ij9k4RUDEjmXQpSUaAUpOLA/SKLAtwPsmhAsUhFgGKa6YtHd3NaZAeKwZFmXgKKSWq3a0oJFpIeubeUYCnBXAmQE/VBHFjFpNv35iOe3rt5eBu1e/YXmi/UFh/4Z2YGwdFRrHm9WJ+fT4Z7zGjE2snToL5OXGtyucayE1dkq1tnGAQcDkQDAeiMRhzXanG0oiJp2YhE8DccxgrDRHmW/abiuO5LY2PTKajsQIKbtdtRZTLBYDTmXZLLfv/GoteboDmuOYWUFZjC1TU2phPbq2hIor7x8fUUUlagp6cH5TQtTE6jAWIxoTNrjCS55PVOXnG5rsoGJAXB9Pai3mJJY9StrVDp9YgND28jNRpobDbEg0GwTmf62h9OZ5Rj2RuyAf39/VAvLMBQW7v50i0IpdcjEQxuIsmwzQbB2Fa6K3Nz+O3xfJAN+L2rC2dqaoRrLxMZCiWBlE63Dc6YerIWf7rdftmAn61WNLS17awJguzoAKXVJr9LhEKIDQ3tXJcApkdG8v95VMhWlxfY3p5MrqhAUVNMUckk02tSySneUSSkIDo7hQWRXSSDg+klIXuR5GozdH09yszmnG2Gm5oC7/FktpkYx7LXZSsS8iaP3Y5yAIbq6r02EMH3ywwTXfL5SKNukRUo+1a3r5+ddbGu8gSaLptBLcxD9WoAp86b9kySJLfo88VFHRYKwWXf+8ViaYiXlTlotfpiVV3dkfKs49ZqOAyC41l2UsVx90Udtw4SmHrWhNVqQSJxW0XTLXGeP0fGVTTtj/P8OCjqfa4D639OFVQ5K+3IzwAAAABJRU5ErkJggg==" width="40" height="40">').draw();
                                                    //                    _table.cell(_table.row(v.context).index(), 2).data(v.files[0].name + ' (Erro detectado: Espaço contratado insuficiente!)').draw();
                                                    break;
                                                    //
                                                case 'NAME':
                                                    //                    _table.cell(_table.row(v.context).index(), 0).data('<img class="ui image" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACgAAAAoCAYAAACM/rhtAAAC/klEQVRYR+3YT0iTYRwH8O+717EKQ3J/LKhp5jpIB0dSiQYLhhR4nBJIkNShQyKLguqQhw6GdooRBAV18BB6Ci+OHTzIjErUyLHROxsi+WfbYeWfrfd9t3imm3vnnK/vfN952I7P3j+ffZ/n93seRuGQf6hD7sOuwMcDbxMHhVfTdOD5g66zUp6XF9j38I6UZwruefLyHXSVFYhEViUhFQE+676F14OfJCEVAZKZ2Ij9k4RUDEjmXQpSUaAUpOLA/SKLAtwPsmhAsUhFgGKa6YtHd3NaZAeKwZFmXgKKSWq3a0oJFpIeubeUYCnBXAmQE/VBHFjFpNv35iOe3rt5eBu1e/YXmi/UFh/4Z2YGwdFRrHm9WJ+fT4Z7zGjE2snToL5OXGtyucayE1dkq1tnGAQcDkQDAeiMRhzXanG0oiJp2YhE8DccxgrDRHmW/abiuO5LY2PTKajsQIKbtdtRZTLBYDTmXZLLfv/GoteboDmuOYWUFZjC1TU2phPbq2hIor7x8fUUUlagp6cH5TQtTE6jAWIxoTNrjCS55PVOXnG5rsoGJAXB9Pai3mJJY9StrVDp9YgND28jNRpobDbEg0GwTmf62h9OZ5Rj2RuyAf39/VAvLMBQW7v50i0IpdcjEQxuIsmwzQbB2Fa6K3Nz+O3xfJAN+L2rC2dqaoRrLxMZCiWBlE63Dc6YerIWf7rdftmAn61WNLS17awJguzoAKXVJr9LhEKIDQ3tXJcApkdG8v95VMhWlxfY3p5MrqhAUVNMUckk02tSySneUSSkIDo7hQWRXSSDg+klIXuR5GozdH09yszmnG2Gm5oC7/FktpkYx7LXZSsS8iaP3Y5yAIbq6r02EMH3ywwTXfL5SKNukRUo+1a3r5+ddbGu8gSaLptBLcxD9WoAp86b9kySJLfo88VFHRYKwWXf+8ViaYiXlTlotfpiVV3dkfKs49ZqOAyC41l2UsVx90Udtw4SmHrWhNVqQSJxW0XTLXGeP0fGVTTtj/P8OCjqfa4D639OFVQ5K+3IzwAAAABJRU5ErkJggg==" width="40" height="40">').draw();
                                                    //                    _table.cell(_table.row(v.context).index(), 2).data(v.files[0].name + ' (Erro detectado: O nome do arquivo já existe!)').draw();
                                                    break;
                                                    //
                                                case 'ERROR':
                                                    //                    _table.cell(_table.row(v.context).index(), 0).data('<img class="ui image" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACgAAAAoCAYAAACM/rhtAAAC/klEQVRYR+3YT0iTYRwH8O+717EKQ3J/LKhp5jpIB0dSiQYLhhR4nBJIkNShQyKLguqQhw6GdooRBAV18BB6Ci+OHTzIjErUyLHROxsi+WfbYeWfrfd9t3imm3vnnK/vfN952I7P3j+ffZ/n93seRuGQf6hD7sOuwMcDbxMHhVfTdOD5g66zUp6XF9j38I6UZwruefLyHXSVFYhEViUhFQE+676F14OfJCEVAZKZ2Ij9k4RUDEjmXQpSUaAUpOLA/SKLAtwPsmhAsUhFgGKa6YtHd3NaZAeKwZFmXgKKSWq3a0oJFpIeubeUYCnBXAmQE/VBHFjFpNv35iOe3rt5eBu1e/YXmi/UFh/4Z2YGwdFRrHm9WJ+fT4Z7zGjE2snToL5OXGtyucayE1dkq1tnGAQcDkQDAeiMRhzXanG0oiJp2YhE8DccxgrDRHmW/abiuO5LY2PTKajsQIKbtdtRZTLBYDTmXZLLfv/GoteboDmuOYWUFZjC1TU2phPbq2hIor7x8fUUUlagp6cH5TQtTE6jAWIxoTNrjCS55PVOXnG5rsoGJAXB9Pai3mJJY9StrVDp9YgND28jNRpobDbEg0GwTmf62h9OZ5Rj2RuyAf39/VAvLMBQW7v50i0IpdcjEQxuIsmwzQbB2Fa6K3Nz+O3xfJAN+L2rC2dqaoRrLxMZCiWBlE63Dc6YerIWf7rdftmAn61WNLS17awJguzoAKXVJr9LhEKIDQ3tXJcApkdG8v95VMhWlxfY3p5MrqhAUVNMUckk02tSySneUSSkIDo7hQWRXSSDg+klIXuR5GozdH09yszmnG2Gm5oC7/FktpkYx7LXZSsS8iaP3Y5yAIbq6r02EMH3ywwTXfL5SKNukRUo+1a3r5+ddbGu8gSaLptBLcxD9WoAp86b9kySJLfo88VFHRYKwWXf+8ViaYiXlTlotfpiVV3dkfKs49ZqOAyC41l2UsVx90Udtw4SmHrWhNVqQSJxW0XTLXGeP0fGVTTtj/P8OCjqfa4D639OFVQ5K+3IzwAAAABJRU5ErkJggg==" width="40" height="40">').draw();
                                                    //                    _table.cell(_table.row(v.context).index(), 2).data(v.files[0].name + ' (Erro detectado: Erro desconhecido!)').draw();
                                                    break;
                                                    //
                                                default:
                                                    _table.ajax.reload();
                                                    $(v.context).removeClass('recent');
                                                    _table.cell(_table.row(v.context).index(), 1).data(v.result).draw();
                                                    _table.cell(_table.row(v.context).index(), 2).data(v.files[0].name).draw();
                                                    _table.cell(_table.row(v.context).index(), 3).data(v.files[0].size).draw();
                                                    _table.cell(_table.row(v.context).index(), 4).data(v.files[0].size).draw();
                                                    _table.cell(_table.row(v.context).index(), 5).data('datetime').draw();
                                                    //
                                                    break;
                                            }
                                        }
                                    });

                                }
                            }
                        ],
                        columnDefs: [
                            {
                                className: 'select-checkbox',
                                orderable: false,
                                targets: 0,
                                visible: false
                            },
                            {
                                targets: 1,
                                visible: false
                            },
                            {
                                orderable: false,
                                targets: 2,
                                visible: false
                            },
                            {
                                className: 'collapsing',
                                targets: 4,
                                render: function (data, type, row) {
                                    return numeral(data).format('0.0 b');
                                }
                            },
                            {
                                type: 'date-br',
                                className: 'collapsing',
                                targets: 5,
                                render: function (data, type, row) {
                                    return moment(data).format('DD/MM/YYYY');
                                }
                            },
                            {
                                searchable: false,
                                orderable: false,
                                className: 'center aligned collapsing',
                                render: function (data, type, row) {
                                    return '<a class="circular ui icon basic button" uk-tooltip="title: Visualizar Registro" href="' + row[2] + '" target="_blank"><i class="eye icon"></i></a>\n\
                        <button class="circular ui icon basic button on-button-delete" uk-tooltip="title: Excluir Registro"><i class="trash icon"></i></button>';
                                },
                                targets: -1
                            }
                        ],
                        select: {
                            style: 'os',
                            selector: 'td:first-child'
                        },
                        order: [[1, 'desc']]
                    });

                },
                onHidden: function () {
                    _modal.remove();
                }
            }).modal('show');

            function _modal() {
                return '<div class="header">Gerencimento de Arquivos</div>\n\
                    <div class="content">\n\
                        <table class="ui very basic selectable table on-table-file">\n\
                        <thead>\n\
                            <tr>\n\
                                <th class="on-select-all"></th>\n\
                                <th>ID</th> \n\
                                <th>Imagem</th>\n\
                                <th>Nome</th>\n\
                                <th>Tamanho</th>\n\
                                <th>Envio</th> \n\
                                <th>Opções</th>\n\
                            </tr>\n\
                        </thead>\n\
                    </table>\n\
                    </div>\n\
                    <div class="actions">\n\
                        <button class="ui labeled icon secondary deny button"><i class="close icon"></i> Fechar</button>\n\
                    </div>\n\
                </div>';
            }

            $('.on-table-file tbody').on('click', 'tr td .on-button-delete', function () {
                var row = _table.row($(this).parents('tr')).data();

                $.ajax({
                    type: 'POST',
                    url: '/finance/file/delete',
                    data: {q: row[1]},
                    success: function (e) {
                        UIkit.notification(e.message, {status: e.status});
                        switch (e.status) {
                            case 'success':
                                _table.ajax.reload();
                                break;
                        }
                    }
                });
            });

        }

    };
})(jQuery);