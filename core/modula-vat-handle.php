<?php
// Hide billing information
remove_action( 'edd_purchase_form_after_cc_form', 'edd_checkout_tax_fields', 999 );

// Add custom location for vat field
add_filter( 'edd_vat_checkout_vat_field_location', 'modula_change_vat_field_location' );
function modula_change_vat_field_location(){
	return 'modula_vat_field';
}

// Add custom template for billing country and vat field
// add_action( 'edd_purchase_form_after_cc_form', 'modula_show_vat' );
function modula_show_vat(){
	edd_get_template_part( 'modula-vat' );
}

// Calculate rate based on CloudFlare Country
add_filter( 'edd_tax_rate', 'modula_get_tax_rate', 10, 3 );
function modula_get_tax_rate( $rate, $country, $state ){

	$rate = (float) edd_get_option( 'tax_rate', 0 );

	$user_address = edd_get_customer_address();

    if ( isset( $_SERVER["HTTP_CF_IPCOUNTRY"] ) && empty( $_POST['billing_country'] ) ) {
        $country = $_SERVER["HTTP_CF_IPCOUNTRY"];
    }

	if( empty( $state ) ) {
		if( ! empty( $_POST['state'] ) ) {
			$state = $_POST['state'];
		} elseif( ! empty( $_POST['card_state'] ) ) {
			$state = $_POST['card_state'];
		} elseif( is_user_logged_in() && ! empty( $user_address['state'] ) ) {
			$state = $user_address['state'];
		}
		$state = ! empty( $state ) ? $state : edd_get_shop_state();
	}

	if( ! empty( $country ) ) {
		$tax_rates   = edd_get_tax_rates();

		if( ! empty( $tax_rates ) ) {

			// Locate the tax rate for this country / state, if it exists
			foreach( $tax_rates as $key => $tax_rate ) {

				if( $country != $tax_rate['country'] )
					continue;

				if( ! empty( $tax_rate['global'] ) ) {
					if( ! empty( $tax_rate['rate'] ) ) {
						$rate = number_format( $tax_rate['rate'], 4 );
					}
				} else {

					if( empty( $tax_rate['state'] ) || strtolower( $state ) != strtolower( $tax_rate['state'] ) ) {
						continue;
					}

					$state_rate = $tax_rate['rate'];
					if( ( 0 !== $state_rate || ! empty( $state_rate ) ) && '' !== $state_rate ) {
						$rate = number_format( $state_rate, 4 );
					}
				}
			}
		}
	}

	// Convert to a number we can use
	$rate = $rate / 100;
	return  $rate;

}

// Add Company name to pdf invoice
add_filter( 'edd_vat_register_pdf_template_callback', 'modula_register_pdf_invoice_template', 10, 2 );

function modula_register_pdf_invoice_template( $bool, $template ){
	add_action( "eddpdfi_pdf_template_{$template}", 'modula_pdf_template', 11, 10 );
	return false;
}

function modula_pdf_template( $eddpdfi_pdf, $eddpdfi_payment, $eddpdfi_payment_meta, $eddpdfi_buyer_info, $eddpdfi_payment_gateway,
        $eddpdfi_payment_method, $address_line_2_line_height, $company_name, $eddpdfi_payment_date, $eddpdfi_payment_status ){

	global $edd_options;

    if ( ! Barn2\Plugin\EDD_VAT\Util::is_eu_payment( $eddpdfi_payment->ID ) ) {
        return;
    }

    // PDF Invoices sometimes passes a WP_Post object instead of EDD_Payment, in which case we need to convert.
    if ( $eddpdfi_payment instanceof \WP_Post ) {
        $eddpdfi_payment = edd_get_payment( $eddpdfi_payment );
    }

    // Get the payment VAT details.
    $payment_vat = Barn2\Plugin\EDD_VAT\Util::get_payment_vat( $eddpdfi_payment->ID );

    // Build lines array for PDF output.
    $lines = array();

    if ( $payment_vat->is_reverse_charged ) {
        $lines[] = __( 'VAT reverse charged', 'edd-eu-vat' );
    } elseif ( $eddpdfi_payment->tax_rate > 0 ) {
        $lines[] = sprintf(
            /* translators: %s: The payment tax rate */
            __( 'VAT charged at %s%%', 'edd-eu-vat' ),
            $eddpdfi_payment->tax_rate * 100
        );
    }

    if ( ! empty( $payment_vat->name ) ) {
        $lines[] = \sprintf( __( 'Company: %s', 'edd-eu-vat' ), esc_html( $payment_vat->name ) );
    }

    if ( ! empty( $payment_vat->vat_number ) ) {
        $lines[] = \sprintf( __( 'Customer VAT number: %s', 'edd-eu-vat' ), esc_html( $payment_vat->vat_number ) );
    }

    if ( ! empty( $lines ) ) {
        $style = modula_get_pdf_style();

        $x          = $style['x'];
        $font       = $style['font'];
        $text_color = $style['text_color'];

        // Spacing
        if ( ! empty( $edd_options['eddpdfi_additional_notes'] ) ) {
            $eddpdfi_pdf->Ln( 5 );
            $eddpdfi_pdf->SetX( $x );
            $eddpdfi_pdf->Cell( 0, 0, '', 'T', 2, 'L', false );
        }

        $eddpdfi_pdf->SetX( $x );
        $eddpdfi_pdf->SetTextColorArray( $text_color );
        $eddpdfi_pdf->SetFont( $font, '', 10 );

        foreach ( $lines as $line ) {
            $eddpdfi_pdf->Cell( 0, 6, $line, false, 2, 'L', false );
        }
    }

}

