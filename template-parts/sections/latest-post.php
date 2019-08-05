<?php

$args         = array(
	'numberposts' => '1',
	'post_status' => 'publish',
);
$recent_posts = wp_get_recent_posts( $args );
$recent       = $recent_posts[0];

if ( empty( $recent_posts ) ) {
	return;
}

?>

<section class="section latest-post-section py-4">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<p class="d-flex mb-0 align-items-center"><i class="icon icon-pen mr-3"></i><strong class="mr-3">Read our latest blog post:</strong> <a href="<?php echo get_permalink( $recent['ID'] ); ?>"><?php echo get_the_title( $recent['ID'] ); ?></a></p>
			</div>
		</div>
	</div>
</section>
