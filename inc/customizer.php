<?php
/**
 * Miga Theme Customizer.
 *
 * @package Miga
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */

/***************************
* THEME CUSTOMIZER
****************************/

/* Include Kirki main plugin file in miga theme */

include_once( dirname( __FILE__ ) . '/kirki/kirki.php' );

/* Register Configuration */

Kirki::add_config( 'miga', array(
    'capability'    => 'edit_theme_options',
    'option_type'   => 'theme_mod',
) );

/* Configure Kirki to use the right path */
function miga_customizer_config() {
    $args = array(
    'url_path'     => get_stylesheet_directory_uri() . '/inc/kirki/',
    );
    return $args;
    }
add_filter( 'kirki/config', 'miga_customizer_config' );

/* Sanitizing the input of the customizer fields */
// select category dropdown and number of posts -> integer
function miga_sanitize_integer( $input ) {
    if( is_numeric( $input ) ) {
        return intval( $input );
    }
}

/**********************************
* CREATE CUSTOM CONTROL FOR TAXONOMY DROPDOWN
**********************************/

function register_miga_custom_control( $wp_customize ) {
    /**
     * The custom control class
     */
    class Kirki_Controls_Taxonomy_Control extends WP_Customize_Control {
        public $type = 'tax_dropdown';
        var $defaults = array();
        public $args = array();

        protected function render() { ?>
            <li class="customize-control customize-control-kirki-select">
                <?php $this->render_content(); ?>
            </li>
        <?php }

        public function render_content() {
            // Call wp_dropdown_cats to ad data-customize-setting-link to select tag
            add_action('wp_dropdown_cats', array($this, 'wp_dropdown_cats'));

            // Set some defaults for our control
            $this->defaults = array(
                'show_option_none' => __('None', 'miga'),
                'orderby' => 'name',
                'hide_empty' => 0,
                'id' => $this->id,
                'selected' => $this->value(),
            );

            // Parse defaults against what the user submitted
            $r = wp_parse_args($this->args, $this->defaults);

            ?>
            <label>
                <span class="customize-control-title">
                    <?php echo esc_html($this->label); ?>
                    <?php if ( ! empty( $this->description ) ) : ?>
                        <?php // The description has already been sanitized in the Fields class, no need to re-sanitize it. ?>
                    <span class="description customize-control-description">
                        <?php echo $this->description; ?>
                    </span>
                    <?php endif; ?>
                </span>
                <div class="selectize-control single">
                <?php
                    // Generate select box
                    wp_dropdown_categories($r);
                ?>
                </div>
            </label>

        <?php
        }

        function wp_dropdown_cats($output){
            // Search for <select and replace it with <select data-customize=setting-link="my_control_id"
            $output = str_replace('<select', '<select ' . $this->get_link(), $output);
            return $output;
        }
    }
    // Register our custom control with Kirki
    add_filter( 'kirki/control_types', function( $controls ) {
        $controls['tax_dropdown'] = 'Kirki_Controls_Taxonomy_Control';
        return $controls;
    } );

}
add_action( 'customize_register', 'register_miga_custom_control' );

/**********************************
* ADDING PANELS, SECTIONS AND FIELDS
**********************************/

