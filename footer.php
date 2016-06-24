<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Miga
 */
$nr_footer_widgets = (int) is_active_sidebar('first-footer-widget') + (int) is_active_sidebar('second-footer-widget') + (int) is_active_sidebar('third-footer-widget');
if ( !empty($nr_footer_widgets) ) {
	$col_nr = 12/$nr_footer_widgets;
}
?>

	</div><!-- #content -->

	<!-- FOOTER
	================================================== -->

	<footer style="background: url('<?php echo get_theme_mod( 'footer_bg_img' );?>') no-repeat; background-size: cover;">
		<div class="footer-bg-color" style="background-color: <?php echo get_theme_mod('footer_bg_color');?>;">
			<div class="container">
				<div class="row">
					
					<?php if ( is_active_sidebar('first-footer-widget' ) ) : ?>
						<div class="col-lg-<?php echo $col_nr; ?>">
							<?php dynamic_sidebar( 'first-footer-widget' ); ?>
						</div>
					<?php endif; ?>

					<?php if ( is_active_sidebar('second-footer-widget' ) ) : ?>
						<div class="col-lg-<?php echo $col_nr; ?>">
							<?php dynamic_sidebar( 'second-footer-widget' ); ?>
						</div>
					<?php endif; ?>

					<?php if ( is_active_sidebar('third-footer-widget' ) ) : ?>
						<div class="col-lg-<?php echo $col_nr; ?>">
							<?php dynamic_sidebar( 'third-footer-widget' ); ?>
						</div>
					<?php endif; ?>
						
		      		<hr>
		      		<div class="col-lg-12 text-center">
		      			<?php 
					    if ( get_theme_mod( 'display_social_footer' ) == '1' ) {
					    ?>
					        <p class="badge-social"><a href="<?php echo get_theme_mod('social_1_href'); ?>"><i class="fa fa-<?php echo get_theme_mod('social_1'); ?>"></i></a><a href="<?php echo get_theme_mod('social_2_href'); ?>"><i class="fa fa-<?php echo get_theme_mod('social_2'); ?>"></i></a><a href="<?php echo get_theme_mod('social_3_href'); ?>"><i class="fa fa-<?php echo get_theme_mod('social_3'); ?>"></i></a></p>
					    <?php } 
					    ?>
						<p class="footer-c">&copy; <?php echo date('Y') . ' '; ?><?php bloginfo('name'); ?></p>	
					</div>
		      	</div>
			</div>
		</div>
	</footer>
	 
	<div id="scroll-top">
	    <i class="fa fa-chevron-up fa-2x"></i>
	</div>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
