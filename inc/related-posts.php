<?php
/**
 * Related Posts functionality
 *
 * @package Mitosis
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get related posts based on customizer settings
 *
 * @param int $post_id The post ID.
 * @return WP_Query
 */
function mitosis_get_related_posts($post_id) {
    // Get settings from customizer
    $count = get_theme_mod('mitosis_related_posts_count', 3);
    $orderby = get_theme_mod('mitosis_related_posts_orderby', 'date');
    $criteria = get_theme_mod('mitosis_related_posts_criteria', 'category');
    
    $args = array(
        'post_type'           => 'post',
        'post_status'         => 'publish',
        'posts_per_page'      => $count,
        'post__not_in'        => array($post_id),
        'orderby'            => $orderby,
    );
    
    // Add comment count orderby
    if ($orderby === 'comment') {
        $args['orderby'] = 'comment_count';
    }
    
    // Get taxonomy terms
    $terms = array();
    $tax_query = array('relation' => 'OR');
    
    if ($criteria === 'category' || $criteria === 'both') {
        $categories = get_the_category($post_id);
        if (!empty($categories)) {
            $tax_query[] = array(
                'taxonomy' => 'category',
                'field'    => 'term_id',
                'terms'    => wp_list_pluck($categories, 'term_id'),
                'orderby'  => 'rand'
            );
        }
    }
    
    if ($criteria === 'tag' || $criteria === 'both') {
        $tags = get_the_tags($post_id);
        if (!empty($tags)) {
            $tax_query[] = array(
                'taxonomy' => 'post_tag',
                'field'    => 'term_id',
                'terms'    => wp_list_pluck($tags, 'term_id'),
                'orderby'  => 'rand'
            );
        }
    }
    
    $args['tax_query'] = $tax_query;
    
    return new WP_Query($args);
}

/**
 * Get post thumbnail or default image
 *
 * @param int $post_id The post ID.
 * @return string Image URL
 */
function mitosis_get_post_image($post_id) {
    if (has_post_thumbnail($post_id)) {
        return get_the_post_thumbnail_url($post_id, 'medium');
    }
    
    $default_image = get_theme_mod('mitosis_related_default_image');
    return $default_image ? $default_image : get_template_directory_uri() . '/assets/images/default-post.jpg';
}

/**
 * Display related posts section
 */
function mitosis_display_related_posts() {
    // Check if related posts should be shown
    if (!get_theme_mod('mitosis_show_related_posts', true)) {
        return;
    }
    
    // Get related posts
    $related_posts = mitosis_get_related_posts(get_the_ID());
    
    if ($related_posts->have_posts()) :
        $section_title = get_theme_mod('mitosis_related_posts_title', __('Related Posts', 'mitosis'));
        ?>
        <div class="related-posts">
            <span class="related-posts-title"><?php echo esc_html($section_title); ?></span>
            <div class="related-posts-grid">
                <?php
                while ($related_posts->have_posts()) : $related_posts->the_post();
                    ?>
                    <article class="related-post">
                        <a href="<?php the_permalink(); ?>" class="related-post-thumbnail">
                            <img src="<?php echo esc_url(mitosis_get_post_image(get_the_ID())); ?>" 
                                 alt="<?php the_title_attribute(); ?>" />
                        </a>
                        
                        <h3 class="related-post-title">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_title(); ?>
                            </a>
                        </h3>
                        
                        <div class="related-post-meta">
                            <?php if (get_theme_mod('mitosis_related_show_category', true)) : ?>
                                <span class="related-post-category">
                                    <?php 
                                    $categories = get_the_category();
                                    if (!empty($categories)) {
                                        echo '<a href="' . esc_url(get_category_link($categories[0]->term_id)) . '">' 
                                            . esc_html($categories[0]->name) . '</a>';
                                    }
                                    ?>
                                </span>
                            <?php endif; ?>

                            <?php if (get_theme_mod('mitosis_related_show_date', true)) : ?>
                                <span class="related-post-date">
                                    <?php echo get_the_date(); ?>
                                </span>
                            <?php endif; ?>

                            <?php if (get_theme_mod('mitosis_related_show_comments', true)) : ?>
                                <span class="related-post-comments">
                                    <?php
                                    $comment_count = get_comments_number();
                                    printf(
                                        _nx(
                                            '%s Comment',
                                            '%s Comments',
                                            $comment_count,
                                            'comments title',
                                            'mitosis'
                                        ),
                                        number_format_i18n($comment_count)
                                    );
                                    ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </article>
                    <?php
                endwhile;
                ?>
            </div>
        </div>
        <?php
        wp_reset_postdata();
    endif;
}