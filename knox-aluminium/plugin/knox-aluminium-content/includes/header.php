<?php
/**
 * Shared site header: force the homepage Windoor header (Home-1-header) everywhere.
 *
 * Homepage uses wdt_headers ID 15461. Inner pages left the page-level header
 * unset and fell back to an empty header-11 placeholder.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Homepage / sitewide custom header post ID (wdt_headers: Home-1-header). */
define( 'KNOX_AC_HOME_HEADER_ID', '15461' );

/**
 * Merge Home-1-header into a Windoor custom settings array.
 *
 * @param mixed $settings Existing settings.
 * @return array
 */
function knox_ac_settings_with_home_header( $settings ) {
	if ( ! is_array( $settings ) ) {
		$settings = array();
	}
	$settings['show-header'] = '1';
	$settings['header']      = KNOX_AC_HOME_HEADER_ID;
	return $settings;
}

/**
 * Front-end: if a singular object has empty/missing custom header, use Home-1-header.
 * Avoids empty header-11 shells without inventing a second header markup.
 */
function knox_ac_filter_custom_settings_meta( $value, $object_id, $meta_key, $single ) {
	if ( '_windoor_custom_settings' !== $meta_key ) {
		return $value;
	}

	// Allow native reads inside this callback.
	remove_filter( 'get_post_metadata', 'knox_ac_filter_custom_settings_meta', 10 );
	$stored = get_metadata( 'post', $object_id, $meta_key, true );
	add_filter( 'get_post_metadata', 'knox_ac_filter_custom_settings_meta', 10, 4 );

	$post_type = get_post_type( $object_id );
	// Never rewrite the header/footer CPT entries themselves.
	if ( in_array( $post_type, array( 'wdt_headers', 'wdt_footers', 'wdt_mega_menus', 'elementor_library' ), true ) ) {
		return null; // Fall through to core meta read.
	}

	$settings = knox_ac_settings_with_home_header( $stored );

	/*
	 * WordPress get_metadata_raw(): when the filter returns an array and
	 * $single is true, core returns $check[0]. Always wrap the associative
	 * settings array so [0] is the full settings value.
	 */
	return array( $settings );
}
add_filter( 'get_post_metadata', 'knox_ac_filter_custom_settings_meta', 10, 4 );

/**
 * Persist Home-1-header on all public content so the page editor matches front-end.
 *
 * @return string[] Log lines.
 */
function knox_ac_apply_home_header_everywhere() {
	$log   = array();
	$types = get_post_types( array( 'public' => true ), 'names' );
	unset( $types['wdt_headers'], $types['wdt_footers'], $types['wdt_mega_menus'], $types['attachment'] );

	$q = new WP_Query(
		array(
			'post_type'              => array_values( $types ),
			'post_status'            => array( 'publish', 'private', 'draft' ),
			'posts_per_page'         => -1,
			'fields'                 => 'ids',
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
		)
	);

	$updated = 0;
	foreach ( $q->posts as $post_id ) {
		// Read raw meta without our filter rewriting it mid-save.
		remove_filter( 'get_post_metadata', 'knox_ac_filter_custom_settings_meta', 10 );
		$stored = get_post_meta( $post_id, '_windoor_custom_settings', true );
		add_filter( 'get_post_metadata', 'knox_ac_filter_custom_settings_meta', 10, 4 );

		$next = knox_ac_settings_with_home_header( $stored );
		if ( $stored === $next ) {
			continue;
		}
		update_post_meta( $post_id, '_windoor_custom_settings', $next );
		$updated++;
	}

	$log[] = sprintf( 'HEADER assigned Home-1-header (%s) on %d content items', KNOX_AC_HOME_HEADER_ID, $updated );

	// Sitewide theme option keys used by Codestar / Windoor (best-effort).
	$option_keys = array(
		'_windoor_cs_options',
		'windoor_cs_options',
		'_cs_options',
		'windoor-settings',
	);
	foreach ( $option_keys as $key ) {
		$opts = get_option( $key );
		if ( ! is_array( $opts ) ) {
			continue;
		}
		$changed = false;
		foreach ( array( 'header', 'custom-header', 'site-header', 'page-header', 'standard-header' ) as $field ) {
			if ( array_key_exists( $field, $opts ) ) {
				$opts[ $field ] = KNOX_AC_HOME_HEADER_ID;
				$changed        = true;
			}
			// Nested page-options style.
			if ( isset( $opts['page-options'] ) && is_array( $opts['page-options'] ) && array_key_exists( $field, $opts['page-options'] ) ) {
				$opts['page-options'][ $field ] = KNOX_AC_HOME_HEADER_ID;
				$changed = true;
			}
		}
		if ( $changed ) {
			update_option( $key, $opts, false );
			$log[] = 'HEADER updated theme option ' . $key;
		}
	}

	update_option( 'knox_ac_home_header_id', KNOX_AC_HOME_HEADER_ID, false );
	$log[] = 'HEADER shared component: wdt_headers#' . KNOX_AC_HOME_HEADER_ID . ' (Home-1-header)';
	return $log;
}
