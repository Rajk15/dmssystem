<?php
/**
 * Plugin Name: Knox Aluminium Content
 * Description: PDF-aligned page content, alternating image/content layouts, SEO helpers, and page sync for Knox Aluminium Windows.
 * Version: 1.2.1
 * Author: Knox Aluminium
 * Text Domain: knox-aluminium-content
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'KNOX_AC_VERSION', '1.2.1' );
define( 'KNOX_AC_FILE', __FILE__ );
define( 'KNOX_AC_DIR', plugin_dir_path( __FILE__ ) );
define( 'KNOX_AC_URL', plugin_dir_url( __FILE__ ) );

require_once KNOX_AC_DIR . 'includes/media.php';
require_once KNOX_AC_DIR . 'includes/content.php';
require_once KNOX_AC_DIR . 'includes/sync.php';
require_once KNOX_AC_DIR . 'includes/seo.php';
require_once KNOX_AC_DIR . 'includes/header.php';
require_once KNOX_AC_DIR . 'includes/quote-form.php';

/**
 * Front-end assets for alternating layouts.
 */
function knox_ac_enqueue_assets() {
	if ( is_admin() ) {
		return;
	}
	wp_enqueue_style(
		'knox-aluminium-content',
		KNOX_AC_URL . 'assets/knox-content.css',
		array(),
		KNOX_AC_VERSION
	);
}
add_action( 'wp_enqueue_scripts', 'knox_ac_enqueue_assets' );

/**
 * Admin tools.
 */
function knox_ac_admin_menu() {
	add_management_page(
		'Knox Content Sync',
		'Knox Content Sync',
		'manage_options',
		'knox-aluminium-content',
		'knox_ac_admin_page'
	);
}
add_action( 'admin_menu', 'knox_ac_admin_menu' );

function knox_ac_admin_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$result = null;
	if ( isset( $_POST['knox_ac_sync'] ) && check_admin_referer( 'knox_ac_sync' ) ) {
		$result = knox_ac_sync_all();
	}
	if ( isset( $_POST['knox_ac_header'] ) && check_admin_referer( 'knox_ac_sync' ) ) {
		$result = knox_ac_apply_home_header_everywhere();
	}
	if ( isset( $_POST['knox_ac_quote'] ) && check_admin_referer( 'knox_ac_sync' ) ) {
		$result = knox_ac_sync_quote_page();
	}

	echo '<div class="wrap"><h1>Knox Aluminium Content Sync</h1>';
	echo '<p>Creates/updates PDF-listed pages, alternating layouts, SEO meta, and repairs primary navigation.</p>';
	echo '<p>Header tool assigns the homepage <strong>Home-1-header</strong> (wdt_headers #15461) to all public content so inner pages share the same topbar, logo, menu, and CTAs.</p>';
	echo '<p>Quote tool creates/updates the Contact Form 7 <strong>Request a Free Rough Quote</strong> form and dedicated page.</p>';
	if ( is_array( $result ) ) {
		echo '<div class="notice notice-success"><p><strong>Sync complete.</strong></p><ul>';
		foreach ( $result as $line ) {
			echo '<li>' . esc_html( $line ) . '</li>';
		}
		echo '</ul></div>';
	}
	echo '<form method="post">';
	wp_nonce_field( 'knox_ac_sync' );
	echo '<p><button type="submit" name="knox_ac_sync" class="button button-primary">Run content sync</button> ';
	echo '<button type="submit" name="knox_ac_header" class="button">Apply homepage header sitewide</button> ';
	echo '<button type="submit" name="knox_ac_quote" class="button">Sync rough quote form</button></p>';
	echo '</form></div>';
}

/**
 * REST trigger for authenticated editors (used by deploy script).
 */
function knox_ac_register_rest() {
	$can = function () {
		return current_user_can( 'manage_options' );
	};
	register_rest_route(
		'knox-ac/v1',
		'/sync',
		array(
			'methods'             => 'POST',
			'permission_callback' => $can,
			'callback'            => function () {
				return rest_ensure_response( knox_ac_sync_all() );
			},
		)
	);
	register_rest_route(
		'knox-ac/v1',
		'/apply-header',
		array(
			'methods'             => 'POST',
			'permission_callback' => $can,
			'callback'            => function () {
				return rest_ensure_response( knox_ac_apply_home_header_everywhere() );
			},
		)
	);
	register_rest_route(
		'knox-ac/v1',
		'/sync-quote',
		array(
			'methods'             => 'POST',
			'permission_callback' => $can,
			'callback'            => function () {
				return rest_ensure_response( knox_ac_sync_quote_page() );
			},
		)
	);
}
add_action( 'rest_api_init', 'knox_ac_register_rest' );
