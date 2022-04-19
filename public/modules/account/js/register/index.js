$(document).ready(function () {
//    setInterval(function(){
//        $.ajax({
//            async: false,
//            type: 'GET',
//            url: base_url + 'image/select',
//            success: function (e) {
//                $('.on-background-imagem').css('background-image', 'url(' + e.imagem + ')');
//            }
//        });
//    }, 5000);
    

    var _base_url = base_url + 'account/register/';

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

    var _form = $('form').form({
        inline: true,
        fields: {
            name: {
                identifier: 'name',
                rules: [
                    {
                        type: 'empty',
                        prompt: 'Por favor informe o seu nome'
                    }
                ]
            },
            email: {
                identifier: 'email',
                rules: [
                    {
                        type: 'empty',
                        prompt: 'Por favor informe o seu E-mail'
                    },
                    {
                        type: 'email',
                        prompt: 'Por favor informe um E-mail válido'
                    },
                    {
                        type: 'maxLength[128]',
                        prompt: 'E-mail com no máximo 128 caracteres'
                    },
                    {
                        type: 'existsEmail',
                        prompt: 'Este e-mail já existe em nossa base de dados'
                    }
                ]
            },
            password: {
                identifier: 'password',
                rules: [
                    {
                        type: 'empty',
                        prompt: 'Por favor informe uma senha'
                    },
                    {
                        type: 'minLength[6]',
                        prompt: 'A senha dve conter no mínimo 6 caracteres'
                    }
                ]
            },
            terms: {
              identifier: 'terms',
              rules: [
                {
                  type   : 'checked',
                  prompt : 'Você precisa aceitar os termos de uso'
                }
              ]
            }
        },
        onSuccess: function () {
            $.ajax({
                type: 'POST',
                url: _base_url + 'insert',
                data: {data: _form.serialize()},
                beforeSend: function (xhr) {
                    _form.addClass('loading');
                    _form.find('button').attr('disabled', 'disabled');
                },
                success: function (e) {
                    UIkit.notification(e.message, {status: e.status, pos: 'bottom-right'});
                    switch (e.status) {
                        case 'success':
                            $('.on-form-register').html('<img src="https://img.icons8.com/material-outlined/50/ffffff/checked.png"/>\n\
                                <h1 class="uk-h1>Sua conta foi criada</h1>\n\
                                <p class="uk-text-meta">Sua conta está sendo analisada, em breve você receberá uma notificação com instruções para acessar sua conta.</p>').addClass('uk-text-center');
                            break;
                            
                        default:
                            
                            break;
                    }
                },
                complete: function () {
                    _form.removeClass('loading');
                    _form.find('button').removeAttr('disabled');
                }
            });
        }
    });

    _form.submit(function (e) {
        //e.preventDefault(); usually use this, but below works best here.
        return false;
    });

});
