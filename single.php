<?php
/**
 * The template for displaying all single posts
 *
 * @package Mitosis
 */

get_header();

mitosis_content_wrapper_start();

if (have_posts()) :
    while (have_posts()) :
        the_post();
        
        // Load the content template
        get_template_part('template-parts/content', get_post_format());
        
        // Add post navigation
        the_post_navigation(array(
            'prev_text' => '<span class="nav-subtitle">' . esc_html__('Previous:', 'mitosis') . '</span> <span class="nav-title">%title</span>',
            'next_text' => '<span class="nav-subtitle">' . esc_html__('Next:', 'mitosis') . '</span> <span class="nav-title">%title</span>',
            'class'     => 'post-navigation'
        ));
        
        // Display related posts if enabled
        mitosis_display_related_posts();
        
        // Load comments if enabled
        if (comments_open() || get_comments_number()) {
            comments_template();
        }
    endwhile;
else :
    get_template_part('template-parts/content', 'none');
endif;

mitosis_content_wrapper_end();

get_footer();