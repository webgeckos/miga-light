<?php
/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Miga
 */
// remove empty p-tags
remove_filter ('the_content', 'wpautop');
remove_filter ('the_excerpt', 'wpautop');
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

	<!-- SINGLE BLOG POST CONTENT
	================================================== -->
    <div class="container blog-single" style="background-color: <?php echo get_theme_mod('blog_bg_color');?>;">
	    <div class="row" id="primary">
		    <main id="content" class="col-sm-8">
		    	<div class="post-single-inner" style="background-color: <?php echo get_theme_mod('blog_bg_cards');?>;">

				<?php while ( have_posts() ) : the_post(); ?>

					<?php get_template_part( 'template-parts/content', 'single' ); ?>

					<?php
						// If comments are open or we have at least one comment, load up the comment template.
						if ( comments_open() || get_comments_number() ) :
							comments_template();
						endif;
					?>

					<?php miga_set_post_views(get_the_ID());?>

				<?php endwhile; // End of the loop. ?>

				</div>
				<div class="related-posts">
					<?php miga_related_posts(); ?>
				</div>
			
			</main>

			<!-- SIDEBAR
			================================================== -->
		    <aside class="col-sm-4 side-archive">
		    	<?php get_sidebar(); ?>
		    </aside>
		</div><!-- #primary -->
	</div><!-- .container -->

<?php get_footer(); ?>
