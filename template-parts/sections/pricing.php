<?php

//localhost
//$download1_id = 237;
//$download2_id = 235;
//$download3_id = 229;
//$download4_id = 61;

//strongtestimonials.com
$download1_id = 1119;
$download2_id = 45;
$download3_id = 43;
$download4_id = 41;

$utm_medium = isset( $_GET['utm_medium'] ) ? $_GET['utm_medium'] : '';
$upgrading  = false;

//Agency, Business, Trio, Basic
$download_ids = array( $download1_id, $download2_id, $download3_id, $download4_id );

if ( isset( $_GET['discount'] ) ) {

	$discount = new EDD_Discount( $_GET['discount'], true );
	if ( $discount->status === 'active' ) {
		EDD()->session->set( 'cart_discounts', $_GET['discount'] );
	}
}
$cart_discounts = edd_get_cart_discounts();

if ( isset( $_GET['license'] ) ) {
	$license_by_key = edd_software_licensing()->get_license( $_GET['license'], true );

	if ( $license_by_key ) {
		$upgrading           = true;
		$download_by_license = $license_by_key->download;
		$upgrades            = edd_sl_get_license_upgrades( $license_by_key->ID );
	}
}

$downloads = array();
foreach ( $download_ids as $id ) {
	$download = edd_get_download( $id );

	if ( $upgrading ) {
		$download->upgrade_id = st_get_upgrade_id_by_download_id( $upgrades, $download->ID );
		if ( $download->upgrade_id ) {
			$download->upgrade_cost = edd_sl_get_license_upgrade_cost( $license_by_key->ID, $download->upgrade_id );
		}
		$download->higher_plan = array_search( $download->id, $download_ids ) >= array_search( $download_by_license->id, $download_ids ) ? false : true;
	}

	$downloads[] = $download;
}

$addons = st_get_all_extensions( $downloads );
wp_enqueue_script( 'st-pricing', ANTREAS_ASSETS_JS . 'pricing.js', array( 'jquery' ), ANTREAS_VERSION, true );
?>

<section class="section pricing-section " id="pricing">

	<div class="container main-table">
		<div class="plans-table row">
			<?php foreach ( $downloads as $download ) : ?>
				<div class="col-md-3 plan">
					<h4 class="plans-table__title mb-2"><?php echo $download->post_title; ?></h4>
					<?php if ( has_excerpt( $download->ID ) && '' != get_the_excerpt( $download->ID ) ): ?>
						<div class="plans-table__package_description"><?php echo get_the_excerpt( $download->ID ); ?></div>
					<?php endif; ?>
					<p class="plans-table__excerpt"></p>
					<div class="plans-table__price">
						<?php if ( $upgrading && $download->higher_plan ) : ?>
							<div class="plans-table__initial-price">
								$<?php echo floor( edd_get_download_price( $download->ID ) ); ?>
							</div>
						<?php elseif ( count( $cart_discounts ) > 0 ) : ?>
							<div class="plans-table__initial-price">
								$<?php echo floor( edd_get_download_price( $download->ID ) ); ?>
							</div>
						<?php endif; ?>
						<?php if ( $upgrading && $download->higher_plan ) { ?>
							<sup>$</sup><?php echo $download->upgrade_cost; ?>
						<?php } else { ?>
							<sup>$</sup><?php echo floor( st_edd_get_download_price( $download->ID ) ); ?><sup>.00</sup>
						<?php } ?>
					</div>
					<div class="plans-table__savings mb-2">
						<?php if ( $upgrading && $download->higher_plan ) : ?>
							<div class="plans-table__savings">
								<p class="wp-block-machothemes-highlight mb-2">
									<mark class="wp-block-machothemes-highlight__content">
										$<?php echo edd_get_download_price( $download->ID ) - $download->upgrade_cost; ?>
										savings
									</mark>
								</p>
							</div>
						<?php elseif ( count( $cart_discounts ) > 0 ) : ?>
							<div class="plans-table__savings">
								<p class="wp-block-machothemes-highlight mb-2">
									<mark class="wp-block-machothemes-highlight__content">
										$<?php echo edd_get_download_price( $download->ID ) - st_edd_get_download_price( $download->ID ); ?>
										savings
									</mark>
								</p>
							</div>
						<?php endif; ?>
					</div>

					<?php if ( $upgrading && $download->higher_plan ) : ?>
						<a class="button plans-table__button plans-table__button"
						   href="<?php echo esc_url( edd_sl_get_license_upgrade_url( $license_by_key->ID, $download->upgrade_id ) ); ?>"
						   title="Upgrade">Upgrade</a>
					<?php else : ?>
						<?php echo do_shortcode( '[purchase_link price="0" class="edd-submit button plans-table__button pricing-table__button" text="Buy Now" id="' . $download->ID . '" direct="true"]' ); ?>
					<?php endif; ?>

					<div class="plans-table__content">
						<?php echo get_the_content( null, null, $download->ID ) ?>
						<div class="compare-plans-button">
							<a class="compare-plans-button"
							   href="/pricing"><?php _e( 'Compare all plans', 'modula-theme' ); ?></a>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>


<div class="pricing-messsage-row">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<div class="pricing-message">
					<div class="pricing-message__content">
						<h5>100% No-Risk Money Back Guarantee!</h5>
						<p>There’s no risk trying Strong Testimonials: if you don’t like Strong Testimonials after 14
							days, we’ll refund your
							purchase. We take pride in a frustration-free refund process.</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div><!-- row -->