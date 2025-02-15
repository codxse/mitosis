<?php
/**
 * Mitosis Customizer options.
 *
 * @package Mitosis
 */

function mitosis_customize_register( $wp_customize ) {

	// Add a section for theme options.
	$wp_customize->add_section( 'mitosis_theme_options', array(
		'title'    => __( 'Theme Options', 'mitosis' ),
		'priority' => 130,
	) );

	// Option: Default Layout.
	$wp_customize->add_setting( 'mitosis_default_layout', array(
		'default'           => 'two-right',
		'sanitize_callback' => 'sanitize_text_field',
	) );

    $wp_customize->add_control( 'mitosis_default_layout', array(
    	'label'    => __( 'Default Layout', 'mitosis' ),
    	'section'  => 'mitosis_theme_options',
    	'type'     => 'select',
    	'choices'  => array(
    		'one-column'   => __( 'One Column', 'mitosis' ),
    		'three-column' => __( 'Three Column (Sidebar, Post, Sidebar)', 'mitosis' ),
    		'two-left'     => __( 'Two Column (Sidebar Left, Post)', 'mitosis' ),
    		'two-right'    => __( 'Two Column (Post, Sidebar Right)', 'mitosis' ),
    	),
    ) );

	// Option: Show/Hide Subtitle.
	$wp_customize->add_setting( 'mitosis_show_subtitle', array(
		'default'           => true,
		'sanitize_callback' => 'wp_validate_boolean',
	) );

	$wp_customize->add_control( 'mitosis_show_subtitle', array(
		'label'    => __( 'Show Subtitle', 'mitosis' ),
		'section'  => 'mitosis_theme_options',
		'type'     => 'checkbox',
	) );

	// Option: Custom Header Scripts.
	$wp_customize->add_setting( 'mitosis_header_scripts', array(
		'default'           => '',
		'sanitize_callback' => 'wp_kses_post',
	) );

	$wp_customize->add_control( 'mitosis_header_scripts', array(
		'label'    => __( 'Header Scripts', 'mitosis' ),
		'section'  => 'mitosis_theme_options',
		'type'     => 'textarea',
	) );

	// Option: Custom Footer Scripts.
	$wp_customize->add_setting( 'mitosis_footer_scripts', array(
		'default'           => '',
		'sanitize_callback' => 'wp_kses_post',
	) );

	$wp_customize->add_control( 'mitosis_footer_scripts', array(
		'label'    => __( 'Footer Scripts', 'mitosis' ),
		'section'  => 'mitosis_theme_options',
		'type'     => 'textarea',
	) );
	
	// Add a section for Footer Options (if you want to group it separately)
    $wp_customize->add_section( 'mitosis_footer_options', array(
        'title'    => __( 'Footer Options', 'mitosis' ),
        'priority' => 140,
    ) );
    
    // Option: Footer Copyright.
    $wp_customize->add_setting( 'mitosis_footer_copyright', array(
        'default'           => __( '© ' . date('Y') . ' Your Site Name. All Rights Reserved.', 'mitosis' ),
        'sanitize_callback' => 'wp_kses_post',
    ) );
    
    $wp_customize->add_control( 'mitosis_footer_copyright', array(
        'label'    => __( 'Footer Copyright', 'mitosis' ),
        'section'  => 'mitosis_footer_options',
        'type'     => 'textarea',
    ) );

    // Add Entry Meta section
    $wp_customize->add_section('mitosis_entry_meta_options', array(
        'title'    => __('Entry Meta Options', 'mitosis'),
        'priority' => 125,
    ));
    
    // Show Created Date
    $wp_customize->add_setting('mitosis_show_created_date', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    
    $wp_customize->add_control('mitosis_show_created_date', array(
        'label'    => __('Show Created Date', 'mitosis'),
        'section'  => 'mitosis_entry_meta_options',
        'type'     => 'checkbox',
    ));
    
    // Show Updated Date
    $wp_customize->add_setting('mitosis_show_updated_date', array(
        'default'           => false,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    
    $wp_customize->add_control('mitosis_show_updated_date', array(
        'label'    => __('Show Updated Date', 'mitosis'),
        'section'  => 'mitosis_entry_meta_options',
        'type'     => 'checkbox',
    ));
    
    // Show Author
    $wp_customize->add_setting('mitosis_show_author', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    
    $wp_customize->add_control('mitosis_show_author', array(
        'label'    => __('Show Author', 'mitosis'),
        'section'  => 'mitosis_entry_meta_options',
        'type'     => 'checkbox',
    ));
    
    // Show Comment Count
    $wp_customize->add_setting('mitosis_show_comment_count', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    
    $wp_customize->add_control('mitosis_show_comment_count', array(
        'label'    => __('Show Comment Count', 'mitosis'),
        'section'  => 'mitosis_entry_meta_options',
        'type'     => 'checkbox',
    ));
    
    // Show Categories
    $wp_customize->add_setting('mitosis_show_categories', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    
    $wp_customize->add_control('mitosis_show_categories', array(
        'label'    => __('Show Categories', 'mitosis'),
        'section'  => 'mitosis_entry_meta_options',
        'type'     => 'checkbox',
    ));
    
    // Show Tags
    $wp_customize->add_setting('mitosis_show_tags', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    
    $wp_customize->add_control('mitosis_show_tags', array(
        'label'    => __('Show Tags', 'mitosis'),
        'section'  => 'mitosis_entry_meta_options',
        'type'     => 'checkbox',
    ));
    
    // Show Estimated Reading Time
    $wp_customize->add_setting('mitosis_show_reading_time', array(
        'default'           => false,
        'sanitize_callback' => 'wp_validate_boolean',
    ));

    $wp_customize->add_control('mitosis_show_reading_time', array(
        'label'    => __('Show Estimated Reading Time', 'mitosis'),
        'section'  => 'mitosis_entry_meta_options',
        'type'     => 'checkbox',
    ));

    // Add Related Posts section
    $wp_customize->add_section('mitosis_related_posts_options', array(
        'title'    => __('Related Posts Options', 'mitosis'),
        'priority' => 126,
    ));
    
    // Show Related Posts
    $wp_customize->add_setting('mitosis_show_related_posts', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    
    $wp_customize->add_control('mitosis_show_related_posts', array(
        'label'    => __('Show Related Posts', 'mitosis'),
        'section'  => 'mitosis_related_posts_options',
        'type'     => 'checkbox',
    ));
    
    // Number of Related Posts
    $wp_customize->add_setting('mitosis_related_posts_count', array(
        'default'           => 3,
        'sanitize_callback' => 'absint',
    ));
    
    $wp_customize->add_control('mitosis_related_posts_count', array(
        'label'       => __('Number of Related Posts to Show', 'mitosis'),
        'section'     => 'mitosis_related_posts_options',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 1,
            'max'  => 9,
            'step' => 1,
        ),
    ));
    
    // Related Posts Order
    $wp_customize->add_setting('mitosis_related_posts_orderby', array(
        'default'           => 'date',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('mitosis_related_posts_orderby', array(
        'label'    => __('Order Related Posts By', 'mitosis'),
        'section'  => 'mitosis_related_posts_options',
        'type'     => 'select',
        'choices'  => array(
            'date'     => __('Date', 'mitosis'),
            'rand'     => __('Random', 'mitosis'),
            'comment'  => __('Comment Count', 'mitosis'),
        ),
    ));
    
    // Related Posts Criteria
    $wp_customize->add_setting('mitosis_related_posts_criteria', array(
        'default'           => 'category',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('mitosis_related_posts_criteria', array(
        'label'    => __('Related Posts Criteria', 'mitosis'),
        'section'  => 'mitosis_related_posts_options',
        'type'     => 'select',
        'choices'  => array(
            'category' => __('Same Category', 'mitosis'),
            'tag'      => __('Same Tags', 'mitosis'),
            'both'     => __('Both Category and Tags', 'mitosis'),
        ),
    ));
    

    // Related Posts Title
    $wp_customize->add_setting('mitosis_related_posts_title', array(
        'default'           => __('Related Posts', 'mitosis'),
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('mitosis_related_posts_title', array(
        'label'    => __('Related Posts Section Title', 'mitosis'),
        'section'  => 'mitosis_related_posts_options',
        'type'     => 'text',
    ));
    
    // Default Featured Image
    $wp_customize->add_setting('mitosis_related_default_image', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'mitosis_related_default_image', array(
        'label'    => __('Default Featured Image', 'mitosis'),
        'description' => __('Select an image to display when posts have no featured image.', 'mitosis'),
        'section'  => 'mitosis_related_posts_options',
    )));
    
    // Show Category
    $wp_customize->add_setting('mitosis_related_show_category', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    
    $wp_customize->add_control('mitosis_related_show_category', array(
        'label'    => __('Show Post Category', 'mitosis'),
        'section'  => 'mitosis_related_posts_options',
        'type'     => 'checkbox',
    ));
    
    // Show Date
    $wp_customize->add_setting('mitosis_related_show_date', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    
    $wp_customize->add_control('mitosis_related_show_date', array(
        'label'    => __('Show Post Date', 'mitosis'),
        'section'  => 'mitosis_related_posts_options',
        'type'     => 'checkbox',
    ));
    
    // Show Comment Count
    $wp_customize->add_setting('mitosis_related_show_comments', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    
    $wp_customize->add_control('mitosis_related_show_comments', array(
        'label'    => __('Show Comment Count', 'mitosis'),
        'section'  => 'mitosis_related_posts_options',
        'type'     => 'checkbox',
    ));

    // Add TOC section
    $wp_customize->add_section('mitosis_toc_options', array(
        'title'    => __('Table of Contents Options', 'mitosis'),
        'priority' => 127,
    ));
    
    // TOC Title
    $wp_customize->add_setting('mitosis_toc_title', array(
        'default'           => __('Table of Contents', 'mitosis'),
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('mitosis_toc_title', array(
        'label'    => __('TOC Title', 'mitosis'),
        'section'  => 'mitosis_toc_options',
        'type'     => 'text',
    ));

    // Add Featured Image section
    $wp_customize->add_section('mitosis_featured_image_options', array(
        'title'    => __('Featured Image Options', 'mitosis'),
        'priority' => 128,
    ));
    
    // Show Featured Image on Archives
    $wp_customize->add_setting('mitosis_show_featured_archives', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    
    $wp_customize->add_control('mitosis_show_featured_archives', array(
        'label'    => __('Show Featured Image on Archives', 'mitosis'),
        'description' => __('Category, Tag, and Archive pages', 'mitosis'),
        'section'  => 'mitosis_featured_image_options',
        'type'     => 'checkbox',
    ));
    
    // Featured Image Size for Archives
    $wp_customize->add_setting('mitosis_featured_size_archives', array(
        'default'           => 'large',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('mitosis_featured_size_archives', array(
        'label'    => __('Featured Image Size on Archives', 'mitosis'),
        'description' => __('Select image size for archive pages', 'mitosis'),
        'section'  => 'mitosis_featured_image_options',
        'type'     => 'select',
        'choices'  => mitosis_get_available_image_sizes(),
    ));
    
    // Custom Class for Featured Images on Archives
    $wp_customize->add_setting('mitosis_featured_class_archives', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('mitosis_featured_class_archives', array(
        'label'       => __('Additional Classes for Archive Featured Images', 'mitosis'),
        'description' => __('Add custom classes separated by spaces', 'mitosis'),
        'section'     => 'mitosis_featured_image_options',
        'type'        => 'text',
    ));
    
    // Alignment for Featured Images on Archives
    $wp_customize->add_setting('mitosis_featured_align_archives', array(
        'default'           => 'aligncenter',
        'sanitize_callback' => 'mitosis_sanitize_image_align',
    ));
    
    $wp_customize->add_control('mitosis_featured_align_archives', array(
        'label'    => __('Featured Image Alignment on Archives', 'mitosis'),
        'section'  => 'mitosis_featured_image_options',
        'type'     => 'select',
        'choices'  => array(
            'alignleft'   => __('Left', 'mitosis'),
            'aligncenter' => __('Center', 'mitosis'),
            'alignright'  => __('Right', 'mitosis'),
            'alignnone'   => __('None', 'mitosis'),
        ),
    ));
    
    // Position for Featured Images on Archives
    $wp_customize->add_setting('mitosis_featured_position_archives', array(
        'default'           => 'below',
        'sanitize_callback' => 'mitosis_sanitize_image_position',
    ));
    
    $wp_customize->add_control('mitosis_featured_position_archives', array(
        'label'    => __('Featured Image Position on Archives', 'mitosis'),
        'section'  => 'mitosis_featured_image_options',
        'type'     => 'select',
        'choices'  => array(
            'above' => __('Above Title', 'mitosis'),
            'below' => __('Below Title', 'mitosis'),
        ),
    ));
    
    // Custom Class for Featured Images on Single Posts
    $wp_customize->add_setting('mitosis_featured_class_posts', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('mitosis_featured_class_posts', array(
        'label'       => __('Additional Classes for Single Post Featured Images', 'mitosis'),
        'description' => __('Add custom classes separated by spaces', 'mitosis'),
        'section'     => 'mitosis_featured_image_options',
        'type'        => 'text',
    ));
    
    // Show Featured Image on Single Posts
    $wp_customize->add_setting('mitosis_show_featured_posts', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    
    $wp_customize->add_control('mitosis_show_featured_posts', array(
        'label'    => __('Show Featured Image on Single Posts', 'mitosis'),
        'section'  => 'mitosis_featured_image_options',
        'type'     => 'checkbox',
    ));
    
    // Featured Image Size for Single Posts
    $wp_customize->add_setting('mitosis_featured_size_posts', array(
        'default'           => 'full',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('mitosis_featured_size_posts', array(
        'label'    => __('Featured Image Size on Single Posts', 'mitosis'),
        'description' => __('Select image size for single posts', 'mitosis'),
        'section'  => 'mitosis_featured_image_options',
        'type'     => 'select',
        'choices'  => mitosis_get_available_image_sizes(),
    ));
    
    // Custom Class for Featured Images on Single Posts
    $wp_customize->add_setting('mitosis_featured_class_posts', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('mitosis_featured_class_posts', array(
        'label'       => __('Additional Classes for Single Post Featured Images', 'mitosis'),
        'description' => __('Add custom classes separated by spaces', 'mitosis'),
        'section'     => 'mitosis_featured_image_options',
        'type'        => 'text',
    ));
    
    // Alignment for Featured Images on Single Posts
    $wp_customize->add_setting('mitosis_featured_align_posts', array(
        'default'           => 'aligncenter',
        'sanitize_callback' => 'mitosis_sanitize_image_align',
    ));
    
    $wp_customize->add_control('mitosis_featured_align_posts', array(
        'label'    => __('Featured Image Alignment on Single Posts', 'mitosis'),
        'section'  => 'mitosis_featured_image_options',
        'type'     => 'select',
        'choices'  => array(
            'alignleft'   => __('Left', 'mitosis'),
            'aligncenter' => __('Center', 'mitosis'),
            'alignright'  => __('Right', 'mitosis'),
            'alignnone'   => __('None', 'mitosis'),
        ),
    ));
    
    // Position for Featured Images on Single Posts
    $wp_customize->add_setting('mitosis_featured_position_posts', array(
        'default'           => 'below',
        'sanitize_callback' => 'mitosis_sanitize_image_position',
    ));
    
    $wp_customize->add_control('mitosis_featured_position_posts', array(
        'label'    => __('Featured Image Position on Single Posts', 'mitosis'),
        'section'  => 'mitosis_featured_image_options',
        'type'     => 'select',
        'choices'  => array(
            'above' => __('Above Title', 'mitosis'),
            'below' => __('Below Title', 'mitosis'),
        ),
    ));
    
    // Show Featured Image on Pages
    $wp_customize->add_setting('mitosis_show_featured_pages', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    
    $wp_customize->add_control('mitosis_show_featured_pages', array(
        'label'    => __('Show Featured Image on Pages', 'mitosis'),
        'section'  => 'mitosis_featured_image_options',
        'type'     => 'checkbox',
    ));

    // Featured Image Size for Pages
    $wp_customize->add_setting('mitosis_featured_size_pages', array(
        'default'           => 'full',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('mitosis_featured_size_pages', array(
        'label'    => __('Featured Image Size on Pages', 'mitosis'),
        'description' => __('Select image size for pages', 'mitosis'),
        'section'  => 'mitosis_featured_image_options',
        'type'     => 'select',
        'choices'  => mitosis_get_available_image_sizes(),
    ));
    
    // Custom Class for Featured Images on Pages
    $wp_customize->add_setting('mitosis_featured_class_pages', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('mitosis_featured_class_pages', array(
        'label'       => __('Additional Classes for Page Featured Images', 'mitosis'),
        'description' => __('Add custom classes separated by spaces', 'mitosis'),
        'section'     => 'mitosis_featured_image_options',
        'type'        => 'text',
    ));
    
    // Alignment for Featured Images on Pages
    $wp_customize->add_setting('mitosis_featured_align_pages', array(
        'default'           => 'aligncenter',
        'sanitize_callback' => 'mitosis_sanitize_image_align',
    ));
    
    $wp_customize->add_control('mitosis_featured_align_pages', array(
        'label'    => __('Featured Image Alignment on Pages', 'mitosis'),
        'section'  => 'mitosis_featured_image_options',
        'type'     => 'select',
        'choices'  => array(
            'alignleft'   => __('Left', 'mitosis'),
            'aligncenter' => __('Center', 'mitosis'),
            'alignright'  => __('Right', 'mitosis'),
            'alignnone'   => __('None', 'mitosis'),
        ),
    ));
    
    // Position for Featured Images on Pages
    $wp_customize->add_setting('mitosis_featured_position_pages', array(
        'default'           => 'below',
        'sanitize_callback' => 'mitosis_sanitize_image_position',
    ));
    
    $wp_customize->add_control('mitosis_featured_position_pages', array(
        'label'    => __('Featured Image Position on Pages', 'mitosis'),
        'section'  => 'mitosis_featured_image_options',
        'type'     => 'select',
        'choices'  => array(
            'above' => __('Above Title', 'mitosis'),
            'below' => __('Below Title', 'mitosis'),
        ),
    ));
    
    // Add SEO section
    $wp_customize->add_section('mitosis_seo_options', array(
        'title'    => __('SEO', 'mitosis'),
        'priority' => 120,
    ));

    // Enable/Disable Theme Schema
    $wp_customize->add_setting('mitosis_enable_schema', array(
        'default'           => false,
        'sanitize_callback' => 'wp_validate_boolean',
    ));

    $wp_customize->add_control('mitosis_enable_schema', array(
        'label'       => __('Enable Theme Schema', 'mitosis'),
        'description' => __('Enable theme schema markup even if SEO plugins are active. Use with caution to avoid duplicate schema.', 'mitosis'),
        'section'     => 'mitosis_seo_options',
        'type'        => 'checkbox',
    ));

    // Default Schema Type
    $wp_customize->add_setting('mitosis_default_schema_type', array(
        'default'           => 'BlogPosting',
        'sanitize_callback' => 'mitosis_sanitize_schema_type',
    ));

    $wp_customize->add_control('mitosis_default_schema_type', array(
        'label'       => __('Default Schema Type', 'mitosis'),
        'description' => __('Select the default schema type for posts. Can be overridden per post.', 'mitosis'),
        'section'     => 'mitosis_seo_options',
        'type'        => 'select',
        'choices'     => array(
            'BlogPosting' => __('Blog Post', 'mitosis'),
            'Article'     => __('Article', 'mitosis'),
            'NewsArticle' => __('News Article', 'mitosis'),
            'TechArticle' => __('Technical Article', 'mitosis'),
        ),
    ));
}
add_action( 'customize_register', 'mitosis_customize_register' );


function mitosis_sanitize_image_align($value) {
    $valid_values = array('alignleft', 'aligncenter', 'alignright', 'alignnone');
    return in_array($value, $valid_values) ? $value : 'aligncenter';
}

function mitosis_sanitize_image_position($value) {
    return in_array($value, array('above', 'below')) ? $value : 'below';
}

function mitosis_sanitize_schema_type($value) {
    $valid_types = array('BlogPosting', 'Article', 'NewsArticle', 'TechArticle');
    return in_array($value, $valid_types) ? $value : 'BlogPosting';
}