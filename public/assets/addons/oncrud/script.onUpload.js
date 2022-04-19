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
        

    };
})(jQuery);