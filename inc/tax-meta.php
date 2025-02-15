<?php

/**
 * Add custom layout field to the Add New Category and Tag forms.
 *
 * @param string $taxonomy The taxonomy slug.
 */
function mitosis_taxonomy_layout_field_add( $taxonomy ) {
    ?>
    <div class="form-field term-layout-wrap">
        <label for="mitosis_layout"><?php esc_html_e( 'Layout', 'mitosis' ); ?></label>
        <select name="mitosis_layout" id="mitosis_layout">
            <option value=""><?php esc_html_e( 'Default', 'mitosis' ); ?></option>
            <option value="one-column"><?php esc_html_e( 'One Column', 'mitosis' ); ?></option>
            <option value="three-column"><?php esc_html_e( 'Three Column (Sidebar, Post, Sidebar)', 'mitosis' ); ?></option>
            <option value="two-left"><?php esc_html_e( 'Two Column (Sidebar Left, Post)', 'mitosis' ); ?></option>
            <option value="two-right"><?php esc_html_e( 'Two Column (Post, Sidebar Right)', 'mitosis' ); ?></option>
        </select>
        <p class="description"><?php esc_html_e( 'Select a layout for this taxonomy archive.', 'mitosis' ); ?></p>
    </div>
    <?php
}
add_action( 'category_add_form_fields', 'mitosis_taxonomy_layout_field_add', 10, 2 );
add_action( 'post_tag_add_form_fields', 'mitosis_taxonomy_layout_field_add', 10, 2 );


/**
 * Add custom layout field to the Edit Category and Tag forms.
 *
 * @param WP_Term $term The current term object.
 */
function mitosis_taxonomy_layout_field_edit( $term ) {
    // Retrieve the current value of the layout meta field.
    $layout = get_term_meta( $term->term_id, 'mitosis_layout', true );
    ?>
    <tr class="form-field term-layout-wrap">
        <th scope="row"><label for="mitosis_layout"><?php esc_html_e( 'Layout', 'mitosis' ); ?></label></th>
        <td>
            <select name="mitosis_layout" id="mitosis_layout">
                <option value=""><?php esc_html_e( 'Default', 'mitosis' ); ?></option>
                <option value="one-column" <?php selected( $layout, 'one-column' ); ?>><?php esc_html_e( 'One Column', 'mitosis' ); ?></option>
                <option value="three-column" <?php selected( $layout, 'three-column' ); ?>><?php esc_html_e( 'Three Column (Sidebar, Post, Sidebar)', 'mitosis' ); ?></option>
                <option value="two-left" <?php selected( $layout, 'two-left' ); ?>><?php esc_html_e( 'Two Column (Sidebar Left, Post)', 'mitosis' ); ?></option>
                <option value="two-right" <?php selected( $layout, 'two-right' ); ?>><?php esc_html_e( 'Two Column (Post, Sidebar Right)', 'mitosis' ); ?></option>
            </select>
            <p class="description"><?php esc_html_e( 'Select a layout for this taxonomy archive.', 'mitosis' ); ?></p>
        </td>
    </tr>
    <?php
}
add_action( 'category_edit_form_fields', 'mitosis_taxonomy_layout_field_edit', 10, 2 );
add_action( 'post_tag_edit_form_fields', 'mitosis_taxonomy_layout_field_edit', 10, 2 );

/**
 * Save the custom layout field when a term is created or edited.
 *
 * @param int $term_id The term ID.
 */
function mitosis_save_taxonomy_layout( $term_id ) {
    if ( isset( $_POST['mitosis_layout'] ) ) {
        update_term_meta( $term_id, 'mitosis_layout', sanitize_text_field( $_POST['mitosis_layout'] ) );
    }
}
add_action( 'created_category', 'mitosis_save_taxonomy_layout', 10, 2 );
add_action( 'edited_category', 'mitosis_save_taxonomy_layout', 10, 2 );
add_action( 'created_post_tag', 'mitosis_save_taxonomy_layout', 10, 2 );
add_action( 'edited_post_tag', 'mitosis_save_taxonomy_layout', 10, 2 );

