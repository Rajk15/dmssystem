<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function knox_ac_btn( $label, $url, $class = 'knox-btn-primary' ) {
	return '<a class="knox-btn ' . esc_attr( $class ) . '" href="' . esc_url( $url ) . '">' . esc_html( $label ) . '</a>';
}

function knox_ac_split( $image_key, $alt, $heading, $body_html, $image_left = true, $alt_bg = false, $ctas = '' ) {
	$classes = 'knox-section' . ( $alt_bg ? ' is-alt' : '' );
	$split   = 'knox-split' . ( $image_left ? '' : ' reverse' );
	$media   = knox_ac_figure( $image_key, $alt );
	ob_start();
	?>
	<section class="<?php echo esc_attr( $classes ); ?>">
		<div class="knox-wrap">
			<div class="<?php echo esc_attr( $split ); ?>">
				<div class="knox-split-media"><?php echo $media; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
				<div class="knox-split-copy">
					<?php echo $heading; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php echo $body_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php if ( $ctas ) : ?>
						<div class="knox-cta-row"><?php echo $ctas; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</section>
	<?php
	return ob_get_clean();
}

function knox_ac_url( $path ) {
	return home_url( '/' . ltrim( $path, '/' ) );
}

function knox_ac_page_home() {
	$contact = knox_ac_url( 'contact-us/' );
	$windows = knox_ac_url( 'windows/' );
	$doors   = knox_ac_url( 'doors/' );
	$commercial = knox_ac_url( 'commercial-projects/' );
	$process = knox_ac_url( 'process/' );

	$html  = '<div class="knox-page">';
	$html .= '<section class="knox-hero"><div class="knox-wrap">';
	$html .= '<h1>Architectural Aluminium Windows &amp; Doors — Designed for Performance. Built in Melbourne.</h1>';
	$html .= '<p>Knox Aluminium Windows &amp; Doors delivers premium, Australian-made aluminium systems engineered for residential, commercial, and architectural projects. With over 25 years of industry experience, we manufacture and install high-performance windows, doors, shopfronts, and commercial framing solutions trusted by builders, developers, architects, and homeowners across Victoria.</p>';
	$html .= '<div class="knox-hero-ctas">';
	$html .= knox_ac_btn( 'Request a Free Quote', $contact, 'knox-btn-primary' );
	$html .= knox_ac_btn( 'Book a Site Consultation', $contact, 'knox-btn-secondary' );
	$html .= knox_ac_btn( 'Explore Window Options', $windows, 'knox-btn-secondary' );
	$html .= '</div></div></section>';

	$html .= knox_ac_split(
		'project_1',
		'Aluminium window and door installation project by Knox Aluminium',
		'<span class="knox-kicker">Why Choose Knox Aluminium</span><h2>Precision Engineering. Architectural Quality. Commercial Strength.</h2>',
		'<ul>
			<li>100% Australian-Made Aluminium Systems</li>
			<li>25+ Years of Industry Experience</li>
			<li>Commercial-Grade Manufacturing</li>
			<li>Qualified Builders &amp; Glaziers</li>
			<li>Custom Fabrication for Any Project</li>
			<li>10-Year Product Warranty</li>
			<li>10-Year Installation Warranty</li>
			<li>Fast Turnaround &amp; Reliable Lead Times</li>
		</ul>',
		true,
		false,
		knox_ac_btn( 'Contact Our Team', $contact, 'knox-btn-dark' )
	);

	$html .= '<section class="knox-section is-alt"><div class="knox-wrap">';
	$html .= '<h2>Our Product Range</h2><p>Premium aluminium systems engineered for performance.</p>';
	$html .= '<div class="knox-grid-4">';
	$html .= '<div class="knox-card"><h3>Windows</h3><ul><li>Sashless Double Hung</li><li>Commercial Sliding</li><li>Awning &amp; Casement</li><li>Fixed Architectural Windows</li></ul><p><a href="' . esc_url( $windows ) . '">Explore Window Options</a></p></div>';
	$html .= '<div class="knox-card"><h3>Doors</h3><ul><li>Commercial Multi-Fold</li><li>High-Performance Sliding</li><li>Ultra-Flat Sliding</li><li>Top-Hung Commercial Sliding</li><li>Shopfront Doors (Swan Evo, Torrens)</li></ul><p><a href="' . esc_url( $doors ) . '">View Door Systems</a></p></div>';
	$html .= '<div class="knox-card"><h3>Commercial Systems</h3><ul><li>Shopfront Framing</li><li>Curtain Wall Systems</li><li>Architectural Framing</li><li>Thermally Broken Systems</li></ul><p><a href="' . esc_url( $commercial ) . '">Commercial Projects</a></p></div>';
	$html .= '<div class="knox-card"><h3>Security &amp; Screening</h3><ul><li>Stainless Steel Mesh</li><li>Invisi-Gard Style Systems</li><li>Flyscreens</li><li>KidScreen Fall Prevention</li></ul><p><a href="' . esc_url( $contact ) . '">Request a Free Quote</a></p></div>';
	$html .= '</div></div></section>';

	$html .= knox_ac_split(
		'project_2',
		'Commercial and residential aluminium glazing suited to Melbourne conditions',
		'<h2>Built for Melbourne Conditions</h2>',
		'<p>Our aluminium systems are engineered for:</p>
		<ul>
			<li>High-wind commercial sites</li>
			<li>Coastal environments</li>
			<li>High-traffic retail spaces</li>
			<li>Multi-residential developments</li>
			<li>Architectural homes</li>
			<li>Industrial buildings</li>
		</ul>',
		false,
		false,
		knox_ac_btn( 'See Our Process', $process, 'knox-btn-dark' ) . knox_ac_btn( 'Request a Free Quote', $contact, 'knox-btn-primary' )
	);

	$html .= '<section class="knox-section is-alt"><div class="knox-wrap knox-narrow">';
	$html .= '<h2>Ready to upgrade your windows and doors?</h2>';
	$html .= '<p>Speak with our specialists today about residential, commercial, and architectural aluminium systems manufactured in Melbourne.</p>';
	$html .= '<div class="knox-cta-row">' . knox_ac_btn( 'Request a Free Quote', $contact, 'knox-btn-primary' ) . knox_ac_btn( 'Contact Our Team', $contact, 'knox-btn-dark' ) . '</div>';
	$html .= '</div></section></div>';

	return $html;
}

