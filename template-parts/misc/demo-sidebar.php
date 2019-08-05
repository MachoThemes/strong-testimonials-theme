

<?php
$page_id = get_the_id();
$args = array(
	'post_type'      => 'page',
	'posts_per_page' => -1,
	'post_parent'    => 575, //the demos page template
	'order'          => 'ASC',
	'orderby'        => 'menu_order'
);
$query = new WP_Query( $args );
?>

<?php while ( $query->have_posts() ) : $query->the_post(); ?>
	<div class="accordion <?php echo in_array ( get_the_id() , get_post_ancestors( $page_id ) ) ? 'accordion--opened': ''; ?> mb-2">
		<div class="accordion__title"><?php echo get_the_title(); ?></div>
		<div class="accordion__content">
			<ul>
				<?php wp_list_pages( 'sort_column=menu_order&title_li=&child_of=' . get_the_id() . '&echo=1' ); ?>
			</ul>
		</div>
	</div>
<?php endwhile; ?>
<?php wp_reset_postdata(); ?>