/**
 * Add featured image field to category and tag forms
 */
function mitosis_taxonomy_featured_image_field_add($taxonomy) {
    ?>
    <div class="form-field term-featured-image-wrap">
        <label><?php esc_html_e('Featured Image Display', 'mitosis'); ?></label>
        <fieldset>
            <label style="display: block; margin: 5px 0;">
                <input type="radio" name="mitosis_tax_featured_image" value="default" checked>
                <span><?php esc_html_e('Use Theme Default', 'mitosis'); ?></span>
            </label>
            <label style="display: block; margin: 5px 0;">
                <input type="radio" name="mitosis_tax_featured_image" value="show">
                <span><?php esc_html_e('Show', 'mitosis'); ?></span>
            </label>
            <label style="display: block; margin: 5px 0;">
                <input type="radio" name="mitosis_tax_featured_image" value="hide">
                <span><?php esc_html_e('Hide', 'mitosis'); ?></span>
            </label>
        </fieldset>
        <p class="description"><?php esc_html_e('Control featured image display for this taxonomy archive.', 'mitosis'); ?></p>
    </div>
    <?php
}
add_action('category_add_form_fields', 'mitosis_taxonomy_featured_image_field_add', 10, 1);
add_action('post_tag_add_form_fields', 'mitosis_taxonomy_featured_image_field_add', 10, 1);

/**
 * Add featured image field to category and tag edit forms
 */
function mitosis_taxonomy_featured_image_field_edit($term) {
    $featured_image = get_term_meta($term->term_id, 'mitosis_featured_image', true);
    if (empty($featured_image)) {
        $featured_image = 'default';
    }
    ?>
    <tr class="form-field term-featured-image-wrap">
        <th scope="row"><?php esc_html_e('Featured Image Display', 'mitosis'); ?></th>
        <td>
            <fieldset>
                <label style="display: block; margin: 5px 0;">
                    <input type="radio" name="mitosis_tax_featured_image" value="default" <?php checked($featured_image, 'default'); ?>>
                    <span><?php esc_html_e('Use Theme Default', 'mitosis'); ?></span>
                </label>
                <label style="display: block; margin: 5px 0;">
                    <input type="radio" name="mitosis_tax_featured_image" value="show" <?php checked($featured_image, 'show'); ?>>
                    <span><?php esc_html_e('Show', 'mitosis'); ?></span>
                </label>
                <label style="display: block; margin: 5px 0;">
                    <input type="radio" name="mitosis_tax_featured_image" value="hide" <?php checked($featured_image, 'hide'); ?>>
                    <span><?php esc_html_e('Hide', 'mitosis'); ?></span>
                </label>
            </fieldset>
            <p class="description"><?php esc_html_e('Control featured image display for this taxonomy archive.', 'mitosis'); ?></p>
        </td>
    </tr>
    <?php
}
add_action('category_edit_form_fields', 'mitosis_taxonomy_featured_image_field_edit', 10, 1);
add_action('post_tag_edit_form_fields', 'mitosis_taxonomy_featured_image_field_edit', 10, 1);

/**
 * Save featured image field for taxonomies
 */
function mitosis_save_taxonomy_featured_image($term_id) {
    if (isset($_POST['mitosis_tax_featured_image'])) {
        update_term_meta(
            $term_id,
            'mitosis_featured_image',
            sanitize_text_field($_POST['mitosis_tax_featured_image'])
        );
    }
}
add_action('created_category', 'mitosis_save_taxonomy_featured_image', 10, 1);
add_action('edited_category', 'mitosis_save_taxonomy_featured_image', 10, 1);
add_action('created_post_tag', 'mitosis_save_taxonomy_featured_image', 10, 1);
add_action('edited_post_tag', 'mitosis_save_taxonomy_featured_image', 10, 1);
