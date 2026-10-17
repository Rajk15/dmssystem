<?php
/**
 * Plugin Name: Eyza Eye SPH Conditional Pricing
 * Description: Doubles the Aqualens product price when OD and OS SPH values differ. Same SPH (or "same power for both eyes") keeps the normal selling price. Works with ThemeComplete Extra Product Options. Enforces the same unit price through cart, checkout, and orders.
 * Version: 1.1.0
 * Author: EyzaKart
 * Requires at least: 5.8
 * Requires PHP: 7.4
 * WC requires at least: 5.0
 * Text Domain: eyza-eye-sph-pricing
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'EYZA_SPH_PLUGIN_FILE', __FILE__ );
define( 'EYZA_SPH_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'EYZA_SPH_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'EYZA_SPH_VERSION', '1.1.0' );

/**
 * Target product ID for Aqualens 24Hrs NXT Monthly Disposable.
 * Filterable: add_filter( 'eyza_sph_product_id', fn() => 33360 );
 */
function eyza_sph_product_id() {
	return (int) apply_filters( 'eyza_sph_product_id', 33360 );
}

/**
 * EPO field names observed on the product page (ThemeComplete EPO).
 * Right eye SPH is a fee select; left eye and combined are option selects.
 */
function eyza_sph_field_names() {
	return (array) apply_filters(
		'eyza_sph_field_names',
		array(
			'od'         => 'tmcp_tmcartfee_select_3',
			'os'         => 'tmcp_select_4',
			'both'       => 'tmcp_select_5',
			'same_power' => 'tmcp_tmcartfee_checkbox_2_0',
		)
	);
}

/**
 * Normalize SPH option values from EPO ("-1.00_12" → "-1.00").
 *
 * @param mixed $raw Raw posted or stored value.
 * @return string|null Normalized value, or null if empty/invalid.
 */
function eyza_sph_normalize( $raw ) {
	if ( null === $raw || false === $raw ) {
		return null;
	}

	$value = trim( wp_strip_all_tags( (string) $raw ) );
	if ( '' === $value ) {
		return null;
	}

	// EPO appends "_{option_index}" to select values.
	if ( preg_match( '/^(.+)_\d+$/', $value, $matches ) ) {
		$value = $matches[1];
	}

	$value = preg_replace( '/\s+/', '', $value );
	$value = strtolower( $value );

	return '' === $value ? null : $value;
}

/**
 * Compute price multiplier from OD/OS SPH and same-power mode.
 *
 * @param string|null $od         Normalized right-eye SPH.
 * @param string|null $os         Normalized left-eye SPH.
 * @param bool        $same_power Whether "same power for both eyes" is active.
 * @param string|null $both       Normalized combined Left & Right SPH.
 * @return int 1 or 2.
 */
function eyza_sph_multiplier( $od, $os, $same_power = false, $both = null ) {
	if ( $same_power ) {
		return 1;
	}

	if ( null !== $both && null === $od && null === $os ) {
		return 1;
	}

	if ( null === $od || null === $os ) {
		return 1;
	}

	return ( $od === $os ) ? 1 : 2;
}

/**
 * Read SPH selections from add-to-cart POST (EPO field names).
 *
 * @return array{od:?string,os:?string,both:?string,same_power:bool,multiplier:int}
 */
