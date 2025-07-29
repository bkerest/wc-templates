<?php
// includes/template-style-loader.php

if (!defined('ABSPATH')) exit;

function wcdt_enqueue_template_styles() {
    global $wcdt_requested_templates;
    if (!is_array($wcdt_requested_templates)) return;

    $template_ids = array_unique(array_map('intval', $wcdt_requested_templates));

    foreach ($template_ids as $template_id) {
        $css_path = WCDT_PATH . 'assets/css/template-' . $template_id . '.css';
        $css_url  = WCDT_URL . 'assets/css/template-' . $template_id . '.css';

        if (file_exists($css_path)) {
            wp_enqueue_style('wcdt-style-' . $template_id, $css_url, array(), filemtime($css_path));
        }
    }
}
add_action('wp_footer', 'wcdt_enqueue_template_styles', 99);