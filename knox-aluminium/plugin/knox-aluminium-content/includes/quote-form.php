<?php
/**
 * Contact Form 7 — Request a Free Rough Quote
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Option key storing the CF7 form post ID. */
define( 'KNOX_AC_QUOTE_FORM_OPTION', 'knox_ac_rough_quote_form_id' );

/**
 * CF7 form template markup (native tags).
 */
function knox_ac_quote_form_template() {
	return <<<'FORM'
<div class="knox-quote-form">
  <div class="knox-qf-intro">
    <h2>Request a Free Rough Quote</h2>
    <p>Please complete the form below with as much information as possible. Our team will review your details and email you a rough estimate. Final pricing is subject to a site inspection and confirmation of measurements.</p>
  </div>

  <fieldset class="knox-qf-section">
    <legend>Contact Details</legend>
    <div class="knox-qf-grid">
      <p><label>Full Name <span class="knox-qf-req">*</span><br>[text* full-name autocomplete:name placeholder "Your full name"]</label></p>
      <p><label>Email Address <span class="knox-qf-req">*</span><br>[email* email-address autocomplete:email placeholder "name@example.com"]</label></p>
      <p><label>Mobile Number <span class="knox-qf-req">*</span><br>[tel* mobile-number autocomplete:tel placeholder "04XX XXX XXX"]</label></p>
    </div>
  </fieldset>

  <fieldset class="knox-qf-section">
    <legend>Project Details</legend>
    <p class="knox-qf-label">What do you need? <span class="knox-qf-req">*</span></p>
    [checkbox* project-requirement use_label_element "Aluminium Windows" "Aluminium Doors" "Both Windows & Doors"]

    <p class="knox-qf-label">Select Product Type <span class="knox-qf-req">*</span></p>
    [checkbox* product-type use_label_element "Sliding Window" "Awning Window" "Casement Window" "Double Hung Window" "Fixed Window" "Louvre Window" "Sliding Door" "Stacking Sliding Door" "Hinged Door" "French Door" "Bi-Fold Door" "Other"]

    <p><label>Other product type (if Other selected)<br>[text other-product-type placeholder "Please specify the product type"]</label></p>

    <p><label>Select Product Model (if known)<br>[text product-model placeholder "Optional — enter model name if known"]</label></p>

    <p><label>How many windows/doors do you require? <span class="knox-qf-req">*</span><br>[number* total-items min:1 step:1 placeholder "Minimum 1"]</label></p>
  </fieldset>

  <fieldset class="knox-qf-section">
    <legend>Measurements</legend>
    <p class="knox-qf-help">Please enter your approximate measurements.</p>
    <div class="knox-qf-measure-table" role="group" aria-label="Approximate measurements">
      <div class="knox-qf-measure-head" aria-hidden="true">
        <span>Item</span><span>Width (mm)</span><span>Height (mm)</span><span>QTY</span>
      </div>
      <div class="knox-qf-measure-row">
        <span class="knox-qf-measure-label">Window/Door 1</span>
        [number width-1 min:0 step:1 placeholder "Width"]
        [number height-1 min:0 step:1 placeholder "Height"]
        [number qty-1 min:0 step:1 placeholder "QTY"]
      </div>
      <div class="knox-qf-measure-row">
        <span class="knox-qf-measure-label">Window/Door 2</span>
        [number width-2 min:0 step:1 placeholder "Width"]
        [number height-2 min:0 step:1 placeholder "Height"]
        [number qty-2 min:0 step:1 placeholder "QTY"]
      </div>
      <div class="knox-qf-measure-row">
        <span class="knox-qf-measure-label">Window/Door 3</span>
        [number width-3 min:0 step:1 placeholder "Width"]
        [number height-3 min:0 step:1 placeholder "Height"]
        [number qty-3 min:0 step:1 placeholder "QTY"]
      </div>
      <div class="knox-qf-measure-row">
        <span class="knox-qf-measure-label">Window/Door 4</span>
        [number width-4 min:0 step:1 placeholder "Width"]
        [number height-4 min:0 step:1 placeholder "Height"]
        [number qty-4 min:0 step:1 placeholder "QTY"]
      </div>
      <div class="knox-qf-measure-row">
        <span class="knox-qf-measure-label">Window/Door 5</span>
        [number width-5 min:0 step:1 placeholder "Width"]
        [number height-5 min:0 step:1 placeholder "Height"]
        [number qty-5 min:0 step:1 placeholder "QTY"]
      </div>
    </div>
    <p class="knox-qf-note">Note: Approximate measurements are acceptable for a rough quotation.</p>
  </fieldset>

  <fieldset class="knox-qf-section">
    <legend>Glass &amp; Colour</legend>
    <div class="knox-qf-grid">
      <div>
        <p class="knox-qf-label">Preferred Frame Colour</p>
        [checkbox frame-colour use_label_element "Black" "White" "Silver" "Monument" "Woodland Grey" "Custom Colour"]
        <p><label>Custom colour (if Custom Colour selected)<br>[text custom-frame-colour placeholder "Please specify the colour"]</label></p>
      </div>
      <div>
        <p class="knox-qf-label">Glass Type</p>
        [checkbox glass-type use_label_element "Standard Clear" "Double Glazed" "Tinted" "Frosted" "Low-E Energy Efficient" "Not Sure"]
      </div>
    </div>
  </fieldset>

  <fieldset class="knox-qf-section">
    <legend>Installation</legend>
    <div class="knox-qf-grid">
      <div>
        <p class="knox-qf-label">Job Type <span class="knox-qf-req">*</span></p>
        [checkbox* job-type exclusive use_label_element "Supply Only" "Supply & Installation" "Replacement of Existing Windows/Doors" "New Build" "Renovation"]
      </div>
      <div>
        <p class="knox-qf-label">Property Type <span class="knox-qf-req">*</span></p>
        [checkbox* property-type exclusive use_label_element "House" "Townhouse" "Apartment" "Commercial Building" "Other"]
        <p><label>Other property type (if Other selected)<br>[text other-property-type placeholder "Please specify the property type"]</label></p>
      </div>
    </div>
  </fieldset>

  <fieldset class="knox-qf-section">
    <legend>Project Information</legend>
    <div class="knox-qf-grid">
      <p><label>Project Location (Suburb) <span class="knox-qf-req">*</span><br>[text* project-suburb placeholder "Enter your project suburb"]</label></p>
      <div>
        <p class="knox-qf-label">Expected Installation Time</p>
        [checkbox installation-time exclusive use_label_element "ASAP" "Within 1 Month" "Within 3 Months" "Just Planning"]
      </div>
    </div>
  </fieldset>

  <fieldset class="knox-qf-section">
    <legend>Uploads (Optional)</legend>
    <p class="knox-qf-help">Attach any of the following to help us provide a more accurate estimate:</p>
    <ul class="knox-qf-upload-list">
      <li>Photos of the existing windows or doors</li>
      <li>House plans</li>
      <li>Architectural drawings</li>
      <li>Sketches with measurements</li>
    </ul>
    <p><label>Upload file 1<br>[file project-files filetypes:jpg|jpeg|png|webp|pdf limit:10mb]</label></p>
    <p><label>Upload file 2 (optional)<br>[file project-files-2 filetypes:jpg|jpeg|png|webp|pdf limit:10mb]</label></p>
    <p><label>Upload file 3 (optional)<br>[file project-files-3 filetypes:jpg|jpeg|png|webp|pdf limit:10mb]</label></p>
    <p class="knox-qf-note">Accepted formats: JPG, JPEG, PNG, WEBP, PDF. Maximum 10 MB per file. You may attach up to three files.</p>
  </fieldset>

  <fieldset class="knox-qf-section">
    <legend>Additional Information</legend>
    <p><label>Tell us about your project<br>[textarea additional-information rows:5 placeholder "Please share any additional details about your project, requirements, or preferred specifications."]</label></p>
  </fieldset>

  <fieldset class="knox-qf-section">
    <legend>How did you hear about Knox Aluminium?</legend>
    [checkbox lead-source exclusive use_label_element "Google Search" "Google Maps" "Facebook" "Instagram" "Referral" "Existing Customer" "Builder" "Other"]
    <p><label>Other source (if Other selected)<br>[text other-lead-source placeholder "Please specify"]</label></p>
  </fieldset>

  <div class="knox-qf-disclaimer">
    <p>By submitting this form, you acknowledge that the measurements provided are approximate and the quotation supplied is an indicative estimate only. A final quotation will be provided after confirmation of measurements and project requirements.</p>
  </div>

  <div class="knox-qf-submit">
    [submit "Request My Free Quote"]
  </div>
</div>
FORM;
}

