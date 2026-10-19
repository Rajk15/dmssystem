<?php
/**
 * Plugin Name: 7 Spices Menu Sync
 * Description: Syncs WooCommerce products and categories from the official 7 Spices scanned menu PDF (embedded JSON). Does not delete products — unmatched catalog items can be drafted.
 * Version: 1.0.0
 * Author: 7 Spices
 * Requires Plugins: woocommerce
 * Text Domain: 7spices-menu-sync
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SEVENSPICES_MENU_SYNC_VERSION', '1.0.0' );
define( 'SEVENSPICES_MENU_SYNC_FILE', __FILE__ );
define( 'SEVENSPICES_MENU_SYNC_DIR', plugin_dir_path( __FILE__ ) );

/**
 * Load embedded menu JSON.
 *
 * @return array|WP_Error
 */
function sevenspices_menu_load() {
	$path = SEVENSPICES_MENU_SYNC_DIR . 'data/menu.json';
	if ( ! file_exists( $path ) ) {
		return new WP_Error( 'missing_menu', 'menu.json not found.' );
	}
	$data = json_decode( (string) file_get_contents( $path ), true );
	if ( ! is_array( $data ) || empty( $data['categories'] ) ) {
		return new WP_Error( 'invalid_menu', 'menu.json is invalid.' );
	}
	return $data;
}

/**
 * @param string $text Text.
 * @return string
 */
function sevenspices_menu_slug( $text ) {
	$slug = sanitize_title( $text );
	return $slug ? $slug : 'item';
}

/**
 * Stable SKU for a menu item / variation.
 *
 * @param string      $category Category name.
 * @param string      $item     Item name.
 * @param string|null $variant  Variant label.
 * @return string
 */
function sevenspices_menu_sku( $category, $item, $variant = null ) {
	$base = '7s-' . sevenspices_menu_slug( $category ) . '-' . sevenspices_menu_slug( $item );
	if ( $variant ) {
		$base .= '-' . sevenspices_menu_slug( $variant );
	}
	// WooCommerce SKU max practical length.
	return substr( $base, 0, 90 );
}

/**
 * Normalize a product title for fuzzy matching.
 *
 * @param string $name Name.
 * @return string
 */
function sevenspices_menu_norm_name( $name ) {
	$name = strtolower( wp_strip_all_tags( (string) $name ) );
	$name = preg_replace( '/[^a-z0-9]+/', '', $name );
	return $name ? $name : '';
}

/**
 * Find product by SKU.
 *
 * @param string $sku SKU.
 * @return WC_Product|null
 */
function sevenspices_menu_get_product_by_sku( $sku ) {
	$id = wc_get_product_id_by_sku( $sku );
	if ( ! $id ) {
		return null;
	}
	$product = wc_get_product( $id );
	return $product ? $product : null;
}

/**
 * Find an existing product by SKU or normalized title.
 *
 * @param string $sku  SKU.
 * @param string $name Product name.
 * @param string $type Optional required type: simple|variable.
 * @return WC_Product|null
 */
function sevenspices_menu_find_product( $sku, $name, $type = '' ) {
	$product = sevenspices_menu_get_product_by_sku( $sku );
	if ( $product && ( ! $type || $product->is_type( $type ) ) ) {
		return $product;
	}

	$target = sevenspices_menu_norm_name( $name );
	if ( ! $target ) {
		return null;
	}

	$q = new WP_Query(
		array(
			'post_type'      => 'product',
			'post_status'    => array( 'publish', 'draft', 'private', 'pending' ),
			'posts_per_page' => -1,
			'fields'         => 'ids',
		)
	);

	foreach ( $q->posts as $pid ) {
		$candidate = wc_get_product( $pid );
		if ( ! $candidate ) {
			continue;
		}
		if ( $type && ! $candidate->is_type( $type ) ) {
			continue;
		}
		if ( sevenspices_menu_norm_name( $candidate->get_name() ) === $target ) {
			return $candidate;
		}
	}

	return null;
}

/**
 * Ensure a product category exists; return term ID.
 *
 * @param string $name   Name.
 * @param string $parent Parent name optional.
 * @return int
 */