function miga_customize_register( $wp_customize ) {

    /**********************************
    * SITE SETTINGS
    **********************************/

	/* ADDING PANEL GENERAL SETTINGS */
	Kirki::add_panel( 'site-settings', array(
	    'priority'    => 10,
	    'title'       => __( 'General Site Settings', 'miga' ),
	    'description' => __( 'Select general settings for your website', 'miga' ),
	) );

    Kirki::add_field( 'miga', array(
        'type'        => 'image',
        'settings'    => 'site_logo',
        'label'       => __( 'Site Logo', 'miga' ),
        'description' => __('Upload a logo to your website.', 'miga'),
        'section'     => 'title_tagline',
        'priority'    => 10,
        'transport'   => 'postMessage',
    ) );

    Kirki::add_section( 'site-layout', array(
        'title'          => __( 'Site Layout', 'miga' ),
        'priority'       => 10,
        'capability'     => 'edit_theme_options',
        'panel'          => 'site-settings',
    ) );

    Kirki::add_field( 'miga', array(
        'type'        => 'radio-image',
        'setting'     => 'sidebar_layout',
        'label'       => __( 'With or without Sidebar', 'miga' ),
        'description' => __( 'Choose the layout for the content area on your site. It is not affecting the header nor the footer.', 'miga' ),
        'section'     => 'site-layout',
        'default'     => 'fullwidth',
        'priority'    => 10,
        'choices'     => array(
          'sidebar-left' => trailingslashit( get_template_directory_uri() ) . 'inc/kirki/assets/images/2cl.png',
          'fullwidth' => trailingslashit( get_template_directory_uri() ) . 'inc/kirki/assets/images/1c.png',
          'sidebar-right' => trailingslashit( get_template_directory_uri() ) . 'inc/kirki/assets/images/2cr.png',
        ),
    ) );

    Kirki::add_field( 'miga', array(
        'type'        => 'radio-image',
        'setting'     => 'box_layout',
        'label'       => __( 'Box or Fullwidth Layout', 'miga' ),
        'description' => __( 'Choose the layout for the content area on your site. It is not affecting the header nor the footer.', 'miga' ),
        'section'     => 'site-layout',
        'default'     => 'box-layout',
        'priority'    => 20,
        'choices'     => array(
          'box-layout' => trailingslashit( get_template_directory_uri() ) . 'inc/kirki/assets/images/box.png',
          'fullwidth-layout' => trailingslashit( get_template_directory_uri() ) . 'inc/kirki/assets/images/full.png',
        ),
    ) );

    Kirki::add_field( 'miga', array(
        'type'        => 'dimension',
        'settings'    => 'fullwidth_padding',
        'label'       => __( 'Section Padding', 'miga' ),
        'description' => __( 'In case you want your content to be wider than the box-layout but not to have the full width you can enter here the space you want to have between your content and the browser window. Entered values can only be valid CSS (exp. 10px, 3em, 48%, 90vh etc.).', 'miga' ),
        'section'     => 'site-layout',
        'default'     => '1.5em',
        'priority'    => 30,
        'transport' => 'postMessage',
        'js_vars'   => array(
            array(
                'element'  => '.container',
                'function' => 'css',
                'property' => 'padding-right, padding-left',
            ),
        ),
        'active_callback'    => array(
            array(
                'setting'  => 'box_layout',
                'operator' => '==',
                'value'    => 'fullwidth-layout',
            ),
        ),
    ) );

    /* ADDING FONTS SECTION */
    Kirki::add_section( 'fonts', array(
        'title'          => __( 'Fonts', 'miga' ),
        'priority'       => 60,
        'capability'     => 'edit_theme_options',
        'panel'          => 'site-settings',
    ) );

    /* ADDING COLORS SETTINGS TO THE COLORS SECTION */
    // PRIMARY COLOR
    Kirki::add_field( 'miga', array(
        'type'        => 'color-alpha',
        'settings'    => 'primary_color',
        'label'       => __( 'Primary Color', 'miga' ),
        'description' => __('The primary color will affect the background color for the cards, single posts, widgets, navbar etc.', 'miga'),
        'section'     => 'colors',
        'default'     => 'rgba(11,182,255,.8)',
        'priority'    => 20,
        'output' => array(
                'element'  => '.widget, .post-single-inner, .card, .top-nav-collapse, .timeline-content',
                'property' => 'background-color',
            ),
        'transport' => 'postMessage',
        'js_vars'   => array(
            array(
                'element'  => '.widget, .post-single-inner, .card, .top-nav-collapse, .card-back, .card-front h3',
                'function' => 'css',
                'property' => 'background-color',
            ),
        ),
    ) );

    // CALL TO ACTION COLOR
    Kirki::add_field( 'miga', array(
        'type'        => 'color',
        'settings'    => 'cta_color',
        'label'       => __( 'Call to Action Color', 'miga' ),
        'description' => __( 'Choose the color for your call to action elements like buttons, input fields, links etc.', 'miga' ),
        'section'     => 'colors',
        'default'     => '#F48C10', //orange
        'priority'    => 30,
        'transport' => 'postMessage',
        'js_vars'   => array(
            array(
                'element'  => '.btn',
                'function' => 'css',
                'property' => 'color',
            ),
            array(
                'element'  => '.btn, #filter button',
                'function' => 'css',
                'property' => 'border-color',
            ),
            array(
                'element'  => '',
                'function' => 'css',
                'property' => 'background-color',
            ),
        ),
    ) );

    // ADDING CONTACT INFORMATION SECTION
    Kirki::add_section( 'miga_contact', array(
        'title'          => __( 'Contact Information', 'miga' ),
        'priority'       => 160,
        'capability'     => 'edit_theme_options',
        'panel'          => 'site-settings',
    ) );

    Kirki::add_field( 'miga', array(
        'type'        => 'text',
        'settings'    => 'email',
        'label'       => __( 'Your Email without @-sign', 'miga' ),
        'description' => __('To avoid spam enter your email without @-sign.', 'miga'),
        'default'     => 'info(at)yourmail.com',
        'section'     => 'miga_contact',
        'priority'    => 10,
        'transport'   => 'postMessage',
        'js_vars'     => array(
            array(
                'element'   => '.your-email',
                'function'  => 'html',
            ),
        ),
    ) );

    Kirki::add_field( 'miga', array(
        'type'        => 'text',
        'settings'    => 'email_href',
        'label'       => __( 'Your Email with @-sign', 'miga' ),
        'description' => __('Here you can enter your email with the @-sign because this email will not be displayed.', 'miga'),
        'default'     => 'info@yourmail.com',
        'section'     => 'miga_contact',
        'sanitize_callback' => 'is_email',
        'priority'    => 20,
    ) );

    Kirki::add_field( 'miga', array(
        'type'        => 'text',
        'settings'    => 'phone',
        'label'       => __( 'Your Phone', 'miga' ),
        'section'     => 'miga_contact',
        'priority'    => 30,
        'transport'   => 'postMessage',
        'js_vars'     => array(
            array(
                'element'   => '.your-phone',
                'function'  => 'html',
            ),
        ),
    ) );

    Kirki::add_field( 'miga', array(
        'type'        => 'text',
        'settings'    => 'street',
        'label'       => __( 'Your Street', 'miga' ),
        'section'     => 'miga_contact',
        'priority'    => 40,
        'transport'   => 'postMessage',
        'js_vars'     => array(
            array(
                'element'   => '.your-street',
                'function'  => 'html',
            ),
        ),
    ) );

    Kirki::add_field( 'miga', array(
        'type'        => 'text',
        'settings'    => 'city',
        'label'       => __( 'Your City', 'miga' ),
        'section'     => 'miga_contact',
        'priority'    => 50,
        'transport'   => 'postMessage',
        'js_vars'     => array(
            array(
                'element'   => '.your-city',
                'function'  => 'html',
                ),
        ),
    ) );

    Kirki::add_field( 'miga', array(
        'type'        => 'text',
        'settings'    => 'opening_hours',
        'label'       => __( 'Opening Hours', 'miga' ),
        'section'     => 'miga_contact',
        'priority'    => 60,
        'transport'   => 'postMessage',
        'js_vars'     => array(
            array(
                'element'   => '.hours',
                'function'  => 'html',
                ),
        ),
    ) );

    // It seems that the kirki text field doesn't allow any iframes -> probably because of sanitization of the text field
    $wp_customize->add_setting( 'google_maps', array(
        'type' => 'theme_mod'
    ));

    $wp_customize->add_control( 'google_maps', array(
        'type'        => 'text',
        'label'       => __( 'Google Maps', 'miga' ),
        'description' => __( 'Go to Google Maps and enter your address. In the menu/share and embed copy the code inside the iframe-tag. Paste the code in the field below. To make it responsive change the width inside the iframe to 100%.', 'miga' ),
        'default'     => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2682.052290504649!2d11.557488115922531!3d47.76104008528907!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x479d944ec5e1b793%3A0x9f124d5f4002d6f!2sMarktstra%C3%9Fe%2C+83646+Bad+T%C3%B6lz!5e0!3m2!1sde!2sde!4v1447177611630" width="100%" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>',
        'section'     => 'miga_contact',
        'sanitize_callback' => 'wp_oembed_get', // ???
        'priority'    => 70,
        'transport'   => 'postMessage',
    ));

    Kirki::add_field( 'miga', array(
        'type'        => 'toggle',
        'setting'     => 'display_social',
        'label'       => __( 'Display Social Icons?', 'miga' ),
        'description' => __( 'Do you want to display Social Icons in the Contact Section?', 'miga' ),
        'section'     => 'miga_contact',
        'default'     => 0,
        'priority'    => 80,
    ) );

    Kirki::add_field( 'miga', array(
        'type'        => 'text',
        'settings'    => 'social_1',
        'label'       => __( 'Name for 1. Social Icon', 'miga' ),
        'description' => __( 'Enter the name (uncapitalized) of the Social Network in the field below.', 'miga' ),
        'input_attrs' => array(
            'style' => 'border: 1px solid #900',
            'placeholder' => 'e.g. facebook',
        ),
        'section'     => 'miga_contact',
        'priority'    => 90,
        'transport'   => 'postMessage',
        'active_callback'    => array(
            array(
                'setting'  => 'display_social',
                'operator' => '==',
                'value'    => 1,
            ),
        ),
    ) );

    Kirki::add_field( 'miga', array(
        'type'        => 'text',
        'settings'    => 'social_1_href',
        'label'       => __( 'Link for 1. Social Icon', 'miga' ),
        'default'     => 'https://facebook.com/yourprofile',
        'section'     => 'miga_contact',
        'sanitize_callback' => 'esc_url_raw',
        'priority'    => 100,
        'transport'   => 'postMessage',
        'active_callback'    => array(
            array(
                'setting'  => 'display_social',
                'operator' => '==',
                'value'    => 1,
            ),
        ),
    ) );

    Kirki::add_field( 'miga', array(
        'type'        => 'text',
        'settings'    => 'social_2',
        'label'       => __( 'Name for 2. Social Icon', 'miga' ),
        'description' => __( 'Enter the name (uncapitalized) of the Social Network in the field below.', 'miga' ),
        'default'     => 'instagram',
        'section'     => 'miga_contact',
        'priority'    => 110,
        'transport'   => 'postMessage',
        'active_callback'    => array(
            array(
                'setting'  => 'display_social',
                'operator' => '==',
                'value'    => 1,
            ),
        ),
    ) );

    Kirki::add_field( 'miga', array(
        'type'        => 'text',
        'settings'    => 'social_2_href',
        'label'       => __( 'Link for 2. Social Icon', 'miga' ),
        'default'     => 'https://instagram.com/yourprofile',
        'section'     => 'miga_contact',
        'sanitize_callback' => 'esc_url_raw',
        'priority'    => 120,
        'transport'   => 'postMessage',
        'active_callback'    => array(
            array(
                'setting'  => 'display_social',
                'operator' => '==',
                'value'    => 1,
            ),
        ),
    ) );

    Kirki::add_field( 'miga', array(
        'type'        => 'text',
        'settings'    => 'social_3',
        'label'       => __( 'Name for 3. Social Icon', 'miga' ),
        'description' => __( 'Enter the name (uncapitalized) of the Social Network in the field below.', 'miga' ),
        'default'     => 'twitter',
        'section'     => 'miga_contact',
        'priority'    => 130,
        'transport'   => 'postMessage',
        'active_callback'    => array(
            array(
                'setting'  => 'display_social',
                'operator' => '==',
                'value'    => 1,
            ),
        ),
    ) );

    Kirki::add_field( 'miga', array(
        'type'        => 'text',
        'settings'    => 'social_3_href',
        'label'       => __( 'Link for 3. Social Icon', 'miga' ),
        'default'     => 'https://twitter.com/yourprofile',
        'section'     => 'miga_contact',
        'sanitize_callback' => 'esc_url_raw',
        'priority'    => 140,
        'transport'   => 'postMessage',
        'active_callback'    => array(
            array(
                'setting'  => 'display_social',
                'operator' => '==',
                'value'    => 1,
            ),
        ),
    ) );

    Kirki::add_field( 'miga', array(
        'type'        => 'toggle',
        'setting'     => 'fullwidth-contact',
        'label'       => __( 'Display contact section in fullwidth', 'miga' ),
        'section'     => 'miga_contact',
        'default'     => 0,
        'priority'    => 150,
    ) );

    /* Adding Custom CSS */
    Kirki::add_section( 'custom_css', array(
        'title'          => __( 'Custom CSS', 'miga' ),
        'priority'       => 200,
        'capability'     => 'edit_theme_options',
        'panel'          => 'site-settings',
    ) );

    Kirki::add_field( 'miga', array(
        'type'        => 'textarea',
        'setting'     => 'custom_theme_css',
        'label'       => __( 'Custom CSS', 'miga' ),
        'description' => __( 'Enter custom CSS to further customize your site', 'miga' ),
        'section'     => 'custom_css',
        'sanitize_callback' => 'esc_textarea',
    ) );

    /**********************************
    * MENU
    **********************************/

    Kirki::add_panel( 'menu', array(
        'priority'    => 20,
        'title'       => __( 'Menu Settings', 'miga' ),
        'description' => __( 'Select the menu settings for your website', 'miga' ),
    ) );

    Kirki::add_section( 'menu-style', array(
        'title'          => __( 'Menu Style', 'miga' ),
        'priority'       => 10,
        'capability'     => 'edit_theme_options',
        'panel'          => 'menu',
    ) );

     Kirki::add_field( 'miga', array(
        'type'        => 'select',
        'settings'    => 'menu_style',
        'label'       => __( 'Select the style of your menu', 'miga' ),
        'section'     => 'menu-style',
        'default'     => 'option-1',
        'priority'    => 10,
        'choices'     => array(
            'static' => __( 'Static with dropdown', 'miga' ),
            'fullpage' => __( 'Fullpage with overlay', 'miga' ),
        ),
    ) );

    Kirki::add_field( 'miga', array(
        'type'        => 'number',
        'settings'    => 'menu_links',
        'label'       => __( 'How many first level links does your primary menu have?', 'miga' ),
        'section'     => 'menu-style',
        'sanitize_callback' => 'miga_sanitize_integer',
        'default'     => 5,
        'priority'    => 20,
        'choices'     => array(
      		'min'  => 1,
      		'max'  => 10,
      		'step' => 1,
      	),
    ) );

    Kirki::add_field( 'miga', array(
        'type'        => 'number',
        'settings'    => 'menu_links_sub',
        'label'       => __( 'How many first level links does your secondary menu have?', 'miga' ),
        'section'     => 'menu-style',
        'sanitize_callback' => 'miga_sanitize_integer',
        'default'     => 3,
        'priority'    => 30,
        'choices'     => array(
      		'min'  => 1,
      		'max'  => 10,
      		'step' => 1,
      	),
    ) );

    /**********************************
    * HEADER
    **********************************/

    Kirki::add_panel( 'header', array(
        'priority'    => 110,
        'title'       => __( 'Header Settings', 'miga' ),
        'description' => __( 'Select the header settings for your website', 'miga' ),
    ) );

    Kirki::add_section( 'header-layout', array(
        'title'          => __( 'Header Layout', 'miga' ),
        'priority'       => 10,
        'capability'     => 'edit_theme_options',
        'panel'          => 'header',
    ) );

    Kirki::add_field( 'miga', array(
        'type'        => 'radio-image',
        'setting'     => 'header_layout',
        'label'       => __( 'Header Layout Settings', 'miga' ),
        'description' => __( 'Choose the layout for the header on your site.', 'miga' ),
        'section'     => 'header-layout',
        'default'     => 'jumbo',
        'priority'    => 10,
        'choices'     => array(
          'jumbo' => trailingslashit( get_template_directory_uri() ) . 'inc/kirki/assets/images/jumbo.png',
          'fullpage' => trailingslashit( get_template_directory_uri() ) . 'inc/kirki/assets/images/fullpage.png',
        ),
    ) );

    Kirki::add_section( 'header-content', array(
        'title'          => __( 'Header Content', 'miga' ),
        'priority'       => 20,
        'capability'     => 'edit_theme_options',
        'panel'          => 'header',
    ) );

    Kirki::add_field( 'miga', array(
        'type'        => 'image',
        'settings'    => 'header_bg_img',
        'label'       => __( 'Header Background Image', 'miga' ),
        'description' => __('Upload an image for the header.', 'miga'),
        'section'     => 'header-content',
        'sanitize_callback' => 'esc_url_raw',
        'priority'    => 10,
        'output' => array(
            array(
                'element'  => '.jumbotron',
                'property' => 'background',
            ),
        ),
        'transport'   => 'postMessage',
        'js_vars'   => array(
            array(
                'element'  => '.intro, .jumbotron',
                'function' => 'css',
                'property' => 'background',
            ),
        ),
    ) );

    // BACKGROUND COLOR OF THE CONTENT BACKGROUND
    Kirki::add_field( 'miga', array(
        'type'        => 'color-alpha',
        'settings'    => 'header_bg_color',
        'label'       => __( 'Header Background Color', 'miga' ),
        'description' => __('Choose the background color for the header.', 'miga'),
        'section'     => 'header-content',
        'default'     => 'rgba(0,0,0,0.5)',
        'priority'    => 20,
        'output' => array(
            array(
                'element'  => '.header-bg-color',
                'property' => 'background-color',
            ),
        ),
        'transport' => 'postMessage',
        'js_vars'   => array(
            array(
                'element'  => '.header-bg-color',
                'function' => 'css',
                'property' => 'background-color',
            ),
        ),
    ) );

    Kirki::add_field( '', array(
        'type'        => 'switch',
        'settings'    => 'parallax',
        'label'       => __( 'Select parallax effect for the background', 'miga' ),
        'section'     => 'header-content',
        'default'     => '1',
        'priority'    => 30,
        'choices'     => array(
            'on'  => __( 'On', 'miga' ),
            'off' => __( 'Off', 'miga' ),
        ),
    ) );

    Kirki::add_field( 'miga', array(
        'type'        => 'image',
        'settings'    => 'header_img',
        'label'       => __( 'Header Image', 'miga' ),
        'description' => __('Upload an image or a logo if you want to replace the headline of the header.', 'miga'),
        'section'     => 'header-content',
        'sanitize_callback' => 'esc_url_raw',
        'priority'    => 30,
        'transport'   => 'postMessage',
        'js_vars'     => array(
            array(
                'element'   => '.jumbotron-heading, .brand-heading',
                'function'  => 'html',
                ),
        ),
    ) );

    Kirki::add_field( 'miga', array(
        'type'        => 'text',
        'settings'    => 'headline_header',
        'label'       => __( 'Headline of the header', 'miga' ),
        'section'     => 'header-content',
        'priority'    => 40,
        'transport'   => 'postMessage',
        'js_vars'     => array(
            array(
                'element'   => '.jumbotron-heading, .brand-heading',
                'function'  => 'html',
                ),
        ),
    ) );

    Kirki::add_field( 'miga', array(
        'type'        => 'text',
        'settings'    => 'header_subtitle',
        'label'       => __( 'Subtitle for the header', 'miga' ),
        'section'     => 'header-content',
        'priority'    => 50,
        'transport'   => 'postMessage',
        'js_vars'     => array(
            array(
                'element'   => '.lead, .intro-text',
                'function'  => 'html',
                ),
        ),
    ) );

    Kirki::add_field( 'miga', array(
        'type'        => 'text',
        'settings'    => 'first_btn_text',
        'label'       => __( 'Text for the first button', 'miga' ),
        'description' => __('If empty button will not be displayed.', 'miga'),
        'section'     => 'header-content',
        'priority'    => 60,
        'transport'   => 'postMessage',
        'js_vars'     => array(
            array(
                'element'   => '.jumbotron .btn',
                'function'  => 'html',
            ),
        ),
    ) );

     Kirki::add_field( 'miga', array(
        'type'        => 'text',
        'settings'    => 'first_btn_link',
        'label'       => __( 'Link for the first button', 'miga' ),
        'section'     => 'header-content',
        'priority'    => 70,
        'transport'   => 'postMessage',
        'js_vars'     => array(
            array(
                'element'   => '.jumbotron .btn',
                'function'  => 'html',
            ),
        ),
    ) );

    Kirki::add_field( 'miga', array(
        'type'        => 'text',
        'settings'    => 'second_btn_text',
        'label'       => __( 'Text for the second button', 'miga' ),
        'description' => __('If empty button will not be displayed.', 'miga'),
        'section'     => 'header-content',
        'priority'    => 80,
        'transport'   => 'postMessage',
        'js_vars'     => array(
            array(
                'element'   => '.jumbotron .btn',
                'function'  => 'html',
            ),
        ),
    ) );

     Kirki::add_field( 'miga', array(
        'type'        => 'text',
        'settings'    => 'second_btn_link',
        'label'       => __( 'Link for the second button', 'miga' ),
        'section'     => 'header-content',
        'priority'    => 90,
        'transport'   => 'postMessage',
        'js_vars'     => array(
            array(
                'element'   => '.jumbotron .btn',
                'function'  => 'html',
            ),
        ),
    ) );

	/**********************************
    * HOME PAGE
    **********************************/
	Kirki::add_panel( 'home-page', array(
	    'priority'    => 120,
	    'title'       => __( 'Front Page', 'miga' ),
	    'description' => __( 'Customize the sections of your home page', 'miga' ),
	) );

    Kirki::add_section( 'general', array(
        'title'          => __( 'General', 'miga' ),
        'priority'       => 10,
        'capability'     => 'edit_theme_options',
        'panel'          => 'home-page',
    ) );

	/* Loop through the content post type sections and adding them to the home-page panel */
    $sections_args = array(
        'tax_query' => array(
            array(
                'taxonomy' => 'display-locations',
                'field' => 'slug',
                'terms' => 'home'
            )
        ),
        'post_type' => 'sections',
        'post_status' => 'publish',
        'posts_per_page' => 3, // if 'post_per_page' is used 'nopaging' has to be set to default which is false
        //'nopaging' => true, // without 'nopaging' section will not be displayed in the customizer
        'order' => 'ASC',
        'orderby' => 'ID'
    );

    // Using WP_Query and the_title function didn't do the job ->
    // therefore and also for better performance get_posts was used instead
    $sections = get_posts($sections_args);
    // set the default value to the same value as the primary color
    $bg_cards_default = get_theme_mod( 'primary_color' );
    // set the default value to the same value as the primary color
    $headline_default = get_theme_mod( 'headline_color' );
    // set the default value to the same value as the primary color
    $post_title_default = get_theme_mod( 'post_title_color' );
    // set the default value to the same value as the primary color
    $text_color_default = get_theme_mod( 'text_color' );

    if ( $sections ) {
        $section_nr = 0;
        $section_prio = 10;
        foreach ( $sections as $section) {
            $section_title = $section->post_name;

            // ADD SECTION FOR MIGA SECTION
            Kirki::add_section( $section_title, array(
                'title'          => $section_title,
                'priority'       => $section_prio,
                'capability'     => 'edit_theme_options',
                'panel'          => 'home-page',
            ) );

            // BACKGROUND COLOR OF THE SECTION
            Kirki::add_field( 'miga', array(
                'type'        => 'color-alpha',
                'settings'    => 'bg_'.$section_nr,
                'label'       => __( 'Section Background Color', 'miga' ),
                'description' => __('Choose the background color for this section.', 'miga'),
                'section'     => $section_title,
                'default'     => 'rgba(0,0,0,0.1)',
                'priority'    => 110,
                'output' => array(
                    array(
                        'element'  => '#section-bg-'.$section_nr,
                        'property' => 'background-color',
                    ),
                ),
                'transport' => 'postMessage',
                'js_vars'   => array(
                    array(
                        'element'  => '#section-bg-'.$section_nr,
                        'function' => 'css',
                        'property' => 'background-color',
                        'suffix'   => ' !important',
                    ),
                ),
            ) );

            // BACKGROUND IMAGE OF THE SECTION -> no Live Update !?
            Kirki::add_field( 'miga', array(
                'type'        => 'image',
                'settings'    => 'bg_img_'.$section_nr,
                'label'      => __( 'Section Background Image', 'miga' ),
                'description' => __('Upload an image for this section if you want to change the default background image.', 'miga'),
                'section'     => $section_title,
                'priority'    => 10,
                'output' => array(
                    array(
                        'element'  => '#section-'.$section_nr,
                        'property' => 'background',
                    ),
                ),
                'transport'   => 'postMessage',
                'js_vars'   => array(
                    array(
                        'element'  => '#section-'.$section_nr,
                        'function' => 'css',
                        'property' => 'background',
                        'suffix'   => ' !important',
                    ),
                ),
            ) );

            Kirki::add_field( 'miga', array(
                'type'        => 'toggle',
                'settings'    => 'parallax_'.$section_nr,
                'label'       => __( 'Select parallax effect for the background', 'miga' ),
                'section'     => $section_title,
                'default'     => 0,
                'priority'    => 20,
            ) );

            // DISPLAY SECTIONS TITLE
            Kirki::add_field( 'miga', array(
                'type'        => 'toggle',
                'setting'     => 'title_'.$section_nr,
                'label'       => __( 'Display Sections Title', 'miga' ),
                'description' => __( 'Select if you want to display the sections title.', 'miga' ),
                'section'     => $section_title,
                'default'     => 0,
                'priority'    => 30,
            ) );

            // SELECT SECTIONS CONTENT
            Kirki::add_field( 'miga', array(
                'type'        => 'select',
                'settings'    => 'content_type_'.$section_nr,
                'label'       => __( 'Select Content Type', 'miga' ),
                'description' => __( 'Select the content you want to display in this section', 'miga' ),
                'section'     => $section_title,
                'default'     => 'option-1',
                'priority'    => 50,
                'choices'     => array(
                    'option-1' => __( 'Recent Posts', 'miga' ),
                    'option-2' => __( 'Random Posts', 'miga' ),
                    'option-3' => __( 'Posts by Category', 'miga' ),
                    'option-4' => __( 'Popular Posts (comments)', 'miga' ),
                    'option-5' => __( 'Newsletter', 'miga' ),
                    'option-6' => __( 'Contact', 'miga' ),
                    'option-7' => __( 'Text/Image', 'miga' ),
                ),
            ) );

            Kirki::add_field( 'miga', array(
                'type'        => 'text',
                'settings'    => 'newsletter_text'.$section_nr,
                'label'       => __( 'Enter the text for the newsletter', 'miga' ),
                'default'     => __( 'Sign up for more news', 'miga' ),
                'section'     => $section_title,
                'priority'    => 60,
                'transport'   => 'postMessage',
                'js_vars'     => array(
                    array(
                        'element'   => '.newsletter-headline',
                        'function'  => 'html',
                    ),
                ),
                'active_callback'    => array(
                    array(
                        'setting'  => 'content_type_'.$section_nr,
                        'operator' => '==',
                        'value'    => 'option-5',
                    ),
                ),
            ) );

            // SELECT POSTS CATEGORY
            Kirki::add_field( 'miga', array(
                'type'        => 'tax_dropdown',
                'settings'    => 'cat_'.$section_nr,
                'label'       => __( 'Select Posts Category', 'miga' ),
                'description' => __( 'Select the post category you want to display in this section', 'miga' ),
                'section'     => $section_title,
                'sanitize_callback' => 'miga_sanitize_integer',
                'default'     => get_option('default_category', ''),
                'priority'    => 60,
                'args' => array(
                    'taxonomy'  => 'category',
                    'post_type' => 'posts',
                ),
                'active_callback'    => array(
                    array(
                        'setting'  => 'content_type_'.$section_nr,
                        'operator' => '==',
                        'value'    => 'option-3',
                    ),
                ),
            ) );

            Kirki::add_field( 'miga', array(
                'type'        => 'select',
                'settings'    => 'img_position_'.$section_nr,
                'label'       => __( 'Select the position of the image', 'miga' ),
                'section'     => $section_title,
                'default'     => 'option-2',
                'priority'    => 60,
                'choices'     => array(
                    'img_left' => __( 'Image left', 'miga' ),
                    'img_right' => __( 'Image right', 'miga' ),
                ),
                'active_callback'    => array(
                    array(
                        'setting'  => 'content_type_'.$section_nr,
                        'operator' => '==',
                        'value'    => 'option-7',
                    ),
                ),
            ) );

            // BACKGROUND IMAGE OF THE SECTION -> no Live Update !?
            Kirki::add_field( 'miga', array(
                'type'        => 'image',
                'settings'    => 'img_'.$section_nr,
                'label'      => __( 'Image for this section', 'miga' ),
                'section'     => $section_title,
                'priority'    => 70,
                'transport'   => 'postMessage',
                'js_vars'   => array(
                    array(
                        'element'  => '#section-'.$section_nr.' img',
                        'function' => 'html',
                    ),
                ),
                'active_callback'    => array(
                    array(
                        'setting'  => 'content_type_'.$section_nr,
                        'operator' => '==',
                        'value'    => 'option-7',
                    ),
                ),
            ) );

            Kirki::add_field( 'miga', array(
                'type'        => 'text',
                'setting'     => 'subtitle_'.$section_nr,
                'label'       => __( 'Subtitle for this section', 'miga' ),
                'section'     => $section_title,
                'priority'    => 80,
                'transport'   => 'postMessage',
                'js_vars'     => array(
                    array(
                        'element'   => '#section-'.$section_nr.' h3',
                        'function'  => 'html',
                    ),
                ),
                'active_callback'    => array(
                    array(
                        'setting'  => 'content_type_'.$section_nr,
                        'operator' => '==',
                        'value'    => 'option-7',
                    ),
                ),
            ) );

            Kirki::add_field( 'miga', array(
                'type'        => 'textarea',
                'setting'     => 'textarea_'.$section_nr,
                'label'       => __( 'Text for this section', 'miga' ),
                'section'     => $section_title,
                'priority'    => 90,
                'transport'   => 'postMessage',
                'js_vars'     => array(
                    array(
                        'element'   => '#section-'.$section_nr.' p',
                        'function'  => 'html',
                    ),
                ),
                'active_callback'    => array(
                    array(
                        'setting'  => 'content_type_'.$section_nr,
                        'operator' => '==',
                        'value'    => 'option-7',
                    ),
                ),
            ) );

            // NUMBER OF POSTS
            Kirki::add_field( 'miga', array(
                'type'        => 'number',
                'settings'    => 'nr_'.$section_nr,
                'label'       => __( 'Number of posts', 'miga' ),
                'description' => __( 'Select the number of posts you want to display in this section', 'miga' ),
                'section'     =>  $section_title,
                'sanitize_callback' => 'miga_sanitize_integer',
                'default'     => 1,
                'priority'    => 100,
                'active_callback'    => array(
                    array(
                        'setting'  => 'content_type_'.$section_nr,
                        'operator' => '!=',
                        'value'    => 'option-5',
                    ),
                    array(
                        'setting'  => 'content_type_'.$section_nr,
                        'operator' => '!=',
                        'value'    => 'option-6',
                    ),
                    array(
                        'setting'  => 'content_type_'.$section_nr,
                        'operator' => '!=',
                        'value'    => 'option-7',
                    ),
                ),
            ) );

            // DISPLAY OPTION OF SECTIONS CONTENT
            Kirki::add_field( 'miga', array(
                'type'        => 'select',
                'settings'    => 'display_'.$section_nr,
                'label'       => __( 'Select Display Option', 'miga' ),
                'description' => __( 'Select the display option for this sections content', 'miga' ),
                'section'     => $section_title,
                'default'     => 'option-1',
                'priority'    => 110,
                'choices'     => array(
                    'option-1' => '',
                    'option-2' => __( 'Flipcards', 'miga' ),
                    'option-3' => __( 'Cards Decks', 'miga' ),
                    'option-4' => __( 'Cards Overlay', 'miga' ),
                    'option-5' => __( 'Mansory', 'miga' ),
                    'option-6' => __( 'Timeline', 'miga' ),
                    'option-7' => __( 'Filtered Gallery', 'miga' ),
                    'option-8' => __( 'Tabs', 'miga' ),
                    'option-9' => __( 'Carousel', 'miga' ),
                ),
                'active_callback'    => array(
                     array(
                        'setting'  => 'content_type_'.$section_nr,
                        'operator' => '!=',
                        'value'    => 'option-5',
                    ),
                    array(
                        'setting'  => 'content_type_'.$section_nr,
                        'operator' => '!=',
                        'value'    => 'option-6',
                    ),
                    array(
                        'setting'  => 'content_type_'.$section_nr,
                        'operator' => '!=',
                        'value'    => 'option-7',
                    ),
                 ),
            ) );

            Kirki::add_field( 'miga', array(
                'type'        => 'select',
                'settings'    => 'tabs_position_'.$section_nr,
                'label'       => __( 'Select the position of the tabs navigation', 'miga' ),
                'section'     => $section_title,
                'priority'    => 120,
                'choices'     => array(
                    'tabs_left' => __( 'Tabs left', 'miga' ),
                    'tabs_right' => __( 'Tabs right', 'miga' ),
                ),
                'active_callback'    => array(
                    array(
                        'setting'  => 'display_'.$section_nr,
                        'operator' => '==',
                        'value'    => 'option-8',
                    ),
                ),
            ) );

            // BACKGROUND COLOR OF THE CONTENT
            Kirki::add_field( 'miga', array(
                'type'        => 'color-alpha',
                'settings'    => 'bg_cards_'.$section_nr,
                'label'       => __( 'Cards Background Color', 'miga' ),
                'description' => __('Choose the background color for the cards', 'miga'),
                'section'     => $section_title,
                'default'     => $bg_cards_default,
                'priority'    => 130,
                'transport' => 'postMessage',
                'js_vars'   => array(
                    array(
                        'element'  => '#section-'.$section_nr.' .cards-bg',
                        'function' => 'css',
                        'property' => 'background-color',
                        'suffix'   => ' !important',
                    ),
                ),
                'active_callback'    => array(
                    array(
                        'setting'  => 'display_'.$section_nr,
                        'operator' => '!=',
                        'value'    => 'option-1',
                    ),
                    array(
                        'setting'  => 'display_'.$section_nr,
                        'operator' => '!=',
                        'value'    => 'option-6',
                    ),
                    array(
                        'setting'  => 'display_'.$section_nr,
                        'operator' => '!=',
                        'value'    => 'option-9',
                    ),
                    array(
                        'setting'  => 'display_'.$section_nr,
                        'operator' => '!=',
                        'value'    => 'option-12',
                    ),
                    array(
                        'setting'  => 'content_type_'.$section_nr,
                        'operator' => '!=',
                        'value'    => 'option-5',
                    ),
                ),
            ) );

            // COLOR OF THE SECTION TITLE
            Kirki::add_field( 'miga', array(
                'type'        => 'color',
                'settings'    => 'headline_color_'.$section_nr,
                'label'       => __( 'Section Title Color', 'miga' ),
                'description' => __('Choose the title color for this section.', 'miga'),
                'section'     => $section_title,
                'default'     => $headline_default,
                'priority'    => 140,
                'transport'   => 'postMessage',
                'js_vars'     => array(
                    array(
                        'element'  => '#section-'.$section_nr.' h2',
                        'function' => 'css',
                        'property' => 'color',
                        'suffix'   => ' !important',
                    ),
                    array(
                        'element'  => '#section-'.$section_nr.' hr',
                        'function' => 'css',
                        'property' => 'border-color',
                        'suffix'   => ' !important',
                    ),
                ),
            ) );

            // COLOR OF THE POST TITLE
            Kirki::add_field( 'miga', array(
                'type'        => 'color',
                'settings'    => 'post_title_color_'.$section_nr,
                'label'       => __( 'Post Title Color', 'miga' ),
                'description' => __('Choose the post title color for this section.', 'miga'),
                'section'     => $section_title,
                'default'     => $post_title_default,
                'priority'    => 150,
                'transport'   => 'postMessage',
                'js_vars'     => array(
                    array(
                        'element'  => '#section-'.$section_nr.' h3, .header-overlay',
                        'function' => 'css',
                        'property' => 'color',
                        'suffix'   => ' !important',
                    ),
                ),
            ) );

            // COLOR OF THE SECTION TEXT
            Kirki::add_field( 'miga', array(
                'type'        => 'color',
                'settings'    => 'text_color_'.$section_nr,
                'label'       => __( 'Section Text Color', 'miga' ),
                'description' => __('Choose the text color for this section.', 'miga'),
                'section'     => $section_title,
                'default'     => $text_color_default,
                'priority'    => 160,
                'transport' => 'postMessage',
                'js_vars'   => array(
                    array(
                        'element'  => '#section-'.$section_nr.' p, .text-overlay, .read-more:hover',
                        'function' => 'css',
                        'property' => 'color',
                        'suffix'   => ' !important',
                    ),
                ),
            ) );

            // DISPLAY EXCERPT OR THE WHOLE CONTENT
            Kirki::add_field( 'miga', array(
                'type'        => 'toggle',
                'setting'     => 'excerpt_'.$section_nr,
                'label'       => __( 'Display excerpt or the whole content of the posts', 'miga' ),
                'description' => __( 'Default is excerpt. Select the content option only if the content of your posts is not too long. Button "read more" will not be display if content option was selected.', 'miga' ),
                'section'     => $section_title,
                'default'     => 0,
                'priority'    => 170,
                'active_callback'    => array(
                    array(
                        'setting'  => 'content_type_'.$section_nr,
                        'operator' => '!=',
                        'value'    => 'option-5',
                    ),
                    array(
                        'setting'  => 'content_type_'.$section_nr,
                        'operator' => '!=',
                        'value'    => 'option-6',
                    ),
                    array(
                        'setting'  => 'content_type_'.$section_nr,
                        'operator' => '!=',
                        'value'    => 'option-7',
                    ),
                ),
            ) );

            // DISPLAY DATE WHEN THE POST WAS PUBLISHED
            Kirki::add_field( 'miga', array(
                'type'        => 'toggle',
                'setting'     => 'date_'.$section_nr,
                'label'       => __( 'Display the date when the post was published', 'miga' ),
                'section'     => $section_title,
                'default'     => 0,
                'priority'    => 180,
                'active_callback'    => array(
                    array(
                        'setting'  => 'display_'.$section_nr,
                        'operator' => '!=',
                        'value'    => 'option-1',
                    ),
                    array(
                        'setting'  => 'display_'.$section_nr,
                        'operator' => '!=',
                        'value'    => 'option-2',
                    ),
                    array(
                        'setting'  => 'display_'.$section_nr,
                        'operator' => '!=',
                        'value'    => 'option-5',
                    ),
                    array(
                        'setting'  => 'display_'.$section_nr,
                        'operator' => '!=',
                        'value'    => 'option-7',
                    ),
                    array(
                        'setting'  => 'display_'.$section_nr,
                        'operator' => '!=',
                        'value'    => 'option-8',
                    ),
                    array(
                        'setting'  => 'display_'.$section_nr,
                        'operator' => '!=',
                        'value'    => 'option-9',
                    ),
                    array(
                        'setting'  => 'display_'.$section_nr,
                        'operator' => '!=',
                        'value'    => 'option-11',
                    ),
                    array(
                        'setting'  => 'display_'.$section_nr,
                        'operator' => '!=',
                        'value'    => 'option-12',
                    ),
                ),
            ) );

            // SELECT NUMBER OF COLUMNS IF NOT TIMELINE
            Kirki::add_field( 'miga', array(
                'type'        => 'select',
                'settings'    => 'col_'.$section_nr,
                'label'       => __( 'Select Number of Columns', 'miga' ),
                'description' => __( 'Select the number of columns for this section', 'miga' ),
                'section'     => $section_title,
                'default'     => 'option-1',
                'priority'    => 190,
                'choices'     => array(
                    '1-column' => __( '1 column', 'miga' ),
                    '2-columns' => __( '2 columns', 'miga' ),
                    '3-columns' => __( '3 columns', 'miga' ),
                ),
                'active_callback'    => array(
                    array(
                        'setting'  => 'display_'.$section_nr,
                        'operator' => '!=',
                        'value'    => 'option-6',
                    ),
                    array(
                        'setting'  => 'display_'.$section_nr,
                        'operator' => '!=',
                        'value'    => 'option-5',
                    ),
                    array(
                        'setting'  => 'content_type_'.$section_nr,
                        'operator' => '!=',
                        'value'    => 'option-5',
                    ),
                    array(
                        'setting'  => 'content_type_'.$section_nr,
                        'operator' => '!=',
                        'value'    => 'option-6',
                    ),
                    array(
                        'setting'  => 'content_type_'.$section_nr,
                        'operator' => '!=',
                        'value'    => 'option-7',
                    ),
                ),
            ) );

            $section_nr++;
            $section_prio + 10;
        } // foreach
    } // if $sections


    /**********************************
    * BLOG PAGE
    **********************************/
	Kirki::add_panel( 'blog-page', array(
	    'priority'    => 130,
	    'title'       => __( 'Blog Page', 'miga' ),
	    'description' => __( 'Customize your blog page', 'miga' ),
	) );

    Kirki::add_section( 'blog-settings', array(
        'title'          => __( 'Blog Page Settings', 'miga' ),
        'priority'       => 10,
        'capability'     => 'edit_theme_options',
        'panel'          => 'blog-page',
    ) );

    // BACKGROUND IMAGE OF THE BLOG PAGE
    Kirki::add_field( 'miga', array(
        'type'        => 'image',
        'settings'    => 'blog_bg_img',
        'label'       => __( 'Blog Background Image', 'miga' ),
        'description' => __('Upload an image for the background of your blog page.', 'miga'),
        'section'     => 'blog-settings',
        'priority'    => 10,
        'transport'   => 'postMessage',
    ) );

    // SELECT PARALLAX EFFECT FOR BLOGS BACKGROUND
    Kirki::add_field( '', array(
        'type'        => 'switch',
        'settings'    => 'parallax_blog',
        'label'       => __( 'Select parallax effect for the background of your blog page', 'miga' ),
        'section'     => 'blog-settings',
        'default'     => '1',
        'priority'    => 20,
        'choices'     => array(
            'on'  => __( 'On', 'miga' ),
            'off' => __( 'Off', 'miga' ),
        ),
    ) );

    // BACKGROUND COLOR OF THE BLOG PAGE
    Kirki::add_field( 'miga', array(
        'type'        => 'color-alpha',
        'settings'    => 'blog_bg_color',
        'label'       => __( 'Blog Background Color', 'miga' ),
        'section'     => 'blog-settings',
        'default'     => 'rgba(0,0,0,0.3)',
        'priority'    => 30,
        'transport' => 'postMessage',
        'js_vars'   => array(
            array(
                'element'  => '.blog-bg-color',
                'function' => 'css',
                'property' => 'background-color',
                'suffix'   => ' !important',
            ),
        ),
    ) );

    // SELECT POSTS CATEGORY
    Kirki::add_field( 'miga', array(
        'type'        => 'tax_dropdown',
        'settings'    => 'blog_cat',
        'label'       => __( 'Select Posts Category', 'miga' ),
        'description' => __( 'Select the post category you want to display on your blog page', 'miga' ),
        'section'     => 'blog-settings',
        'sanitize_callback' => 'miga_sanitize_integer',
        'default'     => get_option('default_category', ''),
        'priority'    => 40,
        'args' => array(
            'taxonomy'  => 'category',
            'post_type' => 'posts',
        ),
    ) );

    // DISPLAY OPTION OF SECTIONS CONTENT
    Kirki::add_field( 'miga', array(
        'type'        => 'select',
        'settings'    => 'blog_display',
        'label'       => __( 'Select Display Option', 'miga' ),
        'description' => __( 'Select the display option for the posts on your blog page. Notice: Option "Mansory" will not work in older browser (Internet Explorer 9 and below).', 'miga' ),
        'section'     => 'blog-settings',
        'default'     => 'option-1',
        'priority'    => 50,
        'choices'     => array(
            'option-1' => __( 'Cards Decks', 'miga' ),
            'option-2' => __( 'Mansory', 'miga' ),
        ),
    ) );

    // BACKGROUND COLOR OF THE CARDS ON BLOG PAGE
    Kirki::add_field( 'miga', array(
        'type'        => 'color-alpha',
        'settings'    => 'blog_bg_cards',
        'label'       => __( 'Cards Background Color', 'miga' ),
        'section'     => 'blog-settings',
        'default'     => $bg_cards_default,
        'priority'    => 60,
        'transport' => 'postMessage',
        'js_vars'   => array(
            array(
                'element'  => '.blog .widget, .blog .card-block',
                'function' => 'css',
                'property' => 'background-color',
                'suffix'   => ' !important',
            ),
        ),
    ) );

    // COLOR OF THE POST TITLE ON BLOG PAGE
    Kirki::add_field( 'miga', array(
        'type'        => 'color',
        'settings'    => 'blog_post_title_color',
        'label'       => __( 'Post Title Color', 'miga' ),
        'section'     => 'blog-settings',
        'default'     => $post_title_default,
        'priority'    => 70,
        'transport'   => 'postMessage',
        'js_vars'     => array(
            array(
                'element'  => '.blog .card-block h3, .blog .widget h4',
                'function' => 'css',
                'property' => 'color',
                'suffix'   => ' !important',
            ),
        ),
    ) );

    // COLOR OF THE SECTION TEXT ON BLOG PAGE
    Kirki::add_field( 'miga', array(
        'type'        => 'color',
        'settings'    => 'blog_text_color',
        'label'       => __( 'Blog Text Color', 'miga' ),
        'section'     => 'blog-settings',
        'default'     => $text_color_default,
        'priority'    => 80,
        'transport' => 'postMessage',
        'js_vars'   => array(
            array(
                'element'  => '.blog .card-block p',
                'function' => 'css',
                'property' => 'color',
                'suffix'   => ' !important',
            ),
        ),
    ) );

    // DISPLAY SOCIAL ICONS IN THE FOOTER?
    Kirki::add_field( 'miga', array(
        'type'        => 'toggle',
        'setting'     => 'display_view_counter',
        'label'       => __( 'Display views counter on single post page?', 'miga' ),
        'section'     => 'blog-settings',
        'default'     => 0,
        'priority'    => 90,
    ) );

    /**********************************
    * FOOTER
    **********************************/
    Kirki::add_panel( 'footer', array(
        'priority'    => 300,
        'title'       => __( 'Footer Settings', 'miga' ),
        'description' => __( 'Customize your footer section', 'miga' ),
    ) );

    Kirki::add_section( 'footer-settings', array(
        'title'          => __( 'Footer Settings', 'miga' ),
        'priority'       => 10,
        'capability'     => 'edit_theme_options',
        'panel'          => 'footer',
    ) );

    // BACKGROUND IMAGE OF THE FOOTER
    Kirki::add_field( 'miga', array(
        'type'        => 'image',
        'settings'    => 'footer_bg_img',
        'label'       => __( 'Footer Background Image', 'miga' ),
        'description' => __('Upload an image for the footer.', 'miga'),
        'section'     => 'footer-settings',
        'priority'    => 10,
        'transport'   => 'postMessage',
    ) );

    // BACKGROUND COLOR OF THE FOOTER
    Kirki::add_field( 'miga', array(
        'type'        => 'color-alpha',
        'settings'    => 'footer_bg_color',
        'label'       => __( 'Footer Background Color', 'miga' ),
        'description' => __('Choose the background color for the footer.', 'miga'),
        'section'     => 'footer-settings',
        'default'     => 'rgba(0,0,0,0.1)',
        'priority'    => 20,
        'output' => array(
            'element'  => '.footer-bg-color',
            'property' => 'background-color',
        ),
        'transport' => 'postMessage',
        'js_vars'   => array(
            array(
                'element'  => '.footer-bg-color',
                'function' => 'css',
                'property' => 'background-color',
            ),
        ),
    ) );

    // DISPLAY SOCIAL ICONS IN THE FOOTER?
    Kirki::add_field( 'miga', array(
        'type'        => 'toggle',
        'setting'     => 'display_social_footer',
        'label'       => __( 'Display Social Icons in the Footer?', 'miga' ),
        'description' => __( 'Select if you want to display your social icons in the footer area.', 'miga' ),
        'section'     => 'footer-settings',
        'default'     => 1,
        'priority'    => 30,
    ) );

    // TEXT COLOR FOR THE FOOTER
    Kirki::add_field( 'miga', array(
        'type'        => 'color',
        'settings'    => 'footer_color',
        'label'       => __( 'Footer Text Color', 'miga' ),
        'section'     => 'footer-settings',
        'default'     => '#000000',
        'priority'    => 40,
        'transport'   => 'postMessage',
        'js_vars'     => array(
            array(
                'element'  => 'footer h3, .footer-c',
                'function' => 'css',
                'property' => 'color',
            ),
        ),
    ) );

    $wp_customize->get_section( 'title_tagline' )->panel = 'site-settings';
    $wp_customize->get_section( 'colors' )->panel = 'site-settings';
    $wp_customize->get_section( 'background_image' )->panel = 'site-settings';
    $wp_customize->get_section( 'static_front_page' )->panel = 'site-settings';
    $wp_customize->get_section( 'header_image' )->panel = 'header';

  	$wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
  	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
  	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

    $wp_customize->remove_section( 'nav' );
    $wp_customize->remove_section( 'header_image' );
    $wp_customize->remove_setting( 'header_textcolor' );

return $wp_customize;

}
add_action( 'customize_register', 'miga_customize_register' );

