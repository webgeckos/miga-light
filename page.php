<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Miga
 */

/*********************************************
* Displaying custom header
**********************************************/
$menu_style = get_theme_mod( 'menu_style' );
if ( $menu_style === 'fullpage' ) {
	get_header('fullpage-custom');
} else {
	get_header('custom');
}
?>

<section class="jumbotron text-xs-center" style="background: url('<?php echo get_theme_mod( 'header_bg_img' );?>') no-repeat; background-size: cover; <?php if ($bg_attachment == '1'){?>background-attachment:fixed; transform: scale(1.2);<?php }?>">
	<div class="blog-header-bg-color" style="background-color: <?php echo get_theme_mod('header_bg_color');?>;">
	  <div class="container">
	    <h1 class="jumbotron-heading"><?php single_post_title();?></h1>
	  </div>
	</div>
</section>

<div class="container blog-single">
	<div class="row" id="primary">
		<main id="content" class="col-sm-8">
			<div class="post-single-inner sub-page" style="background-color: <?php echo get_theme_mod('primary_color');?>;">

				<?php
				while ( have_posts() ) : the_post();

					get_template_part( 'template-parts/content', 'page' );

					// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) :
						comments_template();
					endif;

				endwhile; // End of the loop.
				?>

			</div>
		</main>

		<!-- SIDEBAR
		================================================== -->
	    <aside class="col-sm-4 side-archive">
	    	<?php get_sidebar(); ?>
	    </aside>
	</div><!-- #primary -->
</div><!-- .container -->

<?php
get_sidebar();
get_footer();
