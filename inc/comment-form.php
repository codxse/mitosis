<?php

function remove_plugin_filter() {
    global $CommentReplyEmail;
    if (isset($CommentReplyEmail)) {
        remove_filter('comment_form_field_comment', array($CommentReplyEmail, 'addReplyIdFormField'), 9999);
    }
}
add_action('init', 'remove_plugin_filter');

function mitosis_custom_comment_form_fields() {
    global $CommentReplyEmail;
    
    $commenter = wp_get_current_commenter();
    $req = get_option( 'require_name_email' );
    $aria_req = ( $req ? " aria-required='true'" : '' );
    
    $fields = array(
        'author' => '<p class="comment-form-author"><label for="author">' . __( 'Name', 'mitosis' ) . ( $req ? '<span class="required"> *</span>' : '' ) . '</label> ' .
                    '<input id="author" name="author" type="text" placeholder="e.g Asep Sunandar" value="' . esc_attr( $commenter['comment_author'] ) . 
                    '" size="30"' . $aria_req . ' /></p>',
        'email'  => '<p class="comment-form-email"><label for="email">' . __( 'Email', 'mitosis' ) . ( $req ? '<span class="required"> *</span>' : '' ) . '</label> ' . 
                    '<input id="email" name="email" type="email" placeholder="e.g asepsun@nan.dar" value="' . esc_attr( $commenter['comment_author_email'] ) . 
                    '" size="30"' . $aria_req . ' /></p>',
        'url'    => '<p class="comment-form-url"><label for="url">' . __( 'Website', 'mitosis' ) . '</label>' .
                    '<input id="url" name="url" type="url" placeholder="e.g https://titiknadi.com" value="' . esc_attr( $commenter['comment_author_url'] ) . 
                    '" size="30" /></p>',
    );
    
    if (isset($CommentReplyEmail)) {
        $options = $CommentReplyEmail->options;
        $labelText = !empty($options['checkbox_text']) ? $options['checkbox_text'] : __('Email me when someone replies to my comment', 'comment-reply-email');
        
        if ($options['mail_notify'] === 'parent_check') {
            $fields['comment_mail_notify'] = '<p class="comment-form-comment-mail-notify">' . 
                                             '<input type="checkbox" name="comment_mail_notify" id="comment_mail_notify" value="comment_mail_notify" checked="checked" style="width: auto;" />' . 
                                             '<label for="comment_mail_notify" style="margin-left:7px;">' . strip_tags($labelText) . '</label></p>';
        } elseif ($options['mail_notify'] === 'parent_uncheck') {
            $fields['comment_mail_notify'] = '<p class="comment-form-comment-mail-notify">' . 
                                             '<input type="checkbox" name="comment_mail_notify" id="comment_mail_notify" value="comment_mail_notify" style="width: auto;" />' . 
                                             '<label for="comment_mail_notify" style="margin-left:7px;display: inline;position: relative;top: 2px;">' . strip_tags($labelText) . '</label></p>';
        }
    }
    
    // if ( $consent = wp_get_comment_cookies_consent() ) {
    //    $fields['cookies'] = $consent;
    // }
    
    $comment_field = '<p class="comment-form-comment"><label for="comment">' . _x( 'Comment', 'noun', 'mitosis' ) .  '<span class="required"> *</span></label>' . 
                     '<textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></p>';
    
    return array(
        'fields' => $fields,
        'comment_field' => $comment_field,
    );
}

function mitosis_reorder_comment_form_fields($fields) {
    // Define the desired order
    $order = array('author', 'email', 'comment', 'url', 'comment_mail_notify', 'cookies');

    // Reorder fields based on the desired order
    $new_fields = array();
    foreach ($order as $key) {
        if (isset($fields[$key])) {
            $new_fields[$key] = $fields[$key];
        }
    }

    // Add any remaining fields (e.g., from plugins)
    foreach ($fields as $key => $field) {
        if (!in_array($key, $order)) {
            $new_fields[$key] = $field;
        }
    }

    return $new_fields;
}
add_filter('comment_form_fields', 'mitosis_reorder_comment_form_fields', 9999);