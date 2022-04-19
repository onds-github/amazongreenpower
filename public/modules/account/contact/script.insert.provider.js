$(document).ready(function () {

            var PhoneMaskBehavior = function (val) {
                return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
            },
                    PhoneOptions = {
                        onKeyPress: function (val, e, field, options) {
                            field.mask(PhoneMaskBehavior.apply({}, arguments), options);
                        }
                    };

            $.fn.form.settings.rules.existsDocument = function (value) {
                var isExists;
                $.ajax({
                    async: false,
                    type: 'POST',
                    url: '/account/contact/exists-document',
                    data: {q: value},
                    success: function (e) {
                        isExists = e;
                    }
                });
                return isExists;
            };

            var DocumentMaskBehavior = function (val) {
                return val.replace(/\D/g, '').length === 14 ? '00.000.000/0000-00' : '000.000.000-09999';
            },
                    DocumentOptions = {
                        onKeyPress: function (val, e, field, options) {
                            field.mask(DocumentMaskBehavior.apply({}, arguments), options);
                        }
                    };

            $('.on-mask-document').mask(DocumentMaskBehavior, DocumentOptions);

            $('.on-mask-phone').mask(PhoneMaskBehavior, PhoneOptions);
            
            $(document).on('click', '.on-bins-contact', function () {

                var form = $('.on-form-insert-contact').submit(function (event) {
                    return false;
                }).form({
                    inline: true,
                    fields: {
                        document_contact: {
                            identifier: 'document_contact',
                            rules: [
                                {
                                    type: 'empty',
                                    prompt: 'Informe'
                                }
                            ]
                        },
                        nickname_contact: {
                            identifier: 'nickname_contact',
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
                            url: '/account/contact/insert',
                            data: {data: form.serialize()},
                            success: function (e) {
                                UIkit.notification(e.message, {status: e.status});
                                $.ajax({
                                    type: 'POST',
                                    url: '/finance/order-out/insert-provider',
                                    data: {id_contact: e.returning},
                                    success: function (e) {
                                        UIkit.notification({message: e.message, status: e.status});
                                    },
                                    complete: function () {
                                        _table.ajax.reload();
                                    }
                                });
//                            if ($.isFunction(Args.onSuccess)) {
//                                Args.onSuccess.call(this, e);
//                            }
                            },
                            complete: function () {
                                m.modal('hide');
                                form.form('reset');
//                            settings.table.ajax.reload();
                            }
                        });

                    }
                });

                var m = $('.on-ins-contact').modal({
                    closable: false,
                    observeChanges: true,
                    allowMultiple: true,
                    onDeny: function () {

                    },
                    onApprove: function () {
                        form.submit();
                        return false;
                    },
                    onHidden: function () {

                    }
                }).modal('show');

            });


});
