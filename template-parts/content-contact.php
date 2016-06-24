<?php
/**
 * Template part for displaying contact section.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Miga
 */
// Variables needed

?>

<?php if ( get_theme_mod( 'fullwidth-contact' )) { ?>

    <div class="col-md-12">
    <?php 
    if ( is_active_sidebar( 'miga_contact_form' ) ) : ?>
        <fieldset class="miga-contact" style="background-color:<?php echo get_theme_mod('primary_color');?>;">
            <?php dynamic_sidebar( 'miga_contact_form' ); ?>
        </fieldset>
    <?php endif; ?>
    </div><!-- .col-12 --> 

    <div class="col-md-6">
        <fieldset class="miga-contact-full" style="background-color:<?php echo get_theme_mod('primary_color');?>;">
            <i class="fa fa-envelope-square fa-2x" style="color: <?php echo get_theme_mod( 'text_color' ); ?>;"></i>
            <h3><a class="your-email" href="mailto:<?php echo get_theme_mod('email_href'); ?>"> <?php echo get_theme_mod('email'); ?></a></h3>
            <hr class="divider" style="border-color: <?php echo get_theme_mod( 'text_color' ); ?>;">
            <i class="fa fa-phone-square fa-2x" style="color: <?php echo get_theme_mod( 'text_color' ); ?>;"></i>
            <h3><span class="your-phone" style="color: <?php echo get_theme_mod( 'text_color' ); ?>;"> <?php echo get_theme_mod('phone'); ?></span></h3>
            <?php 
            $street = get_theme_mod('street');
            if ($street) { ?>
                <hr class="divider" style="border-color: <?php echo get_theme_mod( 'text_color' ); ?>;">
                <i class="fa fa-home fa-2x" style="color: <?php echo get_theme_mod( 'text_color' ); ?>;"></i>
                <h3><span class="your-street" style="color: <?php echo get_theme_mod( 'text_color' ); ?>;"> <?php echo $street; ?></span><span class="your-city" style="color: <?php echo get_theme_mod( 'text_color' ); ?>;"> <?php echo get_theme_mod('city'); ?></span></h3>
            <?php } ?>
        </fieldset>
    </div><!-- .col-6 --> 
    <div class="col-md-6">
        <fieldset class="miga-contact-full" style="background-color:<?php echo get_theme_mod('primary_color');?>;">
            <?php if ( get_theme_mod( 'opening_hours' )) {?>
                <i class="fa fa-clock-o fa-2x" style="color: <?php echo get_theme_mod( 'text_color' ); ?>;"></i>
                <h3><span class="hours" style="color: <?php echo get_theme_mod( 'text_color' ); ?>;">  <?php echo get_theme_mod('opening_hours'); ?></span></h3>
            <?php }

            if ( get_theme_mod( 'display_social' ) == '1' ) {?>
                <hr class="divider" style="border-color: <?php echo get_theme_mod( 'text_color' ); ?>;">
                <p class="badge-social"><a href="<?php echo get_theme_mod('social_1_href'); ?>"><i class="fa fa-<?php echo get_theme_mod('social_1'); ?>"></i></a><a href="<?php echo get_theme_mod('social_2_href'); ?>"><i class="fa fa-<?php echo get_theme_mod('social_2'); ?>"></i></a><a href="<?php echo get_theme_mod('social_3_href'); ?>"><i class="fa fa-<?php echo get_theme_mod('social_3'); ?>"></i></a></p>
            <?php } ?>
        </fieldset>
    </div><!-- .col-6 --> 

<?php } else { ?>

    <div class="col-md-6">
        <fieldset class="miga-contact" style="background-color:<?php echo get_theme_mod('primary_color');?>;">
            <i class="fa fa-envelope-square fa-2x" style="color: <?php echo get_theme_mod( 'text_color' ); ?>;"></i>
            <h3><a class="your-email" href="mailto:<?php echo get_theme_mod('email_href'); ?>"> <?php echo get_theme_mod('email'); ?></a></h3>
            <hr class="divider" style="border-color: <?php echo get_theme_mod( 'text_color' ); ?>;">
            <i class="fa fa-phone-square fa-2x" style="color: <?php echo get_theme_mod( 'text_color' ); ?>;"></i>
            <h3><span class="your-phone" style="color: <?php echo get_theme_mod( 'text_color' ); ?>;"> <?php echo get_theme_mod('phone'); ?></span></h3>
            <?php 
            $street = get_theme_mod('street');
            if ($street) { ?>
                <hr class="divider" style="border-color: <?php echo get_theme_mod( 'text_color' ); ?>;">
                <i class="fa fa-home fa-2x" style="color: <?php echo get_theme_mod( 'text_color' ); ?>;"></i>
                <h3><span class="your-street" style="color: <?php echo get_theme_mod( 'text_color' ); ?>;"> <?php echo $street; ?></span><span class="your-city" style="color: <?php echo get_theme_mod( 'text_color' ); ?>;"> <?php echo get_theme_mod('city'); ?></span></h3>
            <?php } 
            if ( get_theme_mod( 'opening_hours' )) {
            ?>
                <hr class="divider" style="border-color: <?php echo get_theme_mod( 'text_color' ); ?>;">
                <i class="fa fa-clock-o fa-2x" style="color: <?php echo get_theme_mod( 'text_color' ); ?>;"></i>
                <h3><span class="hours" style="color: <?php echo get_theme_mod( 'text_color' ); ?>;">  <?php echo get_theme_mod('opening_hours'); ?></span></h3>
            <?php }
            if ( get_theme_mod( 'display_social' ) == '1' ) {
            ?>
                <hr class="divider" style="border-color: <?php echo get_theme_mod( 'text_color' ); ?>;">
                <p class="badge-social"><a href="<?php echo get_theme_mod('social_1_href'); ?>"><i class="fa fa-<?php echo get_theme_mod('social_1'); ?>"></i></a><a href="<?php echo get_theme_mod('social_2_href'); ?>"><i class="fa fa-<?php echo get_theme_mod('social_2'); ?>"></i></a><a href="<?php echo get_theme_mod('social_3_href'); ?>"><i class="fa fa-<?php echo get_theme_mod('social_3'); ?>"></i></a></p>
            <?php } ?>
        </fieldset>
    </div><!-- .col-6 --> 

    <div class="col-md-6">
        <?php 
        if ( is_active_sidebar( 'miga_contact_form' ) ) : ?>
            <fieldset class="miga-contact" style="background-color:<?php echo get_theme_mod('primary_color');?>;">
                <?php dynamic_sidebar( 'miga_contact_form' ); ?>
            </fieldset>
        <?php endif; ?>
    </div><!-- .col-6 --> 

<?php } ?>

<div class="col-md-12 google-maps">
    <?php if( get_theme_mod( 'google_maps' )) { 
        echo get_theme_mod( 'google_maps' );
    }?>
</div>