/**
* Google fonts are not working with "Kirki::add_field( 'miga', array();"
*/

function miga_fields( $fields ) {

    /**
    * Add Google fonts with the typography field
    */
    $fields[] = array(
        'type'        => 'typography',
        'settings'    => 'typo_h',
        'label'       => __( 'Typography for section headlines', 'miga' ),
        'section'     => 'fonts',
        'default'     => array(
            'font-family'    => 'Roboto',
            'variant'        => 'regular',
            'font-size'      => '2.8rem',
            'line-height'    => '1.5',
            'letter-spacing' => '0',
            'subsets'        => array( 'latin-ext' ),
            'color'          => '#333333',
            'text-transform' => 'none',
            'text-align'     => 'center'
        ),
        'priority'    => 10,
        'output'      => array(
            array(
                'element' => 'h2',
                'suffix'  => ' !important'
            ),
        ),
    );

    $fields[] = array(
        'type'        => 'typography',
        'settings'    => 'typo_post_h',
        'label'       => __( 'Typography for post title', 'miga' ),
        'section'     => 'fonts',
        'default'     => array(
            'font-family'    => 'Roboto',
            'variant'        => 'regular',
            'font-size'      => '2.2rem',
            'line-height'    => '1.5',
            'letter-spacing' => '0',
            'subsets'        => array( 'latin-ext' ),
            'color'          => '#333333',
            'text-transform' => 'none',
            'text-align'     => 'center'
        ),
        'priority'    => 10,
        'output'      => array(
            array(
                'element' => 'h3',
                'suffix'  => ' !important'
            ),
        ),
    );

    $fields[] = array(
        'type'        => 'typography',
        'settings'    => 'typo_p',
        'label'       => __( 'Typography for the paragraph text', 'miga' ),
        'section'     => 'fonts',
        'default'     => array(
            'font-family'    => 'Roboto',
            'variant'        => 'regular',
            'font-size'      => '1.2rem',
            'line-height'    => '1.5',
            'letter-spacing' => '0',
            'subsets'        => array( 'latin-ext' ),
            'color'          => '#333333',
            'text-transform' => 'none',
            'text-align'     => 'center'
        ),
        'priority'    => 10,
        'output'      => array(
            array(
                'element' => 'body',
                'suffix'  => ' !important'
            ),
        ),
    );

    return $fields;

}
add_filter( 'kirki/fields', 'miga_fields' );


