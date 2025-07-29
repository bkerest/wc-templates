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

    // Get the template post
    $post = get_post($template_id);
    if (!$post || $post->post_status !== 'publish') {
        return ''; // Template not found or not published
    }

    // Get and process content
    $content = do_shortcode($post->post_content);

    // Add wrapper div for scoping CSS
    $wrapper_class = 'wcdt-template-wrapper template-' . $template_id;
    return '<div class="' . esc_attr($wrapper_class) . '">' . $content . '</div>';
}

add_shortcode('wc_template', 'wcdt_render_template_shortcode');
