<?php
// private backend only post type
function zest_custom_post_type(){
    $labels = array(
        'name'                => _x('Votes', 'Post Type General Name', 'multi-task'),
        'singular_name'       => _x('Vote', 'Post Type Singular Name', 'multi-task'),
        'menu_name'           => __('Votes', 'multi-task'),
        'parent_item_colon'   => __('Parent vote', 'multi-task'),
        'all_items'           => __('All Votes', 'multi-task'),
        'view_item'           => __('View vote', 'multi-task'),
        'add_new_item'        => __('Add New vote', 'multi-task'),
        'add_new'             => __('Add New', 'multi-task'),
        'edit_item'           => __('Edit vote', 'multi-task'),
        'update_item'         => __('Update vote', 'multi-task'),
        'search_items'        => __('Search vote', 'multi-task'),
        'not_found'           => __('Not Found', 'multi-task'),
        'not_found_in_trash'  => __('Not found in Trash', 'multi-task'),
    );
    $args = array(
        'label'               => __('Votes', 'multi-task'),
        'description'         => __('vote and details', 'multi-task'),
        'labels'              => $labels,
        'supports'            => array('title'),
        'hierarchical'        => false,
        'public'              => false,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => false,
        'show_in_admin_bar'   => true,
        'menu_position'       => 5,
        'menu_icon'           => 'dashicons-book',
        'can_export'          => true,
        'has_archive'         => false,
        'exclude_from_search' => false,
        'publicly_queryable'  => false,
        'map_meta_cap'        => true,
        'capabilities' => array(
            'create_posts'       => 'do_not_allow',
        ),
        'show_in_rest'        => false,
    );
    register_post_type('zest-hidden', $args);
}
add_action('init', 'zest_custom_post_type');

// add report sub page for votes
add_action('admin_menu', 'zest_vote_report_page_register');

function zest_vote_report_page_register(){
    add_submenu_page(
        'edit.php?post_type=zest-hidden',
        __( 'Vote Report', 'multi-task' ),
        __( 'Vote Report', 'multi-task' ),
        'manage_options',
        'zest-vote-report',
        'zest_vote_report_page_html'
    );
}
function zest_vote_report_page_html(){
    include  ZEST_PATH . 'src/inc/admin/zest-vote-report.php';
}

// adding columns in CPT
add_filter( 'manage_zest-hidden_posts_columns', 'zest_filter_posts_columns' );
function zest_filter_posts_columns( $columns ) {
    $custom_col = [];
    if( ! empty( $columns ) ){
        foreach( $columns as $key => $label ){
            $custom_col[$key] = $label;
            if( 'title' == $key ){
                $custom_col['vote-details'] = __( 'Vote Details', 'multi-task' );
                $custom_col['personal-details'] = __( 'Personal Details', 'multi-task' );
            }
        }
    }
    return $custom_col;
}

//  add data in column
add_action( 'manage_zest-hidden_posts_custom_column', 'zest_column_data', 10, 2);
function zest_column_data( $column, $post_id ) {
    $content = get_the_content($post_id);
    $cnvrt_to_ary = unserialize( $content );
    $p_details = ( isset( $cnvrt_to_ary['personal_data'] ) ) ? $cnvrt_to_ary['personal_data'] : array();
    $vote_data = ( isset( $cnvrt_to_ary['vote_data'] ) ) ? $cnvrt_to_ary['vote_data'] : array();
    if ( 'vote-details' == $column ) {
        if( ! empty( $vote_data )  ){
            foreach ($vote_data as $key => $value) {
                $vote_ary = explode('--',$value);
                echo "<b>$vote_ary[1]</b>: $vote_ary[2]<br>";
            }
        }
    }
    if( 'personal-details' == $column ){
        if( ! empty( $p_details )  ){
            foreach ($p_details as $key => $p_value) {
                $p_vote_ary = explode('--',$p_value);
                echo "<b>$p_vote_ary[1]</b>: $p_vote_ary[2]<br>";
            }
        }
    }
}

