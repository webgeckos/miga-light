<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Miga
 */

// remove empty p-tags
remove_filter ('the_content', 'wpautop');
remove_filter ('the_excerpt', 'wpautop');
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<h2 style="color: <?php echo get_theme_mod( 'blog_post_title_color' );?>"><?php the_title(); ?></h2>

		<?php if ( 'post' === get_post_type() ) : ?>
			<div class="post-details" style="color: <?php echo get_theme_mod( 'blog_text_color' );?>">
    			<i class="fa fa-user"></i> <?php the_author(); ?>
				<i class="fa fa-clock-o"></i> <time><?php the_date(); ?></time>
				<i class="fa fa-folder"></i> <?php the_category(', '); ?> <!-- , is the seperator -> first parameter of the function the_category-->
				<i class="fa fa-tags"></i> <?php the_tags(); ?>
				<?php if ( get_theme_mod( 'display_view_counter' ) === 1 ) {?>
					<i class="fa fa-eye"></i> <?php echo miga_get_post_views(get_the_ID()); ?>
				<?php }?>
				
				<div class="post-comments-badge">
					<a href="<?php comments_link();?>"><i class="fa fa-comments"></i> <?php comments_number( 0, 1, '%' ); ?></a><!-- % means the actual number of comments-->
				</div><!-- post-comments-badge -->
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
	<div class="post-body" style="color: <?php echo get_theme_mod( 'blog_text_color' );?>;">
		<?php the_content();?>
	</div><!-- post-body -->
</article><!-- #post-## -->
