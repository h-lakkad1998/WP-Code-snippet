<?php 
// ####101#### FAV. WISHLIST CODE SNIPPET STARTS  
//fav product ajax callback
function nls_save_brands_for_user_fn() {
	$is_saved = $_POST['fire_action'];
	$brand_id = $_POST['brand_id'];
	$user_id = get_current_user_id();
	$saved_ids = get_user_meta( $user_id , 'nls_saved_brands_ids' , true ) ;
	$saved_ids = ( $saved_ids ) ? explode(',', $saved_ids ) : array();
	$resp_ary = array();
	if( 'false' ==  $_POST['fire_action'] ){
		if( false === in_array( $brand_id, $saved_ids ) ){
			$saved_ids[] = $brand_id;
			$stringify_ary = join(',', $saved_ids);
			update_user_meta( $user_id, 'nls_saved_brands_ids', $stringify_ary );
			$fafa_html = "<i class='fas fa-heart' ></i>";
			$resp_ary['liked_brand'] = 'yes'; 
		}
	}else{
		if ( ($key = array_search($brand_id, $saved_ids)) !== false) {
			unset($saved_ids[$key]);
		}
		$resp_ary['remove_liked_brand'] = 'yes'; 
		$stringify_ary = join(',', $saved_ids);
		update_user_meta( $user_id, 'nls_saved_brands_ids', $stringify_ary );
		$fafa_html = "<i class='far fa-heart' ></i>";
	}
	$resp_ary['fafa_html'] =  $fafa_html;
	wp_send_json( $resp_ary );
}
add_action( 'wp_ajax_nls_save_brands_for_user', 'nls_save_brands_for_user_fn' );

// html of like button
function nls_save_brand_button( $store_id, $store_info ) {
	$cstm_class_list = array( 'nls_brand_add_to_fav' );
	$cstm_class_list[] = ( is_user_logged_in() ) ? 'user-logged-in' : 'user-not-logged-in';
	$logged_in_data = get_user_meta( get_current_user_id(), 'nls_saved_brands_ids' , true ) ;
	$logged_in_data = ( $logged_in_data ) ?  explode( ',', $logged_in_data ) : array();
	$without_login_data = ( isset( $_COOKIE['nls_saved_brands'] ) && '' !== trim( $_COOKIE['nls_saved_brands'] ) ) ? explode(',', $_COOKIE['nls_saved_brands'] ) : array() ;
	$saved_list = ( is_user_logged_in() ) ? $logged_in_data : $without_login_data;
	$cstm_class_list[] = ( in_array( $store_id, $saved_list ) ) ? 'brand-saved' : 'not-saved';
	
	$class_merger = join( ' ', $cstm_class_list);
	$data_id_html =  "data-storeid='$store_id'";

	$font_icon_html = ( in_array( $store_id, $saved_list ) ) ? "<i class='fas fa-heart' ></i>" : "<i class='far fa-heart'></i>";
	echo "<a href='javascript:;' class='$class_merger' $data_id_html > $font_icon_html </a>" ;
	//echo "<pre>";  print_r( $_COOKIE ); echo "</pre>"; 
}
add_action('wcfmmp_store_list_footer','nls_save_brand_button', 11 ,2 );

// data extraction in the loop listing
$cstm_class_list = array( 'nls_brand_add_to_fav' );
$cstm_class_list[] = ( is_user_logged_in() ) ? 'user-logged-in' : 'user-not-logged-in';
$logged_in_data = get_user_meta( get_current_user_id(), 'nls_saved_brands_ids' , true ) ;
$logged_in_data = ( $logged_in_data ) ?  explode( ',', $logged_in_data ) : array();
$without_login_data = ( isset( $_COOKIE['nls_saved_brands'] ) && '' !== trim( $_COOKIE['nls_saved_brands'] ) ) ? explode(',', $_COOKIE['nls_saved_brands'] ) : array() ;
$saved_list = ( is_user_logged_in() ) ? $logged_in_data : $without_login_data; 

// ####101#### CODE SNIPPET ENDS