function sevenspices_menu_ensure_category( $name, $description = '' ) {
	$slug = sevenspices_menu_slug( $name );
	$term = get_term_by( 'slug', $slug, 'product_cat' );
	if ( $term && ! is_wp_error( $term ) ) {
		if ( $description && empty( $term->description ) ) {
			wp_update_term( (int) $term->term_id, 'product_cat', array( 'description' => $description ) );
		}
		return (int) $term->term_id;
	}
	$created = wp_insert_term(
		$name,
		'product_cat',
		array(
			'slug'        => $slug,
			'description' => $description,
		)
	);
	if ( is_wp_error( $created ) ) {
		// Race / exists by name.
		$term = get_term_by( 'name', $name, 'product_cat' );
		return $term ? (int) $term->term_id : 0;
	}
	return (int) $created['term_id'];
}

/**
 * Build short description HTML from item data.
 *
 * @param array $item Item.
 * @param array $category Category array.
 * @return string
 */
function sevenspices_menu_build_description( $item, $category ) {
	$parts = array();
	if ( ! empty( $item['description'] ) ) {
		$parts[] = wp_kses_post( $item['description'] );
	}
	if ( ! empty( $item['notes'] ) ) {
		$parts[] = '<em>' . esc_html( $item['notes'] ) . '</em>';
	}
	if ( ! empty( $category['protein_prices'] ) && is_array( $category['protein_prices'] ) ) {
		$bits = array();
		foreach ( $category['protein_prices'] as $pp ) {
			$bits[] = esc_html( $pp['label'] ) . ' $' . esc_html( (string) $pp['price'] );
		}
		$parts[] = '<strong>Protein prices:</strong> ' . implode( ' | ', $bits );
		$parts[] = 'Plain Rice Included';
	}
	if ( ! empty( $item['variants'] ) && is_array( $item['variants'] ) ) {
		$bits = array();
		foreach ( $item['variants'] as $v ) {
			$bits[] = esc_html( $v['label'] ) . ' $' . esc_html( (string) $v['price'] );
		}
		$parts[] = '<strong>Options:</strong> ' . implode( ' | ', $bits );
	}
	return implode( "\n\n", $parts );
}

/**
 * Create or update a simple product.
 *
 * @param array $args Args.
 * @return array{id:int,action:string}
 */
function sevenspices_menu_upsert_simple( $args ) {
	$sku     = $args['sku'];
	$name    = $args['name'];
	$price   = $args['price'];
	$desc    = $args['description'];
	$cat_ids = $args['category_ids'];
	$product = sevenspices_menu_find_product( $sku, $name, 'simple' );
	$action  = 'updated';

	if ( ! $product ) {
		$product = new WC_Product_Simple();
		$action  = 'created';
	}

	$product->set_name( $name );
	$product->set_status( 'publish' );
	$product->set_catalog_visibility( 'visible' );
	$product->set_sku( $sku );
	$product->set_regular_price( (string) $price );
	$product->set_price( (string) $price );
	$product->set_short_description( $desc );
	$product->set_description( $desc );
	$product->set_category_ids( $cat_ids );
	$product->set_sold_individually( false );
	$product->update_meta_data( '_7spices_menu_managed', '1' );
	$product->update_meta_data( '_7spices_menu_source', 'pdf-2026-10-12' );
	$id = $product->save();

	return array(
		'id'     => (int) $id,
		'action' => $action,
	);
}

/**
 * Create or update a variable product with one attribute of priced options.
 *
 * @param array $args Args.
 * @return array{id:int,action:string,variations:int}
 */
