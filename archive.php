<?php
/**
 * The template for displaying Archive pages
 *
 * @package Mitosis
 */

get_header();

// Determine the layout based on term meta or fallback to default
$term = get_queried_object();
$term_layout = isset($term->term_id) ? get_term_meta($term->term_id, 'mitosis_layout', true) : '';
$layout = $term_layout ?: get_theme_mod('mitosis_default_layout', 'two-right');

mitosis_content_wrapper_start($layout);
mitosis_archive_header();
mitosis_loop_content();
mitosis_content_wrapper_end($layout);

get_footer();