function knox_ac_page_about() {
	$contact = knox_ac_url( 'contact-us/' );
	$warranty = knox_ac_url( 'warranty/' );

	$html  = '<div class="knox-page">';
	$html .= knox_ac_split(
		'project_3',
		'Knox Aluminium manufacturing and installation craftsmanship',
		'<h1>About Knox Aluminium Windows &amp; Doors</h1>',
		'<p>Knox Aluminium Windows &amp; Doors is a Melbourne-based manufacturer specialising in premium aluminium window and door systems for residential, commercial, and architectural projects. With more than 25 years of experience, we combine precision engineering with industry-leading craftsmanship to deliver products that meet the highest standards of performance, durability, and design.</p>',
		true,
		false,
		''
	);

	$html .= knox_ac_split(
		'service_2',
		'Qualified builders and glaziers installing aluminium systems',
		'<h2>Local manufacturing. Qualified installation.</h2>',
		'<p>Our team includes qualified builders, glaziers, and commercial installers who understand the demands of modern construction. Every product is manufactured locally using high-grade aluminium and advanced fabrication processes, ensuring consistent quality across all projects — from suburban homes to large-scale commercial developments.</p>
		<p>We proudly offer a 10-year product warranty and 10-year installation warranty, giving clients confidence in the long-term performance of our systems.</p>',
		false,
		true,
		knox_ac_btn( 'View Our Warranty', $warranty, 'knox-btn-dark' ) . knox_ac_btn( 'Request a Free Quote', $contact, 'knox-btn-primary' )
	);

	$html .= '</div>';
	return $html;
}

