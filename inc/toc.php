<?php
/**
 * Table of Contents functionality
 *
 * @package Mitosis
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Generate Table of Contents
 */
function mitosis_add_toc_to_content($content) {
    // Check if we're in the main query and it's a single post
    if (!is_singular('post') || !in_the_loop() || !is_main_query()) {
        return $content;
    }
    
    // Check if TOC is enabled for this post
    $show_toc = get_post_meta(get_the_ID(), '_mitosis_show_toc', true);
    if ($show_toc === 'hide') {
        return $content;
    }
    
    // Find all H2 and H3 headings
    preg_match_all('/<h[23](.*?)>(.*?)<\/h[23]>/i', $content, $matches, PREG_SET_ORDER);

    if (empty($matches)) {
        return $content;
    }

    $toc = '<div class="post-toc">';
    $toc .= '<span class="toc-title">' . __('Table of Contents', 'arunika') . '</span>';
    $toc .= '<nav class="toc-nav"><ul>';

    $index = 0;
    $current_depth = 2;
    
    foreach ($matches as $match) {
        $index++;
        $heading = strip_tags($match[2]);
        $heading_level = intval($match[0][2]);
        $anchor = 'toc-' . sanitize_title($heading);

        // Replace original heading with anchored version
        $content = str_replace(
            $match[0],
            sprintf('<%1$s%2$s id="%3$s">%4$s</%1$s>', 
                'h' . $heading_level,
                $match[1],
                $anchor,
                $heading
            ),
            $content
        );

        if ($heading_level === 2) {
            if ($current_depth === 3) {
                $toc .= '</ul></li>';
            }
            $toc .= '<li class="toc-level-2">';
            $current_depth = 2;
        } elseif ($heading_level === 3) {
            if ($current_depth === 2) {
                $toc .= '<li class="toc-level-2 toc-has-items"><ul class="toc-sub">';
            }
            $toc .= '<li class="toc-level-3">';
            $current_depth = 3;
        }

        $toc .= sprintf(
            '<a href="#%s">%s</a>',
            $anchor,
            $heading
        );
        $toc .= '</li>';
    }

    if ($current_depth === 3) {
        $toc .= '</ul></li></li>';
    }

    $toc .= '</ul></nav></div>';

    return $toc . $content;
}
add_filter('the_content', 'mitosis_add_toc_to_content');