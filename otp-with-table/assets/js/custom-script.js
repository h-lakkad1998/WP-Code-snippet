jQuery(document).ready(function ($) {
    $('body').on('input keyup keydown focusout focusin','.form-field input', function () {
        var fl_name = $('#person-fullname');
        var email = $('#person-email');
        var phone = $('#person-phone');
        var member_id = $('#person-member-id');
        if( '' === fl_name.val().trim() || '' === email.val().trim() || '' === phone.val().trim() || '' === member_id.val().trim() ){
            $('.generate-otp').hide();
        }else{
            if( ! $('.generate-otp').hasClass('otp-generated') ){
                $('.generate-otp').show();
            }
        }
    });

    $('body').on('click','.generate-otp button', function () {
        $('.generate-otp').hide();
        $('.vote-form').addClass('processing-action');
        $.ajax({
            type: "post",
            url: ajax_obj.ajax_url,
            data: $('#VOTING_form').serialize(),
            success: function (response) {
                if( 'done' == response.status ){
                    $('.otp-verify').show();
                    $('.generate-otp').addClass('otp-generated');
                }
                $('.responses-message').addClass( response.status );
                $('.responses-message').html( response.message );
            },
            complete: function (data) {
                $('.vote-form').removeClass('processing-action');
            }
        });
    });

    $('body').on('click','.otp-verify button', function () {
        $('#HIDDEN-action').val('zest_verify_otp');
        $('.vote-form').addClass('processing-action');
        $.ajax({
            type: "post",
            url: ajax_obj.ajax_url,
            data: $('#VOTING_form').serialize(),
            success: function (response) {
                console.log( response );
                if( 'done' == response.status ){
                    $('.otp-verify').hide();
                    $('.otp-verify').addClass('otp-verified');
                    $('#CHECK-otp-verification').val('yes');
                    $('.submit-vote').show();
                }
                $('.responses-message').addClass( response.status );
                $('.responses-message').html( response.message );
            },
            complete: function (data) {
                $('.vote-form').removeClass('processing-action');
            }
        });
    });

    $('body').on('submit','#VOTING_form', function (e) {
        e.preventDefault();
        $('#HIDDEN-action').val('zest_submit_vote');
        $('.vote-form').addClass('processing-action');
        $.ajax({
            type: "post",
            url: ajax_obj.ajax_url,
            data: $('#VOTING_form').serialize(),
            success: function (response) {
                console.log( response );
                if( 'done' == response.status ){
                    $('#VOTING_form')[0].reset();
                    $('.submit-vote').hide();
                    $('#CHECK-otp-verification').val('no');
                    $('.otp-verify').removeClass('otp-verified');
                    $('.generate-otp').removeClass('otp-generated');
                    $('#HIDDEN-action').val('zest_generate_opt');
                }
                $('.responses-message').addClass( response.status );
                $('.responses-message').html( '<p>' + response.message + '</p>' );
                $('.responses-message p').fadeOut( 2000, function(){
                    $('.responses-message p').remove();
                });
            },
            complete: function (data) {
                $('.vote-form').removeClass('processing-action');
            }
        });
    });
});