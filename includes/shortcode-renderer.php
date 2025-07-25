<?php
// includes/shortcode-renderer.php

if (!defined('ABSPATH')) exit;

function wcdt_render_template_shortcode($atts) {
    $atts = shortcode_atts(array(
        'id' => 0,
    ), $atts, 'wc_template');

    $template_id = intval($atts['id']);
    if (!$template_id || get_post_type($template_id) !== 'wc_template') {
        return ''; // Invalid or missing template
    }

    // Check role-based visibility
    if (!wcdt_user_can_see_template($template_id)) {
        return ''; // User doesn't have permission
    }

    // Get post content
    $post = get_post($template_id);
    if (!$post || $post->post_status !== 'publish') {
        return '';
    }

    // Apply shortcodes inside content
    $content = do_shortcode($post->post_content);

    return '<div class="wc-template-content">' . $content . '</div>';
}
add_shortcode('wc_template', 'wcdt_render_template_shortcode');