<?php
$utm_medium = isset( $_GET['utm_medium'] ) ? $_GET['utm_medium'] : '';
$addons = st_get_all_extensions();
?>

<section class="section lite-vs-pro-section">

	<div class="container lite-vs-pro-table">

		<a id="lite-vs-pro" style="position: relative; top: -90px;"></a>

		<div class="row text-center">
			<div class="col-xs-12">
				<h3>Feature Comparison PRO vs Lite</h3>
			</div>
		</div><!-- row -->

		<div class="row">
			<div class="col-md-9">

				<div class="pricing-table pricing-table--header row">
					<div class="col-xs-4">

					</div>
					<div class="col-xs-4">
						<h4 class="pricing-table__title">Agency, Business, Plus & Basic</h4>
						<br />
						<a class="button pricing-table__button" href="#pricing">Upgrade Now</a>
					</div>
					<div class="col-xs-4">
						<h4 class="pricing-table__title">Lite</h4>
						<br />
						<a style="opacity: 0; pointer-events:none" class="button pricing-table__button" href="https://wordpress.org/plugins/modula-best-grid-gallery/" target="_blank">Download Lite</a>
					</div>
				</div><!-- row -->

				<div class="pricing-table row">
					<div class="col-xs-4">
						<span>Support</span>
					</div>
					<div class="col-xs-4">
						Dedicated Support
					</div>
					<div class="col-xs-4">
						w.org Forums
					</div>
				</div><!-- row -->

				<?php while ( $addons->have_posts() ): ?>
					<?php $addons->the_post(); ?>

					<div class="row pricing-table <?php echo isset( $utm_medium ) && $utm_medium === get_post_field( 'post_name' ) ? 'pricing-table--highlight' : ''; ?>">
						<div class="col-xs-4">
							<?php echo st_get_post_meta( get_the_id(), 'pricing_title' ) != '' ? st_get_post_meta( get_the_id(), 'pricing_title' ) : ltrim( get_the_title(), "Strong Testimonials" ); ?>

							<?php if ( st_get_post_meta( get_the_id(), 'tooltip' ) != '' || has_excerpt() ): ?>
								<span class="tooltip">
									<i class="icon-question-circle"></i>
									<span class="tooltip__text"><?php echo st_get_post_meta( get_the_id(), 'tooltip' ) != '' ? st_get_post_meta( get_the_id(), 'tooltip' ) : get_the_excerpt(); ?></span>
								</span>
							<?php endif; ?>
						</div>
						<div class="col-xs-4">
							<i class="icon-ok"></i>
						</div>
						<div class="col-xs-4">
							<i class="icon-cancel"></i>
						</div>
					</div><!-- row -->

				<?php endwhile; ?>

				<div class="pricing-table pricing-table--last row">
					<div class="col-xs-4">
					</div>
					<div class="col-xs-4">
						<a class="button pricing-table__button" href="#pricing">Upgrade Now</a>
					</div>
					<div class="col-xs-4">
					</div>
				</div><!-- row -->

			</div><!-- col -->
		</div><!-- row -->
	</div><!-- container -->




</section>

