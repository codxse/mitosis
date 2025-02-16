<?php
/**
 * Schema.org markup implementation
 * 
 * @package Mitosis
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Check if schema should be output
 */
function mitosis_should_output_schema() {
    // Check if theme schema is enabled globally
    $theme_schema_enabled = get_theme_mod('mitosis_enable_schema', false);
    
    if (!$theme_schema_enabled) {
        return false;
    }

    // For single posts, check post-specific setting
    if (is_single()) {
        $post_schema_enabled = get_post_meta(get_the_ID(), '_mitosis_enable_schema', true);
        
        // If post has specific setting, use it
        if ($post_schema_enabled !== '') {
            return $post_schema_enabled === '1';
        }
    }

    return $theme_schema_enabled;
}

/**
 * Get schema type for current post
 */
function mitosis_get_schema_type() {
    if (is_single()) {
        // Get post-specific schema type
        $schema_type = get_post_meta(get_the_ID(), '_mitosis_schema_type', true);
        
        // Fall back to default if not set
        if (empty($schema_type)) {
            $schema_type = get_theme_mod('mitosis_default_schema_type', 'BlogPosting');
        }
        
        return $schema_type;
    }
    
    return 'BlogPosting'; // Default type
}

/**
 * Add schema markup
 */
function mitosis_add_schema_markup() {
    // Check if schema should be output
    if (!mitosis_should_output_schema()) {
        return;
    }

    // Get schema type
    $schema_type = mitosis_get_schema_type();
    
    // Only add schema markup for single blog posts
    if (!is_single()) {
        return;
    }

    // Get post data
    global $post;
    $author_id = $post->post_author;

    // Initialize basic schema structure
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => $schema_type,
        'mainEntityOfPage' => array(
            '@type' => 'WebPage',
            '@id' => get_permalink()
        ),
        'headline' => wp_strip_all_tags(get_the_title()),
        'description' => wp_strip_all_tags(get_the_excerpt()),
        'datePublished' => get_the_date('c'),
        'dateModified' => get_the_modified_date('c')
    );

    // Add extended author information
    $schema['author'] = mitosis_get_author_schema($author_id);

    // Add publisher information
    $schema['publisher'] = mitosis_get_publisher_schema();

    // Add featured image if available
    if (has_post_thumbnail()) {
        $schema['image'] = mitosis_get_featured_image_schema(get_post_thumbnail_id());
    }

    // Allow developers to modify schema
    $schema = apply_filters('mitosis_schema_markup', $schema);

    // Output schema with error handling
    try {
        printf(
            '<script type="application/ld+json">%s</script>',
            wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
        );
    } catch (Exception $e) {
        error_log('Mitosis Schema Error: ' . $e->getMessage());
    }
}

/**
 * Get enhanced author schema information
 * 
 * @param int $author_id The author's user ID
 * @return array Author schema data
 */
function mitosis_get_author_schema($author_id) {
    return array(
        '@type' => 'Person',
        'name' => get_the_author_meta('display_name', $author_id),
        'url' => get_author_posts_url($author_id),
        'description' => get_the_author_meta('description', $author_id)
    );
}

/**
 * Get publisher schema information
 * 
 * @return array Publisher schema data
 */
function mitosis_get_publisher_schema() {
    return array(
        '@type' => 'Organization',
        'name' => get_bloginfo('name'),
        'logo' => mitosis_get_logo_schema()
    );
}

/**
 * Get logo schema information
 * 
 * @return array Logo schema data
 */
function mitosis_get_logo_schema() {
    $logo_url = mitosis_get_site_logo_url();
    
    return array(
        '@type' => 'ImageObject',
        'url' => $logo_url,
        'width' => 600,
        'height' => 60
    );
}

/**
 * Get featured image schema information
 * 
 * @param int $image_id The image attachment ID
 * @return array|null Image schema data or null if image doesn't exist
 */
function mitosis_get_featured_image_schema($image_id) {
    $image_data = wp_get_attachment_image_src($image_id, 'full');
    
    if (!$image_data) {
        return null;
    }
    
    return array(
        '@type' => 'ImageObject',
        'url' => $image_data[0],
        'width' => $image_data[1],
        'height' => $image_data[2]
    );
}

// Add schema markup if no SEO plugin is handling it
add_action('wp_footer', 'mitosis_add_schema_markup');

function mitosis_get_site_logo_url() {
    $custom_logo_id = get_theme_mod( 'custom_logo' );
    $image = wp_get_attachment_image_src( $custom_logo_id , 'full' );
    
    return $image[0];
}