/**
* Preparing output for CSS because kirky output option seems not to work properly
*/

function miga_customizer_css() {
    //$value = get_theme_mod( 'typo_h', array() );
?>
     <style type="text/css">

        /********** COLORS ************/
        /* primary color */
        .content-bg, .contact, .post-single-inner, .widget, .blog, .card, .top-nav-collapse, .card-back, .card-front h3, #scroll-top, .overlay, .pagination span, .pagination a, #miga-main-menu > ul > li:hover > a, #miga-main-menu > ul:not( :hover ) > li.active > a, #miga-main-menu li ul li a:hover, #miga-main-menu li ul:not( :hover ) li.active a,
        #miga-sub-menu > ul > li:hover > a, #miga-sub-menu > ul:not( :hover ) > li.active > a, #miga-sub-menu li ul li a:hover, #miga-sub-menu li ul:not( :hover ) li.active a, .timeline-content, #filter-cards figcaption {
            background-color: <?php echo get_theme_mod('primary_color');?>;
        }
        @media (min-width: 768px) {
            .timeline-block:nth-child(even) .timeline-content::before {
                border-right-color: <?php echo get_theme_mod('primary_color');?>;
            }
            .timeline-content::before {
                border-left-color: <?php echo get_theme_mod('primary_color');?>;
            }
        }
        @media (max-width: 767px) {
            .timeline-content::before {
                border-bottom-color: <?php echo get_theme_mod('primary_color');?>;
            }
        }
        .header-bg-color {
            background-color: <?php echo get_theme_mod('header_bg_color');?>;
        }
        /* call to action color */
        .btn:hover, .btn:active, .btn:focus, .form-submit input:hover, form-submit input:active, form-submit input:focus, #filter button {
            color: <?php echo get_theme_mod('cta_color');?> !important;
        }
        #filter button, .btn, .btn-lg,
        #filter button:hover, .btn-lg:hover, .btn:hover,
        #filter button.active, .btn-lg.active, .btn.active,
        #filter button:focus, .btn-lg:focus, .btn:focus,
        .form-control:focus,
        #comment:focus,
        .form-submit input,
        .form-submit input:hover,
        input[type="search"]:focus {
            border-color: <?php echo get_theme_mod('cta_color');?>;
        }
        #filter button:hover, .first-btn:hover,
        #filter button.active, .first-btn.active,
        #filter button:focus, .first-btn:focus,
        .btn,
        .second-btn,
        .timeline-icon,
        .owl-theme .owl-controls .owl-page.active span,
        .owl-theme .owl-controls.clickable .owl-page:hover span,
        .badge-social i.fa:hover,
        .post-comments-badge,
        .post-comments-badge:hover,
        .form-submit input:hover,
        #scroll-top:hover,
        .button_container span,
        .button_container.active .top,
        .button_container.active .bottom,
        .pagination a:hover,
        .pagination .current {
            background-color: <?php echo get_theme_mod('cta_color');?>;
        }
        <?php if ( get_theme_mod( 'menu_style' ) === 'static' ) {?>
        #miga-main-menu > ul, #miga-main-menu li ul, #miga-sub-menu > ul, #miga-sub-menu li ul {
            background-color: <?php echo get_theme_mod('cta_color');?>;
        }
        <?php }
