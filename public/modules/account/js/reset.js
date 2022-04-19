$(document).ready(function () {

    var _base_url = base_url + 'account/reset/';
    
    $.ajax({
        type: 'POST',
        url: _base_url + 'select',
        data: {q: _url()['q']},
        success: function (e) {
            switch (e) {
                case null:
                    $('.on-form-forgot').html('<img src="https://img.icons8.com/ios/50/333333/lock-error.png"/><p class="uk-text-meta">Não localizamos a solicitação de redefinição de senha. <a href="' + base_url + 'account/forgot" class="uk-link">Solicite novamente!</a></p>').addClass('uk-text-center');
                    break;
            }
        }
    });

    $.fn.form.settings.rules.existsField = function (value) {
        switch (value) {
            case $('form').form('get value', 'password'):
                return true;
            break;
            default:
                return false;
            break;
        }
    };

    var form = $('form').form({
        inline: true,
        fields: {
            password: {
                identifier: 'password',
                rules: [
                    {
                        type: 'empty',
                        prompt: 'Por favor informe sua senha'
                    },
                    {
                        type: 'length[6]',
                        prompt: 'Sua senha deve ter no mínimo 6 caracteres'
                    }
                ]
            },
            password_confirm: {
                identifier: 'password_confirm',
                rules: [
                    {
                        type: 'existsField',
                        prompt: 'A confirmação de senha deve ser idêntica a nova senha'
                    }
                ]
            }
        },
        onSuccess: function () {
            $.ajax({
                type: 'POST',
                url: _base_url + 'update',
                data: {q: _url()['q'], data: form.serialize()},
                beforeSend: function (xhr) {
                    form.addClass('loading');
                    form.find('button').attr('disabled', 'disabled');
                },
                success: function (e) {
                    UIkit.notification(e.message, {status: e.status, pos: 'bottom-right'});
                    switch (e.status) {
                        case 'success':
                            window.location.href = base_url + 'account/access';
                            break;
                    }
                },
                complete: function () {
                    form.removeClass('loading');
                    form.find('button').removeAttr('disabled');
                }
            });
        }
    });

    form.submit(function (e) {
        //e.preventDefault(); usually use this, but below works best here.
        return false;
    });

});