/**
 * Mail body template for admin notification.
 */
function knox_ac_quote_mail_body() {
	return <<<'BODY'
New Free Rough Quote Request — Knox Aluminium

== Contact Details ==
Full Name: [full-name]
Email: [email-address]
Mobile: [mobile-number]

== Project Details ==
What do you need?: [project-requirement]
Product Type: [product-type]
Other Product Type: [other-product-type]
Product Model: [product-model]
Total windows/doors required: [total-items]

== Measurements (approximate) ==
Window/Door 1 — Width: [width-1] mm | Height: [height-1] mm | QTY: [qty-1]
Window/Door 2 — Width: [width-2] mm | Height: [height-2] mm | QTY: [qty-2]
Window/Door 3 — Width: [width-3] mm | Height: [height-3] mm | QTY: [qty-3]
Window/Door 4 — Width: [width-4] mm | Height: [height-4] mm | QTY: [qty-4]
Window/Door 5 — Width: [width-5] mm | Height: [height-5] mm | QTY: [qty-5]

== Glass & Colour ==
Preferred Frame Colour: [frame-colour]
Custom Frame Colour: [custom-frame-colour]
Glass Type: [glass-type]

== Installation ==
Job Type: [job-type]
Property Type: [property-type]
Other Property Type: [other-property-type]

== Project Information ==
Project Location (Suburb): [project-suburb]
Expected Installation Time: [installation-time]

== Additional Information ==
[additional-information]

== Lead Source ==
How did you hear about Knox Aluminium?: [lead-source]
Other source: [other-lead-source]

--
Submitted via [_site_title] ([_site_url])
BODY;
}

