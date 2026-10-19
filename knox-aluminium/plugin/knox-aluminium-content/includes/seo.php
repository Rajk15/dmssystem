<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Store SEO strings in post meta and AIOSEO-compatible fields where possible.
 */
function knox_ac_set_seo( $post_id, $title, $description ) {
	$post_id = (int) $post_id;
	update_post_meta( $post_id, '_knox_ac_seo_title', $title );
	update_post_meta( $post_id, '_knox_ac_seo_desc', $description );

	// Common meta keys used by AIOSEO / compatibility layers.
	update_post_meta( $post_id, '_aioseo_title', $title );
	update_post_meta( $post_id, '_aioseo_description', $description );
	update_post_meta( $post_id, '_aioseop_title', $title );
	update_post_meta( $post_id, '_aioseop_description', $description );

	if ( class_exists( '\AIOSEO\Plugin\Common\Models\Post' ) ) {
		try {
			$aioseo_post = \AIOSEO\Plugin\Common\Models\Post::getPost( $post_id );
			if ( $aioseo_post ) {
				$aioseo_post->title       = $title;
				$aioseo_post->description = $description;
				$aioseo_post->save();
			}
		} catch ( Exception $e ) { // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedCatch
			// Fall back to wp_head filters below.
		}
	}
}

/**
 * Front-end title/description fallback when AIOSEO does not pick up imported meta.
 */
function knox_ac_filter_document_title( $title ) {
	if ( ! is_singular( 'page' ) ) {
		return $title;
	}
	$custom = get_post_meta( get_the_ID(), '_knox_ac_seo_title', true );
	return $custom ? $custom : $title;
}
add_filter( 'pre_get_document_title', 'knox_ac_filter_document_title', 20 );

function knox_ac_output_meta_description() {
	if ( ! is_singular( 'page' ) ) {
		return;
	}
	// Avoid duplicate tags when AIOSEO is already outputting a description.
	if ( defined( 'AIOSEO_VERSION' ) || class_exists( 'AIOSEO\\Plugin\\AIOSEO' ) ) {
		return;
	}
	$desc = get_post_meta( get_the_ID(), '_knox_ac_seo_desc', true );
	if ( ! $desc ) {
		return;
	}
	echo '<meta name="description" content="' . esc_attr( $desc ) . '" />' . "\n";
}
add_action( 'wp_head', 'knox_ac_output_meta_description', 1 );

/**
 * Body class for pages managed by this plugin.
 */
function knox_ac_body_class( $classes ) {
	if ( is_singular( 'page' ) && get_post_meta( get_the_ID(), '_knox_ac_seo_title', true ) ) {
		$classes[] = 'knox-ac-page';
	}
	return $classes;
}
add_filter( 'body_class', 'knox_ac_body_class' );
