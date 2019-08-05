<?php wp_enqueue_style('odometer');  ?>
<?php wp_enqueue_script('odometer');  ?>
<?php wp_enqueue_script('waypoints');  ?>

<section class="footer-section">

	<div class="footer container">
		<div class="row">
			<div class="col-lg-6">
				<?php dynamic_sidebar( 'footer-widgets-1' ); ?>
			</div>
			<div class="col-lg-2">
				<?php dynamic_sidebar( 'footer-widgets-2' ); ?>
			</div>
			<div class="col-lg-2">
				<?php dynamic_sidebar( 'footer-widgets-3' ); ?>
			</div>
			<div class="col-lg-2">
				<?php dynamic_sidebar( 'footer-widgets-4' ); ?>
			</div>
		</div>
	</div>

	<div class="subfooter container">
		<div class="row">
			<div class="col-sm-6">
				<?php
				wp_nav_menu(
					array(
						'menu_id'        => 'footer-menu',
						'menu_class'     => 'footer-menu',
						'theme_location' => 'footer_menu',
						'depth'          => '1',
						'container'      => false,
					)
				);
				?>
			</div>
			<div class="col-sm-6 text-sm-right">
				<p class="mb-3 mb-sm-0">© 2018-<?php echo date("Y"); ?> MachoThemes. All rights reserved. A <a href="https://www.machothemes.com" target="_blank">MachoThemes</a> product</p>
			</div>
		</div>
	</div>

</section>