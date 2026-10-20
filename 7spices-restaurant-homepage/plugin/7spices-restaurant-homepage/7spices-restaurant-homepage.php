<?php
/**
 * Plugin Name: 7 Spices Restaurant Homepage
 * Description: Premium restaurant-style homepage for 7 Spices. Uses existing WooCommerce menu categories and dishes. Replaces the front page only.
 * Version: 1.0.1
 * Author: 7 Spices
 * Requires Plugins: woocommerce
 * Text Domain: 7spices-restaurant-homepage
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SEVENSPICES_HOME_VERSION', '1.0.1' );
define( 'SEVENSPICES_HOME_DIR', plugin_dir_path( __FILE__ ) );
define( 'SEVENSPICES_HOME_URL', plugin_dir_url( __FILE__ ) );

/**
 * Working site URLs.
 *
 * @return array<string,string>
 */
function sevenspices_home_urls() {
	$shop = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : '';
	$cart = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'cart' ) : '';

	$account_page = get_page_by_path( 'my-account' );
	$account      = $account_page ? get_permalink( $account_page ) : ( function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : home_url( '/index.php/my-account/' ) );

	return array(
		'home'    => home_url( '/' ),
		'shop'    => $shop ? $shop : home_url( '/index.php/shop/' ),
		'cart'    => $cart ? $cart : home_url( '/index.php/cart/' ),
		'account' => $account ? $account : home_url( '/index.php/my-account/' ),
		'contact' => home_url( '/index.php/pages/contact-us/' ),
		'about'   => home_url( '/index.php/pages/about-us/' ),
	);
}

/**
 * Non-empty product categories.
 *
 * @param int $limit Limit.
 * @return WP_Term[]
 */
function sevenspices_home_categories( $limit = 12 ) {
	$terms = get_terms(
		array(
			'taxonomy'   => 'product_cat',
			'hide_empty' => true,
			'number'     => $limit,
			'orderby'    => 'count',
			'order'      => 'DESC',
		)
	);
	if ( is_wp_error( $terms ) || ! is_array( $terms ) ) {
		return array();
	}
	return array_values(
		array_filter(
			$terms,
			static function ( $term ) {
				return 'uncategorized' !== $term->slug;
			}
		)
	);
}

/**
 * Featured dishes: featured products first, else products with images, else recent.
 *
 * @param int $limit Limit.
 * @return WC_Product[]
 */
function sevenspices_home_featured_dishes( $limit = 8 ) {
	if ( ! function_exists( 'wc_get_products' ) ) {
		return array();
	}

	$featured = wc_get_products(
		array(
			'status'   => 'publish',
			'featured' => true,
			'limit'    => $limit,
			'orderby'  => 'date',
			'order'    => 'DESC',
		)
	);
	if ( is_array( $featured ) && count( $featured ) >= 4 ) {
		return $featured;
	}

	// Prefer signature food categories with photos over newest drinks/desserts.
	$priority_slugs = array( 'appetizers', 'tandoor', 'curry', 'vegetarian', 'indo-chinese', 'biryani', 'only-chicken-curry', 'lamb-goat' );
	$picked         = array();
	$seen           = array();

	foreach ( $priority_slugs as $slug ) {
		$batch = wc_get_products(
			array(
				'status'   => 'publish',
				'limit'    => 6,
				'category' => array( $slug ),
				'orderby'  => 'date',
				'order'    => 'DESC',
			)
		);
		if ( ! is_array( $batch ) ) {
			continue;
		}
		foreach ( $batch as $product ) {
			$id = $product->get_id();
			if ( isset( $seen[ $id ] ) ) {
				continue;
			}
			if ( ! $product->get_image_id() ) {
				continue;
			}
			$seen[ $id ] = true;
			$picked[]    = $product;
			if ( count( $picked ) >= $limit ) {
				return $picked;
			}
		}
	}

	$recent = wc_get_products(
		array(
			'status'  => 'publish',
			'limit'   => 40,
			'orderby' => 'date',
			'order'   => 'DESC',
		)
	);
	if ( ! is_array( $recent ) ) {
		return $picked;
	}
	foreach ( $recent as $product ) {
		$id = $product->get_id();
		if ( isset( $seen[ $id ] ) ) {
			continue;
		}
		if ( $product->get_image_id() ) {
			$seen[ $id ] = true;
			$picked[]    = $product;
		}
		if ( count( $picked ) >= $limit ) {
			break;
		}
	}
	if ( count( $picked ) >= 4 ) {
		return $picked;
	}

	return array_slice( array_merge( $picked, $recent ), 0, $limit );
}

