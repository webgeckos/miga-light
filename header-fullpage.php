<?php
/**
 * The header option number 2: Fullpage with overlay
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Miga
 */
?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="keywords" content="WordPress, Bootstrap 4, Cards, Timeline, Flipcards, Masonry, Theme Customizer" />
    <meta name="description" content="Flexible and responsive WordPress Theme by WebGeckos" />
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<!--[if lt IE 9]>
        <script src="<?php echo esc_url( get_template_directory_uri() ); ?>/js/html5shiv.min.js"></script>
        <script src="<?php echo esc_url( get_template_directory_uri() ); ?>/js/respond.min.js"></script>
    <![endif]-->
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?> data-spy="scroll" data-target=".navbar-collapse">
<div id="page" class="hfeed site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'miga' ); ?></a>

	<header id="masthead" class="site-header">

		<div class="container fullpage-nav">
		  	<?php if ( get_theme_mod( 'site_logo' ) ) { ?>
	        <a class="navbar-brand" href="#masthead"><img src="<?php echo get_theme_mod( 'site_logo' ); ?>" alt="Logo"></a>
	        <?php } else { ?>
	        <h1 class="site-title"><a href="#masthead"><?php bloginfo( 'name' ); ?></a></h1>
	        <?php } ?>
		</div>

		<div class="button_container" id="toggle">
		  <span class="top"></span>
		  <span class="middle"></span>
		  <span class="bottom"></span>
		</div>

		<div class="overlay" id="overlay">

		      <?php
		      	   /**
		      		* Displays a navigation menu
		      		* @param array $args Arguments
		      		*/
		      		$args = array(
		      			'theme_location' => 'primary',
		      			'menu' => 'primary',
		      			'container' => 'nav',
		      			'container_class' => 'overlay-menu',
		      			'menu_class' => '',
		      		);

		      		wp_nav_menu( $args );

		      ?>

		</div>

	</header><!-- #masthead -->

	<div id="content" class="site-content">
