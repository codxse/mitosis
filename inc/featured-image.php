<?php

/**
 * Determine if featured image should be shown
 *
 * @return boolean
 */
function mitosis_should_show_featured_image() {
    // Check if we're on a taxonomy archive
    if (is_category() || is_tag()) {
        $term = get_queried_object();
        $term_setting = get_term_meta($term->term_id, 'mitosis_featured_image', true);
        
        if ($term_setting === 'show') {
            return true;
        } elseif ($term_setting === 'hide') {
            return false;
        }
        // If 'default', fall through to theme setting
        return get_theme_mod('mitosis_show_featured_archives', true);
    }
    
    // For single posts and pages
    if (is_singular()) {
        $post_setting = get_post_meta(get_the_ID(), '_mitosis_show_featured_image', true);
        
        if ($post_setting === 'show') {
            return true;
        } elseif ($post_setting === 'hide') {
            return false;
        }
        
        // If 'default', use respective theme setting
        if (is_page()) {
            return get_theme_mod('mitosis_show_featured_pages', true);
        } else {
            return get_theme_mod('mitosis_show_featured_posts', true);
        }
    }
    
    // For all other archive pages
    return get_theme_mod('mitosis_show_featured_archives', true);
}

/**
 * Get all available registered image sizes
 *
 * @return array
 */
function mitosis_get_available_image_sizes() {
    $image_sizes = get_intermediate_image_sizes();
    // Add 'full' size
    $image_sizes[] = 'full';
    
    $sizes = array();
    foreach ($image_sizes as $size) {
        // Get nice name for display
        $nice_name = ucwords(str_replace(array('_', '-'), ' ', $size));
        $sizes[$size] = $nice_name;
    }
    
    return $sizes;
}

/**
 * Get appropriate featured image size based on context
 *
 * @return string Image size name
 */
function mitosis_get_featured_image_size() {
    if (is_singular('page')) {
        return get_theme_mod('mitosis_featured_size_pages', 'full');
    } elseif (is_singular('post')) {
        return get_theme_mod('mitosis_featured_size_posts', 'full');
    } else {
        return get_theme_mod('mitosis_featured_size_archives', 'large');
    }
}

/**
 * Get featured image custom classes based on context
 *
 * @return string Class names
 */
function mitosis_get_featured_image_classes() {
    $classes = array('featured-image');
    
    // Add alignment class based on context
    if (is_singular('page')) {
        $align = get_theme_mod('mitosis_featured_align_pages', 'aligncenter');
        $custom_classes = get_theme_mod('mitosis_featured_class_pages', '');
    } elseif (is_singular('post')) {
        $align = get_theme_mod('mitosis_featured_align_posts', 'aligncenter');
        $custom_classes = get_theme_mod('mitosis_featured_class_posts', '');
    } else {
        $align = get_theme_mod('mitosis_featured_align_archives', 'aligncenter');
        $custom_classes = get_theme_mod('mitosis_featured_class_archives', '');
    }
    
    // Add alignment class
    if ($align && $align !== 'alignnone') {
        $classes[] = $align;
    }
    
    // Add custom classes
    if ($custom_classes) {
        $custom_classes_array = array_map('sanitize_html_class', explode(' ', $custom_classes));
        $classes = array_merge($classes, $custom_classes_array);
    }
    
    return implode(' ', array_unique($classes));
}

/**
 * Display the featured image
 */
function mitosis_display_featured_image() {
    if (!mitosis_should_show_featured_image() || !has_post_thumbnail()) {
        return;
    }
    
    $image_size = mitosis_get_featured_image_size();
    $image_classes = mitosis_get_featured_image_classes();
    ?>
    <div class="post-thumbnail featured-image">
        <?php
        if (is_singular()) {
            the_post_thumbnail($image_size, array(
                'class' => $image_classes,
                'alt'   => get_the_title()
            ));
        } else {
            echo '<a href="' . esc_url(get_permalink()) . '" aria-hidden="true" tabindex="-1">';
            the_post_thumbnail($image_size, array(
                'class' => $image_classes,
                'alt'   => get_the_title()
            ));
            echo '</a>';
        }
        ?>
    </div>
    <?php
}

/**
 * Add custom image sizes (optional - add if you need specific sizes)
 */
function mitosis_add_image_sizes() {
    // Add any custom image sizes you need
    add_image_size('mitosis-featured', 1200, 600, true);  // Example size
    add_image_size('mitosis-archive', 800, 400, true);    // Example size
}
add_action('after_setup_theme', 'mitosis_add_image_sizes');

/**
 * Make custom image sizes available in admin (optional)
 */
function mitosis_custom_image_sizes($sizes) {
    return array_merge($sizes, array(
        'mitosis-featured' => __('Mitosis Featured', 'mitosis'),
        'mitosis-archive' => __('Mitosis Archive', 'mitosis'),
    ));
}
add_filter('image_size_names_choose', 'mitosis_custom_image_sizes');

/**
 * Get featured image position based on context
 *
 * @return string Position ('above' or 'below')
 */
function mitosis_get_featured_image_position() {
    if (is_singular('page')) {
        return get_theme_mod('mitosis_featured_position_pages', 'below');
    } elseif (is_singular('post')) {
        return get_theme_mod('mitosis_featured_position_posts', 'below');
    } else {
        return get_theme_mod('mitosis_featured_position_archives', 'below');
    }
}