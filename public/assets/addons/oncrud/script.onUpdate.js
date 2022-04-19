
(function ($) {
    $.fn.onUpdate = function (options) {

        var defaults = {
            url: null,
            table: null,
            modal: null,
            fields: null,
            button: null,
            id: null,
            content: null,
            data: null,
            validate: null,
            onDeny: null,
            onSuccess: null,
            editorJS: null
        };

        var _id = null;
        
        
            

//        const editorUpdate = new EditorJS({
//            /**
//             * Id of Element that should contain Editor instance
//             */
//            holder: 'editorUpdate'
//        });




        
        var settings = $.extend({}, defaults, options);

        var onModalUpdate = $('#' + settings.modal).modal({
            closable: false,
            observeChanges: true,
            autofocus: false,
            onHidden: function () {
                onFormUpdate.form('reset');
            },
            onApprove: function () {
                onFormUpdate.submit();
                return false;
            }
        });
        

        var onFormUpdate = $('#' + settings.modal + ' form').submit(function (event) {
            return false;
        }).form({
            inline: true,
            fields: settings.fields,
            onSuccess: function () {
                $.ajax({
                    type: 'POST',
                    url: settings.url,
                    data: {data: onFormUpdate.serialize(), q: _id},
                    beforeSend: function (xhr) {
                        if ($.isFunction(settings.onBeforeSend)) {
                            settings.onBeforeSend.call(this, xhr);
                        }
                    },
                    success: function (response) {
                        switch (response.status) {
                            case 'success':

//                                editorUpdate.save().then((outputData) => {
//                                    $.ajax({
//                                        type: 'POST',
//                                        url: settings.url,
//                                        data: {q: _id, json: JSON.stringify(outputData)},
//                                        success: function (response) {
//                                            editorUpdate.clear();
                                            settings.table.ajax.reload();
                                            onModalUpdate.modal('hide');
//                                        }
//                                    });
//                                }).catch((error) => {
//                                    console.log('Saving failed: ', error)
//                                });
                                break;
                        }
                        if ($.isFunction(settings.onSuccess)) {
                            settings.onSuccess.call(this, response);
                        }
                    },
                    complete: function () {

                    }
                });
            }
        });

        settings.table.on('click', settings.button ? settings.button : 'tr td.on-edit', function () {
            var row = settings.table.row($(this).parents('tr')).data();
            
            _id = row[settings.id];
            $.each(row, function (attr, value) {
                if (attr.search("price") > -1) {
                    if (value) {
                        onFormUpdate.form('set value', attr, value.replace(".", ","));
                    }
                } 
//                else if (attr == 'description_long_post') {
//                    if (typeof JSON.parse(value).blocks !== "undefined") {
//                        if (JSON.parse(value).blocks.length > 0) {
//                            editorUpdate.render(JSON.parse(value));
//                        } else {
//                            editorUpdate.clear();
//                        }
//                    } else {
//                        editorUpdate.clear();
//                    }
//
//                }
                else {
                    onFormUpdate.form('set value', attr, value);
                }
            });

            onModalUpdate.modal('show');

        });

    };
})(jQuery);