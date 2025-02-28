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
        return get_theme_mod('mitosis_featured_size_pages', 'mitosis-m');
    } elseif (is_singular('post')) {
        return get_theme_mod('mitosis_featured_size_posts', 'mitosis-m');
    } else {
        return get_theme_mod('mitosis_featured_size_archives', 'mitosis-m');
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

    static $first_featured_image = true; // Static variable to track first image
    $apply_fetch_priority = false;

    if ($first_featured_image && !is_singular()) { // Apply only on non-singular (like index) and for the first image
        $apply_fetch_priority = true;
        $first_featured_image = false; // Set to false after the first image
    }

    $image_size = mitosis_get_featured_image_size();
    $image_classes = mitosis_get_featured_image_classes();

    $attr = array(
        'class' => $image_classes,
        'alt'   => get_the_title(),
    );

    if ($apply_fetch_priority) {
        $attr['fetchpriority'] = 'high';
    }

    ?>
    <div class="post-thumbnail featured-image">
        <?php
        if (is_singular()) {
            $attr['fetchpriority'] = 'high'; // Always high on singular pages
            the_post_thumbnail($image_size, $attr);
        } else {
            echo '<a href="' . esc_url(get_permalink()) . '" aria-hidden="true" tabindex="-1">';
            the_post_thumbnail($image_size, $attr);
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
    add_image_size('mitosis-xxl', 1200, 1200, false);
    add_image_size('mitosis-xl', 1024, 1024, false);
    add_image_size('mitosis-l', 964, 964, false);
    add_image_size('mitosis-m', 772, 772, false);
    add_image_size('mitosis-s', 640, 640, false);
    add_image_size('mitosis-sm', 480, 480, false);
}
add_action('after_setup_theme', 'mitosis_add_image_sizes');

/**
 * Make custom image sizes available in admin (optional)
 */
function mitosis_custom_image_sizes($sizes) {
    return array_merge($sizes, array(
        'mitosis-xxl' => __('Mitosis XXL (1200x1200)', 'mitosis'),
        'mitosis-xl' => __('Mitosis XL (1024x1024)', 'mitosis'),
        'mitosis-l' => __('Mitosis L (964x964)', 'mitosis'),
        'mitosis-m' => __('Mitosis M (772x772)', 'mitosis'),
        'mitosis-s' => __('Mitosis S (640x640)', 'mitosis'),
        'mitosis-sm' => __('Mitosis SM (480x480)', 'mitosis'),
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

function mitosis_preload_featured_image() {
    if (is_admin()) {
        return;
    }

    $size = mitosis_get_featured_image_size();
    $image_url = '';

    if (is_singular()) {
        $post_id = get_the_ID();
        if (has_post_thumbnail($post_id)) {
            $image_url = get_the_post_thumbnail_url($post_id, $size);
        }
    } elseif (is_home() || is_archive() || is_search()) {
        global $wp_query;
        // Limit to first post only to avoid unnecessary iteration
        $first_post = !empty($wp_query->posts) ? reset($wp_query->posts) : null;
        if ($first_post && has_post_thumbnail($first_post->ID)) {
            $image_url = get_the_post_thumbnail_url($first_post->ID, $size);
        }
    }

    if (!empty($image_url)) {
        echo '<link rel="preload" href="' . esc_url($image_url) . '" as="image" importance="high">' . "\n";
    }
}
add_action('wp_head', 'mitosis_preload_featured_image', 1);
