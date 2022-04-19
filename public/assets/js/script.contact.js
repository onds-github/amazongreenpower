$(document).ready(function () {
    
    $(document).on('submit', '.form-grid', function () {
        $this = $(this);
        $.ajax({
            type: 'POST',
            url: '/contact/insert',
            data: {data: $this.serialize()},
            success: function (e) {
                if (e.status == 'success') {
                    $('.success-message').css('display', 'block');
                    $this.reset();
                } else {
                    $('.error-message').css('display', 'block');
                }
            }
        });
        return false;
    });

});