function knox_ac_page_windows() {
	$contact = knox_ac_url( 'contact-us/' );
	$doors   = knox_ac_url( 'doors/' );

	$systems = array(
		array(
			'key'  => 'project_1',
			'alt'  => 'Sashless double hung aluminium window',
			'title'=> 'Pro-Vista™ Sashless Double Hung Window',
			'body' => '<p>A minimalist sashless design offering uninterrupted views and smooth vertical operation.</p>',
			'left' => true,
		),
		array(
			'key'  => 'landing_2',
			'alt'  => 'Commercial sliding aluminium window',
			'title'=> 'View-Max® Commercial Sliding Window',
			'body' => '<p>A commercial-grade sliding window engineered for wide openings, smooth movement, and long-term durability.</p>',
			'left' => false,
		),
		array(
			'key'  => 'casement',
			'alt'  => 'Awning and casement aluminium window',
			'title'=> 'ProTilt® Awning &amp; Casement Window',
			'body' => '<p>A versatile system offering excellent ventilation, strong sealing performance, and modern styling.</p>',
			'left' => true,
		),
		array(
			'key'  => 'landing_3',
			'alt'  => 'Standard awning and casement window for suburban homes',
			'title'=> 'Standard Awning &amp; Casement Window',
			'body' => '<p>A reliable, cost-effective solution for suburban homes requiring dependable airflow and durability.</p>',
			'left' => false,
		),
		array(
			'key'  => 'landing_5',
			'alt'  => 'Fixed architectural aluminium window facade',
			'title'=> 'Fixed Architectural Window',
			'body' => '<p>A clean, modern fixed window designed for large glazing areas and high-performance façades.</p>',
			'left' => true,
		),
	);

	$html  = '<div class="knox-page">';
	$html .= knox_ac_split(
		'cascading',
		'Architectural aluminium windows for residential and commercial buildings',
		'<h1>Architectural Aluminium Windows</h1>',
		'<p><strong>Designed for performance, engineered for longevity.</strong></p>
		<p>Knox Aluminium manufactures a complete range of aluminium window systems suitable for residential homes, apartments, commercial buildings, and architectural façades. Our windows deliver exceptional strength, weather protection, thermal performance, and modern aesthetics.</p>',
		true,
		false,
		knox_ac_btn( 'Request a Free Quote', $contact, 'knox-btn-primary' ) . knox_ac_btn( 'View Door Systems', $doors, 'knox-btn-dark' )
	);

	$html .= '<section class="knox-section is-alt"><div class="knox-wrap"><h2>Our Window Systems</h2></div></section>';

	$i = 0;
	foreach ( $systems as $sys ) {
		$html .= knox_ac_split(
			$sys['key'],
			$sys['alt'],
			'<h3>' . $sys['title'] . '</h3>',
			$sys['body'],
			$sys['left'],
			( $i % 2 === 1 ),
			( $i === count( $systems ) - 1 ) ? knox_ac_btn( 'Request a Free Quote', $contact, 'knox-btn-primary' ) : ''
		);
		$i++;
	}

	$html .= '</div>';
	return $html;
}

