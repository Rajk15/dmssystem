<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Disable Elementor builder rendering so post_content is used.
 */
function knox_ac_disable_elementor( $post_id ) {
	delete_post_meta( $post_id, '_elementor_edit_mode' );
	delete_post_meta( $post_id, '_elementor_template_type' );
	delete_post_meta( $post_id, '_elementor_data' );
	delete_post_meta( $post_id, '_elementor_css' );
	update_post_meta( $post_id, '_wp_page_template', 'default' );
}

/**
 * Backup current content before overwrite.
 */
function knox_ac_backup_page( $post_id ) {
	$post = get_post( $post_id );
	if ( ! $post ) {
		return;
	}
	$payload = array(
		'ID'           => $post_id,
		'title'        => $post->post_title,
		'slug'         => $post->post_name,
		'content'      => $post->post_content,
		'elementor'    => get_post_meta( $post_id, '_elementor_data', true ),
		'backed_up_at' => gmdate( 'c' ),
	);
	update_post_meta( $post_id, '_knox_ac_backup_' . time(), wp_json_encode( $payload ) );
	update_option( 'knox_ac_last_backup_' . $post_id, $payload, false );
}

function knox_ac_ensure_locations_parent() {
	$existing = get_page_by_path( 'locations' );
	if ( $existing ) {
		return (int) $existing->ID;
	}
	$id = wp_insert_post(
		array(
			'post_type'    => 'page',
			'post_status'  => 'publish',
			'post_title'   => 'Locations',
			'post_name'    => 'locations',
			'post_content' => '<div class="knox-page"><section class="knox-section"><div class="knox-wrap"><h1>Service Locations</h1><p>Knox Aluminium supplies and installs premium aluminium windows and doors across Melbourne\'s eastern suburbs and surrounds.</p></div></section></div>',
		),
		true
	);
	if ( is_wp_error( $id ) ) {
		return 0;
	}
	knox_ac_set_seo(
		$id,
		'Aluminium Windows & Doors Locations | Knox Aluminium',
		'Service locations for Knox Aluminium Windows & Doors across Melbourne suburbs.'
	);
	return (int) $id;
}

function knox_ac_upsert_page( $def ) {
	$content = is_callable( $def['content_cb'] ) ? call_user_func( $def['content_cb'] ) : '';
	$post_id = 0;
	$action  = 'created';

	if ( ! empty( $def['existing_id'] ) ) {
		$post_id = (int) $def['existing_id'];
		if ( get_post( $post_id ) ) {
			knox_ac_backup_page( $post_id );
			knox_ac_disable_elementor( $post_id );
			wp_update_post(
				array(
					'ID'           => $post_id,
					'post_title'   => $def['title'],
					'post_content' => $content,
					'post_status'  => 'publish',
				)
			);
			$action = 'updated';
		}
	}

	if ( ! $post_id && ! empty( $def['restore_id'] ) ) {
		$restore_id = (int) $def['restore_id'];
		$trashed    = get_post( $restore_id );
		if ( $trashed && 'trash' === $trashed->post_status ) {
			wp_untrash_post( $restore_id );
			knox_ac_backup_page( $restore_id );
			knox_ac_disable_elementor( $restore_id );
			wp_update_post(
				array(
					'ID'           => $restore_id,
					'post_title'   => $def['title'],
					'post_name'    => $def['slug'],
					'post_content' => $content,
					'post_status'  => 'publish',
				)
			);
			$post_id = $restore_id;
			$action  = 'restored';
		}
	}

	if ( ! $post_id ) {
		$path = $def['slug'];
		if ( ! empty( $def['parent_slug'] ) ) {
			$path = trailingslashit( $def['parent_slug'] ) . $def['slug'];
		}
		$by_slug = get_page_by_path( $path );
		if ( ! $by_slug && ! empty( $def['parent_slug'] ) ) {
			// Fallback: find by slug among children of locations parent.
			$parent = get_page_by_path( $def['parent_slug'] );
			if ( $parent ) {
				$children = get_children(
					array(
						'post_parent' => $parent->ID,
						'post_type'   => 'page',
						'post_status' => 'publish',
						'numberposts' => 100,
					)
				);
				foreach ( $children as $child ) {
					if ( $child->post_name === $def['slug'] ) {
						$by_slug = $child;
						break;
					}
				}
			}
		}
		if ( $by_slug ) {
			$post_id = (int) $by_slug->ID;
			knox_ac_backup_page( $post_id );
			knox_ac_disable_elementor( $post_id );
			wp_update_post(
				array(
					'ID'           => $post_id,
					'post_title'   => $def['title'],
					'post_content' => $content,
					'post_status'  => 'publish',
				)
			);
			$action = 'updated';
		}
	}

	if ( ! $post_id ) {
		$parent = 0;
		if ( ! empty( $def['parent_slug'] ) ) {
			$parent = knox_ac_ensure_locations_parent();
		}
		$post_id = wp_insert_post(
			array(
				'post_type'    => 'page',
				'post_status'  => 'publish',
				'post_title'   => $def['title'],
				'post_name'    => $def['slug'],
				'post_content' => $content,
				'post_parent'  => $parent,
			),
			true
		);
		if ( is_wp_error( $post_id ) ) {
			return 'ERROR ' . $def['slug'] . ': ' . $post_id->get_error_message();
		}
		knox_ac_disable_elementor( $post_id );
		$action = 'created';
	}

	if ( ! empty( $def['seo_title'] ) || ! empty( $def['seo_desc'] ) ) {
		knox_ac_set_seo( $post_id, $def['seo_title'], $def['seo_desc'] );
	}

	// Ensure front page stays Home-windoor.
	if ( ! empty( $def['is_front'] ) ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $post_id );
	}

	$link = get_permalink( $post_id );
	return sprintf( '%s #%d %s → %s', strtoupper( $action ), $post_id, $def['title'], $link );
}

