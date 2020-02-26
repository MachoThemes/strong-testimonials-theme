<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<?php wp_head(); ?>
	<meta name="theme-color" content="#5333ED">
	<!-- Hotjar Tracking Code for https://strongtestimonials.com -->
<script>
    (function(h,o,t,j,a,r){
        h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
        h._hjSettings={hjid:1527341,hjsv:6};
        a=o.getElementsByTagName('head')[0];
        r=o.createElement('script');r.async=1;
        r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
        a.appendChild(r);
    })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
</script>
</head>

<body <?php body_class(); ?>>

	<?php //get_template_part( 'template-parts/sections/promotion' ); ?>
	<?php do_action('before_header');  ?>

	<header class="<?php modula_header_class(); ?>">
		<div class="container">
			<div class="row">
				<div class="col header__content">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo-link" rel="home" itemprop="url"></a>

					<div class="menu-icon">
						<div class="menu-icon__navicon"></div>
					</div>

					<?php
					wp_nav_menu(
						array(
							'menu_id'        => 'main-menu',
							'menu_class'     => 'main-menu',
							'theme_location' => 'main_menu',
							'depth'          => '2',
							'container'      => false,
						)
					);
					?>

				</div>
			</div>
		</div>
	</header>