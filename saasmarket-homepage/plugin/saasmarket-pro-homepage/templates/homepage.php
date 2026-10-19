<?php
/**
 * SaaSMarket Pro front-page template.
 *
 * @package SaaSMarket_Pro_Homepage
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$urls      = smp_home_urls();
$count     = smp_home_product_count();
$categories = smp_home_categories( 8 );
$products  = smp_home_featured_products( 8 );
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<?php wp_head(); ?>
</head>
<body <?php body_class( 'smp-body' ); ?>>
<?php wp_body_open(); ?>

<a class="smp-skip" href="#smp-main">Skip to content</a>

<header class="smp-header">
	<div class="smp-wrap smp-header__inner">
		<a class="smp-logo" href="<?php echo esc_url( $urls['home'] ); ?>" aria-label="SaaSMarket Pro home">
			<span class="smp-logo__mark" aria-hidden="true"></span>
			<span class="smp-logo__text">SaaSMarket <strong>Pro</strong></span>
		</a>
		<button class="smp-nav-toggle" type="button" aria-expanded="false" aria-controls="smp-nav" data-smp-nav-toggle>
			<span class="smp-nav-toggle__bar"></span>
			<span class="screen-reader-text">Menu</span>
		</button>
		<nav id="smp-nav" class="smp-nav" data-smp-nav>
			<a href="<?php echo esc_url( $urls['shop'] ); ?>">Explore Software</a>
			<a href="#categories">Categories</a>
			<a href="<?php echo esc_url( $urls['about'] ); ?>">About</a>
			<a href="<?php echo esc_url( $urls['contact'] ); ?>">Contact</a>
			<a class="smp-nav__ghost" href="<?php echo esc_url( $urls['login'] ); ?>">Login</a>
			<a class="smp-btn smp-btn--sm" href="<?php echo esc_url( $urls['account'] ); ?>">Get Started</a>
		</nav>
	</div>
</header>

<main id="smp-main">
	<section class="smp-hero" aria-labelledby="smp-hero-heading">
		<div class="smp-hero__glow" aria-hidden="true"></div>
		<div class="smp-wrap smp-hero__grid">
			<div class="smp-hero__copy">
				<p class="smp-brand-kicker">SaaSMarket Pro</p>
				<h1 id="smp-hero-heading">Discover the Right Software. Simplify Your Business.</h1>
				<p class="smp-hero__lede">Explore powerful software solutions designed to help businesses manage customers, automate workflows, improve productivity, and grow with confidence.</p>
				<div class="smp-hero__actions">
					<a class="smp-btn" href="<?php echo esc_url( $urls['shop'] ); ?>">Explore Software</a>
					<a class="smp-btn smp-btn--ghost" href="<?php echo esc_url( $urls['account'] ); ?>">Get Started</a>
				</div>
				<?php if ( $count > 0 ) : ?>
					<p class="smp-hero__meta"><?php echo esc_html( number_format_i18n( $count ) ); ?> listings currently available in the catalogue</p>
				<?php endif; ?>
			</div>
			<div class="smp-hero__visual" aria-hidden="true">
				<?php include SMP_HOME_DIR . 'assets/svg/hero-dashboard.svg'; ?>
			</div>
		</div>
	</section>

	<section id="categories" class="smp-section smp-categories" aria-labelledby="smp-cat-heading">
		<div class="smp-wrap">
			<header class="smp-section__head">
				<h2 id="smp-cat-heading">Everything Your Business Needs in One Place</h2>
				<p>Browse live catalogue categories available on this marketplace staging site.</p>
			</header>
			<?php if ( $categories ) : ?>
				<ul class="smp-cat-grid">
					<?php foreach ( $categories as $term ) : ?>
						<li>
							<a class="smp-cat" href="<?php echo esc_url( get_term_link( $term ) ); ?>">
								<span class="smp-cat__icon" aria-hidden="true">
									<svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.75"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></svg>
								</span>
								<span class="smp-cat__name"><?php echo esc_html( $term->name ); ?></span>
								<span class="smp-cat__meta"><?php echo esc_html( sprintf( _n( '%s listing', '%s listings', (int) $term->count, 'saasmarket-pro-homepage' ), number_format_i18n( (int) $term->count ) ) ); ?></span>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php else : ?>
				<p class="smp-empty">Catalogue categories will appear here once products are published.</p>
			<?php endif; ?>
		</div>
	</section>

	<section class="smp-section smp-featured" aria-labelledby="smp-feat-heading">
		<div class="smp-wrap">
			<header class="smp-section__head">
				<h2 id="smp-feat-heading">Explore Listings Built for Business Growth</h2>
				<p>Featured items from the live WooCommerce catalogue — names, descriptions, and prices are pulled from real product records.</p>
			</header>
			<?php if ( $products ) : ?>
				<ul class="smp-product-grid">
					<?php foreach ( $products as $product ) : ?>
						<?php
						$image_id = $product->get_image_id();
						$image    = $image_id ? wp_get_attachment_image( $image_id, 'medium', false, array( 'class' => 'smp-product__img', 'loading' => 'lazy', 'alt' => esc_attr( $product->get_name() ) ) ) : '';
						$terms    = get_the_terms( $product->get_id(), 'product_cat' );
						$cat_name = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0]->name : '';
						?>
						<li class="smp-product">
							<a class="smp-product__media" href="<?php echo esc_url( $product->get_permalink() ); ?>">
								<?php
								if ( $image ) {
									echo $image; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								} else {
									echo '<span class="smp-product__placeholder" aria-hidden="true"></span>';
								}
								?>
							</a>
							<div class="smp-product__body">
								<?php if ( $cat_name ) : ?>
									<p class="smp-product__cat"><?php echo esc_html( $cat_name ); ?></p>
								<?php endif; ?>
								<h3 class="smp-product__title">
									<a href="<?php echo esc_url( $product->get_permalink() ); ?>"><?php echo esc_html( $product->get_name() ); ?></a>
								</h3>
								<?php if ( $product->get_short_description() ) : ?>
									<p class="smp-product__desc"><?php echo esc_html( wp_strip_all_tags( wp_trim_words( $product->get_short_description(), 18 ) ) ); ?></p>
								<?php endif; ?>
								<div class="smp-product__foot">
									<span class="smp-product__price"><?php echo wp_kses_post( $product->get_price_html() ); ?></span>
									<a class="smp-btn smp-btn--sm smp-btn--ghost" href="<?php echo esc_url( $product->get_permalink() ); ?>">View Details</a>
								</div>
							</div>
						</li>
					<?php endforeach; ?>
				</ul>
				<p class="smp-section__more">
					<a class="smp-btn smp-btn--ghost" href="<?php echo esc_url( $urls['shop'] ); ?>">View full catalogue</a>
				</p>
			<?php else : ?>
				<p class="smp-empty">No published catalogue items are available yet.</p>
			<?php endif; ?>
		</div>
	</section>

	<section class="smp-section smp-why" aria-labelledby="smp-why-heading">
		<div class="smp-wrap">
			<header class="smp-section__head">
				<h2 id="smp-why-heading">Smarter Software Choices. Better Business Outcomes.</h2>
			</header>
			<ul class="smp-benefit-grid">
				<li>
					<h3>Discover Solutions Faster</h3>
					<p>Find listings that match your business needs through clear categories and product pages.</p>
				</li>
				<li>
					<h3>Compare Your Options</h3>
					<p>Review available product information, descriptions, and pricing before you decide.</p>
				</li>
				<li>
					<h3>Simplify Business Operations</h3>
					<p>Explore tools and offerings that help streamline everyday work and decision-making.</p>
				</li>
				<li>
					<h3>Grow With Confidence</h3>
					<p>Use a structured catalogue and account area as your business needs develop.</p>
				</li>
			</ul>
		</div>
	</section>

	<section class="smp-section smp-steps" aria-labelledby="smp-steps-heading">
		<div class="smp-wrap">
			<header class="smp-section__head">
				<h2 id="smp-steps-heading">Get Started in Three Simple Steps</h2>
			</header>
			<ol class="smp-steps__list">
				<li>
					<span class="smp-steps__num">1</span>
					<h3>Explore</h3>
					<p>Browse available listings and categories in the marketplace catalogue.</p>
				</li>
				<li>
					<span class="smp-steps__num">2</span>
					<h3>Compare</h3>
					<p>Review descriptions, features, and available pricing on each product page.</p>
				</li>
				<li>
					<span class="smp-steps__num">3</span>
					<h3>Choose</h3>
					<p>Open product details and continue with account or purchase options already available on this site.</p>
				</li>
			</ol>
		</div>
	</section>

	<section class="smp-section smp-usecases" aria-labelledby="smp-use-heading">
		<div class="smp-wrap">
			<header class="smp-section__head">
				<h2 id="smp-use-heading">Find Solutions for Your Business Needs</h2>
				<p>Start from the audience that fits you, then continue into the live catalogue.</p>
			</header>
			<ul class="smp-use-grid">
				<?php
				$uses = array(
					'Startups'                 => 'Launch lean with tools that keep early operations organised.',
					'Small and medium businesses' => 'Find practical solutions for day-to-day management.',
					'Sales teams'              => 'Explore listings that support customer and pipeline work.',
					'Marketing teams'          => 'Discover options that help plan campaigns and outreach.',
					'Service businesses'       => 'Browse offerings that fit client delivery workflows.',
					'Growing enterprises'      => 'Scale discovery as your team and catalogue needs expand.',
				);
				foreach ( $uses as $title => $text ) :
					?>
					<li>
						<a class="smp-use" href="<?php echo esc_url( $urls['shop'] ); ?>">
							<h3><?php echo esc_html( $title ); ?></h3>
							<p><?php echo esc_html( $text ); ?></p>
							<span class="smp-use__cta">Browse catalogue</span>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</section>

	<section class="smp-section smp-trust" aria-labelledby="smp-trust-heading">
		<div class="smp-wrap smp-trust__inner">
			<div>
				<h2 id="smp-trust-heading">Make Better-Informed Software Decisions</h2>
				<p>SaaSMarket Pro focuses on clear catalogue information, working product pages, and direct paths to support.</p>
				<ul class="smp-trust__list">
					<li><?php echo esc_html( number_format_i18n( max( $count, 0 ) ) ); ?> published catalogue listings</li>
					<li><?php echo esc_html( (string) count( $categories ) ); ?> active categories with live inventory</li>
					<li>Contact and account access available on this staging site</li>
				</ul>
			</div>
			<div class="smp-trust__panel">
				<p>Need help choosing a direction?</p>
				<a class="smp-btn" href="<?php echo esc_url( $urls['contact'] ); ?>">Contact the team</a>
				<a class="smp-text-link" href="<?php echo esc_url( $urls['about'] ); ?>">About us</a>
			</div>
		</div>
	</section>

	<section class="smp-cta" aria-labelledby="smp-cta-heading">
		<div class="smp-wrap smp-cta__inner">
			<h2 id="smp-cta-heading">Ready to Find Your Next Business Solution?</h2>
			<p>Explore the available catalogue and find tools that fit your workflow.</p>
			<div class="smp-hero__actions">
				<a class="smp-btn" href="<?php echo esc_url( $urls['shop'] ); ?>">Explore Software</a>
				<a class="smp-btn smp-btn--ghost-light" href="<?php echo esc_url( $urls['account'] ); ?>">Create Your Account</a>
			</div>
		</div>
	</section>
</main>

<footer class="smp-footer">
	<div class="smp-wrap smp-footer__grid">
		<div>
			<p class="smp-logo smp-logo--footer"><span class="smp-logo__mark" aria-hidden="true"></span><span class="smp-logo__text">SaaSMarket <strong>Pro</strong></span></p>
			<p class="smp-footer__tag">A marketplace experience for discovering business solutions.</p>
		</div>
		<nav aria-label="Footer">
			<a href="<?php echo esc_url( $urls['shop'] ); ?>">Explore Software</a>
			<a href="#categories">Categories</a>
			<a href="<?php echo esc_url( $urls['about'] ); ?>">About Us</a>
			<a href="<?php echo esc_url( $urls['contact'] ); ?>">Contact</a>
			<a href="<?php echo esc_url( $urls['services'] ); ?>">Help / Services</a>
			<a href="<?php echo esc_url( $urls['login'] ); ?>">Login</a>
			<a href="<?php echo esc_url( $urls['account'] ); ?>">Register</a>
		</nav>
	</div>
	<div class="smp-wrap smp-footer__base">
		<p>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> SaaSMarket Pro. Staging environment.</p>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
