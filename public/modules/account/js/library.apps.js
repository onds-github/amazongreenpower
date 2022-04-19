$(document).ready(function () {

    var menu = '';
    $.ajax({
        type: 'GET',
        url: base_url + 'account/apps/ajax',
        success: function (e) {
            $.each(e, function (i, v) {
                menu += '<div class="column">' +
                        '<a href="' + v.href + '">' +
                        '<div class="ui very padded center aligned segment">' +
                        '<i class="material-icons">' + v.icon + '</i>' +
                        '<p>' + v.label + '</p>' +
                        '</div>' +
                        '</a>' +
                        '</div>';
            });

        },
        complete: function() {
            $('.menu-apps').html(menu);
        }
    });

});