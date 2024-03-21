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

// ####101####  FAV. WISHLIST CODE SNIPPET ENDS


####102#### TIME LOGGER CODE SNIPPET STARTS
$time_log = array(
    array(
        "IN"    => "11:34",
        "OUT"   => "13:05",
    ),
    array(
        "IN"    => "14:03",
        "OUT"   => "21:02",
    ),
);
$diff = 000000;
foreach ($time_log as $key => $single_entry) {
    $hour_in  = null;
    $hour_out = $single_entry["OUT"];

    if (isset($single_entry["IN"]))
        $hour_in = $single_entry["IN"];

    if ($hour_in != null)
        $diff += (strtotime($hour_out) - strtotime($hour_in));
}
$total              = $diff / 60;
$total_hours        = floor($total / 60);
$total_minutes      = floor($total % 60);
$total_hours_worked = sprintf('%02d:%02d', $total_hours, $total_minutes);
echo $total_hours_worked;

$remain_format =  date('h:i:s', $diff);
$c_time1 = strtotime( $remain_format );
$c_time2 = strtotime('08:30:00');
$m_difference = round(abs($c_time2 - $c_time1) / 60);

####102#### TIME LOGGER CODE SNIPPET ENDS

####103#### Woo addon-no plugin CODE SNIPPET STARTS

//  product validation for add to cart
function nls_product_add_on_validation( $passed, $product_id, $qty ) {
	if( isset($_POST['nls_hidden_option_data']) ){
		if ( isset( $_POST['nls-del-custom-date'] ) && trim( $_POST['nls-del-custom-date'] ) == '' ) {
			wc_add_notice( __('Selecteer de leverdatum!', 'tuintotaalodenzaal'), 'error' );
			$passed = false;
		}
		if ( isset( $_POST['nls_vase_selection'] ) && trim( $_POST['nls_vase_selection'] ) == 'yes' && empty( trim( $_POST['nls_f_vase_size'] ) ) ) {
			wc_add_notice( __('De vaasgrootte moet worden geselecteerd!', 'tuintotaalodenzaal'), 'error' );
			$passed = false;
		}
	}
	if( isset( $_POST['nls_delivery_selection'] ) && trim( $_POST['nls_delivery_selection'] ) == 'delivery' && ( ( trim($_POST['nls_delivery_address']) ) == '' || trim( $_POST['nls_calculate_ship_charges'] ) == '' || ( trim($_POST['nls_calculated_distance']) ) == '' )  ){
		wc_add_notice( __('Ongeldige keuze!', 'tuintotaalodenzaal'), 'error' );
		$passed = false;
	}

	/* gift card start */ 
	if( isset( $_POST['nls_gift_card'] ) && '' === trim( $_POST['nls_gift_card'] ) ){
		wc_add_notice( __('Selecteer het cadeaubedrag!', 'tuintotaalodenzaal'), 'error' );
		$passed = false;
	}
	/* gift card ends */ 

	return $passed;
}
add_filter( 'woocommerce_add_to_cart_validation', 'nls_product_add_on_validation', 9999, 3 );

// set selected produt details in cart sessions
function nls_product_add_on_cart_item_data( $cart_item, $product_id ) {
	$nls_cur_sym = get_woocommerce_currency_symbol();
	// set value and label for vase options product
	if( isset($_POST['nls_hidden_option_data']) ){
		$custom_flower = get_field( 'flower_options' , $product_id );
		$current_index = array_search( $_POST['nls_flower_size'] , array_column($custom_flower, 'size'));
		$current_select = $custom_flower[$current_index];
		$cart_item['set_vase_selection'] = $_POST['nls_vase_selection'];
		$cart_item['nls_flower_size'] = $_POST['nls_flower_size'];
		$cart_item['set_flower_price'] = $current_select['size_price']; 
		if( isset( $_POST['nls_vase_selection'] ) && $_POST['nls_vase_selection'] == 'yes' ){
			$cart_item['set_vase_label'] = trim( $current_select['vase_prefix'] . ' (' . $nls_cur_sym . ' ' . $current_select['vase_price'] ) . ')';
			$cart_item['set_vase_price'] = trim( $current_select['vase_price'] );
		}
	}

	/* gift card start */ 
	if( isset( $_POST['nls_gift_card'] ) && ! empty( trim( $_POST['nls_gift_card'] ) ) ){
		$cart_item['set_gift_card_price'] = sanitize_text_field( $_POST['nls_gift_card'] );
	}
	/* gift card ends */ 

	// calculate shipping charges based on km distance
	if( isset( $_POST['nls_delivery_selection'] )  ){
		$distance_rates = get_field('distance_rates','option');
		$cart_item['set_shipping_method'] = ( $_POST['nls_delivery_selection'] == 'delivery' ) ? 'delivery' : 'pickup';
		if( $_POST['nls_delivery_selection'] == 'delivery' ){
			$delivery_charge = 1.00;
			if( ! empty( $distance_rates ) ){
				foreach( $distance_rates as $single_rate ){
					if( (int) $single_rate['from_distance'] <  $_POST['nls_calculated_distance'] && $_POST['nls_calculated_distance'] <= (int) $single_rate['to_distance'] ){
						$delivery_charge = $single_rate['delivery_charges']; break;
					}
				}
			}
			$cart_item['set_delivery_address'] = trim( $_POST['nls_delivery_address'] );
			$cart_item['set_calculated_distance'] = trim( $_POST['nls_calculated_distance'] );
			$cart_item['set_delivery_charge'] = $delivery_charge;
		}
	}
	
	// Message for the recipient save data
	if( isset( $_POST['nls_custom_message'] ) && '' !== trim( $_POST['nls_custom_message'] ) ){
		$nls_card_message_rate = get_field('nls_card_message_rate','option');
		$cart_item['nls_custom_message'] = sanitize_text_field( $_POST['nls_custom_message'] );
		$cart_item['set_card_msg_charge'] = $nls_card_message_rate;
	}

	$cart_item['set_delivery_date'] = sanitize_text_field( $_POST['nls-del-custom-date'] );
	return $cart_item;
}
add_filter( 'woocommerce_add_cart_item_data', 'nls_product_add_on_cart_item_data', 9999, 2 );
 
