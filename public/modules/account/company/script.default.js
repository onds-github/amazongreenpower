$(document).ready(function () {

    $.ajax({
        type: 'GET',
        url: '/account/company/select',
        success: function (a) {
            $.each(a, function (b, c) {
                $.each(c, function (d, e) {
                    _form_company.form('set value', d, e);
                });
            });
        }
    });

    var _form_company = $('.on-form-company').submit(function (event) {
        return false;
    }).form({
        inline: true,
        fields: {
            name_company: {
                identifier: 'name_company',
                rules: [
                    {
                        type: 'empty',
                        prompt: 'Infome'
                    }
                ]
            },
            email_company: {
                identifier: 'email_company',
                rules: [
                    {
                        type: 'empty',
                        prompt: 'Infome'
                    }
                ]
            }
        },
        onSuccess: function () {
            $.ajax({
                type: 'POST',
                url: '/account/company/update',
                data: {data: _form_company.serialize()},
                success: function (e) {
                    UIkit.notification({message: e.message, status: e.status});
                },
                complete: function () {
                    _form_company.find('button').addClass('disabled');
                }
            });
        }
    });
    
    $(document).on('keyup', 'input', function () {
        _form_company.find('button').removeClass('disabled');
    });

});