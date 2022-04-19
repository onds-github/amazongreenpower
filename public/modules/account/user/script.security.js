$(document).ready(function () {

    var _form_security = $('.on-form-security').submit(function (event) {
        return false;
    }).form({
        inline: true,
        fields: {
            password_user: {
                identifier: 'password_user',
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
                url: '/account/security/update',
                data: {data: _form_security.serialize()},
                success: function (e) {
                    UIkit.notification({message: e.message, status: e.status});
                },
                complete: function () {
                    _form_security.find('button').addClass('disabled');
                }
            });
        }
    });
    
    $('.on-form-security').on('keyup', 'input', function () {
        _form_security.find('button').removeClass('disabled');
    });

});