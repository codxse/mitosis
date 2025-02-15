<?php

/**
 * Custom callback for comment display
 * This function controls how individual comments are rendered
 */
 function mitosis_comment_callback($comment, $args, $depth) {
   $tag = ('div' === $args['style']) ? 'div' : 'li';
   ?>
   <<?php echo tag_escape($tag); ?> id="comment-<?php comment_ID(); ?>" <?php comment_class(empty($args['has_children']) ? '' : 'parent'); ?>>
       <article id="div-comment-<?php comment_ID(); ?>" class="comment-article">
           <header class="comment-header">
               <div class="comment-author">
                   <?php if (0 !== $args['avatar_size']) : ?>
                       <?php echo get_avatar($comment, 60, '', '', [
                           'class' => 'comment-avatar',
                           'loading' => 'lazy',
                           'decoding' => 'async'
                       ]); ?>
                   <?php endif; ?>

                   <div class="comment-meta">
                       <h3 class="comment-author-name">
                           <?php echo wp_kses_post(get_comment_author_link()); ?>
                           <?php if (user_can($comment->user_id, 'edit_posts')) : ?>
                               <span class="verified-badge">
                                    <svg height="20px" width="100%" xmlns="http://www.w3.org/2000/svg">
                                      <circle r="10" cx="10" cy="10" fill="green" />
                                    </svg>
                               </span>
                           <?php endif; ?>
                       </h3>
                       <time datetime="<?php comment_time('c'); ?>" class="comment-time">
                           <?php
                           printf(
                               esc_html_x('%1$s at %2$s', 'comment datetime', 'arunika'),
                               get_comment_date('M j, Y'),
                               get_comment_time()
                           );
                           ?>
                       </time>
                   </div>
               </div>
           </header>

           <div class="comment-content">
               <?php comment_text(); ?>
           </div>

           <footer class="comment-footer">
               <?php
               comment_reply_link(array_merge($args, [
                   'add_below'  => 'div-comment',
                   'depth'      => $depth,
                   'max_depth'  => $args['max_depth'],
                   'before'     => '<div class="comment-reply">',
                   'after'      => '</div>',
                   'reply_text' => __('Reply', 'arunika'),
                   'reply_class' => 'comment-reply-link',
                   'login_text' => __('Log in to reply', 'arunika'),
                   'respond_id' => 'respond'
               ]));
               ?>
           </footer>
       </article>
   <?php
}