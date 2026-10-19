<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Preferred media IDs already in the Media Library (company + product imagery).
 */
function knox_ac_media_map() {
	return array(
		'project_1'   => 26005, // IMG_2626
		'project_2'   => 26001, // IMG_2650
		'project_3'   => 26000, // IMG_2472
		'project_4'   => 25995, // IMG_2192
		'service_1'   => 25736,
		'service_2'   => 25737,
		'service_3'   => 25738,
		'service_4'   => 25739,
		'landing_1'   => 25701,
		'landing_2'   => 25702,
		'landing_3'   => 25703,
		'landing_4'   => 25704,
		'landing_5'   => 25705,
		'landing_6'   => 25706,
		'landing_7'   => 25707,
		'landing_8'   => 25709,
		'landing_9'   => 25710,
		'casement'    => 21123,
		'cascading'   => 20029,
	);
}

/**
 * Render a responsive image tag from attachment ID.
 */
function knox_ac_img( $key, $alt = '', $lazy = true ) {
	$map = knox_ac_media_map();
	$id  = isset( $map[ $key ] ) ? (int) $map[ $key ] : 0;
	if ( ! $id || ! wp_attachment_is_image( $id ) ) {
		return '';
	}

	$attr = array(
		'class'   => 'knox-img',
		'alt'     => $alt,
		'loading' => $lazy ? 'lazy' : 'eager',
		'decoding'=> 'async',
	);

	$html = wp_get_attachment_image( $id, 'large', false, $attr );
	return $html ? $html : '';
}

function knox_ac_figure( $key, $alt = '', $lazy = true ) {
	$img = knox_ac_img( $key, $alt, $lazy );
	if ( ! $img ) {
		return '';
	}
	return '<figure>' . $img . '</figure>';
}
