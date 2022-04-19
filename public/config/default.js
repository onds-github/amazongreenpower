var base_url = '/';

function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires=" + d.toGMTString();
    document.cookie = cname + "=" + cvalue + "; " + expires + '; path=/';
}

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ')
            c = c.substring(1);
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function _url() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
        vars[key] = value;
    });
    return vars;
}

var DocumentMaskBehavior = function (val) {
    return val.replace(/\D/g, '').length === 14 ? '00.000.000/0000-00' : '000.000.000-09999';
},
        DocumentOptions = {
            onKeyPress: function (val, e, field, options) {
                field.mask(DocumentMaskBehavior.apply({}, arguments), options);
            }
        };

$('.on-mask-document').mask(DocumentMaskBehavior, DocumentOptions);

var PhoneMaskBehavior = function (val) {
    return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
},
        PhoneOptions = {
            onKeyPress: function (val, e, field, options) {
                field.mask(PhoneMaskBehavior.apply({}, arguments), options);
            }
        };

$('.on-mask-phone').mask(PhoneMaskBehavior, PhoneOptions);
$('.on-mask-cpf').mask('000.000.000-00', {reverse: true});
$('.on-mask-cnpj').mask('00.000.000/0000-00', {reverse: true});
$('.on-mask-zipcode').mask('00000-000', {reverse: true});
$('.on-mask-money').mask("#.##0,00", {reverse: true});