// Display custom add ons options in the cart
function nls_product_add_on_display_cart( $data, $cart_item ) {
	$nls_cur_sym = get_woocommerce_currency_symbol();
	if ( isset( $cart_item['nls_flower_size'] ) ){
		$data[] = array(
			'name' 	=> __('Bloemgrootte','tuintotaalodenzaal'),
            'value' => '<small>' . $cart_item['nls_flower_size'] ." ($nls_cur_sym " . $cart_item['set_flower_price'] . ')' . '</small>',
        );
    }
	/* gift card label start */ 
	if ( isset( $cart_item['set_gift_card_price'] ) ){
		$data[] = array(
			'name' 	=> __('Cadeauprijs','tuintotaalodenzaal'),
            'value' => "<small> $nls_cur_sym" . " " .  $cart_item['set_gift_card_price']  . '</small>',
        );
    }
	/* gift card label ends */ 
	if ( isset( $cart_item['set_vase_selection'] ) ){
		$data[] = array(
			'name' 	=> __('Vaas geselecteerd','tuintotaalodenzaal'),
            'value' => ( $cart_item['set_vase_selection'] == 'yes' ) ?  '<small>' . __( 'Ja', 'tuintotaalodenzaal' ) . '</small>' : '<small>' . __( 'Geen' , 'tuintotaalodenzaal') . '</small>',
        );
    }
	if( isset( $cart_item['set_vase_selection'] )  && $cart_item['set_vase_selection'] == 'yes' ){
		$data[] = array(
			'name' 	=> __('Vaasgrootte','tuintotaalodenzaal'),
            'value' => '<small>' . $cart_item['set_vase_label'] . '</small>'  ,
        );
	}
	if ( isset( $cart_item['set_delivery_date'] ) ){
		$data[] = array(
			'name' 	=> ( isset( $cart_item['set_shipping_method'] ) && 'delivery' === $cart_item['set_shipping_method'] ) ? __('Afleverdatum','tuintotaalodenzaal') : __('Afhaaldatum','tuintotaalodenzaal') ,
            'value' => $cart_item['set_delivery_date'],
        );
	}
	if ( isset( $cart_item['set_shipping_method'] ) ){
		$ship_method_lbl = ( 'delivery' === $cart_item['set_shipping_method'] ) ? "Bezorgen" : "Ophalen in de winkel";
		$data[] = array(
			'name' 	=> __('Verzendmethode','tuintotaalodenzaal'),
            'value' => '<small>' . $ship_method_lbl . '</small>',
        );
	}
	if ( isset( $cart_item['set_shipping_method'] ) && $cart_item['set_shipping_method'] == 'delivery' ){
		if( isset( $cart_item['set_delivery_address'] ) ){
			$data[] = array(
				'name' 	=> __('Afleveradres','tuintotaalodenzaal'),
				'value' => '<small>' . trim( $cart_item['set_delivery_address'] ) . '</small>',
			);
		}
		if( isset( $cart_item['set_calculated_distance'] ) ){
			$data[] = array(
				'name' 	=> __('Berekende afstand','tuintotaalodenzaal'),
				'value' => '<small>' . trim( $cart_item['set_calculated_distance'] ) . ' km' .'</small>' ,
			);
		}
		if( isset( $cart_item['set_delivery_charge'] ) ){
			$data[] = array(
				'name' 	=> __('Verzendingskosten','tuintotaalodenzaal'),
				'value' => "<small>$nls_cur_sym " . trim( $cart_item['set_delivery_charge'] ) . '</small>',
			);
		}
	}
	if( isset( $cart_item['nls_custom_message'] )  ){
		$data[] = array(
			'name' 	=> __('Ontvangend bericht','tuintotaalodenzaal'),
			'value' => '<b class="nls-open-message" data-message="'. esc_attr( $cart_item['nls_custom_message'] )  . '">Bekijken</b>' . ' (' . $nls_cur_sym . ' ' . $cart_item['set_card_msg_charge'] . ')',
		);
	}
    return $data;
}
add_filter( 'woocommerce_get_item_data', 'nls_product_add_on_display_cart', 10, 2 );

