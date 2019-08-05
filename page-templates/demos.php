<?php /* Template Name: Demos */ ?>

<?php get_header(); ?>

<section class="section title-section">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-6">
				<h1 class="text-center h2 mb-0">Strong Testimonials Demos<span class="has-primary-color">.</span></h1>
			</div>
		</div>
	</div>
</section>

<section class="section main-section">
	<div class="container">
	<div class="row">

		<?php
		$args = array(
			'post_type'      => 'page',
			'posts_per_page' => -1,
			'post_parent'    => $post->ID,
			'order'          => 'ASC',
			'orderby'        => 'menu_order'
		);
		$query = new WP_Query( $args );
		?>

		<?php while ( $query->have_posts() ) : $query->the_post(); ?>

			<div class="col-md-6 col-xl-4 mb-4">

				<div class="docs-single">
					<div class="docs-single__header text-center">
						<h6 class="mb-0"><?php echo get_the_title(); ?></h6>
						<?php if ( has_excerpt() ): ?>
							<p class="mb-0"><?php echo wp_kses_post( get_the_excerpt() ); ?></p>
						<?php endif; ?>
					</div>

					<div class="docs-single__content">

						<?php
						$args = array(
							'post_type'      => 'page',
							'posts_per_page' => -1,
							'post_parent'    => get_the_id(),
							'order'          => 'ASC',
							'orderby'        => 'menu_order'
						);
						$query2 = new WP_Query( $args );
						?>

						<ul>
							<?php while ( $query2->have_posts() ) : $query2->the_post(); ?>
								<li><a href="<?php echo get_permalink(); ?>"><?php echo get_the_title(); ?></a></li>
							<?php endwhile; ?>
							<?php wp_reset_postdata(); ?>
						</ul>

					</div><!-- docs-single__content -->

				</div><!-- docs-single -->

			</div><!-- col -->

		<?php endwhile; ?>
		<?php wp_reset_postdata(); ?>


		</div><!-- row -->
	</div>
</section>

<?php get_template_part( 'template-parts/sections/cta' ); ?>
<?php get_template_part( 'template-parts/sections/latest-post' ); ?>

<?php get_footer(); ?>
