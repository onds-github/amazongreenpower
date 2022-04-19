$(document).ready(function () {

    var _base_url = base_url + 'account/forgot/';

    $.fn.form.settings.rules.existsEmail = function (value) {
        var isExists;
        $.ajax({
            async: false,
            type: 'POST',
            url: _base_url + 'exists-email',
            data: {field: value},
            success: function (e) {
                isExists = e;
            }
        });
        return isExists;
    };

    var _form = $('.on-form-forgot').form({
        inline: true,
        fields: {
            email: {
                identifier: 'email',
                rules: [
                    {
                        type: 'empty',
                        prompt: 'Informe o e-mail'
                    },
                    {
                        type: 'existsEmail',
                        prompt: 'Não localizamos nenhuma conta com esse e-mail'
                    }
                ]
            }
        },
        onSuccess: function () {
            $.ajax({
                type: 'POST',
                url: _base_url + 'update',
                data: {data: _form.serialize()},
                beforeSend: function (xhr) {
                    _form.addClass('loading');
                    _form.find('button').attr('disabled', 'disabled');
                },
                success: function (e) {
                    UIkit.notification(e.message, {status: e.status, pos: 'bottom-right'});
                },
                complete: function () {
                    _form.removeClass('loading');
                    _form.find('button').removeAttr('disabled');
                    _form.form('reset');
                }
            });
        }
    });

    _form.submit(function (e) {
        //e.preventDefault(); usually use this, but below works best here.
        return false;
    });

});