function knox_ac_page_doors() {
	$contact = knox_ac_url( 'contact-us/' );
	$windows = knox_ac_url( 'windows/' );

	$systems = array(
		array( 'service_4', 'Commercial multi-fold aluminium door', 'Hawkesbury® Commercial Multi-Fold Door', '<p>A premium multi-fold system ideal for large openings and seamless indoor-outdoor transitions.</p>', true ),
		array( 'landing_8', 'Top hung commercial sliding door', 'Hawkesbury® Top Hung Commercial Sliding Door', '<p>A heavy-duty sliding door engineered for commercial environments requiring wide spans and smooth movement.</p>', false ),
		array( 'landing_9', 'High-performance sliding aluminium door', 'ProGlide® High-Performance Sliding Door', '<p>A refined sliding door system featuring upgraded sill sections for improved strength and weather protection.</p>', true ),
		array( 'service_1', 'Ultra-flat low-profile sliding door', 'ProGlide™ Ultra-Flat Sliding Door', '<p>A sleek, low-profile sliding door designed for expansive openings and modern architectural designs.</p>', false ),
		array( 'landing_6', 'Apartment sliding door for multi-residential buildings', 'Altitude® Apartment Sliding Door', '<p>A high-performance sliding door tailored for multi-residential buildings.</p>', true ),
		array( 'landing_7', 'Swan Evo commercial shopfront door', 'Swan Evo™ Shopfront Door', '<p>A commercial shopfront door designed for retail and commercial entryways.</p>', false ),
		array( 'service_3', 'Torrens shopfront door configurations', 'Torrens Shopfront Door', '<p>A versatile commercial door suitable for hinged, pivot, or sliding configurations.</p>', true ),
	);

	$html  = '<div class="knox-page">';
	$html .= knox_ac_split(
		'project_4',
		'Architectural aluminium doors for residential and commercial projects',
		'<h1>Architectural Aluminium Doors</h1>',
		'<p><strong>Engineered for strength, performance, and architectural impact.</strong></p>
		<p>Knox Aluminium manufactures premium aluminium door systems designed for residential, commercial, and industrial environments. Our doors deliver smooth operation, wide openings, and long-term durability.</p>',
		true,
		false,
		knox_ac_btn( 'Request a Free Quote', $contact, 'knox-btn-primary' ) . knox_ac_btn( 'Explore Window Options', $windows, 'knox-btn-dark' )
	);

	$html .= '<section class="knox-section is-alt"><div class="knox-wrap"><h2>Our Door Systems</h2></div></section>';

	foreach ( $systems as $i => $sys ) {
		$html .= knox_ac_split(
			$sys[0],
			$sys[1],
			'<h3>' . $sys[2] . '</h3>',
			$sys[3],
			$sys[4],
			( $i % 2 === 1 ),
			( $i === count( $systems ) - 1 ) ? knox_ac_btn( 'Request a Free Quote', $contact, 'knox-btn-primary' ) : ''
		);
	}

	$html .= '</div>';
	return $html;
}

function knox_ac_page_commercial() {
	$contact = knox_ac_url( 'contact-us/' );
	$html  = '<div class="knox-page">';
	$html .= knox_ac_split(
		'landing_1',
		'Commercial aluminium shopfront and framing systems',
		'<h1>Commercial Aluminium Systems</h1>',
		'<p><strong>Built for strength. Engineered for performance.</strong></p>
		<p>Knox Aluminium specialises in commercial aluminium systems designed for retail stores, offices, hospitality venues, industrial buildings, and multi-residential developments.</p>',
		true,
		false,
		knox_ac_btn( 'Request a Free Quote', $contact, 'knox-btn-primary' )
	);

	$html .= knox_ac_split(
		'project_2',
		'Commercial aluminium framing and glazing project',
		'<h2>Commercial Solutions</h2>',
		'<ul>
			<li>Shopfront Framing</li>
			<li>Curtain Wall Systems</li>
			<li>Architectural Framing</li>
			<li>Thermally Broken Systems</li>
			<li>Commercial Sliding &amp; Multi-Fold Doors</li>
			<li>Security Screens</li>
			<li>Commercial Windows</li>
		</ul>
		<p>We work closely with builders, architects, and developers to deliver high-performance solutions that meet structural, thermal, and aesthetic requirements.</p>',
		false,
		true,
		knox_ac_btn( 'Contact Our Team', $contact, 'knox-btn-dark' )
	);
	$html .= '</div>';
	return $html;
}

function knox_ac_page_process() {
	$contact = knox_ac_url( 'contact-us/' );
	$html  = '<div class="knox-page">';
	$html .= knox_ac_split(
		'service_2',
		'Site measurement and quotation process for aluminium windows and doors',
		'<h1>Our Process</h1>',
		'<p><strong>A streamlined workflow designed for accuracy and efficiency.</strong></p>
		<ol class="knox-steps">
			<li><strong>Site Measurement</strong> — A qualified installer visits your site to take precise measurements.</li>
			<li><strong>Detailed Quotation</strong> — You receive a transparent, itemised quote tailored to your project.</li>
			<li><strong>Local Manufacturing</strong> — All products are custom-made in Melbourne using premium aluminium.</li>
			<li><strong>Professional Installation</strong> — Our team installs your windows and doors with precision and care.</li>
			<li><strong>Final Inspection</strong> — We ensure every system meets our quality standards before handover.</li>
		</ol>',
		true,
		false,
		knox_ac_btn( 'Request a Free Quote', $contact, 'knox-btn-primary' ) . knox_ac_btn( 'Book a Site Consultation', $contact, 'knox-btn-dark' )
	);
	$html .= '</div>';
	return $html;
}

