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
