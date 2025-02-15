<?php
/**
 * The template for displaying comments.
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @package Mitosis
 */

/*
 * If the current post is protected by a password and the visitor has not yet entered the password,
 * return early without loading the comments.
 */
if ( post_password_required() ) {
    return;
}
?>

<div id="comments" class="comments-area">
   <?php if (have_comments() || comments_open()) : ?>
       
       <?php if (have_comments()) : ?>
           <h2 class="comments-title">
               <?php
               $comment_count = get_comments_number();
               if ('1' === $comment_count) {
                   printf(
                       esc_html__('One thought on "%1$s"', 'mitosis'),
                       '<span>' . get_the_title() . '</span>'
                   );
               } else {
                   printf( 
                       esc_html(_nx(
                           '%1$s thought on "%2$s"',
                           '%1$s thoughts on "%2$s"',
                           $comment_count,
                           'comments title',
                           'mitosis'
                       )),
                       number_format_i18n($comment_count),
                       '<span>' . get_the_title() . '</span>'
                   );
               }
               ?>
           </h2>

           <ol class="comment-list">
               <?php
               wp_list_comments(array(
                   'style'       => 'ol',
                   'short_ping'  => true,
                   'avatar_size' => 60,
                   'callback'    => 'mitosis_comment_callback',
               ));
               ?>
           </ol>

           <?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : ?>
               <nav class="comment-navigation">
                   <div class="nav-previous"><?php previous_comments_link(esc_html__('Older Comments', 'mitosis')); ?></div>
                   <div class="nav-next"><?php next_comments_link(esc_html__('Newer Comments', 'mitosis')); ?></div>
               </nav>
           <?php endif; ?>
       <?php endif; ?>

       <?php if (!comments_open() && get_comments_number() && post_type_supports(get_post_type(), 'comments')) : ?>
           <p class="no-comments"><?php esc_html_e('Comments are closed.', 'mitosis'); ?></p>
       <?php endif; ?>

        <?php
           comment_form(array(
               'title_reply_before' => '<h3 id="reply-title" class="comment-reply-title">',
               'title_reply_after'  => '</h3>',
               'cancel_reply_link'  => '<i class="icon-close"></i> ' . __('Cancel Reply', 'mitosis'),
           ));
        ?>

   <?php endif; ?>
</div>