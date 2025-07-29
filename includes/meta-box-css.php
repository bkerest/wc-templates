<?php
// includes/meta-box-css.php

if (!defined('ABSPATH')) exit;

// Προσθήκη του metabox
add_action('add_meta_boxes', function () {
    add_meta_box(
        'wcdt_custom_css_meta_box',
        __('Custom CSS', 'wc-dynamic-templates'),
        'wcdt_render_custom_css_meta_box',
        'wc_template',
        'normal',
        'default'
    );
});

// Εμφάνιση του πεδίου
function wcdt_render_custom_css_meta_box($post) {
    $custom_css = get_post_meta($post->ID, '_wcdt_custom_css', true);
    wp_nonce_field('wcdt_custom_css_nonce_action', 'wcdt_custom_css_nonce');
    echo '<textarea name="_wcdt_custom_css" style="width:100%;min-height:200px;font-family:monospace;">' . esc_textarea($custom_css) . '</textarea>';
    echo '<p style="font-size:smaller;color:#666;">This CSS will be saved in a file and loaded only when this template is used.</p>';
}

// Αποθήκευση του CSS
add_action('save_post_wc_template', function ($post_id) {
    if (!isset($_POST['wcdt_custom_css_nonce']) || !wp_verify_nonce($_POST['wcdt_custom_css_nonce'], 'wcdt_custom_css_nonce_action')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    if (isset($_POST['_wcdt_custom_css'])) {
        $css = trim($_POST['_wcdt_custom_css']);
        update_post_meta($post_id, '_wcdt_custom_css', $css);
    }
});
