<?php

//localhost
//$download1_id = 237;
//$download2_id = 235;

//strongtestimonials.com
$download1_id = 3119;
$download2_id = 3121;
$download3_id = 6226;

wp_enqueue_script( 'waypoints' );

$upgrading = false;
$cart_discounts = edd_get_cart_discounts();
$utm_medium = isset( $_GET['utm_medium'] ) ? $_GET['utm_medium'] : '';
$download_ids = array( $download3_id, $download1_id, $download2_id );

$downloads = array();
foreach ( $download_ids as $id ) {
	$download = edd_get_download( $id );
	$downloads[] = $download;
}

$addons = st_get_all_extensions( $downloads );

?>

<section class="section pricing-section">

	<a id="pricing" style="position: relative; top: -90px;"></a>

	<div class="container main-table">

		<div class="row justify-content-center">
			<div class="col-md-9">

				<div class="pricing-table pricing-table--header row">

					<div class="col-xs-3">

						<div class="pricing-table__message visible-xs waypoint">
							<div class="row align-items-center">
								<div class="col-xs-10">
								swipe left to see the entire table
								</div>
								<div class="col-xs-2">
									<i class="icon-right-open-big"></i>
								</div>
							</div>
						</div><!-- pricing-table__message -->

					</div><!-- col -->

					<?php foreach( $downloads as $download ): ?>

						<div class="col-xs-3 <?php echo isset( $download->higher_plan ) && $download->higher_plan === false ? 'pricing-table-inactive': ''; ?>">

							<?php if( $download->post_title === 'Plus' ): ?>
								<div class="pricing-table__label">Most Popular</div>
							<?php endif; ?>

							<h3 class="pricing-table__title mb-3"><?php echo $download->post_title; ?><span class="has-primary-color">.</span></h3>

							<?php if ( $upgrading && $download->higher_plan ): ?>
								<div class="pricing-table__initial-price">
									$<?php echo floor( edd_get_download_price( $download->ID ) ); ?>
								</div>
							<?php elseif ( count( $cart_discounts ) > 0 ): ?>
								<div class="pricing-table__initial-price">
									$<?php echo floor( edd_get_download_price( $download->ID ) ); ?>
								</div>
							<?php endif; ?>

							<div class="pricing-table__price mb-3">
								<?php if ( $upgrading && $download->higher_plan ): ?>
									<sup>$</sup><?php echo $download->upgrade_cost; ?>
								<?php else: ?>
									<sup>$</sup><?php echo st_edd_get_download_price( $download->ID ); ?>
								<?php endif; ?>
							</div>

							<p class="pricing-table__description mb-3">
								<?php echo $download->post_excerpt;  ?>
							</p>

							<?php if ( $upgrading && $download->higher_plan ): ?>
								<div class="pricing-table__savings">
									<p class="wp-block-machothemes-highlight mb-2">
										<mark class="wp-block-machothemes-highlight__content">$<?php echo edd_get_download_price( $download->ID ) - $download->upgrade_cost; ?> savings</mark>
									</p>
								</div>
							<?php elseif ( count( $cart_discounts ) > 0 ): ?>
								<div class="pricing-table__savings">
									<p class="wp-block-machothemes-highlight mb-2">
										<mark class="wp-block-machothemes-highlight__content">$<?php echo edd_get_download_price( $download->ID ) - st_edd_get_download_price( $download->ID ); ?> savings</mark>
									</p>
								</div>
							<?php endif; ?>

							<?php if ( $upgrading && $download->higher_plan ): ?>
								<a class="button pricing-table__button" href="<?php echo esc_url( edd_sl_get_license_upgrade_url( $license_by_key->ID, $download->upgrade_id ) ); ?>" title="Upgrade">Upgrade</a>
							<?php else: ?>
								<?php echo do_shortcode( '[purchase_link price="0" class="button pricing-table__button" text="Buy Now" id="' . $download->ID . '" direct="true"]' ) ?>
							<?php endif; ?>

						</div><!-- col -->

					<?php endforeach; ?>

					</div>

					<div class="pricing-table row">
					<div class="col-xs-3" style="border-top-left-radius: 10px;">
						Supported Sites
						<span class="tooltip">
							<i class="icon-question-circle"></i>
							<span class="tooltip__text">The number of sites on which you can use Strong Testimonials.</span>
						</span>
					</div>

					<?php foreach( $downloads as $download ): ?>

						<div class="col-xs-3">
							<?php echo st_nr_of_sites( $download->ID ); ?>
						</div>

					<?php endforeach; ?>

					</div><!-- row -->

					<div class="pricing-table row">
					<div class="col-xs-3">
						Lifetime Support
						<span class="tooltip">
							<i class="icon-question-circle"></i>
							<span class="tooltip__text">In case you ever run into issues with our plugin (unlikely), feel free to reach out to our support at any time.</span>
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
					</div><!-- row -->

					<div class="pricing-table row">
					<div class="col-xs-3">
						Lifetime of Free Updates
						<span class="tooltip">
							<i class="icon-question-circle"></i>
							<span class="tooltip__text">You’ll have lifetime access to updates – including future versions of Strong Testimonials.</span>
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
					</div><!-- row -->

					<?php while ( $addons->have_posts() ): ?>
					<?php $addons->the_post(); ?>

					<div class="row pricing-table <?php echo isset( $utm_medium ) && $utm_medium === get_post_field( 'post_name' ) ? 'pricing-table--highlight' : ''; ?>">
						<div class="col-xs-3">
							<?php echo st_get_post_meta( get_the_id(), 'pricing_title' ) != '' ? st_get_post_meta( get_the_id(), 'pricing_title' ) : ltrim( get_the_title(), "Strong Testimonials" ); ?>

							<?php if ( st_get_post_meta( get_the_id(), 'tooltip' ) != '' || has_excerpt() ): ?>
								<span class="tooltip">
									<i class="icon-question-circle"></i>
									<span class="tooltip__text"><?php echo st_get_post_meta( get_the_id(), 'tooltip' ) != '' ? st_get_post_meta( get_the_id(), 'tooltip' ) : get_the_excerpt(); ?></span>
								</span>
							<?php endif; ?>
						</div>

						<?php foreach ( $downloads as $download ): ?>
							<div class="col-xs-3">
								<?php if ( false === array_search( get_the_id(), $download->get_bundled_downloads() ) ): ?>
									<i class="icon-cancel"></i>
								<?php else: ?>
									<i class="icon-ok"></i>
								<?php endif; ?>
							</div>
						<?php endforeach; ?>

					</div><!-- row -->

					<?php endwhile; ?>

					<div class="pricing-table pricing-table--last row">
					<div class="col-xs-3 text-left">
						<span class="mb-0"><small>Prices are listed in USD<br/> and don't include VAT</small></span>
					</div>

					<?php foreach ( $downloads as $download ): ?>

						<div class="col-xs-3 <?php echo isset( $download->higher_plan ) && $download->higher_plan === false ? 'pricing-table-inactive': ''; ?>">

							<?php if ( $upgrading && $download->higher_plan ): ?>
								<a class="button pricing-table__button" href="<?php echo esc_url( edd_sl_get_license_upgrade_url( $license_by_key->ID, $download->upgrade_id ) ); ?>" title="Upgrade">Upgrade</a>
							<?php else: ?>
								<?php echo do_shortcode( '[purchase_link price="0" class="button pricing-table__button" text="Buy Now" id="' . $download->ID . '" direct="true"]' ) ?>
							<?php endif; ?>

						</div><!-- col -->

					<?php endforeach; ?>

				</div><!-- row -->

			</div>
		</div>

		<div class="row mt-5 mb-5">
			<div class="col-xs-12">
				<div class="pricing-message p-5 p-lg-4 p-xl-5">
					<div class="row align-items-center">
						<div class="col-md-2 text-center">
							<img class="mb-2 mb-md-0" src="<?php echo ANTREAS_ASSETS_IMG; ?>/illustration-8.svg" width="200"/>
						</div>
						<div class="col-md-10">
							<h4>100% No-Risk Money Back Guarantee<span class="has-primary-color">!</span></h4>
							<p class="mb-0">You are fully protected by our 100% No-Risk-Double-Guarantee.  If you don’t like Strong Testimonials over the next 14 days, then we will happily refund 100% of your money. No questions asked.</p>
						</div>
					</div><!-- row -->
				</div>
			</div>
		</div><!-- row -->

	</div><!-- container -->

</section>