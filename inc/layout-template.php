<?php
/**
 * Template part for handling common layout structure
 * 
 * @package Mitosis
 */

if (!function_exists('mitosis_content_wrapper_start')) :
    function mitosis_content_wrapper_start($layout = '') {
        if (empty($layout)) {
            $layout = mitosis_get_layout();
        }
        ?>
        <div id="content" class="site-content layout-<?php echo esc_attr($layout); ?>">
            <div class="container">
                <?php
                // If layout is two-left, output the left sidebar
                if (('two-left' === $layout || 'three-column' === $layout) && is_active_sidebar('sidebar-left')) {
                    get_sidebar('left');
                }
                ?>
                <main id="site-main" class="site-main">
        <?php
    }
endif;

if (!function_exists('mitosis_content_wrapper_end')) :
    function mitosis_content_wrapper_end($layout = '') {
        if (empty($layout)) {
            $layout = mitosis_get_layout();
        }
        ?>
                </main>
                <?php
                // If layout is two-right or three-column, output the right sidebar
                if (('two-right' === $layout || 'three-column' === $layout) && is_active_sidebar('sidebar-right')) {
                    get_sidebar('right');
                }
                ?>
            </div><!-- .container -->
        </div><!-- #content -->
        <?php
    }
endif;

if (!function_exists('mitosis_archive_header')) :
    function mitosis_archive_header() {
        if (is_archive() || is_search()) : ?>
            <header class="page-header">
                <?php
                if (is_archive()) {
                    the_archive_title('<h1 class="page-title">', '</h1>');
                    the_archive_description('<div class="taxonomy-description">', '</div>');
                } elseif (is_search()) {
                    printf(
                        '<h1 class="page-title">%s%s</h1>',
                        esc_html__('Search Results for: ', 'mitosis'),
                        get_search_query()
                    );
                }
                ?>
            </header><!-- .page-header -->
        <?php endif;
    }
endif;

if (!function_exists('mitosis_loop_content')) :
    function mitosis_loop_content() {
        if (have_posts()) :
            while (have_posts()) : the_post();
                get_template_part('template-parts/content', get_post_format());
            endwhile;

            mitosis_pagination();
        else :
            get_template_part('template-parts/content', 'none');
        endif;
    }
endif;