function modula_get_pdf_style(){
	global $edd_options;

    // Default styles.
    $style = array(
        'x'             => 0,
        'font'          => 'freesans',
        'heading_color' => array( 50, 50, 50 ),
        'text_color'    => array( 50, 50, 50 ),
    );

    $font_replace = isset( $edd_options['eddpdfi_enable_char_support'] );

    // Templates specific styles.
    $templates = array(
        'default'     => array(
            'x'             => 60,
            'font'          => $font_replace ? 'freesans' : 'Helvetica',
            'heading_color' => array( 50, 50, 50 ),
            'text_color'    => array( 50, 50, 50 ),
        ),
        'blue_stripe' => array(
            'x'             => 35,
            'font'          => 'droidserif',
            'heading_color' => array( 149, 210, 236 ),
            'text_color'    => array( 50, 50, 50 ),
        ),
        'lines'       => array(
            'x'             => 35,
            'font'          => $font_replace ? 'freeserif' : 'droidserif',
            'heading_color' => array( 224, 65, 28 ),
            'text_color'    => array( 46, 11, 3 ),
        ),
        'minimal'     => array(
            'x'             => 21,
            'font'          => $font_replace ? 'freesans' : 'Helvetica',
            'heading_color' => array( 224, 65, 28 ),
            'text_color'    => array( 46, 11, 3 ),
        ),
        'traditional' => array(
            'x'             => 8,
            'font'          => $font_replace ? 'freeserif' : 'times',
            'heading_color' => array( 50, 50, 50 ),
            'text_color'    => array( 50, 50, 50 ),
        ),
        'blue'        => array(
            'x'             => 61,
            'font'          => $font_replace ? 'freesans' : 'opensans',
            'heading_color' => array( 8, 75, 110 ),
            'text_color'    => array( 7, 46, 66 ),
        ),
        'green'       => array(
            'x'             => 61,
            'font'          => $font_replace ? 'freesans' : 'opensans',
            'heading_color' => array( 8, 110, 39 ),
            'text_color'    => array( 7, 66, 28 ),
        ),
        'orange'      => array(
            'x'             => 61,
            'font'          => $font_replace ? 'freesans' : 'opensans',
            'heading_color' => array( 110, 54, 8 ),
            'text_color'    => array( 65, 66, 7 ),
        ),
        'pink'        => array(
            'x'             => 61,
            'font'          => $font_replace ? 'freesans' : 'opensans',
            'heading_color' => array( 110, 8, 82 ),
            'text_color'    => array( 66, 7, 51 ),
        ),
        'purple'      => array(
            'x'             => 61,
            'font'          => $font_replace ? 'freesans' : 'opensans',
            'heading_color' => array( 66, 8, 110 ),
            'text_color'    => array( 35, 7, 66 ),
        ),
        'red'         => array(
            'x'             => 61,
            'font'          => $font_replace ? 'freesans' : 'opensans',
            'heading_color' => array( 110, 8, 8 ),
            'text_color'    => array( 66, 7, 7 ),
        ),
        'yellow'      => array(
            'x'             => 61,
            'font'          => $font_replace ? 'freesans' : 'opensans',
            'heading_color' => array( 109, 110, 8 ),
            'text_color'    => array( 66, 38, 7 ),
        ),
    );

    $template = 'default';

    if ( isset( $edd_options['eddpdfi_templates'] ) ) {
        $template = $edd_options['eddpdfi_templates'];
    }

    if ( isset( $templates[$template] ) ) {
        $style = $templates[$template];
    }

    return $style;
}

add_filter( 'edd_vat_cart_label_tax_rate', 'modula_change_vat_label_tax', 10, 3 );
function modula_change_vat_label_tax( $label, $cart_tax, $formatted_rate ){

    $label = sprintf( __( '%2$s(%1$s%%)', 'edd-eu-vat' ), $formatted_rate, $cart_tax );
    return $label;

}

function modula_get_country(){

    $user_address = edd_get_customer_address();

    if( ! empty( $_POST['billing_country'] ) ) {
        $country = $_POST['billing_country'];
    } elseif( is_user_logged_in() && ! empty( $user_address['country'] ) ) {
        $country = $user_address['country'];
    }elseif ( isset( $_SERVER["HTTP_CF_IPCOUNTRY"] ) && empty( $_POST['billing_country'] ) ) {
        $country = $_SERVER["HTTP_CF_IPCOUNTRY"];
    }

    $country = ! empty( $country ) ? $country : edd_get_shop_country();
    return $country;

}
