
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php if ( has_post_thumbnail() ) : ?>
		<div class="post__thumbnail mb-4">
			<a href="<?php echo esc_url( get_the_permalink() ); ?>">
				<?php the_post_thumbnail( 'st_medium_cropped' ); ?>
			</a>
		</div>
	<?php endif; ?>

	<div class="post__content">

		<h5 class="post__title mb-4">
			<a href="<?php echo esc_url( get_the_permalink() ); ?>"><?php echo esc_html( get_the_title() ); ?></a>
		</h5>

		<div class="post__excerpt">
			<?php the_excerpt(); ?>
		</div>

	</div>

</article>

