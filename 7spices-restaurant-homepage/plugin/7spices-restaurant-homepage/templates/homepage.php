<?php
/**
 * 7 Spices restaurant front page.
 *
 * @package SevenSpices_Restaurant_Homepage
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$urls       = sevenspices_home_urls();
$categories = sevenspices_home_categories( 12 );
$dishes     = sevenspices_home_featured_dishes( 8 );
$count      = sevenspices_home_product_count();
$cart_count = sevenspices_home_cart_count();

$hero_image = '';
if ( ! empty( $dishes ) ) {
	foreach ( $dishes as $dish ) {
		$id = $dish->get_image_id();
		if ( $id ) {
			$hero_image = wp_get_attachment_image_url( $id, 'large' );
			if ( $hero_image ) {
				break;
			}
		}
	}
}
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<?php wp_head(); ?>
</head>
<body <?php body_class( 'spices-body' ); ?>>
<?php wp_body_open(); ?>

<a class="spices-skip" href="#spices-main">Skip to content</a>

<header class="spices-header">
	<div class="spices-wrap spices-header__inner">
		<a class="spices-logo" href="<?php echo esc_url( $urls['home'] ); ?>">
			<span class="spices-logo__mark" aria-hidden="true">7</span>
			<span class="spices-logo__text">
				<strong>Spices</strong>
				<small>Authentic Indian Restaurant &amp; Bar</small>
			</span>
		</a>
		<button class="spices-nav-toggle" type="button" aria-expanded="false" aria-controls="spices-nav" data-spices-nav-toggle>
			<span></span><span class="screen-reader-text">Menu</span>
		</button>
		<nav id="spices-nav" class="spices-nav" data-spices-nav>
			<a href="<?php echo esc_url( $urls['home'] ); ?>">Home</a>
			<a href="#menu">Our Menu</a>
			<a href="#categories">Categories</a>
			<a href="<?php echo esc_url( $urls['about'] ); ?>">About Us</a>
			<a href="<?php echo esc_url( $urls['contact'] ); ?>">Contact</a>
			<a class="spices-nav__cart" href="<?php echo esc_url( $urls['cart'] ); ?>">
				Cart<?php if ( $cart_count > 0 ) : ?><span class="spices-badge"><?php echo esc_html( (string) $cart_count ); ?></span><?php endif; ?>
			</a>
			<a class="spices-btn spices-btn--sm" href="<?php echo esc_url( $urls['shop'] ); ?>">Order Now</a>
		</nav>
	</div>
</header>

<main id="spices-main">
	<section class="spices-hero" aria-labelledby="spices-hero-title">
		<div class="spices-hero__media<?php echo $hero_image ? '' : ' spices-hero__media--fallback'; ?>" aria-hidden="true">
			<?php if ( $hero_image ) : ?>
				<img src="<?php echo esc_url( $hero_image ); ?>" alt="" loading="eager" />
			<?php endif; ?>
			<div class="spices-hero__veil"></div>
		</div>
		<div class="spices-wrap spices-hero__content">
			<p class="spices-brand">7 Spices</p>
			<h1 id="spices-hero-title">Delicious Food. Unforgettable Moments.</h1>
			<p class="spices-hero__lede">Explore our menu, discover your favourites, and enjoy delicious food prepared to make every meal special.</p>
			<div class="spices-hero__actions">
				<a class="spices-btn" href="#menu">Explore Our Menu</a>
				<a class="spices-btn spices-btn--ghost" href="<?php echo esc_url( $urls['shop'] ); ?>">Order Now</a>
			</div>
			<?php if ( $count > 0 ) : ?>
				<p class="spices-hero__meta"><?php echo esc_html( number_format_i18n( $count ) ); ?> dishes available to order online</p>
			<?php endif; ?>
		</div>
	</section>

	<section id="categories" class="spices-section spices-categories" aria-labelledby="spices-cat-title">
		<div class="spices-wrap">
			<header class="spices-section__head">
				<p class="spices-eyebrow">Our Menu</p>
				<h2 id="spices-cat-title">Explore Our Menu</h2>
				<p>Browse categories from our live restaurant catalogue.</p>
			</header>
			<?php if ( $categories ) : ?>
				<ul class="spices-cat-scroller" role="list">
					<?php foreach ( $categories as $term ) : ?>
						<?php
						$img = sevenspices_home_category_image( $term );
						$link = get_term_link( $term );
						if ( is_wp_error( $link ) ) {
							continue;
						}
						?>
						<li>
							<a class="spices-cat" href="<?php echo esc_url( $link ); ?>">
								<span class="spices-cat__img">
									<?php if ( $img ) : ?>
										<img src="<?php echo esc_url( $img ); ?>" alt="" loading="lazy" />
									<?php else : ?>
										<span class="spices-cat__fallback" aria-hidden="true"></span>
									<?php endif; ?>
								</span>
								<span class="spices-cat__body">
									<span class="spices-cat__name"><?php echo esc_html( $term->name ); ?></span>
									<span class="spices-cat__count"><?php echo esc_html( sprintf( _n( '%s item', '%s items', (int) $term->count, '7spices-restaurant-homepage' ), number_format_i18n( (int) $term->count ) ) ); ?></span>
								</span>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php else : ?>
				<p class="spices-empty">Menu categories will appear here once dishes are published.</p>
			<?php endif; ?>
		</div>
	</section>

	<section id="menu" class="spices-section spices-featured" aria-labelledby="spices-feat-title">
		<div class="spices-wrap">
			<header class="spices-section__head">
				<p class="spices-eyebrow">From the kitchen</p>
				<h2 id="spices-feat-title">Customer Favourites</h2>
				<p>Featured dishes from our current menu — prices and descriptions come from the live catalogue.</p>
			</header>
			<?php if ( $dishes ) : ?>
				<ul class="spices-dish-grid">
					<?php foreach ( $dishes as $product ) : ?>
						<?php
						$image_id = $product->get_image_id();
						$terms    = get_the_terms( $product->get_id(), 'product_cat' );
						$cat_name = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0]->name : '';
						?>
						<li class="spices-dish">
							<a class="spices-dish__media" href="<?php echo esc_url( $product->get_permalink() ); ?>">
								<?php
								if ( $image_id ) {
									echo wp_get_attachment_image( $image_id, 'medium_large', false, array( 'class' => 'spices-dish__img', 'loading' => 'lazy', 'alt' => esc_attr( $product->get_name() ) ) );
								} else {
									echo '<span class="spices-dish__fallback" aria-hidden="true"></span>';
								}
								?>
							</a>
							<div class="spices-dish__body">
								<?php if ( $cat_name ) : ?>
									<p class="spices-dish__cat"><?php echo esc_html( $cat_name ); ?></p>
								<?php endif; ?>
								<h3><a href="<?php echo esc_url( $product->get_permalink() ); ?>"><?php echo esc_html( $product->get_name() ); ?></a></h3>
								<?php if ( $product->get_short_description() ) : ?>
									<p class="spices-dish__desc"><?php echo esc_html( wp_strip_all_tags( wp_trim_words( $product->get_short_description(), 16 ) ) ); ?></p>
								<?php endif; ?>
								<div class="spices-dish__foot">
									<span class="spices-dish__price"><?php echo wp_kses_post( $product->get_price_html() ); ?></span>
									<a class="spices-btn spices-btn--sm spices-btn--ghost" href="<?php echo esc_url( $product->get_permalink() ); ?>">View Dish</a>
								</div>
							</div>
						</li>
					<?php endforeach; ?>
				</ul>
				<p class="spices-section__more">
					<a class="spices-btn spices-btn--ghost" href="<?php echo esc_url( $urls['shop'] ); ?>">View Full Menu</a>
				</p>
			<?php else : ?>
				<p class="spices-empty">No menu items are published yet.</p>
			<?php endif; ?>
		</div>
	</section>

	<section class="spices-section spices-why" aria-labelledby="spices-why-title">
		<div class="spices-wrap spices-why__grid">
			<div>
				<p class="spices-eyebrow">Why 7 Spices</p>
				<h2 id="spices-why-title">A warmer welcome to Indian dining</h2>
				<p>From tandoor and curry to Indo-Chinese favourites, our menu is organised so you can find what you love and order with confidence.</p>
			</div>
			<ul class="spices-why__list">
				<li>
					<strong>Browse by category</strong>
					<span>Tandoor, curry, vegetarian, breads, desserts, and more.</span>
				</li>
				<li>
					<strong>Clear prices</strong>
					<span>Portion options and protein choices shown on dish pages where available.</span>
				</li>
				<li>
					<strong>Order online</strong>
					<span>Add dishes to your cart and checkout through the existing shop.</span>
				</li>
			</ul>
		</div>
	</section>

	<section class="spices-section spices-visit" aria-labelledby="spices-visit-title">
		<div class="spices-wrap spices-visit__panel">
			<div>
				<h2 id="spices-visit-title">Ready to order?</h2>
				<p>Explore the full menu, add your favourites, and complete your order online.</p>
			</div>
			<div class="spices-hero__actions">
				<a class="spices-btn" href="<?php echo esc_url( $urls['shop'] ); ?>">Order Now</a>
				<a class="spices-btn spices-btn--ghost-light" href="<?php echo esc_url( $urls['contact'] ); ?>">Contact Us</a>
			</div>
		</div>
	</section>
</main>

<footer class="spices-footer">
	<div class="spices-wrap spices-footer__grid">
		<div>
			<p class="spices-logo spices-logo--footer">
				<span class="spices-logo__mark" aria-hidden="true">7</span>
				<span class="spices-logo__text"><strong>Spices</strong><small>Authentic Indian Restaurant &amp; Bar</small></span>
			</p>
			<p class="spices-footer__tag">Indian restaurant dining with an online menu and ordering experience.</p>
		</div>
		<nav aria-label="Footer">
			<a href="<?php echo esc_url( $urls['shop'] ); ?>">Our Menu</a>
			<a href="#categories">Categories</a>
			<a href="<?php echo esc_url( $urls['about'] ); ?>">About Us</a>
			<a href="<?php echo esc_url( $urls['contact'] ); ?>">Contact</a>
			<a href="<?php echo esc_url( $urls['cart'] ); ?>">Cart</a>
			<a href="<?php echo esc_url( $urls['account'] ); ?>">Account</a>
		</nav>
	</div>
	<div class="spices-wrap spices-footer__base">
		<p>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> 7 Spices Authentic Indian Restaurant and Bar</p>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
