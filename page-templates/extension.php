<?php /* Template Name: Extension */ ?>

<?php get_header(); ?>

<?php get_template_part( 'template-parts/sections/title' ); ?>

<section class="section main-section pb-5">
	<div class="container">
		<div class="row">
			<?php if ( have_posts() ) : ?>
				<?php while ( have_posts() ) : ?>
					<?php the_post(); ?>
					<div class="col-xl-3 order-1 order-xl-0">

						<div class="post-sidebar">
							<div class="cta-box p-4 mb-4">
								<h4 class="mb-4">Easily collect and display testimonials on your website<span class="has-primary-color">.</span></h4>
								<a class="button button--small mb-0" href="<?php echo esc_url( get_permalink( get_page_by_path( 'pricing' ) ) ); ?>">Buy Strong Testimonials</a>
								<?php echo file_get_contents( get_template_directory_uri() . '/assets/img/illustration-6.svg' ); ?>
							</div><!-- cta-box -->
						</div><!-- post-sidebar -->

					</div>
					<div class="post-content col-xl-6 order-xl-1">
						<?php the_content(); ?>
					</div>
					<div class="col-xl-3 order-2 order-xl-2">
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
					</div>
				<?php endwhile; ?>
			<?php endif; ?>
		</div>
	</div>
</section>

<?php get_footer(); ?>