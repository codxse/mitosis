<?php
/**
 * Template part for displaying content.
 *
 * @package Mitosis
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header">
        <?php
        // Check featured image position
        if (mitosis_get_featured_image_position() === 'above') {
            mitosis_display_featured_image();
        }

        if (is_singular()) :
            the_title('<h1 class="entry-title">', '</h1>');
        else :
            the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');
        endif;
        
        $show_excerpt = get_post_meta(get_the_ID(), '_mitosis_show_excerpt', true);
        if ($show_excerpt === 'show' || ($show_excerpt === 'default' && get_theme_mod('mitosis_show_excerpt', false))) {
            echo '<p class="entry-excerpt">' . get_the_excerpt() . '</p>';
        }

        // Check featured image position
        if (mitosis_get_featured_image_position() === 'below') {
            mitosis_display_featured_image();
        }

        if ('post' === get_post_type()) : ?>
            <div class="entry-meta">
                <?php mitosis_posted_on(); ?>
            </div>
        <?php endif; ?>
    </header>

    <div class="entry-content">
        <?php
        if (is_singular()) :
            the_content();
            
            wp_link_pages(array(
                'before' => '<div class="page-links">' . __('Pages:', 'mitosis'),
                'after'  => '</div>',
            ));
        else :
            // Check if post has read more tag
            if (strpos($post->post_content, '<!--more-->') !== false) {
                the_content(
                    sprintf(
                        /* translators: %s: Post title. */
                        __('Continue reading<span class="screen-reader-text"> "%s"</span>', 'mitosis'),
                        get_the_title()
                    )
                );
            } else {
                the_excerpt();
            }
        endif;
        ?>
    </div>

    <footer class="entry-footer">
        <?php mitosis_entry_footer(); ?>
    </footer>
</article>
