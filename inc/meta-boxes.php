<?php
/**
 * Mitosis Meta Boxes.
 *
 * @package Mitosis
 */

// Add meta boxes.
function mitosis_add_meta_boxes() {
	add_meta_box(
		'mitosis_options',
		__( 'Page Options', 'mitosis' ),
		'mitosis_meta_box_callback',
		['post', 'page'],  // Show on posts and pages
		'side',
		'default'
	);
}
add_action( 'add_meta_boxes', 'mitosis_add_meta_boxes' );

// Display meta box content.
function mitosis_meta_box_callback( $post ) {
	// Add a nonce field so we can check for it later.
	wp_nonce_field( 'mitosis_save_meta_box_data', 'mitosis_meta_box_nonce' );

	// Get existing values from post meta.
	$layout = get_post_meta( $post->ID, '_mitosis_layout', true );
	$header_scripts = get_post_meta( $post->ID, '_mitosis_header_scripts', true );
	$footer_scripts = get_post_meta( $post->ID, '_mitosis_footer_scripts', true );

	?>
    <p>
    	<label for="mitosis_layout"><?php _e( 'Layout', 'mitosis' ); ?></label>
    	<select name="mitosis_layout" id="mitosis_layout">
    		<option value=""><?php _e( 'Default', 'mitosis' ); ?></option>
    		<option value="one-column" <?php selected( $layout, 'one-column' ); ?>><?php _e( 'One Column', 'mitosis' ); ?></option>
    		<option value="three-column" <?php selected( $layout, 'three-column' ); ?>><?php _e( 'Three Column (Sidebar, Post, Sidebar)', 'mitosis' ); ?></option>
    		<option value="two-left" <?php selected( $layout, 'two-left' ); ?>><?php _e( 'Two Column (Sidebar Left, Post)', 'mitosis' ); ?></option>
    		<option value="two-right" <?php selected( $layout, 'two-right' ); ?>><?php _e( 'Two Column (Post, Sidebar Right)', 'mitosis' ); ?></option>
    	</select>
    </p>
	<p>
		<label for="mitosis_header_scripts"><?php _e( 'Header Scripts', 'mitosis' ); ?></label>
		<textarea name="mitosis_header_scripts" id="mitosis_header_scripts" rows="4" style="width:100%;"><?php echo esc_textarea( $header_scripts ); ?></textarea>
	</p>
	<p>
		<label for="mitosis_footer_scripts"><?php _e( 'Footer Scripts', 'mitosis' ); ?></label>
		<textarea name="mitosis_footer_scripts" id="mitosis_footer_scripts" rows="4" style="width:100%;"><?php echo esc_textarea( $footer_scripts ); ?></textarea>
	</p>
	<?php
}

// Save meta box content.
function mitosis_save_meta_box_data( $post_id ) {
	// Check if our nonce is set.
	if ( ! isset( $_POST['mitosis_meta_box_nonce'] ) ) {
		return;
	}
	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( $_POST['mitosis_meta_box_nonce'], 'mitosis_save_meta_box_data' ) ) {
		return;
	}
	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Check user permissions.
	if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {
		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}
	} else {
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	}

	// Sanitize and save layout.
	if ( isset( $_POST['mitosis_layout'] ) ) {
		$layout = sanitize_text_field( $_POST['mitosis_layout'] );
		update_post_meta( $post_id, '_mitosis_layout', $layout );
	}

	// Sanitize and save header scripts.
	if ( isset( $_POST['mitosis_header_scripts'] ) ) {
		$header_scripts = wp_kses_post( $_POST['mitosis_header_scripts'] );
		update_post_meta( $post_id, '_mitosis_header_scripts', $header_scripts );
	}

	// Sanitize and save footer scripts.
	if ( isset( $_POST['mitosis_footer_scripts'] ) ) {
		$footer_scripts = wp_kses_post( $_POST['mitosis_footer_scripts'] );
		update_post_meta( $post_id, '_mitosis_footer_scripts', $footer_scripts );
	}
}
add_action( 'save_post', 'mitosis_save_meta_box_data' );

