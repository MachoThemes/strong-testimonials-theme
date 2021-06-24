<?php

$theme = wp_get_theme();
define( 'ANTREAS_SLUG', 'antreas' );
define( 'ANTREAS_PRO_SLUG', 'antreas-pro' );
define( 'ANTREAS_PREFIX', 'antreas_pro' );
define( 'ANTREAS_NAME', $theme['Name'] );
define( 'ANTREAS_VERSION', $theme['Version'] );
define( 'ANTREAS_ASSETS_CSS', get_template_directory_uri() . '/assets/css/' );
define( 'ANTREAS_ASSETS_JS', get_template_directory_uri() . '/assets/js/' );
define( 'ANTREAS_ASSETS_IMG', get_template_directory_uri() . '/assets/images/' );
define( 'ANTREAS_ASSETS_VENDORS', get_template_directory_uri() . '/assets/vendors/' );
define( 'ANTREAS_CORE', get_template_directory() . '/core/' );
define( 'ANTREAS_SHORTCODES', get_template_directory() . '/shortcodes/' );
define( 'ANTREAS_PREMIUM_NAME', 'Antreas Pro' );
define( 'ANTREAS_PREMIUM_URL', 'www.machothemes.com/theme/antreas/' );

require_once ANTREAS_CORE . 'init.php';

// EU VAT
require_once ANTREAS_CORE . 'modula-vat-handle.php';
/**
 * Removes the billing details section on the checkout screen.
 */
/*
function jp_disable_billing_details() {
	remove_action( 'edd_after_cc_fields', 'edd_default_cc_address_fields' );
}
add_action( 'init', 'jp_disable_billing_details' );
*/

add_filter( 'edd-vat-use-checkout-billing-template', '__return_false' );
add_filter( 'edd_require_billing_address', '__return_false' );

function jp_disable_billing_details() {

	remove_action( 'edd_after_cc_fields', 'edd_default_cc_address_fields' );
	add_action( 'edd_after_cc_fields', 'modula_cc_address_fields' );
	remove_action( 'edd_purchase_form_after_cc_form', 'edd_checkout_submit', 9999 );
	add_action( 'edd_purchase_form_after_cc_form', 'modula_theme_checkout_submit', 9999 );
}

add_action( 'init', 'jp_disable_billing_details' );

// Checkout details
function modula_theme_checkout_submit() { ?>
	<div class="footer-cart-total">
		<div class="footer-message">
			<span><?php _e( "You're almost done!", 'easy-digital-downloads' ); ?></span>
		</div>
		<span class="edd_cart_total edd_cart_total_text"><?php _e( 'Purchase Total', 'easy-digital-downloads' ); ?>: </span>
		<span class="edd_cart_total edd_cart_amount" data-subtotal="<?php echo edd_get_cart_subtotal(); ?>"
			  data-total="<?php echo edd_get_cart_total(); ?>"><?php edd_cart_total(); ?></span>
	</div>
	<fieldset id="edd_purchase_submit">
		<?php do_action( 'edd_purchase_form_before_submit' ); ?>

		<?php edd_checkout_hidden_fields(); ?>

		<?php echo edd_checkout_button_purchase(); ?>

		<?php do_action( 'edd_purchase_form_after_submit' ); ?>

		<?php if ( edd_is_ajax_disabled() ) { ?>
			<p class="edd-cancel"><a
						href="<?php echo edd_get_checkout_uri(); ?>"><?php _e( 'Go back', 'easy-digital-downloads' ); ?></a>
			</p>
		<?php } ?>
	</fieldset>
	<?php
}

// Remove billing address
add_filter( 'edd_require_billing_address', '__return_false' );

add_filter( 'edd_get_cart_discount_html', 'modula_theme_get_cart_discount_html', 10, 4 );
function modula_theme_get_cart_discount_html( $discount_html, $discount, $rate, $remove_url ) {
	$discount_html = "<div class=\"edd_discount\">\n";
	$discount_html .= "<span class=\"discount-rate\">$rate</span><span class=\"discount-code\">($discount)</span>\n";
	$discount_html .= "</div>\n";
	$discount_html .= "<div class=\"edd_cart_actions discount_actions\"><a href=\"$remove_url\" data-code=\"$discount\" class=\"edd_discount_remove\"></a></div>\n";

	return $discount_html;
}

