$(document).ready(function () {
    
    var _base_url = base_url + 'account/access/';

    $.fn.form.settings.rules.existsDocument = function (value) {
        var isExists;
        $.ajax({
            async: false,
            type: 'POST',
            url: _base_url + 'exists-document',
            data: {field: value},
            success: function (e) {
                isExists = e;
            }
        });
        return isExists;
    };

    $.fn.form.settings.rules.validDocument = function (cnpj) {
        cnpj = cnpj.replace(/[^\d]+/g, '');

        if (cnpj == '')
            return false;

        if (cnpj.length != 14)
            return false;

        // Elimina CNPJs invalidos conhecidos
        if (cnpj == "00000000000000" ||
                cnpj == "11111111111111" ||
                cnpj == "22222222222222" ||
                cnpj == "33333333333333" ||
                cnpj == "44444444444444" ||
                cnpj == "55555555555555" ||
                cnpj == "66666666666666" ||
                cnpj == "77777777777777" ||
                cnpj == "88888888888888" ||
                cnpj == "99999999999999")
            return false;

        // Valida DVs
        tamanho = cnpj.length - 2
        numeros = cnpj.substring(0, tamanho);
        digitos = cnpj.substring(tamanho);
        soma = 0;
        pos = tamanho - 7;
        for (i = tamanho; i >= 1; i--) {
            soma += numeros.charAt(tamanho - i) * pos--;
            if (pos < 2)
                pos = 9;
        }
        resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
        if (resultado != digitos.charAt(0))
            return false;

        tamanho = tamanho + 1;
        numeros = cnpj.substring(0, tamanho);
        soma = 0;
        pos = tamanho - 7;
        for (i = tamanho; i >= 1; i--) {
            soma += numeros.charAt(tamanho - i) * pos--;
            if (pos < 2)
                pos = 9;
        }
        resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
        if (resultado != digitos.charAt(1))
            return false;

        return true;
    }

    
    var form = $('form').submit(function (e) {
        return false;
    }).form({
        inline: true,
        on: 'blur',
        fields: {
            documento: {
                identifier: 'documento',
                rules: [
                    {
                        type: 'empty',
                        prompt: 'Informe o CNPJ do logista'
                    },
                    {
                        type: 'validDocument',
                        prompt: 'Por favor informe um CNPJ válido'
                    },
                    {
                        type: 'existsDocument',
                        prompt: 'Não localizamos nenhuma conta com esse CNPJ'
                    }
                ]
            },
            senha: {
                identifier: 'senha',
                rules: [
                    {
                        type: 'empty',
                        prompt: 'Informe sua senha'
                    }
                ]
            }
        },
        onSuccess: function () {
            $.ajax({
                type: 'POST',
                url: '/account/access/request',
                data: {data: form.serialize()},
                success: function (e) {
                    UIkit.notification(e.message, {status: e.status, pos: 'bottom-right'});
                    switch (e.status) {
                        case 'success':
                            if (_url()['p']) {
                                window.location.href = _url()['p'];
                            } else {
                                window.location.reload();
                            }
                            break;
                        case 'danger':
                            form.form('add prompt', 'email', '...');
                            form.form('add prompt', 'password', '...');
                            break;
                    }
                }
            });
        }
    });
});