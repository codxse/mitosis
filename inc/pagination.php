<?php
/**
 * Pagination functionality for Mitosis theme
 *
 * @package Mitosis
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Adds pagination meta tags to improve SEO
 * These meta tags help search engines understand the structure of paginated content
 */
function mitosis_add_pagination_meta() {
    // Only add pagination meta on archives and paginated content
    if (!is_archive() && !is_home() && !is_search()) {
        return;
    }

    global $wp_query;
    $paged = get_query_var('paged') ? get_query_var('paged') : 1;
    $max_pages = $wp_query->max_num_pages;

    // Base URL (remove existing page numbers)
    $base_url = preg_replace('/(\/page\/[0-9]+\/)$/', '/', get_pagenum_link(1));

    // Add canonical URL
    if ($paged === 1) {
        printf('<link rel="canonical" href="%s" />' . "\n", esc_url($base_url));
    } else {
        printf('<link rel="canonical" href="%s" />' . "\n", esc_url(get_pagenum_link($paged)));
    }

    // Add prev/next links
    if ($paged > 1) {
        printf('<link rel="prev" href="%s" />' . "\n", esc_url(get_pagenum_link($paged - 1)));
    }
    if ($paged < $max_pages) {
        printf('<link rel="next" href="%s" />' . "\n", esc_url(get_pagenum_link($paged + 1)));
    }
}

/**
 * Displays the pagination navigation
 * Shows a limited number of page numbers with first/last pages and ellipsis
 */
function mitosis_pagination() {
    global $wp_query;

    // Don't show pagination if we only have one page
    if ($wp_query->max_num_pages <= 1) {
        return;
    }

    $paged = get_query_var('paged') ? get_query_var('paged') : 1;
    $max_pages = $wp_query->max_num_pages;

    echo '<nav class="pagination" role="navigation" aria-label="' . esc_attr__('Posts Navigation', 'mitosis') . '">';
    
    // Previous link
    if ($paged > 1) {
        echo '<a class="prev page-numbers" aria-label="Go to previous page" href="' . esc_url(get_previous_posts_page_link()) . '">' . 
             '<i class="icon icon-chevron-left"></i>' . 
             '</a>';
    }

    // First page
    if ($paged > 3) {
        echo '<a class="page-numbers" aria-label="Go to first page" href="' . esc_url(get_pagenum_link(1)) . '">1</a>';
        if ($paged > 4) {
            echo '<span class="dots">...</span>';
        }
    }

    // Page numbers
    for ($i = max(1, $paged - 2); $i <= min($max_pages, $paged + 2); $i++) {
        if ($i === $paged) {
            echo '<span aria-current="page" class="page-numbers current">' . $i . '</span>';
        } else {
            echo '<a class="page-numbers" aria-label="Go to page ' . esc_url(get_pagenum_link($i)) . '" href="' . esc_url(get_pagenum_link($i)) . '">' . $i . '</a>';
        }
    }

    // Last page
    if ($paged < $max_pages - 2) {
        if ($paged < $max_pages - 3) {
            echo '<span class="dots">...</span>';
        }
        echo '<a class="page-numbers" aria-label="Go to page ' . esc_url(get_pagenum_link($max_pages)) . '" href="' . esc_url(get_pagenum_link($max_pages)) . '">' . $max_pages . '</a>';
    }

    // Next link
    if ($paged < $max_pages) {
        echo '<a class="next page-numbers" aria-label="Go to next page" href="' . esc_url(get_next_posts_page_link()) . '">' . 
             '<i class="icon icon-chevron-right"></i>' . 
             '</a>';
    }

    echo '</nav>';
}