function eyza_sph_from_request() {
	$fields = eyza_sph_field_names();

	$od_raw   = isset( $_POST[ $fields['od'] ] ) ? wp_unslash( $_POST[ $fields['od'] ] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing
	$os_raw   = isset( $_POST[ $fields['os'] ] ) ? wp_unslash( $_POST[ $fields['os'] ] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing
	$both_raw = isset( $_POST[ $fields['both'] ] ) ? wp_unslash( $_POST[ $fields['both'] ] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing
	$same     = ! empty( $_POST[ $fields['same_power'] ] ); // phpcs:ignore WordPress.Security.NonceVerification.Missing

	$od   = eyza_sph_normalize( $od_raw );
	$os   = eyza_sph_normalize( $os_raw );
	$both = eyza_sph_normalize( $both_raw );

	if ( $same && null !== $both ) {
		$od = $both;
		$os = $both;
	}

	$multiplier = eyza_sph_multiplier( $od, $os, $same, $both );

	return array(
		'od'         => $od,
		'os'         => $os,
		'both'       => $both,
		'same_power' => (bool) $same,
		'multiplier' => $multiplier,
	);
}

/**
 * Fallback: recover OD/OS from ThemeComplete EPO cart rows when POST capture missed.
 *
 * @param array $cart_item Cart item.
 * @return array{od:?string,os:?string,both:?string,same_power:bool}|null
 */
function eyza_sph_from_epo_cart_item( $cart_item ) {
	$od   = null;
	$os   = null;
	$both = null;
	$same = false;

	$rows = array();
	foreach ( array( 'tmcartepo', 'tmcartfee' ) as $bucket ) {
		if ( ! empty( $cart_item[ $bucket ] ) && is_array( $cart_item[ $bucket ] ) ) {
			$rows = array_merge( $rows, $cart_item[ $bucket ] );
		}
	}

	foreach ( $rows as $row ) {
		if ( ! is_array( $row ) ) {
			continue;
		}

		$label = '';
		if ( ! empty( $row['section_label'] ) ) {
			$label = strtolower( wp_strip_all_tags( (string) $row['section_label'] ) );
		}
		if ( ! empty( $row['name'] ) ) {
			$label .= ' ' . strtolower( wp_strip_all_tags( (string) $row['name'] ) );
		}

		$value = isset( $row['value'] ) ? eyza_sph_normalize( $row['value'] ) : null;
		if ( null === $value || ! preg_match( '/^-?\d+(\.\d+)?$/', $value ) ) {
			// Same-power checkbox text.
			$raw_value = isset( $row['value'] ) ? strtolower( (string) $row['value'] ) : '';
			if ( false !== strpos( $raw_value, 'same power' ) ) {
				$same = true;
			}
			continue;
		}

		if ( false !== strpos( $label, 'left & right' ) || false !== strpos( $label, 'left and right' ) ) {
			$both = $value;
			$same = true;
		} elseif ( false !== strpos( $label, 'right' ) || false !== strpos( $label, 'od (' ) ) {
			$od = $value;
		} elseif ( false !== strpos( $label, 'left' ) || false !== strpos( $label, 'os (' ) ) {
			$os = $value;
		}
	}

	if ( null === $od && null === $os && null === $both && ! $same ) {
		return null;
	}

	return array(
		'od'         => $od,
		'os'         => $os,
		'both'       => $both,
		'same_power' => $same,
	);
}

/**
 * Fresh WooCommerce selling price (sale price when applicable). Always from a new product load.
 *
 * @param int $product_id Product ID.
 * @return float
 */
function eyza_sph_base_price( $product_id ) {
	$product = wc_get_product( $product_id );
	if ( ! $product ) {
		return 0.0;
	}

	return (float) $product->get_price( 'edit' );
}

/**
 * Resolve multiplier + unit price for a cart item; never compounds a previous custom price.
 *
 * @param array $cart_item Cart item (by value; caller may write back).
 * @return array{sph:array,unit_price:float,multiplier:int}|null
 */
function eyza_sph_resolve_cart_pricing( $cart_item ) {
	$product_id = isset( $cart_item['product_id'] ) ? (int) $cart_item['product_id'] : 0;
	if ( $product_id !== eyza_sph_product_id() ) {
		return null;
	}

	$sph = null;
	if ( ! empty( $cart_item['eyza_sph'] ) && is_array( $cart_item['eyza_sph'] ) ) {
		$sph = $cart_item['eyza_sph'];
	} else {
		$from_epo = eyza_sph_from_epo_cart_item( $cart_item );
		if ( $from_epo ) {
			$sph = $from_epo;
		}
	}

	if ( ! $sph ) {
		return null;
	}

	if ( ! empty( $sph['same_power'] ) && ! empty( $sph['both'] ) ) {
		$sph['od'] = $sph['both'];
		$sph['os'] = $sph['both'];
	}

	$multiplier = eyza_sph_multiplier(
		isset( $sph['od'] ) ? $sph['od'] : null,
		isset( $sph['os'] ) ? $sph['os'] : null,
		! empty( $sph['same_power'] ),
		isset( $sph['both'] ) ? $sph['both'] : null
	);

	if ( $multiplier < 1 ) {
		$multiplier = 1;
	}

	$base = eyza_sph_base_price( $product_id );
	if ( $base <= 0 ) {
		return null;
	}

	$sph['multiplier'] = $multiplier;

	return array(
		'sph'        => $sph,
		'unit_price' => $base * (float) $multiplier,
		'multiplier' => $multiplier,
	);
}

/**
 * Stamp calculated unit price on the cart product object and set_price().
 * Custom property survives later EPO set_price() resets when paired with get_price filters.
 *
 * @param array $cart_item Cart item.
 * @return array
 */
function eyza_sph_apply_price_to_cart_item( $cart_item ) {
	$resolved = eyza_sph_resolve_cart_pricing( $cart_item );
	if ( ! $resolved ) {
		return $cart_item;
	}

	$cart_item['eyza_sph']            = $resolved['sph'];
	$cart_item['eyza_sph_unit_price'] = $resolved['unit_price'];

	if ( ! empty( $cart_item['data'] ) && is_object( $cart_item['data'] ) ) {
		$cart_item['data']->eyza_sph_calculated_price = $resolved['unit_price'];
		$cart_item['data']->set_price( $resolved['unit_price'] );
	}

	return $cart_item;
}

/**
 * Persist SPH data on the cart item. Server recomputes multiplier — never trusts JS.
 */
add_filter(
	'woocommerce_add_cart_item_data',
	function ( $cart_item_data, $product_id ) {
		if ( (int) $product_id !== eyza_sph_product_id() ) {
			return $cart_item_data;
		}

		$sph                        = eyza_sph_from_request();
		$cart_item_data['eyza_sph'] = $sph;
		$cart_item_data['eyza_sph_key'] = md5(
			wp_json_encode(
				array(
					$sph['od'],
					$sph['os'],
					$sph['same_power'],
					$sph['multiplier'],
				)
			)
		);

		return $cart_item_data;
	},
	20,
	2
);

/**
 * Apply price when the item is first added to the cart object.
 */
add_filter(
	'woocommerce_add_cart_item',
	function ( $cart_item ) {
		return eyza_sph_apply_price_to_cart_item( $cart_item );
	},
	999999,
	1
);

/**
 * Restore SPH data from session and re-apply price (EPO may rebuild the product object).
 */
add_filter(
	'woocommerce_get_cart_item_from_session',
	function ( $cart_item, $values ) {
		if ( isset( $values['eyza_sph'] ) ) {
			$cart_item['eyza_sph'] = $values['eyza_sph'];
		}
		if ( isset( $values['eyza_sph_key'] ) ) {
			$cart_item['eyza_sph_key'] = $values['eyza_sph_key'];
		}
		if ( isset( $values['eyza_sph_unit_price'] ) ) {
			$cart_item['eyza_sph_unit_price'] = $values['eyza_sph_unit_price'];
		}

		return eyza_sph_apply_price_to_cart_item( $cart_item );
	},
	999999,
	2
);

/**
 * Zero SPH option/fee row prices early so EPO math leftovers cannot inflate totals.
 */
add_action(
	'woocommerce_before_calculate_totals',
	function ( $cart ) {
		if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
			return;
		}
		if ( ! $cart || ! is_object( $cart ) ) {
			return;
		}

		$target_id = eyza_sph_product_id();

		foreach ( $cart->get_cart() as $cart_item_key => $cart_item ) {
			if ( (int) ( $cart_item['product_id'] ?? 0 ) !== $target_id ) {
				continue;
			}
			if ( empty( $cart_item['eyza_sph'] ) && empty( $cart_item['tmcartepo'] ) && empty( $cart_item['tmcartfee'] ) ) {
				continue;
			}

			foreach ( array( 'tmcartepo', 'tmcartfee' ) as $bucket ) {
				if ( empty( $cart->cart_contents[ $cart_item_key ][ $bucket ] ) || ! is_array( $cart->cart_contents[ $cart_item_key ][ $bucket ] ) ) {
					continue;
				}

				foreach ( $cart->cart_contents[ $cart_item_key ][ $bucket ] as $i => $row ) {
					if ( ! is_array( $row ) ) {
						continue;
					}

					$label = '';
					if ( ! empty( $row['name'] ) ) {
						$label = strtolower( wp_strip_all_tags( (string) $row['name'] ) );
					} elseif ( ! empty( $row['section_label'] ) ) {
						$label = strtolower( wp_strip_all_tags( (string) $row['section_label'] ) );
					}

					$value         = isset( $row['value'] ) ? eyza_sph_normalize( $row['value'] ) : null;
					$is_sph_value  = ( null !== $value && preg_match( '/^-?\d+(\.\d+)?$/', $value ) );
					$is_sph_label  = ( false !== strpos( $label, 'sph' ) );

					if ( $is_sph_value || $is_sph_label ) {
						$cart->cart_contents[ $cart_item_key ][ $bucket ][ $i ]['price']          = 0;
						$cart->cart_contents[ $cart_item_key ][ $bucket ][ $i ]['price_excl_tax'] = 0;
						$cart->cart_contents[ $cart_item_key ][ $bucket ][ $i ]['price_incl_tax'] = 0;
						if ( isset( $cart->cart_contents[ $cart_item_key ][ $bucket ][ $i ]['price_per_unit'] ) ) {
							$cart->cart_contents[ $cart_item_key ][ $bucket ][ $i ]['price_per_unit'] = 0;
						}
					}
				}
			}
		}
	},
	5
);

/**
 * Apply multiplier AFTER ThemeComplete EPO (often priority ~99999).
 * Always recalculates from fresh base × multiplier (never doubles an already-doubled price).
 */
add_action(
	'woocommerce_before_calculate_totals',
	function ( $cart ) {
		if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
			return;
		}
		if ( ! $cart || ! is_object( $cart ) ) {
			return;
		}

		foreach ( $cart->get_cart() as $cart_item_key => $cart_item ) {
			$updated = eyza_sph_apply_price_to_cart_item( $cart_item );
			if ( isset( $updated['eyza_sph'] ) ) {
				$cart->cart_contents[ $cart_item_key ] = $updated;
			}
		}
	},
	PHP_INT_MAX
);

/**
 * Force get_price() to return our stamped unit price even if EPO calls set_price() later
 * during the same request with the base selling price.
 *
 * @param mixed      $price   Price.
 * @param WC_Product $product Product.
 * @return mixed
 */
function eyza_sph_filter_get_price( $price, $product ) {
	if ( is_object( $product ) && isset( $product->eyza_sph_calculated_price ) && is_numeric( $product->eyza_sph_calculated_price ) ) {
		return $product->eyza_sph_calculated_price;
	}
	return $price;
}
add_filter( 'woocommerce_product_get_price', 'eyza_sph_filter_get_price', PHP_INT_MAX, 2 );
add_filter( 'woocommerce_product_variation_get_price', 'eyza_sph_filter_get_price', PHP_INT_MAX, 2 );

/**
 * Ensure cart line product objects keep the stamped price when templates read them.
 */
add_filter(
	'woocommerce_cart_item_product',
	function ( $product, $cart_item ) {
		if ( empty( $cart_item['eyza_sph'] ) || ! is_object( $product ) ) {
			return $product;
		}
		$resolved = eyza_sph_resolve_cart_pricing( $cart_item );
		if ( $resolved ) {
			$product->eyza_sph_calculated_price = $resolved['unit_price'];
			$product->set_price( $resolved['unit_price'] );
		}
		return $product;
	},
	PHP_INT_MAX,
	2
);

/**
 * Show OD/OS and multiplier note in cart/checkout item data.
 */
add_filter(
	'woocommerce_get_item_data',
	function ( $item_data, $cart_item ) {
		if ( empty( $cart_item['eyza_sph'] ) || ! is_array( $cart_item['eyza_sph'] ) ) {
			return $item_data;
		}

		$sph  = $cart_item['eyza_sph'];
		$mult = isset( $sph['multiplier'] ) ? (int) $sph['multiplier'] : 1;

		if ( ! empty( $sph['od'] ) ) {
			$item_data[] = array(
				'key'   => __( 'OD (Right Eye) SPH', 'eyza-eye-sph-pricing' ),
				'value' => esc_html( $sph['od'] ),
			);
		}
		if ( ! empty( $sph['os'] ) ) {
			$item_data[] = array(
				'key'   => __( 'OS (Left Eye) SPH', 'eyza-eye-sph-pricing' ),
				'value' => esc_html( $sph['os'] ),
			);
		}
		if ( $mult > 1 ) {
			$item_data[] = array(
				'key'   => __( 'Lens pricing', 'eyza-eye-sph-pricing' ),
				'value' => __( 'Different SPH for each eye — price × 2', 'eyza-eye-sph-pricing' ),
			);
		}

		return $item_data;
	},
	20,
	2
);

/**
 * Persist SPH metadata on the order line item. Line totals come from cart calculation.
 */
add_action(
	'woocommerce_checkout_create_order_line_item',
	function ( $item, $cart_item_key, $values ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
		if ( empty( $values['eyza_sph'] ) || ! is_array( $values['eyza_sph'] ) ) {
			return;
		}

		$sph = $values['eyza_sph'];

		if ( ! empty( $sph['od'] ) ) {
			$item->add_meta_data( __( 'OD (Right Eye) SPH', 'eyza-eye-sph-pricing' ), $sph['od'], true );
		}
		if ( ! empty( $sph['os'] ) ) {
			$item->add_meta_data( __( 'OS (Left Eye) SPH', 'eyza-eye-sph-pricing' ), $sph['os'], true );
		}
		if ( ! empty( $sph['multiplier'] ) && (int) $sph['multiplier'] > 1 ) {
			$item->add_meta_data( __( 'Lens pricing', 'eyza-eye-sph-pricing' ), __( 'Different SPH for each eye — price × 2', 'eyza-eye-sph-pricing' ), true );
		}

		$item->add_meta_data( '_eyza_sph', $sph, true );

		if ( isset( $values['eyza_sph_unit_price'] ) ) {
			$item->add_meta_data( '_eyza_sph_unit_price', $values['eyza_sph_unit_price'], true );
		}
	},
	20,
	3
);

/**
 * Enqueue frontend script only on the target product page.
 * Product-page calculation logic is unchanged — display only.
 */
add_action(
	'wp_enqueue_scripts',
	function () {
		if ( ! function_exists( 'is_product' ) || ! is_product() ) {
			return;
		}

		global $post;
		if ( ! $post || (int) $post->ID !== eyza_sph_product_id() ) {
			return;
		}

		$product = wc_get_product( $post->ID );
		if ( ! $product ) {
			return;
		}

		wp_enqueue_script(
			'eyza-eye-sph-pricing',
			EYZA_SPH_PLUGIN_URL . 'assets/eye-sph-pricing.js',
			array( 'jquery' ),
			EYZA_SPH_VERSION,
			true
		);

		wp_localize_script(
			'eyza-eye-sph-pricing',
			'eyzaEyeSphPricing',
			array(
				'productId'  => eyza_sph_product_id(),
				'basePrice'  => eyza_sph_base_price( eyza_sph_product_id() ),
				'fieldNames' => eyza_sph_field_names(),
				'currency'   => array(
					'symbol'   => get_woocommerce_currency_symbol(),
					'decimals' => wc_get_price_decimals(),
					'decimal'  => wc_get_price_decimal_separator(),
					'thousand' => wc_get_price_thousand_separator(),
				),
			)
		);
	},
	30
);
