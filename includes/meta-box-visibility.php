<?php
// includes/meta-box-visibility.php

if (!defined('ABSPATH')) exit;

function wcdt_add_visibility_meta_box() {
    add_meta_box(
        'wcdt_visibility_meta_box',
        __('Visibility per Role', 'wc-dynamic-templates'),
        'wcdt_render_visibility_meta_box',
        'wc_template',
        'side'
    );
}
add_action('add_meta_boxes', 'wcdt_add_visibility_meta_box');

function wcdt_render_visibility_meta_box($post) {
    wp_nonce_field('wcdt_save_visibility_roles', 'wcdt_visibility_nonce');

    $selected_roles = get_post_meta($post->ID, '_wcdt_allowed_roles', true);
    if (!is_array($selected_roles)) {
        $selected_roles = array();
    }

    global $wp_roles;
    $all_roles = $wp_roles->roles;

    echo '<div style="max-height:200px; overflow:auto;">';
    foreach ($all_roles as $role_key => $role) {
        echo '<label style="display:block; margin-bottom:4px;">';
        echo '<input type="checkbox" name="wcdt_allowed_roles[]" value="' . esc_attr($role_key) . '"' . checked(in_array($role_key, $selected_roles), true, false) . '> ';
        echo esc_html($role['name']);
        echo '</label>';
    }
    echo '</div>';
}

function wcdt_save_visibility_meta_box($post_id) {
    if (!isset($_POST['wcdt_visibility_nonce']) || !wp_verify_nonce($_POST['wcdt_visibility_nonce'], 'wcdt_save_visibility_roles')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    if (isset($_POST['wcdt_allowed_roles']) && is_array($_POST['wcdt_allowed_roles'])) {
        $roles = array_map('sanitize_text_field', $_POST['wcdt_allowed_roles']);
        update_post_meta($post_id, '_wcdt_allowed_roles', $roles);
    } else {
        delete_post_meta($post_id, '_wcdt_allowed_roles');
    }
}
add_action('save_post', 'wcdt_save_visibility_meta_box');
