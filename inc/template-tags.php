<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Mitosis
 */

// Update in inc/template-tags.php

if (!function_exists('mitosis_posted_on')) :
    /**
     * Prints HTML with meta information for the current post-date/time, author, and comments.
     */
    function mitosis_posted_on() {
        $output = '';
        
        // Author
        if (get_theme_mod('mitosis_show_author', true)) {
            $author_id = get_the_author_meta('ID');
            $twitter_profile = get_the_author_meta('twitter_profile', $author_id);
            $author_name = get_the_author();
            $author_link = esc_url(get_author_posts_url(get_the_author_meta('ID')));

            $byline = sprintf(
                /* translators: %s: post author. */
                esc_html_x('by %s', 'post author', 'mitosis'),
                '<span class="author vcard"><a class="url fn n" href="' . $author_link . '">' . esc_html($author_name) . '</a></span>'
            );

            // Replace with twitter link if available
            if ($twitter_profile) {
                $byline = '<span class="author vcard"><a href="' . esc_url($twitter_profile) . '" target="_blank" rel="noopener noreferrer">' . esc_html($author_name) . '</a></span>';
            }

            $output .= '<span class="byline meta"><i class="icon icon-twitter"></i>' . $byline . '</span>';
        }
        
        // Estimated Reading Time
        if (get_theme_mod('mitosis_show_reading_time', true)) {
            global $post;
            $reading_time = mitosis_get_reading_time($post->post_content);
            $output .= '<span class="reading-time meta"><i class="icon icon-stopwatch"></i>' . $reading_time . '</span>';
        }

        // Comment Count
        if (get_theme_mod('mitosis_show_comment_count', true)) {
            if (!post_password_required() && (comments_open() || get_comments_number())) {
                $comment_count = get_comments_number();
                
                $comment_text = sprintf(
                    _nx(
                        '%s Comment',
                        '%s Comments',
                        $comment_count,
                        'comments title',
                        'mitosis'
                    ),
                    number_format_i18n($comment_count)
                );
                
                $output .= '<span class="comments-link meta"><i class="icon icon-chat"></i><a href="' . esc_url(get_comments_link()) . '">' 
                    . $comment_text . '</a></span>';
            }
        }

        echo $output;
    }
endif;

function write_created_at() {
    $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';
    $time_string = sprintf($time_string,
        esc_attr(get_the_date(DATE_W3C)),
        esc_html(get_the_date())
    );
    
    $posted_on = sprintf(
        /* translators: %s: post date. */
        esc_html_x('Posted on %s', 'post date', 'mitosis'),
        '<a href="' . esc_url(get_permalink()) . '" rel="bookmark">' . $time_string . '</a>'
    );
    
    echo '<span class="posted-on meta"><i class="icon icon-calendar"></i>' . $posted_on . '</span>';
}

if (!function_exists('mitosis_entry_footer')) :
    /**
     * Prints HTML with meta information for categories and tags.
     */
    function mitosis_entry_footer() {
        // Hide category and tag text for pages.
        if ('post' === get_post_type()) {
            
            // Created date
            if (get_theme_mod('mitosis_show_created_date', true)) {
                write_created_at();
            }
    
            // Updated date
            if (get_theme_mod('mitosis_show_updated_date', false)) {
                if (get_the_time('U') !== get_the_modified_time('U')) {
                    $time_string = '<time class="updated" datetime="%1$s">%2$s</time>';
                    $time_string = sprintf($time_string,
                        esc_attr(get_the_modified_date(DATE_W3C)),
                        esc_html(get_the_modified_date())
                    );
                    
                        
                    $updated_on = sprintf(
                        /* translators: %s: post modified date. */
                        esc_html_x('Updated on %s', 'post modified date', 'mitosis'),
                        '<a href="' . esc_url(get_permalink()) . '" rel="bookmark">' . $time_string . '</a>'
                    );
                    
                    echo '<span class="updated-on meta"><i class="icon icon-calendar"></i>' . $updated_on . '</span>';
                } else {
                    write_created_at();
                }
            }
        
            // Categories
            if (get_theme_mod('mitosis_show_categories', true)) {
                $categories_list = get_the_category_list(esc_html__(', ', 'mitosis'));
                if ($categories_list) {
                    printf('<span class="cat-links meta"><i class="icon icon-folder"></i><span class="categories">' . esc_html__('Posted in %1$s', 'mitosis') . '</span></span>', $categories_list);
                }
            }

            // Tags
            if (get_theme_mod('mitosis_show_tags', true)) {
                $tags_list = get_the_tag_list('', esc_html__(', ', 'mitosis'));
                if ($tags_list) {
                    printf('<span class="tags-links meta"><i class="icon icon-price-tag"></i><span class="tags">' . esc_html__('Tagged %1$s', 'mitosis') . '</span></span>', $tags_list);
                }
            }
        }

        // Edit link
        edit_post_link(
            sprintf(
                wp_kses(
                    /* translators: %s: Name of current post. Only visible to screen readers */
                    __('Edit <span class="screen-reader-text">%s</span>', 'mitosis'),
                    array(
                        'span' => array(
                            'class' => array(),
                        ),
                    )
                ),
                get_the_title()
            ),
            '<span class="edit-link meta"><i class="icon icon-pencil"></i>',
            '</span>'
        );
    }
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function mitosis_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'mitosis_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,
			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'mitosis_categories', $all_the_cool_cats );
	}

	return $all_the_cool_cats > 1;
}

/**
 * Flush out the transients used in mitosis_categorized_blog.
 */
function mitosis_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Clear the transient.
	delete_transient( 'mitosis_categories' );
}
add_action( 'edit_category', 'mitosis_category_transient_flusher' );
add_action( 'save_post',     'mitosis_category_transient_flusher' );

/**
 * Calculates the estimated reading time of a post.
 *
 * @param string $content Post content.
 * @param int    $wpm     Words per minute (default: 200).
 * @return string Estimated reading time string.
 */
function mitosis_get_reading_time( $content, $wpm = 200 ) {
    $clean_content = strip_shortcodes( strip_tags( $content ) );
    $word_count    = str_word_count( $clean_content );
    $time          = ceil( $word_count / $wpm );

    if ( $time < 1 ) {
        $time_string = __('Less than a minute', 'mitosis');
    } else {
        /* translators: %s: estimated reading time in minutes */
        $time_string = sprintf( _n( '%s minute', '%s minutes', $time, 'mitosis' ), $time );
    }

    return $time_string;
}
