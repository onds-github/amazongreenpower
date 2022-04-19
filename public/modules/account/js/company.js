$(document).ready(function () {

    var _base_url = base_url + 'account/company/';

    $.ajax({
        type: 'GET',
        url: _base_url + 'select',
        success: function (a) {
            $.each(a, function (b, c) {
                $.each(c, function (d, e) {
                    $('.on-form-company').form('set value', d, e);
                });
            });
        }
    });


});