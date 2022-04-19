/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var on = on || (function(){
    var _arrgs; // private

    return {
        _init : function(Args) {
            _arrgs = Args;
        },
        _tracking: function() {
            
            if (_url()['on_cookie_key']) {
                setCookie('on_cookie_key', _url()['on_cookie_key'], 365);
            }
            
            if (!getCookie('on_cookie_key')) {
                $.ajax({
                    type: 'POST',
                    url: 'https://analytics.wtech.solutions/tracking/session/cookie-key',
                    data: {
                        url_session: window.location.href,  
                        url_prev_session: document.referrer
                    },
                    success: function (e) {
                        switch (e.status) {
                            case 'success':
                                setCookie('on_cookie_key', e.cookie_key, 365);
                                break;

                            case 'danger':
                                setCookie('on_cookie_key', '', -1);
                                break;
                        }
                    },
                    complete: function() {
                        $.ajax({
                            type: 'POST',
                            url: 'https://analytics.wtech.solutions/tracking/session-route/insert',
                            data: {
                                cookie_key: getCookie('on_cookie_key'), 
                                url_session: window.location.href, 
                                url_prev_session: document.referrer
                            },
                            success: function (e) {

                            }
                        });
                    }
                });
            } else {
                $.ajax({
                    type: 'POST',
                    url: 'https://analytics.wtech.solutions/tracking/session-route/insert',
                    data: {
                        cookie_key: getCookie('on_cookie_key'), 
                        url_session: window.location.href, 
                        url_prev_session: document.referrer
                    },
                    success: function (e) {

                    }
                });
            }
        }
    };
}());

function _url() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
        vars[key] = value;
    });
    return vars;
}

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



