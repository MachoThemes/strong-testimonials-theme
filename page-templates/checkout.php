<?php /* Template Name: Checkout */ ?>

<?php get_header('2'); ?>

<?php
	$payment_mode = edd_get_chosen_gateway();
	$form_action  = esc_url( edd_get_checkout_uri( 'payment-mode=' . $payment_mode ) );
?>

<section class="section title-section py-5">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-6">
				<h1 class="h2 text-center">Complete Your Purchase<span class="has-primary-color">.</span></h1>
				<p class="mb-0 text-center">You’re just 60 seconds away from powerful, easy-to-use testimonials.</p>
			</div>
		</div>
	</div>
</section>

<section class="section main-section py-5">
	<div class="container">
		<div class="row justify-content-center">

			<?php if ( function_exists('edd_get_cart_contents') && edd_get_cart_contents() ) : ?>

				<div class="col-lg-5 order-lg-2">

					<div class="edd-cart-col">

						<div class="row checkout-badges align-items-center mb-5">
							<div class="col-6 col-sm-3 text-center mb-3 mb-sm-0">
								<div title="SSL Encrypted Payment" class="checkout-badges__ssl">
									<?php echo file_get_contents( get_template_directory_uri() . '/assets/img/checkout-badges/ssl.svg' ); ?>
								</div>
							</div>
							<div class="col-6 col-sm-4 text-center mb-3 mb-sm-0">
								<div title="14 Day Moneyback Guarantee" class="checkout-badges__cc">
									<?php echo file_get_contents( get_template_directory_uri() . '/assets/img/checkout-badges/moneyback.svg' ); ?>
								</div>
							</div>
							<div class="col-6 col-sm-2 text-center">
								<div title="Norton Secured Transaction" class="checkout-badges__norton">
									<?php echo file_get_contents( get_template_directory_uri() . '/assets/img/checkout-badges/norton-secured.svg' ); ?>
								</div>
							</div>
							<div class="col-6 col-sm-3 text-center">
								<div title="McAfee Secured Transaction" class="checkout-badges__mcafee">
									<?php echo file_get_contents( get_template_directory_uri() . '/assets/img/checkout-badges/mcafee.svg' ); ?>
								</div>
							</div>
						</div><!-- row -->

						<div class="testimonial mb-3">
							<div class="d-flex justify-content-start mb-3">
								<div class="testimonial__stars"></div>
							</div>

							<div class="testimonial__content p-3">
								<p class="mb-0">I have used various testimonials plugins.  What I get here for free is just amazing. The support is great. And updates fresh. Looking at the ability to get reviews indexed by Google is more than enough reason to get an upgrade.</p>
							</div>
							<div class="testimonial__author ml-3 mt-3">
								<?php echo wp_get_attachment_image( 463, "thumbnail", false, array('class' => 'testimonial__avatar mr-3') ); ?>
								<div class="testimonial__name">
									<p class="mb-0">Johan Horak</p>
									<p class="testimonial__title mb-0">Marketing at CapeHolidays</p>
								</div>
							</div>
						</div><!-- testimonial -->


					</div><!-- edd-cart-col -->

				</div><!-- col -->

				<div class="col-lg-7 order-lg-1">

					<?php edd_checkout_cart(); ?>

					<div id="edd_checkout_form_wrap" class="edd_clearfix">
						<?php do_action( 'edd_before_purchase_form' ); ?>
						<form id="edd_purchase_form" class="edd_form" action="<?php echo $form_action; ?>" method="POST">
							<?php
							/**
							 * Hooks in at the top of the checkout form
							 *
							 * @since 1.0
							 */
							do_action( 'edd_checkout_form_top' );

							if ( edd_is_ajax_disabled() && ! empty( $_REQUEST['payment-mode'] ) ) {
								do_action( 'edd_purchase_form' );
							} elseif ( edd_show_gateways() ) {
								do_action( 'edd_payment_mode_select'  );
							} else {
								do_action( 'edd_purchase_form' );
							}

							/**
							 * Hooks in at the bottom of the checkout form
							 *
							 * @since 1.0
							 */
							do_action( 'edd_checkout_form_bottom' );


							?>
						</form>
						<?php do_action( 'edd_after_purchase_form' ); ?>
					</div><!--end #edd_checkout_form_wrap-->

				</div><!-- col -->

			<?php else: ?>

				<div class="col-sm-12 title-wrap">
					<div class="text-center">
						<p class="empty-cart">Your cart is empty</p>
						<a class="button" href="<?php echo esc_url( get_permalink( get_page_by_path( 'pricing' ) ) ); ?>">Buy Strong Testimonials</a>
					</div>
				</div>

			<?php endif; ?>

		</div>
	</div>
</section>

<?php get_footer(); ?>
