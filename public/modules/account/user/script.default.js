$(document).ready(function () {
// Turn input element into a pond
    $('.on-filepond').filepond();

    // Set allowMultiple property to true
    $('.on-filepond').filepond('allowMultiple', false);
    
    FilePond.setOptions({
        labelIdle: 'Arraste & solte sua imagem ou <span class="filepond--label-action"> Procure </span>',
        allowReplace: false,
        server: {
            url: '/account/user/',
            process: 'upload',
            revert: 'remove-image'
        }
    });
    
    $.ajax({
        type: 'GET',
        url: '/account/user/select',
        success: function (e) {
            $.each(e, function (i, v) {
                $.each(v, function (x, z) {
                    switch (x) {
                        case 'image_user':
                            if (z) {
                                $('.' + x).attr('src', z);
                            }
                            break;

                        default:
                            $('.on-form-user').form('set value', x, z);
                            break;
                    }
                });
            });
        }
    });

    var _form_user = $('.on-form-user').submit(function (event) {
        return false;
    }).form({
        inline: true,
        fields: {
            name_user: {
                identifier: 'name_user',
                rules: [
                    {
                        type: 'empty',
                        prompt: 'Infome'
                    }
                ]
            },
            email_user: {
                identifier: 'email_user',
                rules: [
                    {
                        type: 'empty',
                        prompt: 'Infome'
                    }
                ]
            },
            phone_user: {
                identifier: 'phone_user',
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
                url: '/account/user/update',
                data: {data: _form_user.serialize()},
                success: function (e) {
                    UIkit.notification({message: e.message, status: e.status});
                },
                complete: function () {
                    _form_user.find('button').addClass('disabled');
                }
            });
        }
    });
    
    $('.on-form-user').on('keyup', 'input', function () {
        _form_user.find('button').removeClass('disabled');
    });

});