// generate otp ajax
function zest_generate_opt_callback(){
    $resp['status'] = $resp['message'] = 'false';
    $nonce = $_POST['vote-nonce'];
    if (!wp_verify_nonce($nonce, 'vote-nonce')) {
        $resp['status'] = 'fail';
        $resp['message'] = __('You may need to reload the page and submit the vote once again!', 'multi-task');
        wp_send_json($resp);
    }
    $email = (isset($_POST['user-email']) && '' !== trim($_POST['user-email']) && is_email($_POST['user-email'])) ?  sanitize_email($_POST['user-email']) : false;
    if (false === $email) {
        $resp['status'] =  'fail';
        $resp['message'] = __('Please provide a valid email address!', 'multi-task');
        wp_send_json($resp);
    } else {
        global $wpdb;
        $table_name = $wpdb->prefix . 'zest_otp_log';
        $prep_query = $wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE email=%s", array($email));
        $email_exist = (int)$wpdb->get_var($prep_query);
        $randomDigit = random_int(111111, 9999999);
        if (0 === $email_exist) {
            $curren_timestemp = date('Y-m-d H:i:s');
            $prep_insert_query = $wpdb->prepare("INSERT INTO $table_name (`email`, `otp`, `otp_timestamp`) VALUES(%s, %d, %s)", array($email, $randomDigit, $curren_timestemp));
            $is_inserted = $wpdb->query($prep_insert_query);
            $resp['is_row_inserted'] = ($is_inserted) ? 'yes' : 'no';
        } else {
            $curren_timestemp = date('Y-m-d H:i:s');
            $resp['update_otp'] =  $randomDigit;
            $prep_updt_query = $wpdb->prepare("UPDATE $table_name SET otp = %s, otp_timestamp = %s  WHERE email=%s", array($randomDigit, $curren_timestemp, $email));
            $is_updated = $wpdb->query($prep_updt_query);
            $resp['is_row_updated'] = ($is_updated) ? 'yes' : 'no';
        }
        $message = '';
        ob_start(); ?>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title><?php _e('OTP verification', 'multi-task') ?></title>
        </head>

        <body>
            <div style="text-align: center;">
                <p><?php _e('Your OTP for voting is given below:', 'multi-task') ?></p>
                <p><?php echo $randomDigit;  ?></p>
            </div>
        </body>

        </html>
    <?php
        $message = ob_get_clean();
        wp_mail($email, 'OTP verification', $message, array('Content-Type: text/html; charset=UTF-8'));
        $resp['status'] = 'done';
        $resp['message'] = __('OTP is sent to your email address. Please check your inbox and verify.');
    }
    wp_send_json($resp);
}
add_action('wp_ajax_zest_generate_opt', 'zest_generate_opt_callback');
add_action('wp_ajax_nopriv_zest_generate_opt', 'zest_generate_opt_callback');

// verify otp ajax
function zest_verify_otp_callback(){
    $resp['status'] = $resp['message'] = 'false';
    $nonce = $_POST['vote-nonce'];
    $otp =  (isset($_POST['otp-verification']) && '' !== trim($_POST['otp-verification'])) ? sanitize_text_field($_POST['otp-verification']) : '';
    if (!wp_verify_nonce($nonce, 'vote-nonce')) {
        $resp['status'] = 'fail';
        $resp['message'] = __('You may need to reload the page and submit the vote once again!', 'multi-task');
        wp_send_json($resp);
    }
    $email = (isset($_POST['user-email']) && '' !== trim($_POST['user-email']) && is_email($_POST['user-email'])) ?  sanitize_email($_POST['user-email']) : false;
    if (false === $email) {
        $resp['status'] =  'fail';
        $resp['message'] = __('Please provide a valid email address!', 'multi-task');
        wp_send_json($resp);
    } elseif ('' === $otp) {
        $resp['status'] =  'fail';
        $resp['message'] = __('Please enter a valid OTP!', 'multi-task');
        wp_send_json($resp);
    } else {
        global $wpdb;
        $table_name = $wpdb->prefix . 'zest_otp_log';
        $prep_query = $wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE email=%s AND otp=%s", array($email, $otp));
        $is_otp_verified = (int)$wpdb->get_var($prep_query);
        if (0 !== $is_otp_verified) {
            $resp['status'] =  'done';
            $resp['message'] = __('OTP Verified', 'multi-task');
        } else {
            $resp['status'] =  'fail';
            $resp['message'] = __('Invalid OTP', 'multi-task');
        }
    }
    wp_send_json($resp);
}
add_action('wp_ajax_zest_verify_otp', 'zest_verify_otp_callback');
add_action('wp_ajax_nopriv_zest_verify_otp', 'zest_verify_otp_callback');

