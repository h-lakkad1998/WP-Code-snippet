// ####101#### FAV WISHLIST STARTS
function nls___global_setCookie(cname, cvalue, exdays) {
    const d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    let expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function nls___global_getCookie(cname) {
    let name = cname + "=";
    let ca = document.cookie.split(';');
    for(let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

 // save brands functionality
$(document).on('click','.nls_brand_add_to_fav', function () {
    var this_ele = $(this);
    var is_fav_listing_template = ( $('.nls-fav-brands-template-listing').length >= 1 ) ? true : false;
    this_ele.html('<i class="fas fa-circle-notch fa-spin"></i>');
    var is_logged_in = this_ele.hasClass('user-logged-in');
    var brand_id = String( this_ele.data('storeid') );
    var is_saved = this_ele.hasClass('brand-saved');
    let brands_ids_string = nls___global_getCookie('nls_saved_brands').trim();
    let arry_data = brands_ids_string.split(',');
    // save data in cookie if not logged in
    if( false === is_logged_in ){
        if( ( false === is_saved || '' === brands_ids_string ) && false === arry_data.includes( brand_id ) ){
            // save the id in cookie
            arry_data.push(   brand_id );
            var filtered_array = arry_data.filter( function(e){return e} );
            let process_string_data = filtered_array.join(',');
            nls___global_setCookie('nls_saved_brands', process_string_data, 2 );
            this_ele.addClass('brand-saved');
            this_ele.removeClass('not-saved');
            setTimeout(() => {
                this_ele.html("<i class='fas fa-heart' ></i>");
            }, 1000);
        }else{
            // remove id in cookie
            var filteredArray = arry_data.filter(e => e !==  brand_id )
            let process_string_data = filteredArray.join(',');
            nls___global_setCookie('nls_saved_brands', process_string_data, 2 );

            this_ele.removeClass('brand-saved');
            this_ele.addClass('not-saved');
            setTimeout(() => {
                this_ele.html("<i class='far fa-heart' ></i>");
            }, 1000);
        }
    }else{
        // save data in db is user logged in
        $.ajax({
            type: "post",
            url: ajax_obj.ajax_url,
            data: {
                'action': 'nls_save_brands_for_user',
                'brand_id': brand_id,
                'fire_action': is_saved
            },
            success: function (response) {
                if( 'yes' == response.liked_brand ){
                    this_ele.addClass('brand-saved');
                    this_ele.removeClass('not-saved');
                }
                if( 'yes' == response.remove_liked_brand ){
                    this_ele.removeClass('brand-saved');
                    this_ele.addClass('not-saved');
                }
                this_ele.html( response.fafa_html );
            }
        });
    }
    if( is_fav_listing_template ){
        this_ele.fadeOut( 1500 , function() {
            this_ele.parents('li').remove();
            if( $('.nls-fav-brands-template-listing ul li.item').length <= 0 ){
                $('.no-fav-list').show();
            }
        });

    }
});

// show text area on checkbox check
$(document).on('change','#nls_baba_is_gift', function () {
    if( $(this).prop('checked')==true  ){
        $('.nls-gift-notes-wrapper').fadeIn( );
    }else{
        $('.nls-gift-notes-wrapper').fadeOut();
    }
});
// ####101#### FAV WISHLIST ENDS

// ####102#### FAV WISHLIST STARTS
document.addEventListener("DOMContentLoaded", () => {
    var minutes = $('#set-time').val();

    var target_date = new Date().getTime() + ((minutes * 60) * 1000); // set the countdown date
    var time_limit = ((minutes * 60) * 1000);
    //set actual timer
    setTimeout(
        function() {
            document.getElementById("left").innerHTML = "Timer Stopped";
        }, time_limit);

    var days, hours, minutes, seconds; // variables for time units

    var countdown = document.getElementById("tiles"); // get tag element

    getCountdown();

    setInterval(function() {
        getCountdown();
    }, 1000);

    function getCountdown() {

        // find the amount of "seconds" between now and target
        var current_date = new Date().getTime();
        var seconds_left = (target_date - current_date) / 1000;

        if (seconds_left >= 0) {
            console.log(time_limit);
            if ((seconds_left * 1000) < (time_limit / 2)) {
                $('#tiles').removeClass('color-full');
                $('#tiles').addClass('color-half');

            }
            if ((seconds_left * 1000) < (time_limit / 4)) {
                $('#tiles').removeClass('color-half');
                $('#tiles').addClass('color-empty');
            }

            days = pad(parseInt(seconds_left / 86400));
            seconds_left = seconds_left % 86400;

            hours = pad(parseInt(seconds_left / 3600));
            seconds_left = seconds_left % 3600;

            minutes = pad(parseInt(seconds_left / 60));
            seconds = pad(parseInt(seconds_left % 60));

            // format countdown string + set tag value
            countdown.innerHTML = "<span>" + hours + ":</span><span>" + minutes + ":</span><span>" + seconds + "</span>";
        }

    }

    function pad(n) {
        return (n < 10 ? '0' : '') + n;
    }
});
// ####102#### FAV WISHLIST ENDS