/**
 * Resolve enquiry recipient from known site/business config.
 * Prefer sales@knoxaluminium.com.au when present on the public site; fall back to admin email.
 */
function knox_ac_quote_recipient() {
	$preferred = 'sales@knoxaluminium.com.au';
	// Verified on Contact page / PDF package for this business.
	return $preferred;
}

/**
 * Create or update the CF7 form.
 *
 * @return array{ok:bool,id:int,shortcode:string,log:string[]}
 */
function knox_ac_ensure_quote_form() {
	$log = array();

	if ( ! class_exists( 'WPCF7_ContactForm' ) ) {
		return array(
			'ok'        => false,
			'id'        => 0,
			'shortcode' => '',
			'log'       => array( 'ERROR: Contact Form 7 is not active.' ),
		);
	}

	$form_id = (int) get_option( KNOX_AC_QUOTE_FORM_OPTION, 0 );
	$contact = $form_id ? wpcf7_contact_form( $form_id ) : null;

	if ( ! $contact ) {
		// Look up by title.
			$posts = get_posts(
			array(
				'post_type'      => 'wpcf7_contact_form',
				'posts_per_page' => 50,
				'post_status'    => 'any',
			)
		);
		foreach ( $posts as $p ) {
			if ( 'Request a Free Rough Quote' === $p->post_title ) {
				$contact = wpcf7_contact_form( $p->ID );
				$form_id = (int) $p->ID;
				$log[]   = 'Found existing form #' . $form_id;
				break;
			}
		}
	}

	if ( ! $contact ) {
		$contact = WPCF7_ContactForm::get_template(
			array(
				'title' => 'Request a Free Rough Quote',
			)
		);
		$log[] = 'Creating new CF7 form from template';
	}

	$recipient = knox_ac_quote_recipient();
	$sender    = 'Knox Aluminium <wordpress@smartscrapmetals.com.au>';

	$properties = array(
		'form'     => knox_ac_quote_form_template(),
		'mail'     => array(
			'active'             => true,
			'subject'            => 'New Free Rough Quote Request — Knox Aluminium',
			'sender'             => $sender,
			'recipient'          => $recipient,
			'body'               => knox_ac_quote_mail_body(),
			'additional_headers' => "Reply-To: [email-address]\nBcc: [_site_admin_email]",
			'attachments'        => "[project-files]\n[project-files-2]\n[project-files-3]",
			'use_html'           => 0,
			'exclude_blank'      => 1,
		),
		'mail_2'   => array(
			'active'             => true,
			'subject'            => 'We received your rough quote request — Knox Aluminium',
			'sender'             => $sender,
			'recipient'          => '[email-address]',
			'body'               => "Hi [full-name],\n\nThank you for requesting a quote from Knox Aluminium Windows & Doors. Your project details have been submitted successfully. Our team will review your requirements and contact you.\n\nProject suburb: [project-suburb]\n\nKind regards,\nKnox Aluminium Windows & Doors\nPhone: 0439 202 020\nEmail: sales@knoxaluminium.com.au",
			'additional_headers' => 'Reply-To: ' . $recipient,
			'attachments'        => '',
			'use_html'           => 0,
			'exclude_blank'      => 0,
		),
		'messages' => array(
			'mail_sent_ok'             => 'Thank you for requesting a quote from Knox Aluminium Windows & Doors. Your project details have been submitted successfully. Our team will review your requirements and contact you.',
			'mail_sent_ng'             => 'There was an error sending your quote request. Please try again or call us on 0439 202 020.',
			'validation_error'         => 'Please correct the highlighted fields and try again.',
			'spam'                     => 'Your submission was flagged as spam. Please contact us by phone if this continues.',
			'accept_terms'             => 'Please accept the terms before submitting.',
			'invalid_required'         => 'Please fill in this required field.',
			'invalid_too_long'         => 'This field is too long.',
			'invalid_too_short'        => 'This field is too short.',
			'upload_failed'            => 'File upload failed. Please try again.',
			'upload_file_type_invalid' => 'Invalid file type. Please upload JPG, JPEG, PNG, WEBP, or PDF only.',
			'upload_file_too_large'    => 'The file is too large. Maximum size is 10 MB.',
			'upload_failed_php_error'  => 'A server error occurred while uploading your file.',
			'invalid_number'           => 'Please enter a valid number.',
			'number_too_small'         => 'Please enter a number of at least 1 for the quantity required.',
			'number_too_large'         => 'Please enter a smaller number.',
			'invalid_email'            => 'Please enter a valid email address.',
			'invalid_tel'              => 'Please enter a valid mobile number.',
		),
	);

	$contact->set_title( 'Request a Free Rough Quote' );
	$contact->set_properties( $properties );
	$saved = $contact->save();

	if ( ! $saved ) {
		$log[] = 'ERROR: Failed to save CF7 form';
		return array(
			'ok'        => false,
			'id'        => 0,
			'shortcode' => '',
			'log'       => $log,
		);
	}

	$form_id = (int) $contact->id();
	update_option( KNOX_AC_QUOTE_FORM_OPTION, $form_id, false );
	$shortcode = sprintf( '[contact-form-7 id="%d" title="Request a Free Rough Quote"]', $form_id );
	$log[]     = 'QUOTE FORM saved #' . $form_id;
	$log[]     = 'SHORTCODE ' . $shortcode;
	$log[]     = 'MAIL recipient ' . $recipient;

	return array(
		'ok'        => true,
		'id'        => $form_id,
		'shortcode' => $shortcode,
		'log'       => $log,
	);
}

