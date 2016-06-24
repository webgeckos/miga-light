<?php
/**
 * The header option number 1: Static Menu with Dropdown
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Miga
 */
//Variable
$nr_header_widgets = (int) is_active_sidebar('first-header-widget') + (int) is_active_sidebar('second-header-widget') + (int) is_active_sidebar('third-header-widget');
if ( !empty($nr_header_widgets) ) {
	$col_nr = 12/$nr_header_widgets;
}
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="keywords" content="WordPress, Bootstrap 4, Cards, Timeline, Flipcards, Theme Customizer" />
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
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'miga' ); ?></a>

	<header id="masthead" class="site-header">
		<!--
		<div class="navbar-collapse collapse inverse" id="navbar-header">
	      <div class="container-fluid">

	      	<?php if ( is_active_sidebar('first-header-widget' ) ) : ?>
				<div class="col-lg-<?php echo $col_nr; ?>">
					<?php dynamic_sidebar( 'first-header-widget' ); ?>
				</div>
			<?php endif; ?>

			<?php if ( is_active_sidebar('second-header-widget' ) ) : ?>
				<div class="col-lg-<?php echo $col_nr; ?>">
					<?php dynamic_sidebar( 'second-header-widget' ); ?>
				</div>
			<?php endif; ?>

			<?php if ( is_active_sidebar('third-header-widget' ) ) : ?>
				<div class="col-lg-<?php echo $col_nr; ?>">
					<?php dynamic_sidebar( 'third-header-widget' ); ?>
				</div>
			<?php endif; ?>

	      </div>
	    </div>-->

	    <div class="container-fluid nav-container">
	    	<!--
	        <div class="navbar-header">
	          <?php if ( is_active_sidebar('first-header-widget') || is_active_sidebar('second-header-widget') || is_active_sidebar('third-header-widget') ) : ?>
		          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-header">
		            <i class="fa fa-angle-double-down fa-2x"></i>
		          </button>
	          <?php endif; ?>
	          <?php if ( get_theme_mod( 'site_logo' ) ) { ?>
		        <a class="navbar-brand page-scroll" href="#masthead"><img src="<?php echo get_theme_mod( 'site_logo' ); ?>" alt="Logo"></a>
		        <?php } else { ?>
		        <h1 class="site-title page-scroll"><a href="#masthead"><?php bloginfo( 'name' ); ?></a></h1>
		        <?php } ?>

	          <div class="navbar-toggle-static visible-xs" data-toggle="mobil-nav" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
		          <span class="sr-only">Toggle navigation</span>
		          <span class="icon-bar"></span>
		          <span class="icon-bar"></span>
		          <span class="icon-bar"></span>
		      </div>
	        </div>-->

	        <a href="#nav" title="Show navigation">Show navigation</a>
			<a href="#" title="Hide navigation">Hide navigation</a>

	        <!--
			<nav id="nav" role="navigation">
			    <a href="#nav" title="Show navigation">Show navigation</a>
			    <a href="#" title="Hide navigation">Hide navigation</a>
			    <ul>
			        <li><a href="/">Home</a></li>
			        <li>
			            <a href="/" aria-haspopup="true">Blog</a>
			            <ul>
			                <li><a href="/">Design</a></li>
			                <li><a href="/">HTML</a></li>
			                <li><a href="/">CSS</a></li>
			                <li><a href="/">JavaScript</a></li>
			            </ul>
			        </li>
			        <li>
			            <a href="/" aria-haspopup="true">Work</a>
			            <ul>
			                <li><a href="/">Web Design</a></li>
			                <li><a href="/">Typography</a></li>
			                <li><a href="/">Front-End</a></li>
			            </ul>
			        </li>
			        <li><a href="/">About</a></li>
			    </ul>
			</nav>
			-->

	        <?php
	      	   /**
	      		* Displays a navigation menu
	      		* @param array $args Arguments
	      		*/
	      		$args = array(
	      			'theme_location' => 'primary',
	      			'menu' => 'primary',
	      			'container' => 'nav',
	      			'container_class' => 'clearfix',
	      			'container_id' => 'miga-main-menu',
	      		);

	      		wp_nav_menu( $args );
	      	?>

	    </div>

	</header><!-- #masthead -->

	<div id="content" class="site-content">
