<?php
/**
 * Plugin Name:       Multi Task
 * Description:       This is just for the submission of a task.
 * Requires at least: 6.1
 * Requires PHP:      7.0
 * Version:           0.1.0
 * Author:            The WordPress Contributors
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       multi-task
 *
 * @package           create-block
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */

class MultiDefaultAction{
	public function __construct(){
		define( 'ZEST_PATH', plugin_dir_path( __FILE__ ) );
		define( 'ZEST_URL', plugin_dir_url( __FILE__ ) );
		add_action( 'init', array( $this, 'multi_task_multi_task_block_init' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'multi_task_load_scripts' ) );
		register_activation_hook( __FILE__, array( $this, 'multi_task_on_plugin_activate' ) );
		include  ZEST_PATH . 'src/inc/admin/admin-functions.php';
		include  ZEST_PATH . 'src/inc/front/front-functions.php'; 
	}

	public function multi_task_multi_task_block_init() {
		register_block_type( __DIR__ . '/build' );
	}

	public function  multi_task_load_scripts(){
		if( has_block('create-block/multi-task') ){
			wp_enqueue_script('multi-script', ZEST_URL . 'src/assets/js/custom-script.js' ,array('jquery'), 1.0 , true);
			wp_localize_script( 'multi-script', 'ajax_obj', array( 'ajax_url' => admin_url( 'admin-ajax.php' )));
		}
	}
	
	public function multi_task_on_plugin_activate(){
		global $wpdb;
	
		$tblname = 'zest_otp_log';
		$wp_track_table = $wpdb->prefix . "$tblname";
		$charset_collate = $wpdb->get_charset_collate();
		
		$sql = "CREATE TABLE IF NOT EXISTS $wp_track_table ( ";
		$sql .= "  `ID`  int(11)   NOT NULL AUTO_INCREMENT PRIMARY KEY, ";
		$sql .= "  `email`  varchar(255) NOT NULL, ";
		$sql .= "  `otp`  varchar(255) NOT NULL, ";
		$sql .= "  `otp_timestamp` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' "; 
		$sql .= ") ". $charset_collate .";";
		require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
		dbDelta($sql);
		add_submenu_page(
			'edit.php?post_type=zest-hidden',
			__( 'Vote Report', 'multi-task' ),
			__( 'Vote Report', 'multi-task' ),
			'manage_options',
			'zest-vote-report',
			array($this,'zest_vote_report_page_html'), 'dashicons-analytics'
		);
	}

	public function zest_vote_report_page_html(){
		
	}
}
new MultiDefaultAction();