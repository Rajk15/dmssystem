<?php
/**
 * Plugin Name: SaaSMarket Pro Homepage
 * Description: Premium SaaS marketplace homepage for SaaSMarket Pro. Replaces the front page presentation while preserving existing shop, account, and contact routes. Does not invent software products.
 * Version: 1.0.0
 * Author: SaaSMarket Pro
 * Text Domain: saasmarket-pro-homepage
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SMP_HOME_VERSION', '1.0.0' );
define( 'SMP_HOME_FILE', __FILE__ );
define( 'SMP_HOME_DIR', plugin_dir_path( __FILE__ ) );
define( 'SMP_HOME_URL', plugin_dir_url( __FILE__ ) );

/**
 * Verified destination URLs discovered on this staging site.
 *
 * @return array<string,string>
 */
function smp_home_urls() {
	$shop     = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/index.php/shop/' );
	$account  = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : home_url( '/index.php/my-account/' );
	$contact  = home_url( '/index.php/pages/contact-us/' );
	$about    = home_url( '/index.php/pages/about-us/' );
	$services = home_url( '/index.php/pages/our-services/' );

	return array(
		'home'     => home_url( '/' ),
		'shop'     => $shop ? $shop : home_url( '/index.php/shop/' ),
		'account'  => $account ? $account : home_url( '/index.php/my-account/' ),
		'contact'  => $contact,
		'about'    => $about,
		'services' => $services,
		'login'    => wp_login_url(),
	);
}

/**
 * Published product count (real catalogue size).
 *
 * @return int
 */
function smp_home_product_count() {
	$count = wp_count_posts( 'product' );
	return ( isset( $count->publish ) ) ? (int) $count->publish : 0;
}

/**
 * Real product category terms with at least one product.
 *
 * @param int $limit Max categories.
 * @return array<int,WP_Term>
 */
function smp_home_categories( $limit = 8 ) {
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
			function ( $term ) {
				return $term->slug !== 'uncategorized';
			}
		)
	);
}

/**
 * Featured published products for the catalogue grid (real data only).
 *
 * @param int $limit Limit.
 * @return WC_Product[]
 */
function smp_home_featured_products( $limit = 8 ) {
	if ( ! function_exists( 'wc_get_products' ) ) {
		return array();
	}
	$products = wc_get_products(
		array(
			'status'  => 'publish',
			'limit'   => $limit,
			'orderby' => 'date',
			'order'   => 'DESC',
		)
	);
	return is_array( $products ) ? $products : array();
}

/**
 * Use custom front-page template.
 *
 * @param string $template Template path.
 * @return string
 */
function smp_home_template_include( $template ) {
	if ( is_front_page() && ! is_admin() ) {
		$custom = SMP_HOME_DIR . 'templates/homepage.php';
		if ( file_exists( $custom ) ) {
			return $custom;
		}
	}
	return $template;
}
add_filter( 'template_include', 'smp_home_template_include', 99 );

/**
 * Enqueue homepage assets only on the front page.
 */
function smp_home_enqueue() {
	if ( ! is_front_page() ) {
		return;
	}

	wp_enqueue_style(
		'smp-homepage',
		SMP_HOME_URL . 'assets/css/homepage.css',
		array(),
		SMP_HOME_VERSION
	);

	wp_enqueue_script(
		'smp-homepage',
		SMP_HOME_URL . 'assets/js/homepage.js',
		array(),
		SMP_HOME_VERSION,
		true
	);

	// Prefer a modern readable stack already common on the web without relying on Inter/Roboto.
	wp_enqueue_style(
		'smp-fonts',
		'https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=Fraunces:opsz,wght@9..144,560;9..144,650&display=swap',
		array(),
		null
	);
}
add_action( 'wp_enqueue_scripts', 'smp_home_enqueue', 40 );

/**
 * Front-page document title.
 *
 * @param array $parts Title parts.
 * @return array
 */
function smp_home_document_title( $parts ) {
	if ( is_front_page() ) {
		$parts['title'] = 'SaaSMarket Pro';
		$parts['tagline'] = 'Discover software solutions for your business';
	}
	return $parts;
}
add_filter( 'document_title_parts', 'smp_home_document_title' );

/**
 * Basic meta description for the homepage.
 */
function smp_home_meta_description() {
	if ( ! is_front_page() ) {
		return;
	}
	echo '<meta name="description" content="' . esc_attr( 'SaaSMarket Pro helps you discover and explore software solutions to manage customers, automate workflows, and grow with confidence.' ) . '" />' . "\n";
}
add_action( 'wp_head', 'smp_home_meta_description', 1 );

/**
 * Body class for scoped styling.
 *
 * @param array $classes Classes.
 * @return array
 */
function smp_home_body_class( $classes ) {
	if ( is_front_page() ) {
		$classes[] = 'smp-homepage-active';
	}
	return $classes;
}
add_filter( 'body_class', 'smp_home_body_class' );