// 5. Save custom input field value into order item meta
function nls_product_add_on_order_item_meta( $item, $cart_item_key, $cart_item_val, $order  ) {
	$nls_cur_sym = get_woocommerce_currency_symbol();
	if ( ! empty( $cart_item_val['set_delivery_date'] ) ) {
		//wc_add_order_item_meta( $item_id, __('Leveringsdatum', 'tuintotaalodenzaal'), $cart_item_val['set_delivery_date'], true );
		$dele_method_lable = ( isset( $cart_item_val['set_shipping_method'] ) && 'delivery' === $cart_item_val['set_shipping_method'] ) ?  __('Leveringsdatum','tuintotaalodenzaal') : __('Afhaaldatum','tuintotaalodenzaal');
		$item->update_meta_data( $dele_method_lable, $cart_item_val['set_delivery_date'] );          
    }

	if ( isset( $cart_item_val['set_shipping_method'] ) ) {
		$ship_label = ( 'delivery' === $cart_item_val['set_shipping_method'] ) ? "Bezorgen" : "Ophalen in de winkel";
		$item->update_meta_data( __('Bezorgen/Pick-up', 'tuintotaalodenzaal'), $ship_label );          
		if( 'delivery' === $cart_item_val['set_shipping_method'] ){	
			$item->update_meta_data( __('Afleveradres', 'tuintotaalodenzaal'), $cart_item_val['set_delivery_address'] );     
			$item->update_meta_data( __('Berekende afstand', 'tuintotaalodenzaal'), $cart_item_val['set_calculated_distance'] . ' km.' );     
			$item->update_meta_data( __('Verzendingskosten', 'tuintotaalodenzaal'), $nls_cur_sym . ' ' . $cart_item_val['set_delivery_charge'] );     
		}
	}

	// /* gift product starts*/
	if ( ! empty( $cart_item_val['set_gift_card_price'] ) ) {
		$item->update_meta_data( __('Cadeaubon', 'tuintotaalodenzaal'), $nls_cur_sym . ' ' . $cart_item_val['set_gift_card_price'] );     
    }
	// /* gift product ends */

	if ( ! empty( $cart_item_val['nls_flower_size'] ) ) {
		$item->update_meta_data( __('Bloemgrootte', 'tuintotaalodenzaal'), $cart_item_val['nls_flower_size'] . " ($nls_cur_sym  $cart_item_val[set_flower_price])" );     
	}

	if ( isset( $cart_item_val['set_vase_selection'] ) && $cart_item_val['set_vase_selection'] == 'yes' ) {
		$item->update_meta_data( __('Vaasgrootte', 'tuintotaalodenzaal'), $cart_item_val['set_vase_label'] );     
    }

	if( isset( $cart_item_val['nls_custom_message'] ) ){
		$item->update_meta_data( __('Bericht voor de ontvanger', 'tuintotaalodenzaal'), $cart_item_val['nls_custom_message'] . " ($nls_cur_sym  $cart_item_val[set_card_msg_charge])" );     
	}
}
add_action( 'woocommerce_checkout_create_order_line_item', 'nls_product_add_on_order_item_meta', 20, 4 );
 

// 6. Display custom input field value into order table
function nls_product_add_on_display_order( $cart_item, $order_item ){
	if( isset( $order_item['set_delivery_date'] ) ) {
		$cart_item['set_delivery_date'] = $order_item['set_delivery_date'];
    }

	/* gift card starts */ 
	if( isset( $order_item['set_gift_card_price'] ) ) {
		$cart_item['set_gift_card_price'] =  $order_item['set_gift_card_price'];
    }
	/* gift card ends */ 

	if( isset( $order_item['nls_flower_size'] ) ) {
		$cart_item['nls_flower_size'] = $order_item['nls_flower_size'];
	}

	if ( $order_item['set_vase_label'] == 'yes' ) {
		$cart_item['set_vase_label'] = $order_item['set_vase_label'];
    }

	if ( $order_item['set_shipping_method'] === 'delivery' ) {
		$cart_item['set_delivery_address'] = $order_item['set_delivery_address'];
		$cart_item['set_calculated_distance'] = $order_item['set_calculated_distance'];
		$cart_item['set_delivery_charge'] = $order_item['set_delivery_charge'];
	}

	if( isset( $order_item['nls_custom_message'] ) ){
		$cart_item['nls_custom_message'] = $order_item['nls_custom_message'];
	}
    return $cart_item;
}
add_filter( 'woocommerce_order_item_product', 'nls_product_add_on_display_order', 10, 2 );

####103#### Woo addon-no plugin CODE SNIPPET ENDS