/**
 * Category thumbnail or first product image in category.
 *
 * @param WP_Term $term Term.
 * @return string URL or empty.
 */
function sevenspices_home_category_image( $term ) {
	$thumb_id = (int) get_term_meta( $term->term_id, 'thumbnail_id', true );
	if ( $thumb_id ) {
		$url = wp_get_attachment_image_url( $thumb_id, 'medium_large' );
		if ( $url ) {
			return $url;
		}
	}
	if ( ! function_exists( 'wc_get_products' ) ) {
		return '';
	}
	$products = wc_get_products(
		array(
			'status'   => 'publish',
			'limit'    => 8,
			'category' => array( $term->slug ),
			'orderby'  => 'date',
			'order'    => 'DESC',
		)
	);
	if ( ! is_array( $products ) ) {
		return '';
	}
	foreach ( $products as $product ) {
		$id = $product->get_image_id();
		if ( $id ) {
			$url = wp_get_attachment_image_url( $id, 'medium_large' );
			if ( $url ) {
				return $url;
			}
		}
	}
	return '';
}

/**
 * Published product count.
 *
 * @return int
 */
function sevenspices_home_product_count() {
	$count = wp_count_posts( 'product' );
	return isset( $count->publish ) ? (int) $count->publish : 0;
}

/**
 * Cart item count for header badge.
 *
 * @return int
 */
function sevenspices_home_cart_count() {
	if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
		return 0;
	}
	return (int) WC()->cart->get_cart_contents_count();
}

/**
 * Front-page template swap.
 *
 * @param string $template Template.
 * @return string
 */
function sevenspices_home_template_include( $template ) {
	if ( is_front_page() && ! is_admin() ) {
		$custom = SEVENSPICES_HOME_DIR . 'templates/homepage.php';
		if ( file_exists( $custom ) ) {
			return $custom;
		}
	}
	return $template;
}
add_filter( 'template_include', 'sevenspices_home_template_include', 100 );

/**
 * Assets on front page only.
 */
function sevenspices_home_enqueue() {
	if ( ! is_front_page() ) {
		return;
	}
	wp_enqueue_style(
		'7spices-home-fonts',
		'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Manrope:wght@400;500;600;700&display=swap',
		array(),
		null
	);
	wp_enqueue_style(
		'7spices-home',
		SEVENSPICES_HOME_URL . 'assets/css/homepage.css',
		array( '7spices-home-fonts' ),
		SEVENSPICES_HOME_VERSION
	);
	wp_enqueue_script(
		'7spices-home',
		SEVENSPICES_HOME_URL . 'assets/js/homepage.js',
		array(),
		SEVENSPICES_HOME_VERSION,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'sevenspices_home_enqueue', 45 );

/**
 * Document title.
 *
 * @param array $parts Parts.
 * @return array
 */
function sevenspices_home_title( $parts ) {
	if ( is_front_page() ) {
		$parts['title']   = '7 Spices';
		$parts['tagline'] = 'Authentic Indian Restaurant and Bar';
	}
	return $parts;
}
add_filter( 'document_title_parts', 'sevenspices_home_title' );

/**
 * Meta description.
 */
function sevenspices_home_meta() {
	if ( ! is_front_page() ) {
		return;
	}
	echo '<meta name="description" content="' . esc_attr( '7 Spices Authentic Indian Restaurant and Bar — explore our menu, discover favourites, and order delicious Indian food online.' ) . '" />' . "\n";
}
add_action( 'wp_head', 'sevenspices_home_meta', 1 );

/**
 * Body class.
 *
 * @param array $classes Classes.
 * @return array
 */
function sevenspices_home_body_class( $classes ) {
	if ( is_front_page() ) {
		$classes[] = 'spices-home-active';
	}
	return $classes;
}
add_filter( 'body_class', 'sevenspices_home_body_class' );

/**
 * If the SaaS homepage plugin is active, prefer this restaurant homepage (higher priority already set).
 * Soft notice in admin.
 */
add_action(
	'admin_notices',
	static function () {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}
		if ( is_plugin_active( 'saasmarket-pro-homepage/saasmarket-pro-homepage.php' ) ) {
			echo '<div class="notice notice-warning"><p><strong>7 Spices Restaurant Homepage</strong> is active together with <em>SaaSMarket Pro Homepage</em>. Deactivate the SaaS homepage plugin so the restaurant front page is used consistently.</p></div>';
		}
	}
);