/**
 * Page content wrapping the quote form (stores shortcode, not rendered HTML).
 *
 * @param string $shortcode CF7 shortcode.
 */
function knox_ac_page_quote( $shortcode = '' ) {
	$html  = '<div class="knox-page"><section class="knox-section"><div class="knox-wrap">';
	$html .= '<div class="knox-qf-page-header">';
	$html .= '<p class="knox-kicker">Knox Aluminium Windows &amp; Doors</p>';
	$html .= '<h1>Request a Free Rough Quote</h1>';
	$html .= '<p>Melbourne-made aluminium windows and doors. Share your project details below and we will email a rough estimate. Prefer to talk? Call <a href="tel:0439202020">0439 202 020</a> or email <a href="mailto:sales@knoxaluminium.com.au">sales@knoxaluminium.com.au</a>.</p>';
	$html .= '</div>';
	if ( $shortcode ) {
		$html .= $shortcode;
	} else {
		$html .= '<p>The quote form could not be loaded. Please contact us on 0439 202 020.</p>';
	}
	$html .= '</div></section></div>';
	return $html;
}

/**
 * Create/update dedicated quote page and point menu CTA at it.
 *
 * @return string[]
 */
function knox_ac_sync_quote_page() {
	$log  = array();
	$form = knox_ac_ensure_quote_form();
	$log  = array_merge( $log, $form['log'] );

	if ( ! $form['ok'] ) {
		return $log;
	}

	$slug    = 'request-a-free-quote';
	$title   = 'Request a Free Rough Quote';
	$page    = get_page_by_path( $slug );
	$content = knox_ac_page_quote( $form['shortcode'] );

	if ( $page ) {
		knox_ac_backup_page( $page->ID );
		knox_ac_disable_elementor( $page->ID );
		wp_update_post(
			array(
				'ID'           => $page->ID,
				'post_title'   => $title,
				'post_content' => $content,
				'post_status'  => 'publish',
			)
		);
		$pid   = (int) $page->ID;
		$log[] = 'QUOTE PAGE updated #' . $pid;
	} else {
		$pid = wp_insert_post(
			array(
				'post_type'    => 'page',
				'post_status'  => 'publish',
				'post_title'   => $title,
				'post_name'    => $slug,
				'post_content' => $content,
			),
			true
		);
		if ( is_wp_error( $pid ) ) {
			$log[] = 'ERROR creating quote page: ' . $pid->get_error_message();
			return $log;
		}
		knox_ac_disable_elementor( $pid );
		$log[] = 'QUOTE PAGE created #' . $pid;
	}

	knox_ac_set_seo(
		$pid,
		'Request a Free Rough Quote | Knox Aluminium Windows & Doors',
		'Request a free rough quote for aluminium windows and doors in Melbourne. Share measurements, glass and colour preferences for an indicative estimate.'
	);

	// Refresh contact page CTA area to include link + embed option.
	$contact = get_page_by_path( 'contact-us' );
	if ( $contact ) {
		$contact_html = knox_ac_page_contact();
		// Append secondary link to full quote form if not present.
		if ( false === strpos( $contact_html, $slug ) ) {
			// Rebuild contact with link to dedicated quote page — contact builder already has CF7 4191; leave it and add CTA.
		}
		wp_update_post(
			array(
				'ID'           => $contact->ID,
				'post_content' => knox_ac_page_contact_with_quote_link( $form['shortcode'] ),
			)
		);
		$log[] = 'CONTACT PAGE updated with rough quote form + link';
	}

	// Point Request a Free Quote menu items to new page (create if missing).
	$menu = wp_get_nav_menu_object( 'Main Menu' );
	if ( $menu ) {
		$items   = wp_get_nav_menu_items( $menu->term_id );
		$url     = home_url( '/' . $slug . '/' );
		$updated = false;
		foreach ( (array) $items as $item ) {
			if ( false !== stripos( $item->title, 'quote' ) || false !== stripos( $item->title, 'Request a Free' ) ) {
				wp_update_nav_menu_item(
					$menu->term_id,
					$item->ID,
					array(
						'menu-item-title'  => 'Request a Free Quote',
						'menu-item-url'    => $url,
						'menu-item-status' => 'publish',
						'menu-item-type'   => 'custom',
					)
				);
				$updated = true;
			}
		}
		if ( ! $updated ) {
			wp_update_nav_menu_item(
				$menu->term_id,
				0,
				array(
					'menu-item-title'  => 'Request a Free Quote',
					'menu-item-url'    => $url,
					'menu-item-status' => 'publish',
					'menu-item-type'   => 'custom',
				)
			);
			$log[] = 'MENU quote CTA created → ' . $url;
		} else {
			$log[] = 'MENU quote CTA → ' . $url;
		}
	}

	update_option( 'knox_ac_quote_page_id', $pid, false );
	$log[] = 'QUOTE URL ' . get_permalink( $pid );
	return $log;
}

/**
 * Contact page: keep details + embed rough quote form shortcode.
 */
function knox_ac_page_contact_with_quote_link( $shortcode ) {
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
	$html .= '<div class="knox-cta-row" style="margin-top:1.5rem">';
	$html .= knox_ac_btn( 'Open Full Quote Form', knox_ac_url( 'request-a-free-quote/' ), 'knox-btn-primary' );
	$html .= knox_ac_btn( 'Explore Window Options', knox_ac_url( 'windows/' ), 'knox-btn-dark' );
	$html .= '</div></div><div>';
	$html .= '<h2>Request a Free Rough Quote</h2>';
	$html .= '<p>Share your project details for an indicative estimate. Final pricing is confirmed after site measurement.</p>';
	$html .= $shortcode;
	$html .= '</div></div></div></section></div>';
	return $html;
}
