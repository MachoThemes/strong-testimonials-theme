<?php

add_filter( 'wp_nav_menu_items', 'modula_main_menu_filter', 10, 2 );
function modula_main_menu_filter( $items, $args ) {

	if ( $args->theme_location == 'main_menu' ) {

		if ( is_page_template( 'page-templates/pricing.php' ) ) {
			$items = '';
		}

		if ( ! is_user_logged_in() ) {
			$items .= '<li class="menu-item"><a class="login-link" href="#" rel="nofollow">Log In</a></li>';
			$items .= '<li class="menu-item"><a class="get-started-link" href="' . get_permalink( get_page_by_path( 'pricing' ) ) . '">Buy Strong Testimonials</a></li>';
		} else {
			$items         .= '<li class="menu-item menu-item-has-children">';
				$items     .= '<a href="' . get_permalink( get_page_by_path( 'account' ) ) . '">My Account</a>';
				$items     .= '<ul class="sub-menu">';
					$items .= '<li class="menu-item"><a href="' . get_permalink( get_page_by_path( 'account' ) ) . '">Purchase History</a></li>';
					$items .= '<li class="menu-item"><a href="' . get_permalink( get_page_by_path( 'account' ) ) . '#subscriptions">Subscriptions</a></li>';
					$items .= '<li class="menu-item"><a href="' . get_permalink( get_page_by_path( 'account' ) ) . '#account-information">Account Information</a></li>';
					$items .= '<li class="menu-item"><a href="' . get_permalink( get_page_by_path( 'account' ) ) . '#download-history">Download History</a></li>';

			if ( function_exists( 'affwp_is_affiliate' ) && affwp_is_affiliate( get_current_user_id() ) ) :
				$items .= '<li class="menu-item"><a href="' . get_permalink( get_page_by_path( 'affiliate-area' ) ) . '">Affiliate Area</a></li>';
					endif;

					$items .= '<li class="menu-item"><a href="' . wp_logout_url( home_url() ) . '">Log Out</a></li>';
				$items     .= '</ul>';
			$items         .= '</li>';
		}



	}

	return $items;
}


add_filter( 'wp_nav_menu_items', 'modula_add_quick_access_links', 10, 2 );
function modula_add_quick_access_links( $items, $args ) {

	if ( $args->menu->slug === 'quick-access' ) {
		if ( ! is_user_logged_in() ) {
			$items .= '<li class="menu-item"><a class="login-link" href="#" rel="nofollow">Log In</a></li>';
		} else {
			$items .= '<li class="menu-item"><a href="' . get_permalink( get_page_by_path( 'account' ) ) . '">My Account</a></li>';
		}
	}

	return $items;
}



add_action( 'wp_ajax_modula_search_articles', 'modula_search_articles' );
add_action( 'wp_ajax_nopriv_modula_search_articles', 'modula_search_articles' );
function modula_search_articles() {

	if ( ! wp_verify_nonce( $_REQUEST['nonce'], 'search_articles_nonce' ) ) {
		exit( 'No naughty business please' );
	}

	$query = new WP_Query(
		array(
			'post_type' => $_REQUEST['post_type'],
			's'         => $_REQUEST['s'],
		)
	);

	if ( $query->have_posts() ) {
		echo '<p>' . $query->post_count . ' articles found for <strong>' . $_REQUEST['s'] . '</strong>:</p>';
		echo '<ul class="mb-0">';
		while ( $query->have_posts() ) {
			$query->the_post();
			echo '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a></li>';
		}
		echo '</ul>';
	} else {
		echo '<p class="mb-0">no articles found with that keyword</p>';
	}

	die();
}


// Limits excerpt length to a certain size
add_filter( 'excerpt_length', 'st_excerpt_length' );
function st_excerpt_length( $length ) {
	return 30;
}

//Displays an ellipsis on automatic excerpts
add_filter( 'excerpt_more', 'st_excerpt_more' );
function st_excerpt_more( $more ) {
	$output = '&hellip;';
	return $output;
}


add_action( 'the_content', 'modula_the_content', 20 );
function modula_the_content( $content ) {
	return $content;
}

add_action( 'upload_mimes', 'st_add_file_types_to_uploads' );
function st_add_file_types_to_uploads( $file_types ){

	$new_filetypes = array();
	$new_filetypes['svg'] = 'image/svg+xml';
	$file_types = array_merge( $file_types, $new_filetypes );

	return $file_types;
}


remove_action( 'edd_before_purchase_form', 'edd_sl_renewal_form', -1 );
remove_action( 'edd_checkout_form_top', 'edd_discount_field', -1 );