function knox_ac_page_contact() {
	$html  = '<div class="knox-page"><section class="knox-section"><div class="knox-wrap">';
	$html .= '<h1>Contact Knox Aluminium Windows &amp; Doors</h1>';
	$html .= '<div class="knox-contact-grid">';
	$html .= '<div><p>Speak with our team about residential, commercial, and architectural aluminium windows and doors.</p>';
	$html .= '<dl class="knox-contact-details">';
	$html .= '<dt>Phone</dt><dd><a href="tel:0439202020">0439 202 020</a></dd>';
	$html .= '<dt>Email</dt><dd><a href="mailto:sales@knoxaluminium.com.au">sales@knoxaluminium.com.au</a></dd>';
	$html .= '<dt>Address</dt><dd>4/573 Burwood Hwy, Knoxfield VIC</dd>';
	$html .= '<dt>Hours</dt><dd>Mon–Thu: 8am–4pm<br>Fri: 8am–1:30pm</dd>';
	$html .= '</dl>';
	$html .= '<div class="knox-cta-row" style="margin-top:1.5rem">' . knox_ac_btn( 'Explore Window Options', knox_ac_url( 'windows/' ), 'knox-btn-dark' ) . knox_ac_btn( 'View Door Systems', knox_ac_url( 'doors/' ), 'knox-btn-dark' ) . '</div>';
	$html .= '</div><div>';
	$html .= '<h2>Request a Free Quote</h2>';
	$html .= '<p>Share your project details and we will follow up with a tailored response.</p>';
	$html .= do_shortcode( '[contact-form-7 id="4191" title="Request a Quote Form"]' );
	$html .= '</div></div></div></section></div>';
	return $html;
}

function knox_ac_page_warranty() {
	$contact = knox_ac_url( 'contact-us/' );
	$html  = '<div class="knox-page"><section class="knox-section"><div class="knox-wrap knox-narrow">';
	$html .= '<h1>Our Warranty</h1>';
	$html .= '<p>All Knox Aluminium products are backed by a 10-year product warranty and a 10-year installation warranty when installed by our qualified team.</p>';
	$html .= '<div class="knox-cta-row">' . knox_ac_btn( 'Request a Free Quote', $contact, 'knox-btn-primary' ) . knox_ac_btn( 'Contact Our Team', $contact, 'knox-btn-dark' ) . '</div>';
	$html .= '</div></section></div>';
	return $html;
}

function knox_ac_page_gallery() {
	$html  = '<div class="knox-page"><section class="knox-section"><div class="knox-wrap">';
	$html .= '<h1>Project Gallery</h1>';
	$html .= '<p>Explore our completed residential, commercial, and architectural projects showcasing premium aluminium windows, doors, shopfronts, and framing solutions.</p>';
	$html .= '<div class="knox-gallery-grid">';
	foreach ( array(
		array( 'project_1', 'Knox Aluminium project photography — aluminium glazing installation' ),
		array( 'project_2', 'Knox Aluminium project photography — window and door systems' ),
		array( 'project_3', 'Knox Aluminium project photography — completed installation' ),
		array( 'project_4', 'Knox Aluminium project photography — architectural aluminium work' ),
		array( 'landing_4', 'Aluminium window and door product imagery' ),
		array( 'service_1', 'Aluminium door system product imagery' ),
	) as $item ) {
		$fig = knox_ac_figure( $item[0], $item[1] );
		if ( $fig ) {
			$html .= $fig;
		}
	}
	$html .= '</div>';
	$html .= '<div class="knox-cta-row" style="margin-top:2rem">' . knox_ac_btn( 'Request a Free Quote', knox_ac_url( 'contact-us/' ), 'knox-btn-primary' ) . '</div>';
	$html .= '</div></section></div>';
	return $html;
}

