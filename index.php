<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
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

/*********************************************
* Declaring variables
**********************************************/
$bg_attachment = get_theme_mod( 'parallax' );
$bg_attachment_blog = get_theme_mod( 'parallax_blog' );
$blog_cat = get_theme_mod( 'blog_cat' );
$display_option = get_theme_mod( 'blog_display' );

/*********************************************
* Displaying the selected intro section (header)
**********************************************/
?>
<section class="jumbotron text-xs-center" style="background: url('<?php echo get_theme_mod( 'header_bg_img' );?>') no-repeat; background-size: cover; <?php if ($bg_attachment == '1'){?>background-attachment:fixed; transform: scale(1.2);<?php }?>">
	<div class="blog-header-bg-color" style="background-color: <?php echo get_theme_mod('header_bg_color');?>;">
	  <div class="container">
	    <h1 class="jumbotron-heading"><?php single_post_title();?></h1>
	  </div>
	</div>
</section>

<div class="container blog-container" style="background: url('<?php echo get_theme_mod( 'blog_bg_img' );?>') no-repeat; background-size: cover; <?php if ($bg_attachment_blog == '1'){?>background-attachment:fixed;<?php }?>">
	<div class="blog-bg-color" style="background-color: <?php echo get_theme_mod('blog_bg_color');?>;">

		<div class="row">

			<!-- main content area-->
			<div class="col-lg-9 content-fullwidth">

		    			<div class="container">

						  <div class="row section-padding">  	

						  		<!-- include opening tag if mansory display option was selected -->
						  		<?php if ( $display_option === 'option-2' ) {?>
				    				<div class="card-columns">
				    			<?php }

				    		$pages = $wp_query->max_num_pages;
				    		
				    		$args = array(
										
								//Category Parameters
								'cat'            => $blog_cat,
								
								//Type & Status Parameters
								'post_type'      => 'post',
								'post_status'    => 'publish',
								
								//Order & Orderby Parameters
								'order'          => 'DESC',
								'orderby'        => 'date',

								//Pagination Parameters
								'posts_per_page' => 4,
								'paged' => $paged
							);
							$blog_query = new WP_Query( $args );

				    		if ( $blog_query->have_posts() ) {

						        while ( $blog_query->have_posts() ) {
							        $blog_query->the_post();

							        // get the url of thumbnail image
							        $image_id = get_post_thumbnail_id();
							        $image_url = wp_get_attachment_url( $image_id, 'medium', true );
							        // get the alt-tag of thumbnail image
							        $image_alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true );

							        if ( $display_option === 'option-1' ) {?>

						    			<div class="col-lg-4">
								          <div class="card">
								          	<figure class="<?php echo $img_filter;?>">
								            	<img class="card-img-top" src="<?php echo $image_url; ?>" alt="<?php echo $image_alt; ?>">
								            </figure>
								            <div class="card-block">
								              <h3 class="card-title"><?php the_title(); ?></h3>
								              <p class="card-text">

								              	<?php echo the_excerpt(); ?>

								              </p>
								              <?php if ( get_theme_mod( 'blog_show_views' ) === 1 ) {?>
											  <p class="card-text"><small class="text-muted"><?php the_time( get_option( 'date_format' ) ); ?></small></p><!-- if multiple post are posted on the same day the_date function only displays the first one-->
											  <?php } ?>
								            </div>
								          </div>
								        </div>

								    <?php 
						    		} else {

						    				if ( has_post_format( 'quote' )) { ?>

											    <div class="card card-block card-inverse card-primary text-xs-center">
									              <blockquote class="card-blockquote">
									                <?php echo the_content(); ?>
									              </blockquote>
									            </div>

											<?php } else if (has_post_format('video')) { ?>

											   	<div class="card">
									              <?php echo the_content(); ?>
									            </div>

											<?php } else if (has_post_format('image')) { ?>

											   	<div class="card">
									              <img class="card-img" src="<?php echo $image_url; ?>" alt="<?php echo $image_alt; ?>">
									            </div>

											<?php } else if (has_post_format('link')) {?>

											   	<div class="card card-block">
									              <h3 class="card-title"><?php echo the_title(); ?></h3>
									              <p class="card-text"><?php echo the_content(); ?></p>
									            </div>

											<?php } else { //standard post format?>

											   <div class="card card-decks">
									            <img class="card-img-top" src="<?php echo $image_url; ?>" alt="<?php echo $image_alt; ?>">
									            <div class="card-block">
									              <h3 class="card-title"><?php echo the_title(); ?></h3>
									              <p class="card-text"><?php echo the_excerpt(); ?></p>
									            </div>
									          </div>

											<?php }
									} // end else

								} // end while
							// Previous/next page navigation.
							if ($blog_query->max_num_pages > 1) { // check if the max number of pages is greater than 1
								miga_pagination($blog_query->max_num_pages);
							}

						    wp_reset_query();

						} else {

							get_template_part( 'template-parts/content', 'none' );

						} // end if ?>

		    			
					  		<!-- include opening tag if mansory display option was selected -->
					  		<?php if ( $display_option === 'option-2' ) {?>
			    				</div>
			    			<?php } ?>

				  </div><!-- .row -->

				</div><!-- .container -->

			</div>

			<div class="col-lg-3 side-right">
				<?php get_sidebar();?>
			</div>
		</div><!-- .row -->

	</div><!-- .blog-bg-color -->

</div><!-- .container -->
<?php get_footer(); ?>