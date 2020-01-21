<?php /* Template Name: Pricing */ ?>

<?php get_header('2'); ?>

<section class="section title-section pt-5">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-6">
				<h1 class="h2 text-center ">Sales copy grabs attention<span class="has-primary-color">.</span><br/>Testimonials drive sales<span class="has-primary-color">.</span></h1>
				<div class="title-section__excerpt text-center">Choose the perfect plan for you, risk free with our 14 day money-back guarantee.</div>
				<div class="row checkout-badges align-items-center mt-3 mb-3">
					<div class="col-6 col-sm-3 text-center mb-3 mb-sm-0">
						<div title="SSL Encrypted Payment" class="checkout-badges__ssl">
							<?php echo file_get_contents( get_template_directory_uri() . '/assets/img/checkout-badges/ssl.svg' ); ?>
						</div>
					</div>
					<div class="col-6 col-sm-4 text-center mb-3 mb-sm-0">
						<div class="checkout-badges__cc">
							<?php echo file_get_contents( get_template_directory_uri() . '/assets/img/checkout-badges/cc.svg' ); ?>
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

			</div>
		</div>
	</div>
</section>

<?php get_template_part( 'template-parts/sections/pricing' ); ?>
<?php get_template_part( 'template-parts/sections/faq' ); ?>
<?php get_template_part( 'template-parts/sections/as-seen-on' ); ?>
<?php get_template_part( 'template-parts/sections/lite-vs-pro' ); ?>
<?php get_template_part( 'template-parts/sections/testimonials' ); ?>

<?php get_footer(); ?>