function knox_ac_location_blurb( $suburb ) {
	$blurbs = array(
		'Knoxfield'         => 'Knox Aluminium supplies and installs premium aluminium systems throughout Knoxfield.',
		'Wantirna'          => 'Premium aluminium windows and doors for homes, apartments, and commercial buildings in Wantirna.',
		'Rowville'          => 'Architectural-grade aluminium systems designed for Rowville residential and commercial developments.',
		'Ferntree Gully'    => 'High-performance aluminium windows and doors for Ferntree Gully homes and businesses.',
		'Boronia'           => 'Commercial-grade aluminium systems manufactured locally for Boronia projects.',
		'Scoresby'          => 'Premium aluminium windows, doors, and shopfronts for Scoresby industrial and commercial sites.',
		'Bayswater'         => 'Architectural aluminium systems tailored for Bayswater homes and commercial buildings.',
		'Pakenham'          => 'High-performance aluminium windows and doors for Pakenham residential and commercial projects.',
		'Malvern'           => 'Premium architectural aluminium systems designed for Malvern homes and developments.',
		'Glen Waverley'     => 'Custom aluminium windows and doors for Glen Waverley residential and commercial projects.',
		'Mount Waverley'    => 'Architectural aluminium systems engineered for Mount Waverley homes and businesses.',
		'Chadstone'         => 'High-quality aluminium windows and doors for Chadstone residential and commercial sites.',
		'Clayton'           => 'Commercial-grade aluminium systems for Clayton industrial and residential projects.',
		'Mulgrave'          => 'Premium aluminium windows and doors designed for Mulgrave developments.',
		'Ringwood'          => 'Architectural aluminium systems for Ringwood homes, apartments, and commercial buildings.',
		'Vermont'           => 'High-performance aluminium windows and doors for Vermont residential and commercial projects.',
		'Mitcham'           => 'Premium aluminium systems tailored for Mitcham homes and businesses.',
		'Blackburn'         => 'Architectural aluminium windows and doors for Blackburn residential and commercial sites.',
		'Doncaster'         => 'High-end aluminium systems designed for Doncaster homes and developments.',
		'Templestowe'       => 'Premium aluminium windows and doors for Templestowe residential and commercial projects.',
	);
	return isset( $blurbs[ $suburb ] ) ? $blurbs[ $suburb ] : '';
}

function knox_ac_page_location( $suburb ) {
	$contact = knox_ac_url( 'contact-us/' );
	$blurb   = knox_ac_location_blurb( $suburb );
	$html    = '<div class="knox-page">';
	$html   .= knox_ac_split(
		'project_1',
		'Aluminium windows and doors installed for ' . $suburb . ' projects',
		'<h1>Aluminium Windows &amp; Doors in ' . esc_html( $suburb ) . '</h1>',
		'<p>' . esc_html( $blurb ) . '</p>
		<p>From architectural homes to commercial façades, our Melbourne-made aluminium systems are fabricated and installed to suit local project requirements. Explore our <a href="' . esc_url( knox_ac_url( 'windows/' ) ) . '">window systems</a>, <a href="' . esc_url( knox_ac_url( 'doors/' ) ) . '">door systems</a>, and <a href="' . esc_url( knox_ac_url( 'commercial-projects/' ) ) . '">commercial solutions</a>.</p>',
		true,
		false,
		knox_ac_btn( 'Request a Free Quote', $contact, 'knox-btn-primary' ) . knox_ac_btn( 'Contact Our Team', $contact, 'knox-btn-dark' )
	);
	$html .= '</div>';
	return $html;
}

/**
 * Page registry for sync.
 */
