<div id="edd_cc_address">

	<div id="edd_vat_info">

		<p id="edd_show_vat_info" style="">
			<?php _e('You can exclude sales tax if your business has a registered tax number.', 'edd_vat'); ?>
			<a class="edd_vat_link" href="#"><?php _e('Click here to enter tax details.', 'edd_vat'); ?></a>
		</p>

		<fieldset id="edd_vat_fields" class="vat_info"  style="display:none">

			<p id="edd-card-country-wrap">
				<label for="billing_country" class="edd-label">
					<?php _e( 'Billing Country', 'modula-theme' ); ?>
					<?php if( edd_field_is_required( 'billing_country' ) ) { ?>
						<span class="edd-required-indicator">*</span>
					<?php } ?>
				</label>
				<span class="edd-description"><?php _e( 'The country for your billing address.', 'modula-theme' ); ?></span>
				<select name="billing_country" id="billing_country" data-nonce="<?php echo wp_create_nonce( 'edd-country-field-nonce' ); ?>" class="billing_country edd-select">
					<?php

					$selected_country = '';
					if ( isset( $_SERVER["HTTP_CF_IPCOUNTRY"] ) ) {
						$selected_country = $_SERVER["HTTP_CF_IPCOUNTRY"];
					}

					if (empty($selected_country)) $selected_country = edd_get_shop_country();

					if( $logged_in && ! empty( $user_address['country'] ) && '*' !== $user_address['country'] ) {
						$selected_country = $user_address['country'];
					}

					$countries = edd_get_country_list();
					foreach( $countries as $country_code => $country ) {
					  echo '<option value="' . esc_attr( $country_code ) . '"' . selected( $country_code, $selected_country, false ) . '>' . $country . '</option>';
					}
					?>
				</select>
			</p>

			<?php do_action( 'modula_vat_field' ) ?>

		</fieldset>
		<?php wp_nonce_field( 'edd-checkout-address-fields', 'edd-checkout-address-fields-nonce', false, true ); ?>
	</div>
</div>