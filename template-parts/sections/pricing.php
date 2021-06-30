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
<section class="section pricing-table--comparison" style="display:none;">
	<div class="container main-table">
		<div id="pricing-table" class="pricing-table pricing-table--header row no-gutters">
			<div class="col-xs-3">

				<div class="pricing-table__message">
					<div class="row align-items-center">
						<div class="col-xs-12">
							<h6>You can change plans or cancel your account at any time</h6>
							<small>Choose a plan and you can upgrade or cancel it any time you want.</small>
						</div>

					</div>
				</div><!-- pricing-table__message -->
			</div>

			<?php foreach ( $downloads as $download ) : ?>
				<div class="col-xs-3 <?php echo isset( $download->higher_plan ) && $download->higher_plan === false ? 'pricing-table-inactive' : ''; ?>">

					<h4 class="pricing-table__title mb-2"><?php echo $download->post_title; ?></h4>

					<div class="pricing-table__price mb-2">
						<?php if ( $upgrading && $download->higher_plan ) : ?>
							<div class="pricing-table__initial-price">
								$<?php echo floor( edd_get_download_price( $download->ID ) ); ?>
							</div>
						<?php elseif ( count( $cart_discounts ) > 0 ) : ?>
							<div class="pricing-table__initial-price">
								$<?php echo floor( edd_get_download_price( $download->ID ) ); ?>
							</div>
						<?php endif; ?>
						<?php if ( $upgrading && $download->higher_plan ) { ?>
							<sup>$</sup><?php echo $download->upgrade_cost; ?>
						<?php } else { ?>
							<sup>$</sup><?php echo floor( st_edd_get_download_price( $download->ID ) ); ?><sup>.00</sup>
						<?php } ?>
					</div>

					<?php if ( $upgrading && $download->higher_plan ) : ?>
						<div class="pricing-table__savings">
							<p class="wp-block-machothemes-highlight mb-2">
								<mark class="wp-block-machothemes-highlight__content">
									$<?php echo edd_get_download_price( $download->ID ) - $download->upgrade_cost; ?>
									savings
								</mark>
							</p>
						</div>
					<?php elseif ( count( $cart_discounts ) > 0 ) : ?>
						<div class="pricing-table__savings">
							<p class="wp-block-machothemes-highlight mb-2">
								<mark class="wp-block-machothemes-highlight__content">
									$<?php echo edd_get_download_price( $download->ID ) - st_edd_get_download_price( $download->ID ); ?>
									savings
								</mark>
							</p>
						</div>
					<?php endif; ?>

					<?php if ( $upgrading && $download->higher_plan ) : ?>
						<a class="button pricing-table__button"
						   href="<?php echo esc_url( edd_sl_get_license_upgrade_url( $license_by_key->ID, $download->upgrade_id ) ); ?>"
						   title="Upgrade">Upgrade</a>
					<?php else : ?>
						<?php echo do_shortcode( '[purchase_link price="0" class="edd-submit button pricing-table__button" text="Buy Now" id="' . $download->ID . '" direct="true"]' ); ?>
					<?php endif; ?>

				</div><!-- col -->

			<?php endforeach; ?>

		</div>

		<div class="pricing-table row">
			<div class="col-xs-3">
				Supported Sites
				<span class="tooltip">
					<i class="icon-question-circle"></i>
					<span class="tooltip__text">The number of sites on which you can use Strong Testimonials.</span>
				</span>
			</div>

			<?php foreach ( $downloads as $download ) : ?>

				<div class="col-xs-3">
					<?php echo st_nr_of_sites( $download->ID ); ?>
				</div>

			<?php endforeach; ?>

		</div><!-- row -->


		<div class="pricing-table row">
			<div class="col-xs-3">
				Support for 1 full year
				<span class="tooltip">
					<i class="icon-question-circle"></i>
					<span class="tooltip__text">In case you ever run into issues with our plugin (unlikely), feel free to reach out to our support at any time.
					<br/>------------
					<br/>1. Priority support - tickets get handled in 12 hours or less.
					<br/>2. Regular support - tickets get handled in 48 hours or less.
					<br/>------------
					<br/>* On weekends, response time might slow down to 48hours for priority and up to 96 hours for regular support.</span>
				</span>
			</div>
			<div class="col-xs-3">
				<mark>Priority</mark>
			</div>
			<div class="col-xs-3">
				<mark>Priority</mark>
			</div>
			<div class="col-xs-3">
				Regular
			</div>
			<div class="col-xs-3">
				Regular
			</div>
		</div><!-- row -->

		<div class="pricing-table row">
			<div class="col-xs-3">
				Updates for 1 full year
				<span class="tooltip">
					<i class="icon-question-circle"></i>
					<span class="tooltip__text">You’ll have access to free updates for 1 year or until you cancel your subscription.
					<br/> All of our subscriptions are automatically renewing and renew every 12 months from the last payment date.
					</span>
				</span>
			</div>
			<div class="col-xs-3">
				<i class="icon-ok"></i>
			</div>
			<div class="col-xs-3">
				<i class="icon-ok"></i>
			</div>
			<div class="col-xs-3">
				<i class="icon-ok"></i>
			</div>
			<div class="col-xs-3">
				<i class="icon-ok"></i>
			</div>
		</div><!-- row -->


		<div class="pricing-table row">
			<div class="pricing-breaker">
				<h4>Extensions included with each purchase</h4>
			</div><!--pricing-breaker-->
		</div><!--row -->


		<?php while ( $addons->have_posts() ) : ?>
			<?php $addons->the_post(); ?>

			<div class="row pricing-table <?php echo isset( $utm_medium ) && $utm_medium === get_post_field( 'post_name' ) ? 'pricing-table--highlight' : ''; ?>">
				<div class="col-xs-3">
					<?php echo st_get_post_meta( get_the_id(), 'pricing_title' ) != '' ? st_get_post_meta( get_the_id(), 'pricing_title' ) : get_the_title(); ?>

					<?php if ( st_get_post_meta( get_the_id(), 'tooltip' ) != '' || has_excerpt() ) : ?>
						<span class="tooltip">
							<i class="icon-question-circle"></i>
							<span class="tooltip__text"><?php echo st_get_post_meta( get_the_id(), 'tooltip' ) != '' ? st_get_post_meta( get_the_id(), 'tooltip' ) : get_the_excerpt(); ?></span>
						</span>
					<?php endif; ?>
				</div>

				<?php foreach ( $downloads as $download ) : ?>
					<div class="col-xs-3">
						<?php if ( false === array_search( get_the_id(), $download->get_bundled_downloads() ) ) : ?>
							<i class="icon-cancel"></i>
						<?php else : ?>
							<i class="icon-ok"></i>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>

			</div><!-- row -->

		<?php endwhile; ?>


		<div class="pricing-table pricing-table--last row">
			<div class="col-xs-3">
				<!-- left this here as a spacer -->
			</div>

			<?php foreach ( $downloads as $download ) : ?>

				<div class="col-xs-3 <?php echo isset( $download->higher_plan ) && $download->higher_plan === false ? 'pricing-table-inactive' : ''; ?>">

					<?php if ( $upgrading && $download->higher_plan ) : ?>
						<a class="button pricing-table__button"
						   href="<?php echo esc_url( edd_sl_get_license_upgrade_url( $license_by_key->ID, $download->upgrade_id ) ); ?>"
						   title="Upgrade">Upgrade</a>
					<?php else : ?>
						<?php echo do_shortcode( '[purchase_link price="0" class="edd-submit button pricing-table__button" text="Buy Now" id="' . $download->ID . '" direct="true"]' ); ?>
					<?php endif; ?>

				</div><!-- col -->

			<?php endforeach; ?>

		</div><!-- row -->

	</div><!-- container -->

</section>