function knox_ac_page_definitions() {
	$pages = array(
		'home' => array(
			'slug'          => 'home-windoor',
			'title'         => 'Home',
			'existing_id'   => 15212,
			'seo_title'     => 'Aluminium Windows & Doors Melbourne | Knox Aluminium',
			'seo_desc'      => 'Premium Australian-made aluminium windows, doors and commercial systems. 25+ years experience. Request a free quote in Melbourne.',
			'content_cb'    => 'knox_ac_page_home',
			'is_front'      => true,
		),
		'about' => array(
			'slug'        => 'about-us',
			'title'       => 'About Knox Aluminium Windows & Doors',
			'existing_id' => 6108,
			'seo_title'   => 'About Knox Aluminium Windows & Doors | Melbourne',
			'seo_desc'    => 'Melbourne manufacturer of premium aluminium windows and doors with 25+ years experience and 10-year product and installation warranties.',
			'content_cb'  => 'knox_ac_page_about',
		),
		'windows' => array(
			'slug'       => 'windows',
			'title'      => 'Architectural Aluminium Windows',
			'seo_title'  => 'Architectural Aluminium Windows Melbourne | Knox Aluminium',
			'seo_desc'   => 'Pro-Vista sashless double hung, View-Max commercial sliding, ProTilt awning and casement, and fixed architectural aluminium windows.',
			'content_cb' => 'knox_ac_page_windows',
		),
		'doors' => array(
			'slug'       => 'doors',
			'title'      => 'Architectural Aluminium Doors',
			'seo_title'  => 'Architectural Aluminium Doors Melbourne | Knox Aluminium',
			'seo_desc'   => 'Hawkesbury multi-fold and sliding doors, ProGlide systems, Altitude apartment doors, and Swan Evo and Torrens shopfront doors.',
			'content_cb' => 'knox_ac_page_doors',
		),
		'commercial' => array(
			'slug'       => 'commercial-projects',
			'title'      => 'Commercial Aluminium Systems',
			'seo_title'  => 'Commercial Aluminium Systems Melbourne | Knox Aluminium',
			'seo_desc'   => 'Shopfront framing, curtain wall, architectural and thermally broken systems for retail, office, hospitality and multi-residential projects.',
			'content_cb' => 'knox_ac_page_commercial',
		),
		'process' => array(
			'slug'       => 'process',
			'title'      => 'Our Process',
			'seo_title'  => 'Our Process | Knox Aluminium Windows & Doors',
			'seo_desc'   => 'From site measurement and quotation to local manufacturing, professional installation and final inspection.',
			'content_cb' => 'knox_ac_page_process',
		),
		'contact' => array(
			'slug'          => 'contact-us',
			'title'         => 'Contact Knox Aluminium Windows & Doors',
			'restore_id'    => 5209,
			'seo_title'     => 'Contact Knox Aluminium | Knoxfield VIC',
			'seo_desc'      => 'Contact Knox Aluminium Windows & Doors: 0439 202 020, sales@knoxaluminium.com.au, 4/573 Burwood Hwy, Knoxfield VIC.',
			'content_cb'    => 'knox_ac_page_contact',
		),
		'warranty' => array(
			'slug'       => 'warranty',
			'title'      => 'Our Warranty',
			'seo_title'  => 'Warranty | Knox Aluminium Windows & Doors',
			'seo_desc'   => '10-year product warranty and 10-year installation warranty when installed by the Knox Aluminium team.',
			'content_cb' => 'knox_ac_page_warranty',
		),
		'gallery' => array(
			'slug'       => 'project-gallery',
			'title'      => 'Project Gallery',
			'seo_title'  => 'Project Gallery | Knox Aluminium Windows & Doors',
			'seo_desc'   => 'Residential, commercial and architectural aluminium window, door and shopfront projects by Knox Aluminium.',
			'content_cb' => 'knox_ac_page_gallery',
		),
	);

	$suburbs = array(
		'Knoxfield', 'Wantirna', 'Rowville', 'Ferntree Gully', 'Boronia', 'Scoresby', 'Bayswater',
		'Pakenham', 'Malvern', 'Glen Waverley', 'Mount Waverley', 'Chadstone', 'Clayton', 'Mulgrave',
		'Ringwood', 'Vermont', 'Mitcham', 'Blackburn', 'Doncaster', 'Templestowe',
	);

	foreach ( $suburbs as $suburb ) {
		$slug = 'aluminium-windows-doors-' . sanitize_title( $suburb );
		$key  = 'loc_' . sanitize_title( $suburb );
		$pages[ $key ] = array(
			'slug'       => $slug,
			'title'      => 'Aluminium Windows & Doors in ' . $suburb,
			'seo_title'  => 'Aluminium Windows & Doors in ' . $suburb . ' | Knox Aluminium',
			'seo_desc'   => knox_ac_location_blurb( $suburb ),
			'content_cb' => function () use ( $suburb ) {
				return knox_ac_page_location( $suburb );
			},
			'parent_slug'=> 'locations',
			'suburb'     => $suburb,
		);
	}

	return $pages;
}