?>      .navbar-nav > li > a:hover,
        .navbar-nav > li > a:focus,
        .navbar-nav > .active > a,
        .navbar-nav > .active > a:hover,
        .navbar-nav > .active > a:focus {
            border-bottom-color: <?php echo get_theme_mod('cta_color');?>;
        }
        blockquote {
            border-left-color: <?php echo get_theme_mod('cta_color');?>;
        }
        /* headline color and font-size */

        /* post title color and font-size */
        /*.miga-section h3 {
            font-size: <?php echo get_theme_mod('font_size_h3');?>;
            color: <?php echo get_theme_mod('post_title_color');?>;
        }*/
        /* text color */
        /*.miga-section p, .read-more:active, .read-more:focus, .read-more:visited, .comment-reply-title, .widget-title {
            color: <?php echo get_theme_mod('text_color');?>;
        }*/
        /* colors blog page */
        .blog-bg-color {
            background-color: <?php echo get_theme_mod('blog_bg_color');?>;
        }
        .blog-container .widget, .single .widget, .blog .card-block {
            background-color: <?php echo get_theme_mod('blog_bg_cards');?>;
        }
        .blog .card-block h3, .blog-container .widget h4, .single .widget h4 {
            color: <?php echo get_theme_mod('blog_post_title_color');?>;
        }
        .blog .card-block p, .blog-container .widget a {
            color: <?php echo get_theme_mod('blog_text_color');?>;
        }

        /* text color footer*/
        footer h3, .footer-c {
            color: <?php echo get_theme_mod('footer_color');?>;
        }
        /* LAYOUT SETTINGS */
        <?php if ( get_theme_mod( 'box_layout' ) === 'fullwidth-layout' ) {?>
        .container {
            max-width: 1920px;
            width: 100%;
            padding-left: <?php echo get_theme_mod('fullwidth_padding') ?>;
            padding-right: <?php echo get_theme_mod('fullwidth_padding') ?>;
        }
        <?php } ?>
        <?php if ( get_theme_mod( 'full_custom_header' ) === 1 ) {?>
        .custom-header {
            max-width: 1920px;
            width: 100%;
            margin-top: -6rem;
            margin-bottom: -6rem;
        }
        <?php } ?>
        /* MENU SETTINGS */
        <?php
        // get number of links for the primary menu
        $nr_menu_links = get_theme_mod( 'menu_links' );
        $link_width = 100/$nr_menu_links;
        // get number of links for the secondary menu
        $nr_menu_links_sub = get_theme_mod( 'menu_links_sub' );
        $link_width_sub = 100/$nr_menu_links_sub;
        ?>

        .overlay ul li {
            height: <?php echo $link_width;?>%;
        }
        #miga-main-menu > ul > li {
            width: <?php echo $link_width;?>%;
        }

        .overlay ul li {
            height: <?php echo $link_width_sub;?>%;
        }
        #miga-sub-menu > ul > li {
            width: <?php echo $link_width_sub;?>%;
        }

        <?php if ( get_theme_mod( 'menu_style' ) === 'static' ) {?>
        .blog-header-bg-color {
            padding-top: 7rem;
        }
        /* to remove empty p tags in widgets / shortcodes like contact form */
        p:empty {
          display: none;
        }
        <?php } ?>
     </style>
<?php
}
add_action( 'wp_head', 'miga_customizer_css' );

/**
* Preparing output for custom CSS textarea
*/

function miga_custom_theme_css() {
    echo '<style type="text/css" id="custom-theme-css">' .
    get_theme_mod( 'custom_theme_css', '' ) . '</style>';
}

add_action( 'wp_head', 'miga_custom_theme_css' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function miga_customize_preview_js() {
	wp_enqueue_script( 'miga_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20130508', true );
}
add_action( 'customize_preview_init', 'miga_customize_preview_js' );