/**
 * Rebuild primary navigation to PDF structure.
 */
function knox_ac_fix_menu() {
	$menu_name = 'Main Menu';
	$menu      = wp_get_nav_menu_object( $menu_name );
	if ( ! $menu ) {
		$menus = wp_get_nav_menus();
		$menu  = $menus ? $menus[0] : null;
	}
	if ( ! $menu ) {
		$menu_id = wp_create_nav_menu( $menu_name );
	} else {
		$menu_id = (int) $menu->term_id;
		$items   = wp_get_nav_menu_items( $menu_id );
		if ( $items ) {
			foreach ( $items as $item ) {
				wp_delete_post( $item->ID, true );
			}
		}
	}

	$structure = array(
		array( 'Home', '/' ),
		array( 'About Us', '/about-us/' ),
		array( 'Windows', '/windows/' ),
		array( 'Doors', '/doors/' ),
		array( 'Commercial Projects', '/commercial-projects/' ),
		array( 'Process', '/process/' ),
		array( 'Gallery', '/project-gallery/' ),
		array( 'Contact Us', '/contact-us/' ),
		array( 'Request a Free Quote', '/contact-us/' ),
	);

	$position = 1;
	foreach ( $structure as $row ) {
		wp_update_nav_menu_item(
			$menu_id,
			0,
			array(
				'menu-item-title'  => $row[0],
				'menu-item-url'    => home_url( $row[1] ),
				'menu-item-status' => 'publish',
				'menu-item-type'   => 'custom',
				'menu-item-position' => $position++,
			)
		);
	}

	$locations = get_theme_mod( 'nav_menu_locations' );
	if ( ! is_array( $locations ) ) {
		$locations = array();
	}
	// Common Windoor location keys.
	foreach ( array( 'main-menu', 'primary', 'header_menu', 'mainmenu' ) as $loc ) {
		$locations[ $loc ] = $menu_id;
	}
	set_theme_mod( 'nav_menu_locations', $locations );

	return 'MENU rebuilt (' . $menu_name . ' ID ' . $menu_id . ')';
}

/**
 * Clear contact-us redirect pointing at trashed slug if present.
 */
function knox_ac_fix_redirects() {
	$lines = array();
	// Redirection plugin store.
	global $wpdb;
	if ( $wpdb->get_var( "SHOW TABLES LIKE '{$wpdb->prefix}redirection_items'" ) === $wpdb->prefix . 'redirection_items' ) {
		$deleted = $wpdb->query(
			$wpdb->prepare(
				"DELETE FROM {$wpdb->prefix}redirection_items WHERE url LIKE %s OR action_data LIKE %s",
				'%contact-us%',
				'%pages__trashed%'
			)
		);
		$lines[] = 'Redirection rows cleared: ' . (int) $deleted;
	}

	// Safe Redirect Manager.
	$srm = get_posts(
		array(
			'post_type'      => 'redirect_rule',
			'posts_per_page' => 50,
			's'              => 'contact-us',
			'post_status'    => 'any',
		)
	);
	foreach ( $srm as $rule ) {
		wp_delete_post( $rule->ID, true );
		$lines[] = 'Deleted SRM rule #' . $rule->ID;
	}

	if ( ! $lines ) {
		$lines[] = 'No dedicated redirect plugin rows found (WP may still remap old trash slugs).';
	}
	return $lines;
}

function knox_ac_sync_all() {
	$log   = array();
	$defs  = knox_ac_page_definitions();

	// Ensure locations parent first.
	$parent = knox_ac_ensure_locations_parent();
	$log[]  = 'Locations parent #' . $parent;

	foreach ( $defs as $key => $def ) {
		$log[] = knox_ac_upsert_page( $def );
	}

	$log[] = knox_ac_fix_menu();
	$log   = array_merge( $log, knox_ac_fix_redirects() );
	$log   = array_merge( $log, knox_ac_apply_home_header_everywhere() );

	// Flush caches.
	if ( function_exists( 'flush_rewrite_rules' ) ) {
		flush_rewrite_rules( false );
	}
	if ( class_exists( '\Elementor\Plugin' ) ) {
		try {
			\Elementor\Plugin::$instance->files_manager->clear_cache();
		} catch ( Exception $e ) { // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedCatch
		}
	}

	update_option( 'knox_ac_last_sync', array( 'at' => gmdate( 'c' ), 'log' => $log ), false );
	$log[] = 'Sync finished at ' . gmdate( 'c' );
	return $log;
}