function sevenspices_menu_upsert_variable( $args ) {
	$sku         = $args['sku'];
	$name        = $args['name'];
	$desc        = $args['description'];
	$cat_ids     = $args['category_ids'];
	$attr_name   = $args['attribute_name'];
	$variants = $args['variants'];
	$product  = sevenspices_menu_find_product( $sku, $name, 'variable' );
	$action   = 'updated';

	if ( ! $product ) {
		// If a simple product exists with this name, leave it drafted later and create variable.
		$product = new WC_Product_Variable();
		$action  = 'created';
	}

	$product->set_name( $name );
	$product->set_status( 'publish' );
	$product->set_catalog_visibility( 'visible' );
	$product->set_sku( $sku );
	$product->set_short_description( $desc );
	$product->set_description( $desc );
	$product->set_category_ids( $cat_ids );
	$product->update_meta_data( '_7spices_menu_managed', '1' );
	$product->update_meta_data( '_7spices_menu_source', 'pdf-2026-10-12' );

	$options = array();
	foreach ( $variants as $v ) {
		$options[] = $v['label'];
	}

	$attribute = new WC_Product_Attribute();
	$attribute->set_id( 0 );
	$attribute->set_name( $attr_name );
	$attribute->set_options( $options );
	$attribute->set_visible( true );
	$attribute->set_variation( true );
	$product->set_attributes( array( $attribute ) );
	$parent_id = $product->save();

	// Remove old variations not in the new set.
	$existing_vars = $product->get_children();
	$keep_skus     = array();
	$count         = 0;

	foreach ( $variants as $v ) {
		$vsku      = sevenspices_menu_sku( $args['category_name'], $name, $v['label'] );
		$keep_skus[] = $vsku;
		$variation = sevenspices_menu_get_product_by_sku( $vsku );
		if ( ! $variation || ! $variation->is_type( 'variation' ) ) {
			$variation = new WC_Product_Variation();
		}
		$variation->set_parent_id( $parent_id );
		$variation->set_sku( $vsku );
		$variation->set_regular_price( (string) $v['price'] );
		$variation->set_price( (string) $v['price'] );
		$variation->set_status( 'publish' );
		$variation->set_attributes( array( sanitize_title( $attr_name ) => $v['label'] ) );
		$variation->update_meta_data( '_7spices_menu_managed', '1' );
		$variation->save();
		$count++;
	}

	foreach ( $existing_vars as $vid ) {
		$child = wc_get_product( $vid );
		if ( ! $child ) {
			continue;
		}
		$csku = $child->get_sku();
		if ( $csku && ! in_array( $csku, $keep_skus, true ) && $child->get_meta( '_7spices_menu_managed' ) === '1' ) {
			$child->set_status( 'private' );
			$child->save();
		}
	}

	WC_Product_Variable::sync( $parent_id );

	return array(
		'id'         => (int) $parent_id,
		'action'     => $action,
		'variations' => $count,
	);
}

/**
 * Sync the full menu into WooCommerce.
 *
 * @param bool $draft_unmanaged Draft products not tagged as managed after sync.
 * @return array Report.
 */
