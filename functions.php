<?php
/**
 * Miga functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Miga
 */

if ( ! function_exists( 'miga_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function miga_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Miga, use a find and replace
	 * to change 'miga' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'miga', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

    // adding custom miga image sizes
    add_image_size('miga-thumb', 300, 220, true ); // hard crop mode

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary Menu', 'miga' ),
		'secondary' => esc_html__( 'Secondary Menu', 'miga' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 * See https://developer.wordpress.org/themes/functionality/post-formats/
	 */
	add_theme_support( 'post-formats', array(
		'image',
		'video',
		'quote',
		'link',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'miga_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
}
endif;
add_action( 'after_setup_theme', 'miga_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function miga_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'miga_content_width', 640 );
}
add_action( 'after_setup_theme', 'miga_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function miga_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'miga' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );

	// Widget area for the contact form
	register_sidebar( array(
        'name' => __( 'Contact Form', 'geckosphoto' ),
        'id' => 'miga_contact_form',
        'before_widget' => '<div>',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="widget-title">',
        'after_title' => '</h4>'
    ) );

	// First footer widget area
    register_sidebar( array(
        'name' => __( '1. Footer Widget Area', 'miga' ),
        'id' => 'first-footer-widget',
        'description' => __( 'The first footer widget area', 'miga' ),
        'before_widget' => '<div id="%1$s" class="widget-container %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="widget-title">',
        'after_title' => '</h4>',
    ) );

    // Second Footer Widget Area
    register_sidebar( array(
        'name' => __( '2. Footer Widget Area', 'miga' ),
        'id' => 'second-footer-widget',
        'description' => __( 'The second footer widget area', 'miga' ),
        'before_widget' => '<div id="%1$s" class="widget-container %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="widget-title">',
        'after_title' => '</h4>',
    ) );

    // Third Footer Widget Area
    register_sidebar( array(
        'name' => __( '3. Footer Widget Area', 'miga' ),
        'id' => 'third-footer-widget',
        'description' => __( 'The third footer widget area', 'miga' ),
        'before_widget' => '<div id="%1$s" class="widget-container %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="widget-title">',
        'after_title' => '</h4>',
    ) );

    // Register widgetized area for the contact form
    register_sidebar( array(
        'name' => esc_html__( 'Contact Form', 'miga' ),
        'id' => 'miga_contact_form',
        'before_widget' => '<div>',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="widget-title">',
        'after_title' => '</h4>'
    ) );
}
add_action( 'widgets_init', 'miga_widgets_init' );

/**
 * Making widgets accept shortcodes
 */

add_filter( 'widget_text', 'shortcode_unautop');
add_filter( 'widget_text', 'do_shortcode');

/**
 * Enqueue scripts and styles.
 */
if ( ! function_exists( 'miga_scripts' ) ) :
function miga_scripts() {
		//enqueue style sheets
    wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/css/bootstrap.min.css', false, null, 'all');

    wp_enqueue_style( 'fontawesome', get_template_directory_uri() . '/css/font-awesome.min.css', false, null, 'all');

    wp_enqueue_style( 'featherlight', get_template_directory_uri() . '/css/featherlight.css', false, null, 'all');

    wp_enqueue_style( 'featherlight-gallery', get_template_directory_uri() . '/css/featherlight.gallery.css', false, null, 'all');

    wp_enqueue_style( 'owl', get_template_directory_uri() . '/css/owl.carousel.css', false, null, 'all');

    wp_enqueue_style( 'owl-theme', get_template_directory_uri() . '/css/owl.theme.css', false, null, 'all');

		wp_enqueue_style( 'miga-style', get_stylesheet_uri() );

		//enqueue scripts
    wp_enqueue_script( 'jquery', get_template_directory_uri() . '/js/jquery.min.js', false, null, true);

		wp_enqueue_script( 'tether', get_template_directory_uri() . '/js/tether.min.js', false, null, true);

    wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/js/bootstrap.min.js', false, null, true);

    wp_enqueue_script( 'slimscroll', get_template_directory_uri() . '/js/jquery.slimscroll.min.js', false, null, true);

    wp_enqueue_script( 'easing', get_template_directory_uri() . '/js/jquery.easing.min.js', false, null, true);

    wp_enqueue_script( 'multi-level-nav', get_template_directory_uri() . '/js/doubletaptogo.min.js', false, null, true);

    // For photo galleries
    wp_enqueue_script( 'featherlight', get_template_directory_uri() . '/js/featherlight.js', false, null, true);

    wp_enqueue_script( 'featherlight-gallery', get_template_directory_uri() . '/js/featherlight.gallery.js', false, null, true);

    wp_enqueue_script( 'google', 'https://maps.google.com/maps/api/js?sensor=true', false, null, true);

		wp_enqueue_script( 'miga-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20120206', true );

		wp_enqueue_script( 'miga-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );

    wp_enqueue_script( 'owl', get_template_directory_uri() . '/js/owl.carousel.min.js', false, null, true);

		wp_enqueue_script( 'ie10-viewport', get_template_directory_uri() . '/js/ie10-viewport-bug-workaround.js', false, null, true);

    wp_enqueue_script( 'script', get_template_directory_uri() . '/js/scripts.js', false, null, true);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'miga_scripts' );
endif;

/**
 * Implement the Custom Header feature.
 */
//require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Addition to Bootstrap Menus
 */
//require_once get_template_directory() . '/inc/wp_bootstrap_navwalker.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Loading Custom Widgets file (Newsletter)
 */
require get_template_directory() . '/inc/custom-widgets.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/**
 * Include the TGM_Plugin_Activation class.
 */
require_once get_template_directory() . '/inc/class-tgm-plugin-activation.php';


/**
 * CUSTOM MIGA FUNCTIONS
 */

 add_action( 'tgmpa_register', 'miga_register_required_plugins' );

 /**
  * Register the required plugins for this theme.
  * The variables passed to the `tgmpa()` function should be:
  * - an array of plugin arrays;
  * - optionally a configuration array.
  * If you are not changing anything in the configuration array, you can remove the array and remove the
  * variable from the function call: `tgmpa( $plugins );`.
  * In that case, the TGMPA default settings will be used.
  *
  * This function is hooked into `tgmpa_register`, which is fired on the WP `init` action on priority 10.
  */
 function miga_register_required_plugins() {
 	/*
 	 * Array of plugin arrays. Required keys are name and slug.
 	 * If the source is NOT from the .org repo, then source is also required.
 	 */
 	$plugins = array(

		// Include the WebGeckos plugin bundle with the Miga theme from the github repository
		array(
			'name'      					=> 'Miga Light Plugin Bundle',
			'slug'      					=> 'miga-light-plugin-bundle',
			'source'    					=> 'https://github.com/webgeckos/miga-light-plugin-bundle/archive/master.zip',
			'required'  					=> true,
			'force_activation'   	=> true, // Plugin is activated upon theme activation and cannot be deactivated until theme switch.
			'force_deactivation' 	=> true, // Plugin is deactivated upon theme switch
		),

 		// Include the plugin one click demo import from the WordPress Plugin Repository.
 		array(
 			'name'      => 'One Click Demo Import',
 			'slug'      => 'one-click-demo-import',
 			'required'  => false,
 		),

		// Include the plugin Customizer Export/Import from the WordPress Plugin Repository.
 		array(
 			'name'      => 'Customizer Export/Import',
 			'slug'      => 'customizer-export-import',
 			'required'  => false,
 		),

		// Include the plugin Contact Form 7 from the WordPress Plugin Repository.
 		array(
 			'name'      => 'Contact Form 7',
 			'slug'      => 'contact-form-7',
 			'required'  => false,
 		),

 	);

 	/*
 	 * Array of configuration settings. Amend each line as needed.
 	 *
 	 * TGMPA will start providing localized text strings soon. If you already have translations of our standard
 	 * strings available, please help us make TGMPA even better by giving us access to these translations or by
 	 * sending in a pull-request with .po file(s) with the translations.
 	 *
 	 * Only uncomment the strings in the config array if you want to customize the strings.
 	 */
 	$config = array(
 		'id'           => 'miga',                 // Unique ID for hashing notices for multiple instances of TGMPA.
 		'default_path' => '',                      // Default absolute path to bundled plugins.
 		'menu'         => 'tgmpa-install-plugins', // Menu slug.
 		'has_notices'  => true,                    // Show admin notices or not.
 		'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
 		'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
 		'is_automatic' => false,                   // Automatically activate plugins after installation or not.
 		'message'      => '',                      // Message to output right before the plugins table.

 		/*
 		'strings'      => array(
 			'page_title'                      => __( 'Install Required Plugins', 'miga' ),
 			'menu_title'                      => __( 'Install Plugins', 'miga' ),
 			/* translators: %s: plugin name. * /
 			'installing'                      => __( 'Installing Plugin: %s', 'miga' ),
 			/* translators: %s: plugin name. * /
 			'updating'                        => __( 'Updating Plugin: %s', 'miga' ),
 			'oops'                            => __( 'Something went wrong with the plugin API.', 'miga' ),
 			'notice_can_install_required'     => _n_noop(
 				/* translators: 1: plugin name(s). * /
 				'This theme requires the following plugin: %1$s.',
 				'This theme requires the following plugins: %1$s.',
 				'miga'
 			),
 			'notice_can_install_recommended'  => _n_noop(
 				/* translators: 1: plugin name(s). * /
 				'This theme recommends the following plugin: %1$s.',
 				'This theme recommends the following plugins: %1$s.',
 				'miga'
 			),
 			'notice_ask_to_update'            => _n_noop(
 				/* translators: 1: plugin name(s). * /
 				'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.',
 				'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.',
 				'miga'
 			),
 			'notice_ask_to_update_maybe'      => _n_noop(
 				/* translators: 1: plugin name(s). * /
 				'There is an update available for: %1$s.',
 				'There are updates available for the following plugins: %1$s.',
 				'miga'
 			),
 			'notice_can_activate_required'    => _n_noop(
 				/* translators: 1: plugin name(s). * /
 				'The following required plugin is currently inactive: %1$s.',
 				'The following required plugins are currently inactive: %1$s.',
 				'miga'
 			),
 			'notice_can_activate_recommended' => _n_noop(
 				/* translators: 1: plugin name(s). * /
 				'The following recommended plugin is currently inactive: %1$s.',
 				'The following recommended plugins are currently inactive: %1$s.',
 				'miga'
 			),
 			'install_link'                    => _n_noop(
 				'Begin installing plugin',
 				'Begin installing plugins',
 				'miga'
 			),
 			'update_link' 					  => _n_noop(
 				'Begin updating plugin',
 				'Begin updating plugins',
 				'miga'
 			),
 			'activate_link'                   => _n_noop(
 				'Begin activating plugin',
 				'Begin activating plugins',
 				'miga'
 			),
 			'return'                          => __( 'Return to Required Plugins Installer', 'miga' ),
 			'plugin_activated'                => __( 'Plugin activated successfully.', 'miga' ),
 			'activated_successfully'          => __( 'The following plugin was activated successfully:', 'miga' ),
 			/* translators: 1: plugin name. * /
 			'plugin_already_active'           => __( 'No action taken. Plugin %1$s was already active.', 'miga' ),
 			/* translators: 1: plugin name. * /
 			'plugin_needs_higher_version'     => __( 'Plugin not activated. A higher version of %s is needed for this theme. Please update the plugin.', 'miga' ),
 			/* translators: 1: dashboard link. * /
 			'complete'                        => __( 'All plugins installed and activated successfully. %1$s', 'miga' ),
 			'dismiss'                         => __( 'Dismiss this notice', 'miga' ),
 			'notice_cannot_install_activate'  => __( 'There are one or more required or recommended plugins to install, update or activate.', 'miga' ),
 			'contact_admin'                   => __( 'Please contact the administrator of this site for help.', 'miga' ),

 			'nag_type'                        => '', // Determines admin notice type - can only be one of the typical WP notice classes, such as 'updated', 'update-nag', 'notice-warning', 'notice-info' or 'error'. Some of which may not work as expected in older WP versions.
 		),
 		*/
 	);

 	tgmpa( $plugins, $config );
 }

/**
 * Loading demo content files
 */
function miga_import_files() {
    return array(
        array(
            'import_file_name'       			=> 'Import Miga Demo 1',
            'import_file_url'        			=> get_stylesheet_directory_uri() . '/demo-content/miga-demo-content-1.xml',
            'import_widget_file_url' 			=> get_stylesheet_directory_uri() . '/demo-content/miga-demo-widgets-1.wie',
						'import_customizer_file_url' 	=> get_stylesheet_directory_uri() . '/demo-content/miga-customizer-demo-1.dat'
        ),
        array(
            'import_file_name'       			=> 'Import Miga Demo 2',
            'import_file_url'        			=> get_stylesheet_directory_uri() . '/demo-content/miga-demo-content-2.xml',
            'import_widget_file_url' 			=> get_stylesheet_directory_uri() . '/demo-content/miga-demo-widgets-2.wie',
						'import_customizer_file_url' 	=> get_stylesheet_directory_uri() . '/demo-content/miga-customizer-demo-2.dat'
        ),
    );
}
add_filter( 'pt-ocdi/import_files', 'miga_import_files' );

/**
 * Apply custom stylesheet to the visual editor
 * Important: it doesn't work with admin-init hook as suggested in codex -> use after_setup_theme hook instead
 */

function miga_add_editor_styles() {
    add_editor_style( 'css/miga-editor-style.css' );
}
add_action( 'after_setup_theme', 'miga_add_editor_styles' );

/**
 * Replaces the standard excerpt more [...] by a "read more"-link
 */

function miga_excerpt_more( $more ) {
    global $post;
    return sprintf( '...<br><br><a class="read-more btn" href="%1$s"> %2$s</a>',
        get_permalink( get_the_ID() ),
        __( 'Read More', 'miga' )
    );
}
add_filter( 'excerpt_more', 'miga_excerpt_more' );

/**
 * Filter the excerpt link to 25 characters
 */

function miga_custom_excerpt_length( $length ) {
    return 25;
}
add_filter( 'excerpt_length', 'miga_custom_excerpt_length', 999 );

// Custom pagination
function miga_pagination($pages = '', $range = 2)
{
    $showitems = ($range * 2)+1;

    global $paged;
    if (empty($paged)) $paged = 1;

    if ($pages == '') {
        global $wp_query;
        $pages = $wp_query->max_num_pages;
        if (!$pages) {
            $pages = 1;
        }
    }

    if (1 != $pages) {
        echo "<div class='pagination'>";
        if ($paged > 2 && $paged > $range+1 && $showitems < $pages) echo "<a href='".get_pagenum_link(1)."'>&laquo;</a>";
        if ($paged > 1 && $showitems < $pages) echo "<a href='".get_pagenum_link($paged - 1)."'>&lsaquo;</a>";

        for ($i=1; $i <= $pages; $i++) {
            if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems )) {
                echo ($paged == $i)? "<span class='current'>".$i."</span>":"<a href='".get_pagenum_link($i)."' class='inactive' >".$i."</a>";
            }
        }

        if ($paged < $pages && $showitems < $pages) echo "<a href='".get_pagenum_link($paged + 1)."'>&rsaquo;</a>";
        if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo "<a href='".get_pagenum_link($pages)."'>&raquo;</a>";
        echo "</div>\n";
    }
}
