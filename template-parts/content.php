<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Miga
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="post-single-inner">
		<header class="entry-header">
			<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

			<?php if ( 'post' === get_post_type() ) : ?>
				<div class="post-details">
	    			<i class="fa fa-user"></i> <?php the_author(); ?>
					<i class="fa fa-clock-o"></i> <time><?php the_date(); ?></time>
					<i class="fa fa-folder"></i> <?php the_category(', '); ?> <!-- , is the seperator -> first parameter of the function the_category-->
					<i class="fa fa-tags"></i> <?php the_tags(); ?>
					
					<!-- if the user is logged in add the edit post link with a pencil from fontawesome -->
					<?php edit_post_link( 'Edit', '<i class="fa fa-pencil"></i>', '' );?>
				</div><!-- post-details -->
			<?php endif; ?>
		</header><!-- .entry-header -->

		<?php if( has_post_thumbnail() ) { //check for feature image?>
		<div class="post-image">
			<?php the_post_thumbnail();?>
		</div><!-- post-image -->
		<?php } ?>
		<div class="post-excerpt">
			<?php the_excerpt();?>
		</div><!-- post-excerpt -->
	</div><!-- .post-single-inner-->
</article><!-- #post-## -->
