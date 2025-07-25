<?php
// includes/template-style-loader.php

if (!defined('ABSPATH')) exit;

function wcdt_enqueue_template_styles() {
    if (!is_singular('product')) return;

    global $post;
    if (!isset($post->post_content)) return;

    // Find all used wc_template shortcodes in product content
    preg_match_all('/\[wc_template[^\]]*id=["\']?(\d+)["\']?[^\]]*\]/', $post->post_content, $matches);
    $template_ids = array_map('intval', $matches[1]);

    foreach ($template_ids as $template_id) {
        $css_path = WCDT_PATH . 'assets/css/template-' . $template_id . '.css';
        $css_url  = WCDT_URL . 'assets/css/template-' . $template_id . '.css';

        if (file_exists($css_path)) {
            wp_enqueue_style('wcdt-style-' . $template_id, $css_url, array(), filemtime($css_path));
        }
    }
}
add_action('wp_enqueue_scripts', 'wcdt_enqueue_template_styles');