// Add this to inc/meta-boxes.php or directly to functions.php

/**
 * Add TOC meta box to posts
 */
function mitosis_add_toc_meta_box() {
    add_meta_box(
        'mitosis_toc_meta_box',
        __('Table of Contents Settings', 'mitosis'),
        'mitosis_toc_meta_box_callback',
        'post',
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'mitosis_add_toc_meta_box');

/**
 * Render TOC meta box content
 */
function mitosis_toc_meta_box_callback($post) {
    wp_nonce_field('mitosis_toc_meta_box', 'mitosis_toc_meta_box_nonce');
    
    $show_toc = get_post_meta($post->ID, '_mitosis_show_toc', true);
    if (empty($show_toc)) {
        $show_toc = 'hide'; // Default to hide
    }
    ?>
    <div class="mitosis-toc-options">
        <p><?php _e('Table of Contents:', 'mitosis'); ?></p>
        <label style="display: block; margin: 5px 0;">
            <input type="radio" name="mitosis_show_toc" value="show" <?php checked($show_toc, 'show'); ?>>
            <span><?php _e('Show', 'mitosis'); ?></span>
        </label>
        <label style="display: block; margin: 5px 0;">
            <input type="radio" name="mitosis_show_toc" value="hide" <?php checked($show_toc, 'hide'); ?>>
            <span><?php _e('Hide', 'mitosis'); ?></span>
        </label>
    </div>
    <style>
        .mitosis-toc-options label {
            margin-bottom: 8px;
        }
        .mitosis-toc-options input[type="radio"] {
            margin-right: 8px;
        }
    </style>
    <?php
}

/**
 * Save TOC meta box data
 */
function mitosis_save_toc_meta_box_data($post_id) {
    if (!isset($_POST['mitosis_toc_meta_box_nonce'])) {
        return;
    }
    
    if (!wp_verify_nonce($_POST['mitosis_toc_meta_box_nonce'], 'mitosis_toc_meta_box')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    if (isset($_POST['mitosis_show_toc'])) {
        update_post_meta($post_id, '_mitosis_show_toc', sanitize_text_field($_POST['mitosis_show_toc']));
    }
}
add_action('save_post', 'mitosis_save_toc_meta_box_data');

/**
 * Add Featured Image Display meta box
 */
function mitosis_add_featured_image_meta_box() {
    add_meta_box(
        'mitosis_featured_image_meta_box',
        __('Featured Image Display', 'mitosis'),
        'mitosis_featured_image_meta_box_callback',
        ['post', 'page'],
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'mitosis_add_featured_image_meta_box');

/**
 * Render Featured Image meta box content
 */
function mitosis_featured_image_meta_box_callback($post) {
    wp_nonce_field('mitosis_featured_image_meta_box', 'mitosis_featured_image_meta_box_nonce');
    
    $show_featured = get_post_meta($post->ID, '_mitosis_show_featured_image', true);
    if (empty($show_featured)) {
        $show_featured = 'default'; // Default to theme settings
    }
    ?>
    <div class="mitosis-featured-image-options">
        <p><?php _e('Featured Image Display:', 'mitosis'); ?></p>
        <label style="display: block; margin: 5px 0;">
            <input type="radio" name="mitosis_show_featured_image" value="default" <?php checked($show_featured, 'default'); ?>>
            <span><?php _e('Use Theme Default', 'mitosis'); ?></span>
        </label>
        <label style="display: block; margin: 5px 0;">
            <input type="radio" name="mitosis_show_featured_image" value="show" <?php checked($show_featured, 'show'); ?>>
            <span><?php _e('Show', 'mitosis'); ?></span>
        </label>
        <label style="display: block; margin: 5px 0;">
            <input type="radio" name="mitosis_show_featured_image" value="hide" <?php checked($show_featured, 'hide'); ?>>
            <span><?php _e('Hide', 'mitosis'); ?></span>
        </label>
    </div>
    <?php
}

/**
 * Save Featured Image meta box data
 */
function mitosis_save_featured_image_meta_box_data($post_id) {
    if (!isset($_POST['mitosis_featured_image_meta_box_nonce'])) {
        return;
    }
    
    if (!wp_verify_nonce($_POST['mitosis_featured_image_meta_box_nonce'], 'mitosis_featured_image_meta_box')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    if (isset($_POST['mitosis_show_featured_image'])) {
        update_post_meta($post_id, '_mitosis_show_featured_image', sanitize_text_field($_POST['mitosis_show_featured_image']));
    }
}
add_action('save_post', 'mitosis_save_featured_image_meta_box_data');

/**
 * Add SEO meta box to posts
 */
function mitosis_add_seo_meta_box() {
    add_meta_box(
        'mitosis_seo_meta_box',
        __('SEO Schema Settings', 'mitosis'),
        'mitosis_seo_meta_box_callback',
        'post',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'mitosis_add_seo_meta_box');

/**
 * Render SEO meta box content
 */
function mitosis_seo_meta_box_callback($post) {
    // Add nonce for security
    wp_nonce_field('mitosis_seo_meta_box', 'mitosis_seo_meta_box_nonce');

    // Get existing values
    $enable_schema = get_post_meta($post->ID, '_mitosis_enable_schema', true);
    $schema_type = get_post_meta($post->ID, '_mitosis_schema_type', true);
    
    // If no value is set, use theme default
    if (empty($schema_type)) {
        $schema_type = get_theme_mod('mitosis_default_schema_type', 'BlogPosting');
    }
    ?>
    <div class="mitosis-seo-options">
        <p>
            <label>
                <input type="checkbox" name="mitosis_enable_schema" value="1" 
                       <?php checked($enable_schema, '1'); ?>>
                <?php _e('Enable Schema for this post', 'mitosis'); ?>
            </label>
            <span class="description">
                <?php _e('Override global schema settings for this post.', 'mitosis'); ?>
            </span>
        </p>

        <p>
            <label for="mitosis_schema_type"><?php _e('Schema Type:', 'mitosis'); ?></label>
            <select name="mitosis_schema_type" id="mitosis_schema_type">
                <option value="BlogPosting" <?php selected($schema_type, 'BlogPosting'); ?>>
                    <?php _e('Blog Post', 'mitosis'); ?>
                </option>
                <option value="Article" <?php selected($schema_type, 'Article'); ?>>
                    <?php _e('Article', 'mitosis'); ?>
                </option>
                <option value="NewsArticle" <?php selected($schema_type, 'NewsArticle'); ?>>
                    <?php _e('News Article', 'mitosis'); ?>
                </option>
                <option value="TechArticle" <?php selected($schema_type, 'TechArticle'); ?>>
                    <?php _e('Technical Article', 'mitosis'); ?>
                </option>
            </select>
            <span class="description">
                <?php _e('Select the most appropriate schema type for this content.', 'mitosis'); ?>
            </span>
        </p>
    </div>
    <?php
}

/**
 * Save SEO meta box data
 */
function mitosis_save_seo_meta_box_data($post_id) {
    // Check nonce and permissions
    if (!isset($_POST['mitosis_seo_meta_box_nonce']) ||
        !wp_verify_nonce($_POST['mitosis_seo_meta_box_nonce'], 'mitosis_seo_meta_box') ||
        !current_user_can('edit_post', $post_id)) {
        return;
    }

    // Save enable schema setting
    $enable_schema = isset($_POST['mitosis_enable_schema']) ? '1' : '';
    update_post_meta($post_id, '_mitosis_enable_schema', $enable_schema);

    // Save schema type
    if (isset($_POST['mitosis_schema_type'])) {
        $schema_type = sanitize_text_field($_POST['mitosis_schema_type']);
        update_post_meta($post_id, '_mitosis_schema_type', $schema_type);
    }
}
add_action('save_post', 'mitosis_save_seo_meta_box_data');