// remove child licenses
add_filter( 'edd_sl_manage_template_payment_licenses', 'st_edd_sl_manage_template_payment_licenses', 10, 2 );
function st_edd_sl_manage_template_payment_licenses( $licenses, $payment_id ) {
	$new_licenses = array();
	foreach ( $licenses as $license ) :
		if ( 0 == $license->parent ) {
			$new_licenses[] = $license;
		}
	endforeach;
	return $new_licenses;
}

// remove child licenses
add_filter( 'edd_sl_licenses_of_purchase', 'st_edd_sl_licenses_of_purchase', 10, 3 );
function st_edd_sl_licenses_of_purchase( $licenses, $payment, $args ) {
	$new_licenses = array();
	foreach ( $licenses as $license ) :
		if ( 0 == $license->parent ) {
			$new_licenses[] = $license;
		}
	endforeach;
	return $new_licenses;
}

//Add theme-specific body classes
add_filter( 'body_class', 'st_body_class' );
function st_body_class( $class ) {

	if ( is_singular('post') ) {
		$class[] = 'post-layout-'. st_get_post_meta( get_the_id(), 'layout' );
	}

	return $class;
}

add_action( 'wp_head', 'st_track_post_views');
function st_track_post_views ( $post_id ) {
    if ( ! is_single() ) return;
    if ( empty ( $post_id) ) {
        global $post;
        $post_id = $post->ID;
	}
	st_set_post_views( $post_id );
}

add_filter( 'send_password_change_email', '__return_false' );


remove_action( 'edd_before_purchase_form', 'edd_sl_renewal_form', - 1 );
add_action( 'edd_before_purchase_form', 'modula_theme_sl_renewal_form', -1 );

function modula_theme_sl_renewal_form() {

	if( ! edd_sl_renewals_allowed() ) {
		return;
	}

	$renewal      = EDD()->session->get( 'edd_is_renewal' );
	$renewal_keys = edd_sl_get_renewal_keys();
	$preset_key   = ! empty( $_GET['key'] ) ? esc_html( urldecode( $_GET['key'] ) ) : '';
	$error        = ! empty( $_GET['edd-sl-error'] ) ? sanitize_text_field( $_GET['edd-sl-error'] ) : '';
	$color        = edd_get_option( 'checkout_color', 'blue' );
	$color        = ( $color == 'inherit' ) ? '' : $color;
	$style        = edd_get_option( 'button_style', 'button' );
	ob_start(); ?>
	<form method="post" id="edd_sl_renewal_form">
		<fieldset id="edd_sl_renewal_fields">
			<p id="edd_sl_show_renewal_form_wrap">
				<?php _e( 'Renewing a license key? <a href="#" id="edd_sl_show_renewal_form">Click to renew an existing license</a>', 'edd_sl' ); ?>
			</p>
			<p id="edd-license-key-container-wrap" class="edd-cart-adjustment" style="display:none;">
				<span class="edd-description"><?php _e( 'Enter the license key you wish to renew. Leave blank to purchase a new one.', 'edd_sl' ); ?></span>
				<input class="edd-input required" type="text" name="edd_license_key" autocomplete="off" placeholder="<?php _e( 'Enter your license key', 'edd_sl' ); ?>" id="edd-license-key" value="<?php echo $preset_key; ?>"/>
				<input type="hidden" name="edd_action" value="apply_license_renewal"/>
			</p>
			<p class="edd-sl-renewal-actions" style="display:none">
				<input type="submit" id="edd-add-license-renewal" disabled="disabled" class="edd-submit button <?php echo $color . ' ' . $style; ?>" value="<?php _e( 'Apply License Renewal', 'edd_sl' ); ?>"/>&nbsp;<span><a href="#" id="edd-cancel-license-renewal"><?php _e( 'Cancel', 'edd_sl' ); ?></a></span>
			</p>

			<?php if( ! empty( $renewal ) && ! empty( $renewal_keys ) ) : ?>
				<p id="edd-license-key-container-wrap" class="edd-cart-adjustment">
					<span class="edd-description"><?php _e( 'You may renew multiple license keys at once.', 'edd_sl' ); ?></span>
				</p>
			<?php endif; ?>
		</fieldset>
		<?php if( ! empty( $error ) ) : ?>
			<div class="edd_errors">
				<p class="edd_error"><?php echo urldecode( sanitize_text_field( $_GET['message'] ) ); ?></p>
			</div>
		<?php endif; ?>
	</form>
	<?php if( ! empty( $renewal ) && ! empty( $renewal_keys ) ) : ?>
		<form method="post" id="edd_sl_cancel_renewal_form">
			<p>
				<input type="hidden" name="edd_action" value="cancel_license_renewal"/>
				<input type="submit" class="edd-submit button" value="<?php _e( 'Cancel License Renewal', 'edd_sl' ); ?>"/>
			</p>
		</form>
	<?php
	endif;
	echo ob_get_clean();
}