function zest_submit_vote_callback(){
    $resp['status'] = $resp['message'] = 'false';
    $otp_verified = (isset($_POST['is-otp-verified']) && $_POST['is-otp-verified'] === 'yes') ? true : false;
    if (false === $otp_verified) {
        $resp['status'] = 'fail';
        $resp['message'] = __('Please verify the otp first', 'multi-task');
        wp_send_json( $resp );
    }
    $full_name = (isset($_POST['full-name']) && '' !== trim($_POST['full-name'])) ? sanitize_text_field($_POST['full-name']) : '';
    $member_id = (isset($_POST['is-otp-verified']) && '' !== trim($_POST['is-otp-verified'])) ? sanitize_text_field($_POST['is-otp-verified']) : '';
    $member_email = (isset($_POST['user-email']) && '' !== trim($_POST['user-email'])) ? sanitize_email($_POST['user-email']) : '';
    $member_phone = (isset($_POST['user-phone']) && '' !== trim($_POST['user-phone'])) ? sanitize_text_field($_POST['user-phone']) : '';
    $john_w = (isset($_POST['op-id-user-1']) && ('yes' === $_POST['op-id-user-1'] || 'no' === $_POST['op-id-user-1'])) ?  sanitize_text_field($_POST['op-id-user-1']) : 'no';
    $adheera = (isset($_POST['op-id-user-2']) && ('yes' === $_POST['op-id-user-2'] || 'no' === $_POST['op-id-user-2'])) ?  sanitize_text_field($_POST['op-id-user-2']) : 'no';
    $klaas = (isset($_POST['only-one-user']) && 'user-3' === $_POST['only-one-user'])  ?  'yes' : 'no';
    $aleksa = (isset($_POST['only-one-user']) && 'user-4' === $_POST['only-one-user'])  ?  'yes' : 'no';

    if ('' === $full_name || '' === $member_id || '' === $member_email || '' === $member_phone) {
        $resp['status'] = 'fail';
        $resp['message'] = __('Missing personal details', 'multi-task');
        wp_send_json($resp);
    } else {
        //$get_post_count = array_sum((array) wp_count_posts('zest-hidden') );
        $customized_content = array(
            'vote_data' => array(
                "--John Wick--$john_w--",
                "--Adheera--$adheera--",
                "--Klaas--$klaas--",
                "--Aleksa--$aleksa--",
            ),
            'personal_data' => array(
                "--Email--$member_email--",
                "--Phone--$member_phone--",
                "--Member id--$member_id--",
                "--Full Name--$full_name--",
            )
        );
        $serialized_data = serialize($customized_content);
        $post_data = array(
            'post_title'    => "candidate-Nill",
            'post_content'  => $serialized_data,
            'post_status'   => 'publish',
            'post_type'     => 'zest-hidden'
        );
        $post_id = wp_insert_post($post_data);
        if (!is_wp_error($post_id)) {
            wp_update_post(array(
                'ID'         => $post_id,
                'post_title' => "candidate-$post_id"
            ));
        } else {
            $resp['status'] = 'fail';
            $error_message = $post_id->get_error_message();
            $resp['message']    = 'Error: ' . $error_message;
        }
        $resp['status']     = 'done';
        $resp['message']    = __('Vote submitted', 'multi-task');
        $resp['added_data'] = $customized_content;
    }
    wp_send_json($resp);
}
add_action('wp_ajax_zest_submit_vote', 'zest_submit_vote_callback');
add_action('wp_ajax_nopriv_zest_submit_vote', 'zest_submit_vote_callback');
