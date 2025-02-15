<?php
/**
 * Mitosis functions and definitions.
 *
 * @package Mitosis
 */

// Set content width.
if ( ! isset( $content_width ) ) {
	$content_width = 800;
}

// Load text domain for translations.
function mitosis_setup() {
	load_theme_textdomain( 'mitosis', get_template_directory() . '/languages' );

	// Add support for various theme features.
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
// 	add_theme_support( 'html5', array(
// 		'search-form',
// 		'comment-form',
// 		'comment-list',
// 		'gallery',
// 		'caption',
// 	) );

	// Custom logo support.
	add_theme_support( 'custom-logo', array(
		'height'      => 60,
		'width'       => 200,
		'flex-height' => true,
		'flex-width'  => true,
	) );

	// This theme uses a custom menu.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'mitosis' ),
	) );
}
add_action( 'after_setup_theme', 'mitosis_setup' );

// Enqueue scripts and styles.
function mitosis_scripts() {
    // Get theme information including version
    $theme_version = wp_get_theme()->get('Version');

    // Enqueue main stylesheet
    wp_enqueue_style(
        'mitosis-style', 
        get_stylesheet_uri(),
        array(),
        $theme_version
    );
    
    wp_enqueue_style(
        'mitosis-icons', 
        get_template_directory_uri() . '/assets/fonts/style.css',
        array(),
        $theme_version
    );
    
    // Enqueue main JavaScript
    wp_enqueue_script(
        'mitosis-main',
        get_template_directory_uri() . '/assets/js/main.js',
        array(),
        $theme_version,
        true
    );

    // Enqueue search functionality
    wp_enqueue_script(
        'mitosis-search',
        get_template_directory_uri() . '/assets/js/search.js',
        array(),
        $theme_version,
        true
    );
    
    if ( is_singular() ) {
        wp_enqueue_style( 'mitosis-single-style',
            get_template_directory_uri() . '/assets/styles/single.css',
            array(),
            $theme_version
        );
    }
    
    if (is_singular() && comments_open()) {
        wp_enqueue_script('comment-reply');
    }
}
add_action( 'wp_enqueue_scripts', 'mitosis_scripts' );

// Register widget areas (sidebars).
function mitosis_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Left Sidebar', 'mitosis' ),
		'id'            => 'sidebar-left',
		'description'   => __( 'Widgets in this area will be shown on the left sidebar.', 'mitosis' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => __( 'Right Sidebar', 'mitosis' ),
		'id'            => 'sidebar-right',
		'description'   => __( 'Widgets in this area will be shown on the right sidebar.', 'mitosis' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
}
add_action( 'widgets_init', 'mitosis_widgets_init' );

// Load additional theme files.
require get_template_directory() . '/inc/customizer.php';  // Customizer settings (logo, subtitle, layout, scripts)
require get_template_directory() . '/inc/meta-boxes.php';    // Custom meta boxes for posts/pages
require get_template_directory() . '/inc/template-tags.php'; // Helper functions
require get_template_directory() . '/inc/tax-meta.php'; // Layout option for categories and tags 
require get_template_directory() . '/inc/layout-template.php';
require get_template_directory() . '/inc/related-posts.php';
require get_template_directory() . '/inc/toc.php';
require get_template_directory() . '/inc/featured-image.php';
require get_template_directory() . '/inc/schema.php';
require get_template_directory() . '/inc/pagination.php';
require get_template_directory() . '/inc/comments.php';

// Add hooks for the new functions
add_action('wp_head', 'mitosis_add_pagination_meta', 1);
add_action('wp_footer', 'mitosis_add_schema_markup');


// Output custom header scripts.
function mitosis_custom_header_scripts() {
	// Global header scripts set via Customizer.
	$global_header_scripts = get_theme_mod( 'mitosis_header_scripts' );
	if ( $global_header_scripts ) {
		echo $global_header_scripts;
	}

	// If on a singular page/post, allow for per-page meta field override.
	if ( is_singular() ) {
		global $post;
		$page_header_scripts = get_post_meta( $post->ID, '_mitosis_header_scripts', true );
		if ( $page_header_scripts ) {
			echo $page_header_scripts;
		}
	}
}
add_action( 'wp_head', 'mitosis_custom_header_scripts' );

// Output custom footer scripts.
function mitosis_custom_footer_scripts() {
	// Global footer scripts set via Customizer.
	$global_footer_scripts = get_theme_mod( 'mitosis_footer_scripts' );
	if ( $global_footer_scripts ) {
		echo $global_footer_scripts;
	}

	// If on a singular page/post, allow for per-page meta field override.
	if ( is_singular() ) {
		global $post;
		$page_footer_scripts = get_post_meta( $post->ID, '_mitosis_footer_scripts', true );
		if ( $page_footer_scripts ) {
			echo $page_footer_scripts;
		}
	}
}
add_action( 'wp_footer', 'mitosis_custom_footer_scripts' );

/**
 * Determine the current layout.
 *
 * The default layout is taken from the Customizer, but it can be overridden by
 * per–post/page meta. Additionally, you can force one-column on certain pages.
 *
 * @return string The layout identifier.
 */
function mitosis_get_layout() {
	// For archives, home, and single pages, you might want to force one column.
	// Uncomment the next lines if that behavior is desired:
	/*
	if ( is_archive() || is_home() || is_single() ) {
		return 'one-column';
	}
	*/

	// Otherwise, get the default from the Customizer.
	$layout = get_theme_mod( 'mitosis_default_layout', 'two-right' );

	// Check for per–post/page override.
	if ( is_singular() ) {
		global $post;
		$meta_layout = get_post_meta( $post->ID, '_mitosis_layout', true );
		if ( $meta_layout ) {
			$layout = $meta_layout;
		}
	}

	// You can add additional conditions for specific categories or tags here.

	return $layout;
}

/**
 * Adds a custom Twitter/X profile field to the user profile.
 *
 * @param array $contactmethods Existing contact methods.
 * @return array Modified contact methods.
 */
function mitosis_add_twitter_profile_field( $contactmethods ) {
    $contactmethods['twitter_profile'] = __('Twitter/X Profile URL', 'mitosis');
    return $contactmethods;
}
add_filter( 'user_contactmethods', 'mitosis_add_twitter_profile_field', 10, 1 );