function sevenspices_menu_sync( $draft_unmanaged = false ) {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return array( 'error' => 'WooCommerce is not active.' );
	}

	$menu = sevenspices_menu_load();
	if ( is_wp_error( $menu ) ) {
		return array( 'error' => $menu->get_error_message() );
	}

	$report = array(
		'categories_ensured' => array(),
		'created'            => 0,
		'updated'            => 0,
		'variable'           => 0,
		'simple'             => 0,
		'drafted'            => 0,
		'notices'            => array(),
		'items'              => array(),
	);

	// Global notices as WooCommerce store notice options if empty.
	if ( ! empty( $menu['service_charge_notice'] ) ) {
		update_option( '7spices_service_charge_notice', sanitize_text_field( $menu['service_charge_notice'] ) );
	}
	if ( ! empty( $menu['allergy_notice'] ) ) {
		update_option( '7spices_allergy_notice', sanitize_text_field( $menu['allergy_notice'] ) );
	}

	$managed_ids = array();

	foreach ( $menu['categories'] as $category ) {
		$cat_name = $category['name'];
		$cat_desc = '';
		if ( ! empty( $category['notes'] ) ) {
			$cat_desc = $category['notes'];
		}
		if ( ! empty( $category['protein_prices'] ) ) {
			$cat_desc .= ( $cat_desc ? ' — ' : '' ) . 'Creamy Gravy Dish With a Choice of Protein. Plain Rice Included.';
		}
		$cat_id = sevenspices_menu_ensure_category( $cat_name, $cat_desc );
		$report['categories_ensured'][] = $cat_name . ' (#' . $cat_id . ')';

		$is_curry_matrix = ! empty( $category['protein_prices'] ) && is_array( $category['protein_prices'] );

		foreach ( $category['items'] as $item ) {
			$name = $item['name'];
			$desc = sevenspices_menu_build_description( $item, $category );
			$sku  = sevenspices_menu_sku( $cat_name, $name );

			if ( $is_curry_matrix ) {
				// Each curry style → variable product over proteins.
				// Madras: no seafood or paneer.
				$proteins = $category['protein_prices'];
				if ( false !== stripos( $name, 'madras' ) ) {
					$proteins = array_values(
						array_filter(
							$proteins,
							function ( $p ) {
								$l = strtolower( $p['label'] );
								return ! in_array( $l, array( 'paneer', 'fish', 'shrimp', 'salmon', 'crab kofta' ), true );
							}
						)
					);
				}
				$result = sevenspices_menu_upsert_variable(
					array(
						'sku'            => $sku,
						'name'           => $name,
						'description'    => $desc,
						'category_ids'   => array( $cat_id ),
						'attribute_name' => 'Protein',
						'variants'       => $proteins,
						'category_name'  => $cat_name,
					)
				);
				$report['variable']++;
			} elseif ( ! empty( $item['variants'] ) && is_array( $item['variants'] ) ) {
				$result = sevenspices_menu_upsert_variable(
					array(
						'sku'            => $sku,
						'name'           => $name,
						'description'    => $desc,
						'category_ids'   => array( $cat_id ),
						'attribute_name' => 'Option',
						'variants'       => $item['variants'],
						'category_name'  => $cat_name,
					)
				);
				$report['variable']++;
			} else {
				if ( null === $item['price'] || '' === $item['price'] ) {
					$report['notices'][] = "Skipped {$name}: missing price and variants.";
					continue;
				}
				$result = sevenspices_menu_upsert_simple(
					array(
						'sku'          => $sku,
						'name'         => $name,
						'price'        => $item['price'],
						'description'  => $desc,
						'category_ids' => array( $cat_id ),
					)
				);
				$report['simple']++;
			}

			if ( 'created' === $result['action'] ) {
				$report['created']++;
			} else {
				$report['updated']++;
			}
			$managed_ids[]        = $result['id'];
			$report['items'][]    = $result['action'] . ': ' . $name . ' (#' . $result['id'] . ')';
		}
	}

	if ( $draft_unmanaged ) {
		$q = new WP_Query(
			array(
				'post_type'      => 'product',
				'post_status'    => array( 'publish', 'private' ),
				'posts_per_page' => -1,
				'fields'         => 'ids',
			)
		);
		foreach ( $q->posts as $pid ) {
			if ( in_array( (int) $pid, $managed_ids, true ) ) {
				continue;
			}
			$product = wc_get_product( $pid );
			if ( ! $product ) {
				continue;
			}
			// Only draft previously unmanaged catalog items that look like leftovers.
			if ( $product->get_meta( '_7spices_menu_managed' ) === '1' ) {
				continue;
			}
			$product->set_status( 'draft' );
			$product->save();
			$report['drafted']++;
		}
	}

	$report['managed_ids'] = $managed_ids;
	update_option( '7spices_menu_last_sync', array(
		'time'   => time(),
		'report' => array(
			'created'  => $report['created'],
			'updated'  => $report['updated'],
			'simple'   => $report['simple'],
			'variable' => $report['variable'],
			'drafted'  => $report['drafted'],
		),
	) );

	return $report;
}

/**
 * Display service-charge / allergy notices on shop and product archives.
 */
add_action(
	'woocommerce_before_shop_loop',
	function () {
		$allergy = get_option( '7spices_allergy_notice', '' );
		$service = get_option( '7spices_service_charge_notice', '' );
		if ( ! $allergy && ! $service ) {
			return;
		}
		echo '<div class="7spices-menu-notices" style="margin:1rem 0;padding:1rem;border:1px solid #ccc;background:#fff8f0;">';
		if ( $allergy ) {
			echo '<p style="margin:0 0 .5rem;"><strong>Notice:</strong> ' . esc_html( $allergy ) . '</p>';
		}
		if ( $service ) {
			echo '<p style="margin:0;"><strong>Service charge:</strong> ' . esc_html( $service ) . '</p>';
		}
		echo '</div>';
	},
	5
);

