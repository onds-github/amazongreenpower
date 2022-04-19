$(document).ready(function () {
    
    $('.on-dropdown').dropdown();

    $(document).on('click', '#onButtonMenu', function () {
        $this = $(this);
        $.ajax({
            type: 'POST',
            url: '/account/user/update',
            data: {data: 'menu_user=true'},
            success: function (e) {
                if (e.menu_user) {
                    $('.isMenuLargeMargin').addClass('isMenuSmallMargin').removeClass('isMenuLargeMargin');
                    $('.isMenuLargeVisible').addClass('isMenuNoLargeVisible').removeClass('isMenuLargeVisible');
                    $('.isMenuLarge').addClass('isMenuSmall').removeClass('isMenuLarge');
                    
                    $('.isLogoSmallVisible').css('diplay', 'block');
                    $('.isLogoLargeVisible').css('diplay', 'none');
                } else {
                    $('.isMenuSmallMargin').addClass('isMenuLargeMargin').removeClass('isMenuSmallMargin');
                    $('.isMenuNoLargeVisible').addClass('isMenuLargeVisible').removeClass('isMenuNoLargeVisible');
                    $('.isMenuSmall').addClass('isMenuLarge').removeClass('isMenuSmall');
                    
                    $('.isLogoSmallVisible').css('diplay', 'none');
                    $('.isLogoLargeVisible').css('diplay', 'block');
                }
            }
        });
    });

});