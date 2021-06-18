<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<?php wp_head(); ?>
	<meta name="theme-color" content="#5333ED">
</head>

<body <?php body_class(); ?>>

<?php //get_template_part( 'template-parts/sections/promotion' ); ?>
<?php do_action('before_header');  ?>

<header class="<?php modula_header_class(); ?>">
	<div class="container">
		<div class="row">
			<div class="col header__content">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo-link" rel="home" itemprop="url"></a>
			</div>
		</div>
	</div>
</header>