/**
 * Admin UI.
 */
add_action(
	'admin_menu',
	function () {
		add_submenu_page(
			'woocommerce',
			'7 Spices Menu Sync',
			'7 Spices Menu Sync',
			'manage_woocommerce',
			'7spices-menu-sync',
			'sevenspices_menu_admin_page'
		);
	}
);

/**
 * Admin page renderer.
 */
function sevenspices_menu_admin_page() {
	if ( ! current_user_can( 'manage_woocommerce' ) ) {
		return;
	}

	$report = null;
	if ( isset( $_POST['7spices_menu_sync_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['7spices_menu_sync_nonce'] ) ), '7spices_menu_sync' ) ) {
		$draft = ! empty( $_POST['draft_unmanaged'] );
		$report = sevenspices_menu_sync( $draft );
	}

	$menu  = sevenspices_menu_load();
	$last  = get_option( '7spices_menu_last_sync' );
	$count = 0;
	if ( ! is_wp_error( $menu ) ) {
		foreach ( $menu['categories'] as $c ) {
			$count += count( $c['items'] );
		}
	}
	?>
	<div class="wrap">
		<h1>7 Spices Menu Sync</h1>
		<p>Syncs WooCommerce products from the embedded PDF menu JSON. Existing managed products are updated by SKU; unmatched products are never deleted (optional draft).</p>
		<?php if ( is_wp_error( $menu ) ) : ?>
			<div class="notice notice-error"><p><?php echo esc_html( $menu->get_error_message() ); ?></p></div>
		<?php else : ?>
			<p><strong>Source:</strong> <?php echo esc_html( count( $menu['categories'] ) ); ?> categories / <?php echo esc_html( (string) $count ); ?> items in <code>data/menu.json</code>.</p>
		<?php endif; ?>

		<?php if ( $last ) : ?>
			<p><strong>Last sync:</strong> <?php echo esc_html( gmdate( 'Y-m-d H:i:s', (int) $last['time'] ) ); ?> UTC
			— created <?php echo esc_html( (string) $last['report']['created'] ); ?>,
			updated <?php echo esc_html( (string) $last['report']['updated'] ); ?>,
			drafted <?php echo esc_html( (string) $last['report']['drafted'] ); ?>.</p>
		<?php endif; ?>

		<form method="post">
			<?php wp_nonce_field( '7spices_menu_sync', '7spices_menu_sync_nonce' ); ?>
			<p>
				<label>
					<input type="checkbox" name="draft_unmanaged" value="1" />
					Draft WooCommerce products that are <em>not</em> part of this PDF menu (safe rollback: set them back to Publish manually)
				</label>
			</p>
			<?php submit_button( 'Run menu sync now' ); ?>
		</form>

		<?php if ( $report ) : ?>
			<?php if ( ! empty( $report['error'] ) ) : ?>
				<div class="notice notice-error"><p><?php echo esc_html( $report['error'] ); ?></p></div>
			<?php else : ?>
				<div class="notice notice-success"><p>
					Sync complete.
					Created: <?php echo esc_html( (string) $report['created'] ); ?>,
					Updated: <?php echo esc_html( (string) $report['updated'] ); ?>,
					Simple: <?php echo esc_html( (string) $report['simple'] ); ?>,
					Variable: <?php echo esc_html( (string) $report['variable'] ); ?>,
					Drafted: <?php echo esc_html( (string) $report['drafted'] ); ?>.
				</p></div>
				<details><summary>Categories</summary><pre><?php echo esc_html( implode( "\n", $report['categories_ensured'] ) ); ?></pre></details>
				<details><summary>Items</summary><pre style="max-height:320px;overflow:auto;"><?php echo esc_html( implode( "\n", $report['items'] ) ); ?></pre></details>
				<?php if ( ! empty( $report['notices'] ) ) : ?>
					<details open><summary>Notices</summary><pre><?php echo esc_html( implode( "\n", $report['notices'] ) ); ?></pre></details>
				<?php endif; ?>
			<?php endif; ?>
		<?php endif; ?>
	</div>
	<?php
}