function modula_cc_address_fields() {

	$logged_in = is_user_logged_in();
	$customer  = EDD()->session->get( 'customer' );
	$customer  = wp_parse_args( $customer, array(
			'address' => array(
					'line1'   => '',
					'line2'   => '',
					'city'    => '',
					'zip'     => '',
					'state'   => '',
					'country' => ''
			)
	) );

	$customer['address'] = array_map( 'sanitize_text_field', $customer['address'] );

	if ( $logged_in ) {

		$user_address = get_user_meta( get_current_user_id(), '_edd_user_address', true );

		foreach ( $customer['address'] as $key => $field ) {

			if ( empty( $field ) && ! empty( $user_address[ $key ] ) ) {
				$customer['address'][ $key ] = $user_address[ $key ];
			} else {
				$customer['address'][ $key ] = '';
			}

		}

	}

	/**
	 * Billing Address Details.
	 *
	 * Allows filtering the customer address details that will be pre-populated on the checkout form.
	 *
	 * @param array $address  The customer address.
	 * @param array $customer The customer data from the session
	 *
	 * @since 2.8
	 *
	 */
	$customer['address'] = apply_filters( 'edd_checkout_billing_details_address', $customer['address'], $customer );

	ob_start(); ?>

	<fieldset id="edd_cc_address" class="cc-address">

		<legend><?php _e( 'Billing Details', 'easy-digital-downloads' ); ?></legend>

		<?php do_action( 'edd_cc_billing_top' ); ?>

		<p id="edd-card-country-wrap">

			<label for="billing_country" class="edd-label">
				<?php _e( 'Country', 'easy-digital-downloads' ); ?>
				<?php if ( edd_field_is_required( 'billing_country' ) ) { ?>
					<span class="edd-required-indicator">*</span>
				<?php } ?>
			</label>

			<span class="edd-description"><?php _e( 'The country for your billing address.', 'easy-digital-downloads' ); ?></span>

			<select name="billing_country" id="billing_country"
					data-nonce="<?php echo wp_create_nonce( 'edd-country-field-nonce' ); ?>"
					class="billing_country edd-select<?php if ( edd_field_is_required( 'billing_country' ) ) {
						echo ' required';
					} ?>"<?php if ( edd_field_is_required( 'billing_country' ) ) {
				echo ' required ';
			} ?>>
				<?php

				$selected_country = edd_get_shop_country();
				if ( isset( $_SERVER["HTTP_CF_IPCOUNTRY"] ) ) {
					$selected_country = $_SERVER["HTTP_CF_IPCOUNTRY"];
				}

				if ( ! empty( $customer['address']['country'] ) && '*' !== $customer['address']['country'] ) {
					$selected_country = $customer['address']['country'];
				}

				$countries = edd_get_country_list();
				foreach ( $countries as $country_code => $country ) {
					echo '<option value="' . esc_attr( $country_code ) . '"' . selected( $country_code, $selected_country, false ) . '>' . $country . '</option>';
				}
				?>
			</select>
		</p>

		<p id="edd-card-zip-wrap">
			<label for="card_zip" class="edd-label">
				<?php _e( 'Zip / Postal Code', 'easy-digital-downloads' ); ?>
				<?php if ( edd_field_is_required( 'card_zip' ) ) { ?>
					<span class="edd-required-indicator">*</span>
				<?php } ?>
			</label>
			<span class="edd-description"><?php _e( 'The zip or postal code for your billing address.', 'easy-digital-downloads' ); ?></span>
			<input type="text" size="4" id="card_zip" name="card_zip"
				   class="card-zip edd-input<?php if ( edd_field_is_required( 'card_zip' ) ) {
					   echo ' required';
				   } ?>" placeholder="<?php _e( 'Zip / Postal Code', 'easy-digital-downloads' ); ?>"
				   value="<?php echo $customer['address']['zip']; ?>"<?php if ( edd_field_is_required( 'card_zip' ) ) {
				echo ' required ';
			} ?>/>
		</p>

		<?php do_action( 'edd_cc_billing_bottom' ); ?>

		<?php wp_nonce_field( 'edd-checkout-address-fields', 'edd-checkout-address-fields-nonce', false, true ); ?>
	</fieldset>

	<?php
	echo ob_get_clean();

}